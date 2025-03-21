<?php
// Require composer autoload
require_once '../../vendor/autoload.php';
// Create an instance of the class:

include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/solicitudes_items.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/cotizacion_items.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/proveedores.php';
include_once '../funciones.php';

error_reporting(-1);


$idSolicitud = isset($_GET['idSolicitud']) ? $_GET['idSolicitud'] : '-1';
$idCotizacion = isset($_GET['idCotizacion']) ? $_GET['idCotizacion'] : '-1';
$tipoSolicitud = isset($_GET['idTipoSolicitud']) ? $_GET['idTipoSolicitud'] : '';


if ($tipoSolicitud == 1) {
    $sol_estado = new solicitudes_estados();
    $solicitud = new solicitudes($idSolicitud);
    $cotizacion = new cotizacion_solic_prov($idCotizacion);
    $items = new solicitudes_items();
    $citems = new cotizacion_item();
} else {
    $sol_estado = new materiales_solicitudes_estados();
    $solicitud = new materiales_solicitudes($idSolicitud);
    $cotizacion = new materiales_cotizacion_solic_prov($idCotizacion);
    $items = new materiales_solicitudes_items();
    $citems = new materiales_cotizacion_item();
}


//print_r($solicitud);

$items->setid_solicitudes($idSolicitud);
$proveedor = new proveedores($cotizacion->getid_proveedores());
$persona = new personas($solicitud->getid_personas());
$puntosdispensa = new puntos_dispensa($solicitud->getid_puntos_dispensa());
$data['idSolicitud'] = $idSolicitud;
$data['idCotizacion'] = $idCotizacion;
$data['cotiza'] = 'S';
$data['idsProveedores'] = '';
$estadores = $cotizacion->SelectAllCotizacionesFiltros($data);
$row = mysqli_fetch_assoc($estadores);
$idProveedor = $row['idProveedor'];

if ($tipoSolicitud == 1) {

    if ($solicitud->getesB24() === "1") {
        $afiliado = $persona->getcodigoB24();
    } else {
        $afiliado = $persona->getapellido() . " " . $persona->getnombre();
    }
} else {
    $afiliado = $persona->getapellido() . " " . $persona->getnombre();
}


ob_start();

?>



<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family:serif; font-size: 9pt; color: #000088;">
    <tr>
        <td width="34%" style="text-align:left;"><img src="../../img/ospepri.png" width="150px" height="100px" /></td>
        <td width="83%" style="text-align:left;"> <span style="font-size:18pt;">Sistema de Medicamentos de Alto Costo y Materiales</span></td>

    </tr>
</table>
<br>

<table width="100%">

    <tr>
        <td width="50%">
            <b>Id. Solicitud:</b> <?= $solicitud->getid() ?>
        </td>
        <td width="50%">
            <b>Estado:</b> <?php echo $row['nombreEst'] ?>
        </td>
    </tr>
    <tr>

        <td width="50%">
            <b>DNI Afiliado:</b> <?= $persona->getdni() ?>
        </td>
        <td width="50%">
            <b>Afiliado:</b> <?= $afiliado ?>
        </td>
    </tr>
    <tr>

    </tr>
    <tr>
        <td width="50%">
            <b>Punto Dispensa:</b> <?= $puntosdispensa->getnombre() ?>
        </td>
        <td width="50%">
            <b>Proveedor: </b> <?= $proveedor->getnombre() ?>
        </td>
    </tr>

    <tr>
        <td width="50%">
            <b>Id. Cotizacion:</b> <?php echo $idCotizacion ?>
        </td>

        <td width="50%">
            <b>Fecha Vencimiento Cotización: </b> <?= fecha4($solicitud->getfecha_vigencia_cotiz()) ?>
        </td>


    </tr>
</table>


<br />
<h3 style="padding-bottom: -2%;">Listado de Items Cotizados (Aprobados)</h3>
<hr />



<table border="1" width="100%">
    <thead>
        <tr>


            <th bgcolor="#73879C">Producto</th>
            <th bgcolor="#73879C">Cant.Solicitada</th>
            <th bgcolor="#73879C">Cant. Cotizada</th>
            <th bgcolor="#73879C">Importe Unitario ($)</th>
            <th bgcolor="#73879C">Total Cotizado ($)</th>
            <th bgcolor="#73879C">Cant. Aprobada</th>
            <th bgcolor="#73879C">Total Aprobado ($)</th>
            <th bgcolor="#73879C">Marca</th>

        </tr>
    </thead>
    <tbody>

        <?php

        $arrayItems = $citems->getItemsCotizacion($idSolicitud, $idProveedor);
        $totalAprob = 0;
        $total=0;  
        while ($row = mysqli_fetch_assoc($arrayItems)) {


            if ($row['id_estados'] == 46 or $row['id_estados'] == 41) {

                $total = $total + $row['totalAprob'];

                echo '<tr>';
                echo '<td>' . $row['nombre'] . '</td>';
                echo '<td style="text-align:center;">' . $row['cantSolicitada'] . '</td>';
                echo '<td style="text-align:center;">' . $row['cantCotizada'] . '</td>';
                echo '<td style="text-align:center;">' . $row['importe_unitario'] . '</td>';
                echo '<td style="text-align:center;">' . number_format($row['total'], 2, '.', '') . '</td>';
                echo '<td style="text-align:center;">' . $row['cantidadAprob'] . '</td>';
                echo '<td style="text-align:center;">' . $row['totalAprob'] . '</td>';
                echo '<td>' . $row['marca'] . '</td>';
                echo '</tr>';
            }
        } ?>


    </tbody>
</table>
<br />
<br />
<table border="0" width="100%">
    <tr>

        <td width="50%"><b>Importe Total Aprobado ($):</b> <?= number_format($total, 2, '.', '') ?></td>
    </tr>
</table>





<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
$html = ob_get_clean(); //Toma el codigo anterior
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L', 'default_font' => 'helvetica', 'default_font_size' => 9]); //Creo el pdf y lo formateo
$mpdf->SetHTMLHeader('
<div>
    <p style="text-align: right; font-size:9px;">Fecha de Impresi&oacute;n: ' . date('d/m/Y H:i') . '
</div>');
$mpdf->setFooter('{PAGENO}');

$nombre = 'reporteSolicitud_Nro_' . $idSolicitud . '.pdf';
$mpdf->SetTitle($nombre);
// Write some HTML code:
$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser

$mpdf->Output();
