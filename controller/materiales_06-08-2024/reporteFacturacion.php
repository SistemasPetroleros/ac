<?php
set_time_limit(30);
// Require composer autoload
require_once '../../vendor/autoload.php';

include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../funciones.php';

error_reporting(0);


$fechaDesde = isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : $hoyMenosUnMes;
$fechaHasta = isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : $hoy;
$buscaBeneficiario = isset($_GET['buscaBeneficiario']) ? $_GET['buscaBeneficiario'] : '';
$buscaProducto = isset($_GET['buscaProducto']) ? $_GET['buscaProducto'] : '';
$estado = isset($_GET['buscaEstado']) ? $_GET['buscaEstado'] : '';
$idSolicitud = isset($_GET['idSolicitudBuscar']) ? $_GET['idSolicitudBuscar'] : '';
$buscaBeneficiarioB24=isset($_GET['buscaBeneficiarioB24']) ? $_GET['buscaBeneficiarioB24'] : '';
$idPuntoDispensa= isset($_GET['idPuntoDispensa']) ? $_GET['idPuntoDispensa'] : '';
$buscarNroRemito= isset($_GET['buscarNroRemito']) ? $_GET['buscarNroRemito'] : '';
$buscarEsSur= isset($_GET['buscarEsSur']) ? $_GET['buscarEsSur'] : '';
$buscarNroRemito= isset($_GET['buscarNroRemito']) ? $_GET['buscarNroRemito'] : '';
$urgenteBuscar= isset($_GET['urgenteBuscar']) ? $_GET['urgenteBuscar'] : '';
$idCategoriaBuscar= isset($_GET['idCategoriaBuscar']) ? $_GET['idCategoriaBuscar'] : '';
$idTipoSBuscar= isset($_GET['idTipoSBuscar']) ? $_GET['idTipoSBuscar'] : '';
$idProveedorBuscar= isset($_GET['idProveedorBuscar']) ? $_GET['idProveedorBuscar'] : '';
$cotizacionFin= isset($_GET['cotizacionFin']) ? $_GET['cotizacionFin'] : '';


$solicitud = new materiales_solicitudes();
$arraySolicitudes = $solicitud->SelectAllFiltrosFact($fechaDesde,$fechaHasta,$buscaProducto,$estado,$idSolicitud,$buscaBeneficiario, $buscaBeneficiarioB24,$idPuntoDispensa, $buscarNroRemito, $buscarEsSur, $urgenteBuscar, $idCategoriaBuscar, $idTipoSBuscar,'', $idProveedorBuscar, $cotizacionFin);




ob_start();

?>



<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family:serif; font-size: 9pt; color: #000088;">
    <tr>
        <td width="34%" style="text-align:left;"><img src="../../img/ospepri.png" width="150px" height="100px" /></td>
        <td width="83%" style="text-align:left;"> <span style="font-size:18pt;">Sistema de Medicamentos de Alto Costo y Materiales</span></td>

    </tr>
</table>
<br>

<table border="1" width="100%">
    <thead>
        <tr>
            <th bgcolor="#73879C">#</th>
            <th bgcolor="#73879C">Fecha</th>
            <th bgcolor="#73879C">Dni</th>
            <th bgcolor="#73879C">Nombre</th>
            <th bgcolor="#73879C">Categoría</th>
            <th bgcolor="#73879C">Punto Dispensa</th>
            <th bgcolor="#73879C">Estado</th>
            <th bgcolor="#73879C">Proveedor</th>
            <th bgcolor="#73879C">Total Aprobado ($)</th>
        </tr>
    </thead>
    <tbody>
        <?php

       
        $cont=0;
        $total=0;
        while ($x = mysqli_fetch_assoc($arraySolicitudes)) {

            $total=$total+$x['totalSol'];

            echo '<tr class="odd gradeX">';
            echo '<td>' . $x['id'] . '</td>';
            echo '<td>' . fecha($x['fecha']) . '</td>';
            echo '<td>' . $x['dni'] . '</td><td>' . $x['nombre'] . '</td>';
            echo '<td>' . $x['categoria'] . '</td> ';
            echo '<td>' . $x['puntoDispensa'] . '</td> ';
            echo '<td>' . $x['estado'] . '</td>'; 
            echo '<td>' . $x['nomProv'] . '</td>'; 
            echo '<td>$'.$x['totalSol'] .'</td>';
            echo "</tr>"; 

            $cont++;
        }
        ?>


    </tbody>
</table>
<br>
<table border="0" width="100%">
    <tr>
        <td width="100%"><b>Total Solicitudes:</b> <?= $cont ?></td>
    </tr>
    <tr>  
        <td width="100%"><b>Importe Total ($):</b> <?= $total ?></td>
    </tr>
</table>


<?php 

date_default_timezone_set('America/Argentina/Buenos_Aires');
$html = ob_get_clean(); //Toma el codigo anterior
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'legal', 'orientation' => 'L', 'default_font' => 'helvetica', 'default_font_size' => 9]); //Creo el pdf y lo formateo
$mpdf->SetHTMLHeader('
<div>
    <p style="text-align: right; font-size:9px;">Fecha de Impresi&oacute;n: ' . date('d/m/Y H:i') . '
</div>');
$mpdf->setFooter('{PAGENO}');

$mpdf->WriteHTML($html); //Escribo en el pdf el c�digo anterior

// Genera el fichero y fuerza la descarga
$fecha_actual = date('YmdHis');
//$mpdf->Output('reporte_' . $fecha_actual . '.pdf', 'D');
$mpdf->OutputHttpDownload('reporte_' . $fecha_actual . '.pdf');

exit;
