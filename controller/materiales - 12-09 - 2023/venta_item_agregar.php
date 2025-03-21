<?php

header('Content-type: text/html; charset=iso-8859-1');


include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/productos.php';
include_once '../funciones.php';




$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '';
$idProducto = isset($_POST['idProducto']) ? $_POST['idProducto'] : '';
$observaciones = isset($_POST['obsItem']) ? $_POST['obsItem'] : '';
$cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : '';


$solicitud = new materiales_solicitudes($idSolicitud);
$obj = new materiales_solicitudes_items();
$obj->setid_solicitudes($idSolicitud);

$estado = mysqli_fetch_assoc($solicitud->getestado());
$idEstado = isset($estado['idEstado'])?$estado['idEstado']:-1;

 if (isset($_POST['eliminar'])) {
    if ($idEstado == '31') {
        
        $obj->setid($_POST['eliminar']);
        $obj->Delete();
        echo '-1';
    }
} else {
    if ($idEstado == '31') {
        if($cantidad>0){
        $obj->setcantidad($cantidad);
        $obj->setid_producto($idProducto);
       
        $obj->setobservaciones($observaciones);
        $obj->Create();
        echo '1';
    }else{
        echo '-2';
    }
    }else{
    echo '0';
    }
//    echo $obj->Total($idVenta);
}




