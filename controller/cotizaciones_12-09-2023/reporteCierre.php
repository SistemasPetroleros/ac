<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(-1);
require_once '..\..\lib\mpdf\vendor\autoload.php';
include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../funciones.php';


$data['fechaDesde'] = isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : $hoyMenosUnMes;
$data['fechaHasta'] = isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : $hoy;
$data['idCotizacion'] = isset($_GET['idSolicitudBuscar']) ? $_GET['idSolicitudBuscar'] : '';
$data['afiliado'] = isset($_GET['buscaBeneficiario']) ? $_GET['buscaBeneficiario'] : '';
$data['farmacia'] = isset($_GET['idPuntoDispensa']) ? $_GET['idPuntoDispensa'] : '';
$data['idEstadoBuscar'] = isset($_GET['buscaEstado']) ? $_GET['buscaEstado'] : '';
$data['idProveedorB'] = isset($_GET['idProveedorB']) ? $_GET['idProveedorB'] : '';

$obj = new cotizacion_solic_prov();
$arraySolicitudes = $obj->SelectACotizacionesUserProveedor($data);



ob_start();
?>


<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family:
serif; font-size: 9pt; color: #000088;">
    <tr>
        <td width="17%" style="text-align:center;"><img src="../../img/02.png" style="height: 70px; width:175px;" /></td>
        <td width="83%" style="text-align:center;"> <span style="font-size:18pt;">Resumen de Solicitudes (Del <?= fecha($_GET['fechaDesde']) ?> al <?= fecha($_GET['fechaHasta']) ?>)</span></td>
    </tr>
</table>
<br>




<table border="1" width="100%">
    <thead>
        <tr>
            <th bgcolor="#73879C"># Solicitud</th>
            <th bgcolor="#73879C">Fecha</th>
            <th bgcolor="#73879C">Dni</th>
            <th bgcolor="#73879C">Afiliado</th>
            <th bgcolor="#73879C">Proveedor</th>
            <th bgcolor="#73879C">Estado</th>
            <th bgcolor="#73879C">Importe Aprobado(En $)</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $cont = 0;
        $total = 0;
        while ($x = mysqli_fetch_assoc($arraySolicitudes)) {

            if ($x['id_tipo_solicitud'] == 1) $tipo = 'AC';
            else $tipo = 'M';

            $total = $total + $x['totalSol'];

            echo '<tr class="odd gradeX"><td> ' . $tipo . $x['id'] . '</td>
                                         <td>' . fecha($x['fecha']) . '</td>
                                         <td>' . $x['dni'] . '</td>
                                         <td>' . $x['nombre'] . '</td>
                                         <td>' . $x['nomProv'] . '</td>
                                         <td>' . $x['estadoGral'] . '</td>
                                         <td>$' . $x['totalSol'] . '</td>
                                         </tr>';

            $cont++;
        }
        ?>


    </tbody>
</table>
<br />
<table border="0" width="100%">
    <tr>
        <td width="50%"><b>Total Solicitudes:</b> <?= $cont ?></td>
    </tr>
    <tr>
        <td width="50%"><b>Importe Total ($):</b> <?= $total ?></td>
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

$mpdf->WriteHTML($html); //Escribo en el pdf el c�digo anterior

// Genera el fichero y fuerza la descarga
$fecha_actual = date('YmdHis');
$mpdf->Output('reporte_' . $fecha_actual . '.pdf', 'D');
exit;



?>