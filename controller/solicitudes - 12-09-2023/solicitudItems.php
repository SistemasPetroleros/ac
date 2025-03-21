<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_items.php';
include_once '../../model/productos.php';
include_once '../funciones.php';



$solicitud = new solicitudes($_POST['idSolicitud']);

$items = new solicitudes_items();
$items->setid_solicitudes($solicitud->getid());
$arrayItems = $items->SelectAll();



$productos = new productos();
$arrayProductos = $productos->SelectAll();


include_once '../../view/solicitudes/solicitudItems.php';