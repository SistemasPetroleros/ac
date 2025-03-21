<?php

include_once '../config.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../funciones.php';

$items = new materiales_cotizacion_item('');
$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');

$resultado = $items->getItemsCotizacion($idSolicitud);

if (mysqli_num_rows($resultado) > 0) {
    echo 1;
} else
    echo "0";
