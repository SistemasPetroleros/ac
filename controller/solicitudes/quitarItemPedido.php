<?php

include_once '../config.php';
include_once '../funciones.php';
include_once '../../model/cotizacion_items.php';



$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');
$idItem = (isset($_POST['idItem']) ? $_POST['idItem'] : '');
$idItemCotizacion = (isset($_POST['idItemCotizacion']) ? $_POST['idItemCotizacion'] : '');

$citems = new cotizacion_item($idItemCotizacion);
$citems->setcantidadAprob('');

$resultado = $citems->save(); //almaceno cantidad aprobada
if (!$resultado) {
    echo "-1"; //error al actualizar
} else {
    echo "1";
}

