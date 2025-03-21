<?php

include_once '../config.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../funciones.php';


$itemsC = new materiales_cotizacion_item();
$itemsS= new materiales_solicitudes_items($_POST['idItemS']);
$itemsS->setid_solicitudes($_POST['idSolicitud']);


$r = $itemsC->eliminarItemCotizacion($_POST['idItemCotizacion']);

if ($r) {
     $x=$itemsS->Delete();
     if($x) echo "1";
     else echo "0";

}
else echo "0";
