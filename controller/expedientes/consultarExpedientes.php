<?php
include_once '../config.php';
include_once '../../model/expedientes.php';
include_once '../../model/estados.php';
include_once '../funciones.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';


$rand = rand(100, 999);
$mensaje = '';


$fechaDesde = isset($_POST['fechaDesde']) ? $_POST['fechaDesde'] : $hoyMenosUnMes;
$fechaHasta = isset($_POST['fechaHasta']) ? $_POST['fechaHasta'] : $hoy;
$buscaBeneficiario = isset($_POST['buscaBeneficiario']) ? $_POST['buscaBeneficiario'] : '';
$buscaProducto = isset($_POST['buscaProducto']) ? $_POST['buscaProducto'] : '';
$estado = isset($_POST['buscaEstado']) ? $_POST['buscaEstado'] : '';
$idSolicitud = isset($_POST['idSolicitudBuscar']) ? $_POST['idSolicitudBuscar'] : '';


$estados = new estados();
$arrayEstados = $estados->SelectAllEstadosSolicitudes();









if(isset($_POST['buscaProducto'])){
    
    $solicitud = new solicitudes();
    $arraySolicitudes = $solicitud->SelectAllFiltros($fechaDesde,$fechaHasta,$buscaProducto,$estado,$idSolicitud,$buscaBeneficiario);

    include_once '../../view/solicitudes/consultarSolicitudesGrilla.php';
}else{
    include_once '../../view/solicitudes/consultarSolicitudes.php';
}