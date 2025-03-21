<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../funciones.php';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$resultado=$solicitud->existeRemitoDocsSinCheck();
echo $resultado;
