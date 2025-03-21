<?php
error_reporting(0);
date_default_timezone_set('America/Argentina/Buenos_Aires');
include_once '../config.php';
include_once '../funciones.php';
include_once('../../model/materiales_solicitudes.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Contdol: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/html;charset=utf-8");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"reporteconsulta.xls\"");


$fechaDesde = isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : $hoyMenosUnMes;
$fechaHasta = isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : $hoy;
$buscaBeneficiario = isset($_GET['buscaBeneficiario']) ? $_GET['buscaBeneficiario'] : '';
$buscaProducto = isset($_GET['buscaProducto']) ? $_GET['buscaProducto'] : '';
$estado = isset($_GET['buscaEstado']) ? $_GET['buscaEstado'] : '';
$idSolicitud = isset($_GET['idSolicitudBuscar']) ? $_GET['idSolicitudBuscar'] : '';
$idPuntoDispensa= isset($_GET['idPuntoDispensa']) ? $_GET['idPuntoDispensa'] : '';
$buscarEsSur= isset($_GET['buscarEsSur']) ? $_GET['buscarEsSur'] : '';

$solicitud= new materiales_solicitudes();

$resultado = $solicitud ->SelectExcel($fechaDesde,$fechaHasta,$buscaProducto,$estado,$idSolicitud,$buscaBeneficiario, $idPuntoDispensa, $buscarEsSur);


?>
<div id="contenido" align="left">
    <h2 align="left"><stdong>
            <?php echo utf8_decode('Listado de Solicitudes Detallado')." - ".date('d/m/Y H:i:s'); ?>
        </stdong></h2>
    <div id="formulario" align="left">
       <table border="1">
           <thead>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;">Id. Solicitud</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;" >Fecha Solicitud</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;" >DNI</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;">Apellido</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;">Nombres</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;">Estado Solicitud</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;">Punto de Dispensa</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;">Es Sur?</th>
                <th style="background-color:#394D5F; color: #FFFFFF; font-weight: bold; border-color: #364B5F;">Cantidad / Nombre Producto</th>
           </thead>
           <tbody>
            <?php 
                 while($row=mysqli_fetch_assoc($resultado)){

                    if($row['esB24']==0) $esB24='NO';
                    else $esB24='SI';

                    if($row['esSur']==0) $esSur='NO';
                    else $esSur='SI';

                    echo "<tr>";
                    echo '<td>'.$row['idSolicitud'].'</td>';
                    echo '<td>'.$row['fechaS'].'</td>';
                    echo '<td>'.$row['dni'].'</td>';
                    echo '<td>'.utf8_decode($row['apellido']).'</td>';
                    echo '<td>'.utf8_decode($row['nombre']).'</td>';
                    echo '<td>'.($row['estadoSol']).'</td>';
                    echo '<td>'.utf8_decode($row['ptoDispensa']).'</td>';
                   // echo '<td>'.$esB24.'</td>';
                    echo '<td>'.$esSur.'</td>';
                    echo '<td>'.utf8_decode($row['Productos']).'</td>';
                  //  echo '<td>'.utf8_decode($row['Trazados']).'</td>';
                    echo "</tr>";

                 }
            
            ?>
            
           </tbody>
       </table>
    </div>
</div>