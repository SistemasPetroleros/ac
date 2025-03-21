<?php

include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/cotizacion_items.php';
include_once '../../model/usuario_tipo_solicitud.php';
include_once '../funciones.php';
include_once '../../model/usuarios.php';
include_once '../../model/estados.php';
include_once '../../login.php';

error_reporting(0);


$data['fechaDesde'] = isset($_POST['fechaDesde']) ? $_POST['fechaDesde'] : $hoyMenosUnMes;
$data['fechaHasta'] = isset($_POST['fechaHasta']) ? $_POST['fechaHasta'] : $hoy;
$data['idCotizacion'] = isset($_POST['idSolicitudBuscar']) ? $_POST['idSolicitudBuscar'] : '';
$data['afiliado'] = isset($_POST['buscaBeneficiario']) ? $_POST['buscaBeneficiario'] : '';
$data['farmacia'] = isset($_POST['idPuntoDispensa']) ? $_POST['idPuntoDispensa'] : '';
$data['idEstadoBuscar'] = isset($_POST['buscaEstado']) ? $_POST['buscaEstado'] : '';


$permisots = new usuario_tipo_solicitud();
$permisots->setidUsuario($_SESSION['idUsuario']);
$resultadoPermiso = $permisots->SelectForUser();

$tspermisos= array();
while($row=mysqli_fetch_assoc($resultadoPermiso)){
    array_push($tspermisos, $row['idTipoSolicitud']);
}


$estados = new estados();
$resultadoEstados=  $estados -> SelectAllXTipo('C');




if (isset($_POST['idSolicitudBuscar'])) {

   
    $obj = new cotizacion_solic_prov();
    $arraySolicitudes = $obj->SelectACotizacionesUserProveedor($data);

    $item = new cotizacion_item();

    include_once '../../view/cotizaciones/consultarCotizacionesGrilla.php';
} else {
    include_once '../../view/cotizaciones/consultarCotizaciones.php';
}
