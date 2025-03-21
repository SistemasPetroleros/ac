<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_estados.php';
include_once '../funciones.php';

$idSolicitud = isset($_POST['idSolicitud'])?$_POST['idSolicitud']:-1;
$tipo = isset($_POST['tipo'])?$_POST['tipo']:'';
$observacion = isset($_POST['observacion'])?$_POST['observacion']:'';


$sol_estado = new solicitudes_estados();

$solicitud = new solicitudes($idSolicitud);
$estado = mysqli_fetch_assoc($solicitud->getestado());
$idEstado = isset($estado['idEstado'])?$estado['idEstado']:1;

if($tipo=='obs' and strlen($observacion)>0){
$sol_estado->setid_estados($idEstado);
$sol_estado->setobservaciones($observacion);
$sol_estado->setid_solicitudes($solicitud->getid());
$sol_estado->Create();
}











//$arrayEstadosSolicitud = $sol_estado->SelectAll();


$rand = rand(100, 999);
include_once 'estadosSolicitud.php';
