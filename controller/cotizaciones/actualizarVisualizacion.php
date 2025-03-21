<?php

include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../funciones.php';

$data['idCotizacion']=$_POST['idCotizacion'];

$obj = new cotizacion_solic_prov();
$res = $obj->updateVisualizacion($data);

echo $res;
