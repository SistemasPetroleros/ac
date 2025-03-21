<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_estados.php';
include_once '../funciones.php';

    $idSolicitud = isset($_POST['idSolicitud'])?$_POST['idSolicitud']:-1;
    $tipo = isset($_POST['tipo'])?$_POST['tipo']:'';
    $observacion = isset($_POST['observacion'])?$_POST['observacion']:'';
    $idEstado = isset($_POST['idEstado'])?$_POST['idEstado']:'';

    $sol_estado = new solicitudes_estados();

    $sol_estado->setid_estados($idEstado);
    $sol_estado->setobservaciones($observacion);
    $sol_estado->setid_solicitudes($idSolicitud);

    $sol_estado->Create();
