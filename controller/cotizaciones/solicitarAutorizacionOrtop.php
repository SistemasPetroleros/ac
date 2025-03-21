<?php

include_once '../config.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizaciones_estados.php';
include_once '../funciones.php';

$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');
$idCotizacion = (isset($_POST['idCotizacion']) ? $_POST['idCotizacion'] : '');
$autoriza = (isset($_POST['autoriza']) ? $_POST['autoriza'] : '');
$comentarios = (isset($_POST['comentarios']) ? $_POST['comentarios'] : '');

$solicitud = new materiales_solicitudes_estados('');
$items = new materiales_cotizacion_item('');

$idEstado = 32;
$idEstadoCot = 11;
if ($autoriza == "S") {
    $idEstado = "37";
    $idEstadoCot = "10";
}

$solicitud->setid_solicitudes($idSolicitud);
$solicitud->setid_estados($idEstado);
$solicitud->setobservaciones('Solicitud creada por proveedor. <br/>' . $comentarios);
$r = $solicitud->Create();

//crear estado 
$estadoCot = new materiales_cotizaciones_estados();
$estadoCot->setid_estados($idEstadoCot);
$estadoCot->setid_cotizacion($idCotizacion);
$estadoCot->setobservaciones('Cambio de estado Cotizacion #' . $idCotizacion . ' de la solicitud #' . $idSolicitud . "<br/>" . $comentarios);
$estadoCot->Create();

if ($r) {
    //Cambiar estado cotizacion
    $cotizacion = new materiales_cotizacion_solic_prov($idCotizacion);
    $cotizacion->setid_estados($idEstadoCot);
    $cotizacion->setuserModif($_SESION['user']);
    $cotizacion->cambiarEstado();

    //cambiar estado a cada ítem
    $resultado = $items->getItemsCotizacion($idSolicitud);
    while ($row = mysqli_fetch_assoc($resultado)) {

        $itemsx = new materiales_cotizacion_item($row['idItemCot']);
        
        if($idEstadoCot==10) {
            $itemsx->setcantidadAprob($itemsx->getcantidad()); //La cantidad aprobada es igual a la cotizada
            $itemsx->setid_estados(46);
        }
        else{
            $itemsx->setid_estados(41);
        }
        $itemsx->save();
    }

    if ($autoriza == "S"  and $idEstado = "37") echo "1";
    else
        echo "2";
} else
    echo "0";
