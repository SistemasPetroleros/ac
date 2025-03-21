<?php
error_reporting(-1);
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_cotizaciones_estados.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';

include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/solicitudes.php';
include_once '../../model/cotizaciones_estados.php';
include_once '../../model/solicitudes_estados.php';
include_once '../funciones.php';


$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$idCotizacion = isset($_POST['idCotizacion']) ? $_POST['idCotizacion'] : -1;

/************************************************************************************************** */


$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$idTipoSolicitud = isset($_POST['idTipoSolicitud']) ? $_POST['idTipoSolicitud'] : '';
$observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';

if ($idTipoSolicitud == 1) {
	
	$sol_estado = new solicitudes_estados();

	$solicitud = new solicitudes($idSolicitud);
	$estado = mysqli_fetch_assoc($solicitud->getestado());
	$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : 31;

	$sol_estado->setid_solicitudes($solicitud->getid());

	$rand = rand(100, 999);

	//ESTADO ACTUAL DE LA SOLICITUD
	$objStatus = new solicitudes_estados();
	$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
	if ($x = mysqli_fetch_assoc($lastStatus)) {
		$estadoActual = $x['id_estados'];
	}



	//PERMISOS DE ACCESO DEL USUARIO LOGEADO
	$objPermisos = new usuario_permisos_estados();
	$objPermisos->setidUsuario($_SESSION['idUsuario']);
	$permisosUser = $objPermisos->SelectForUser();

	$permisos = array();
	while ($p = mysqli_fetch_assoc($permisosUser)) {
		array_push($permisos, $p['idEstado']);
	}




	//PERMISOS DE ACCESO DEL USUARIO LOGEADO
	$objPermisos = new usuario_permisos_estados();
	$objPermisos->setidUsuario($_SESSION['idUsuario']);
	$permisosUser = $objPermisos->SelectForUser();

	$permisos = array();
	while ($p = mysqli_fetch_assoc($permisosUser)) {
		array_push($permisos, $p['idEstado']);
	}


	if ($tipo == 'obs' and strlen($observacion) > 0) {
		$sol_estado->setid_estados($idEstado);
		$sol_estado->setobservaciones($observacion);
		$sol_estado->setid_solicitudes($solicitud->getid());
		$sol_estado->Create();
		$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
		if ($x = mysqli_fetch_assoc($lastStatus)) {
			$estadoActual = $x['id_estados'];
		}
	}



	if (esMiembro($tipo, $permisos) and ($tipo == 36 and ($estadoActual == '31'  or $estadoActual == '34' or $estadoActual == '37'))) {

		//cambio a ANULADO al solciitud
		$sol_estado->setid_estados($tipo);
		$sol_estado->setobservaciones($observacion);
		$sol_estado->setid_solicitudes($solicitud->getid());
		$sol_estado->Create();

		//crear estado ANULADO (C)
		$estadoCot = new cotizaciones_estados();
		$estadoCot->setid_estados(12);
		$estadoCot->setid_cotizacion($idCotizacion);
		$estadoCot->setobservaciones('Cambio de estado Cotizacion #' . $idCotizacion . ' de la solicitud #' . $idSolicitud . " a ANULADA.<br/> <b>MOTIVO:</b>" . $observacion);
		$estadoCot->Create();


		//Anulo la cotizacion
		$cotizacion = new cotizacion_solic_prov($idCotizacion);
		$cotizacion->setid_estados(12);
		$cotizacion->setuserModif($_COOKIE['user']);
		$cotizacion->cambiarEstado();
	}



	/**************************************************************************************************** */

	$sol_estado = new cotizaciones_estados();
	$sol_estado->setid_cotizacion($idCotizacion);
	$arrayEstadosSolicitud = $sol_estado->SelectAll();


	include_once '../../view/cotizaciones/estadosSolicitud.php';


} else {




	$sol_estado = new materiales_solicitudes_estados();

	$solicitud = new materiales_solicitudes($idSolicitud);
	$estado = mysqli_fetch_assoc($solicitud->getestado());
	$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : 31;

	$sol_estado->setid_solicitudes($solicitud->getid());

	$rand = rand(100, 999);

	//ESTADO ACTUAL DE LA SOLICITUD
	$objStatus = new materiales_solicitudes_estados();
	$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
	if ($x = mysqli_fetch_assoc($lastStatus)) {
		$estadoActual = $x['id_estados'];
	}
	


	//PERMISOS DE ACCESO DEL USUARIO LOGEADO
	$objPermisos = new usuario_permisos_estados();
	$objPermisos->setidUsuario($_SESSION['idUsuario']);
	$permisosUser = $objPermisos->SelectForUser();

	$permisos = array();
	while ($p = mysqli_fetch_assoc($permisosUser)) {
		array_push($permisos, $p['idEstado']);
	}



	//PERMISOS DE ACCESO DEL USUARIO LOGEADO
	$objPermisos = new usuario_permisos_estados();
	$objPermisos->setidUsuario($_SESSION['idUsuario']);
	$permisosUser = $objPermisos->SelectForUser();

	$permisos = array();
	while ($p = mysqli_fetch_assoc($permisosUser)) {
		array_push($permisos, $p['idEstado']);
	}


	if ($tipo == 'obs' and strlen($observacion) > 0) {
		$sol_estado->setid_estados($idEstado);
		$sol_estado->setobservaciones($observacion);
		$sol_estado->setid_solicitudes($solicitud->getid());
		$sol_estado->Create();
		$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
		if ($x = mysqli_fetch_assoc($lastStatus)) {
			$estadoActual = $x['id_estados'];
		}
	}



	if (esMiembro($tipo, $permisos) and ($tipo == 36 and ($estadoActual == '31'  or $estadoActual == '34' or $estadoActual == '37'))) {

		//cambio a ANULADO al solciitud
		$sol_estado->setid_estados($tipo);
		$sol_estado->setobservaciones($observacion);
		$sol_estado->setid_solicitudes($solicitud->getid());
		$sol_estado->Create();

		//crear estado ANULADO (C)
		$estadoCot = new materiales_cotizaciones_estados();
		$estadoCot->setid_estados(12);
		$estadoCot->setid_cotizacion($idCotizacion);
		$estadoCot->setobservaciones('Cambio de estado Cotizacion #' . $idCotizacion . ' de la solicitud #' . $idSolicitud . " a ANULADA.<br/> <b>MOTIVO:</b>" . $observacion);
		$estadoCot->Create();


		//Anulo la cotizacion
		$cotizacion = new materiales_cotizacion_solic_prov($idCotizacion);
		$cotizacion->setid_estados(12);
		$cotizacion->setuserModif($_COOKIE['user']);
		$cotizacion->cambiarEstado();
	}

	if (esMiembro($tipo, $permisos) and ($tipo == 48 and ( $estadoActual == '37'))) {

		//cambio a PENDIENTE DE ENTREGA a la solciitud
		$sol_estado->setid_estados($tipo);
		$sol_estado->setobservaciones($observacion);
		$sol_estado->setid_solicitudes($solicitud->getid());
		$sol_estado->Create();

		//crear estado PENDIENTE DE ENTREGA (C)
		$estadoCot = new materiales_cotizaciones_estados();
		$estadoCot->setid_estados(50);
		$estadoCot->setid_cotizacion($idCotizacion);
		$estadoCot->setobservaciones('Cambio de estado Cotizacion #' . $idCotizacion . ' de la solicitud #' . $idSolicitud . " a PENDIENTE DE ENTREGA.<br/> <b>MOTIVO:</b>" . $observacion);
		$estadoCot->Create();


		//Anulo la cotizacion
		$cotizacion = new materiales_cotizacion_solic_prov($idCotizacion);
		$cotizacion->setid_estados(50);
		$cotizacion->setuserModif($_COOKIE['user']);
		$cotizacion->cambiarEstado();
	}

	if (esMiembro($tipo, $permisos) and ($tipo == 49 and ( $estadoActual == '48'))) {

		//cambio a ENTREGADO a la solciitud
		$sol_estado->setid_estados($tipo);
		$sol_estado->setobservaciones($observacion);
		$sol_estado->setid_solicitudes($solicitud->getid());
		$sol_estado->Create();

		//crear estado ENTREGADO (C)
		$estadoCot = new materiales_cotizaciones_estados();
		$estadoCot->setid_estados(51);
		$estadoCot->setid_cotizacion($idCotizacion);
		$estadoCot->setobservaciones('Cambio de estado Cotizacion #' . $idCotizacion . ' de la solicitud #' . $idSolicitud . " a ENTREGADO.<br/> <b>MOTIVO:</b>" . $observacion);
		$estadoCot->Create();


		//Anulo la cotizacion
		$cotizacion = new materiales_cotizacion_solic_prov($idCotizacion);
		$cotizacion->setid_estados(51);
		$cotizacion->setuserModif($_COOKIE['user']);
		$cotizacion->cambiarEstado();
	}




	/**************************************************************************************************** */

	$sol_estado = new materiales_cotizaciones_estados();
	$sol_estado->setid_cotizacion($idCotizacion);
	$arrayEstadosSolicitud = $sol_estado->SelectAll();


	include_once '../../view/cotizaciones/estadosSolicitud.php';
}
