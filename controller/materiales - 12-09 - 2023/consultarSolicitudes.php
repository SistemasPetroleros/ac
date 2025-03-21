<?php
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/estados.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/solicitudes_categorias.php';
include_once '../../model/tipo_solicitud.php';
include_once '../../model/proveedores.php';
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
$buscaBeneficiarioB24=isset($_POST['buscaBeneficiarioB24']) ? $_POST['buscaBeneficiarioB24'] : '';
$idPuntoDispensa= isset($_POST['idPuntoDispensa']) ? $_POST['idPuntoDispensa'] : '';
$buscarNroRemito= isset($_POST['buscarNroRemito']) ? $_POST['buscarNroRemito'] : '';
$buscarEsSur= isset($_POST['buscarEsSur']) ? $_POST['buscarEsSur'] : '';
$buscarNroRemito= isset($_POST['buscarNroRemito']) ? $_POST['buscarNroRemito'] : '';
$urgenteBuscar= isset($_POST['urgenteBuscar']) ? $_POST['urgenteBuscar'] : '';
$idCategoriaBuscar= isset($_POST['idCategoriaBuscar']) ? $_POST['idCategoriaBuscar'] : '';
$idTipoSBuscar= isset($_POST['idTipoSBuscar']) ? $_POST['idTipoSBuscar'] : '';
$idProveedorBuscar= isset($_POST['idProveedorBuscar']) ? $_POST['idProveedorBuscar'] : '';
$cotizacionFin= isset($_POST['cotizacionFin']) ? $_POST['cotizacionFin'] : '';



$estados = new estados();
$arrayEstados = $estados->SelectAllEstadosMateriales();

$pdispensa = new puntos_dispensa();
$param['idUsuario']=$_SESSION['idUsuario'];
$arrayPD= $pdispensa->SelectAll($param);

$categorias= new solicitudes_categorias('');
$arraycategorias=$categorias-> SelectAll();

$tipo_solicitud= new tipo_solicitud('');
$tipo_solicitud->setseccion('M');
$arraytiposolicitud=$tipo_solicitud-> SelectAll();



$proveedores= new proveedores('');
$tipos="2,3";
$arrayproveedores=$proveedores-> SelectAllXTipo($tipos);


if(isset($_POST['buscaProducto'])){
    
    $solicitud = new materiales_solicitudes();
    $arraySolicitudes = $solicitud->SelectAllFiltros($fechaDesde,$fechaHasta,$buscaProducto,$estado,$idSolicitud,$buscaBeneficiario, $buscaBeneficiarioB24,$idPuntoDispensa, $buscarNroRemito, $buscarEsSur, $urgenteBuscar, $idCategoriaBuscar, $idTipoSBuscar,'', $idProveedorBuscar, $cotizacionFin);

    include_once '../../view/materiales/consultarSolicitudesGrilla.php';
}else{
    include_once '../../view/materiales/consultarSolicitudes.php';
}