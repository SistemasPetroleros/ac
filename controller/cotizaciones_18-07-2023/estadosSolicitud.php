<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_cotizaciones_estados.php';
include_once '../funciones.php';


$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$idCotizacion = isset($_POST['idCotizacion']) ? $_POST['idCotizacion'] : -1;



$sol_estado = new materiales_cotizaciones_estados();
$sol_estado->setid_cotizacion($idCotizacion );



$arrayEstadosSolicitud = $sol_estado->SelectAll();


include_once '../../view/cotizaciones/estadosSolicitud.php';
