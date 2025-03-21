<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../funciones.php';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);
$idRemito = $_POST['idRemito'];
$nroRemito = $_POST['nroRemito'];

$items = new SolicitudesItemTraza();

//verificar primero si existe en la tabla de trazabilidad
$resultado = $items->ExisteRemito($idSolicitud, $nroRemito);
$row = mysqli_fetch_assoc($resultado);
if ($row['cant'] == 0) {
    //si no existe, elimino. Sino Error,  no permitir la eliminación.
    $eliminar = $solicitud->eliminarRemito($idRemito, $idSolicitud);
    if ($eliminar)
        echo 1;
    else echo 0;
} else
    echo -1;
