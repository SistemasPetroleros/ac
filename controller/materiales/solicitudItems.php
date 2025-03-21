<?php
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_productos.php';
include_once '../funciones.php';



$solicitud = new materiales_solicitudes($_POST['idSolicitud']);

$items = new materiales_solicitudes_items();
$items->setid_solicitudes($solicitud->getid());
$arrayItems = $items->SelectAll();



$productos = new materiales_productos();
$arrayProductos = $productos->SelectAll();


include_once '../../view/materiales/solicitudItems.php';