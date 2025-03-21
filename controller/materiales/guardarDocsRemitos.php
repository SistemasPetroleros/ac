<?php

include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../funciones.php';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$idRemito = $_POST['idRemito'];
$size = $_POST['size'];
$data = $_POST;
$i = 1;
while ($i <= $size) {
    $param['idRemito'] = $idRemito;
    $param['idDoc'] = $data['idDoc' . $i];
    $param['obs'] = $data['obs' . $i];
    $param['chequear'] = $data['chequear' . $i];
    $edit = $solicitud->editSolicitudRemitosDocs($param);
    if (!$edit) {
        echo 0;
        exit;
    }

    $i++;
}

echo 1;
