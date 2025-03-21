<?php
error_reporting(0);

include_once '../config.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../funciones.php';


$cotizacion = new materiales_cotizacion_solic_prov();

//inicializo objeto
$cotizacion->setid_solicitudes($_POST['idSolicitud']);
$cotizacion->setvalidez_propuesta($_POST['validezPropuesta']);
$cotizacion->setplazo_entrega_dias($_POST['plazo_entrega_dias']);
$cotizacion->setincluye_flete($_POST['incluye_flete']);
$cotizacion->setcondiciones_pago($_POST['condiciones_pago']);
$cotizacion->setobservaciones($_POST['observaciones']);
$cotizacion->setid_proveedores($_POST['idProveedor']);

if ($cotizacion->updateCotizacion())
    echo 1;
else
    echo 0;
