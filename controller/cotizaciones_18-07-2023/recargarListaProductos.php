<?php

include_once '../config.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../funciones.php';

$items = new materiales_cotizacion_item('');
$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');
$control = (isset($_POST['control']) ? $_POST['control'] : '');


$resultado=$items->getItemsCotizacion($idSolicitud);
$filas="";
while($row= mysqli_fetch_assoc($resultado)){
   $filas.="<tr>";
   $filas.="<td>".$row['nombre']."</td>";
   $filas.="<td>".$row['cantCotizada']."</td>";
   $filas.="<td>".$row['importe_unitario']."</td>";
   $filas.="<td>".number_format($row['cantCotizada']*$row['importe_unitario'],2,'.','')."</td>";
   $filas.='<td><button type="button" class="btn btn-danger" onclick="eliminarItem('.$row['idItem'].','.$row['idItemCot'].','.$control.');"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
   $filas.="</tr>";
}

echo $filas;

