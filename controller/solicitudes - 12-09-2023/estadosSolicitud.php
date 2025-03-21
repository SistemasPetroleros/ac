<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/solicitudes_items.php';
include_once '../funciones.php';
include_once '../enviar_mail.php';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';


$sol_estado = new solicitudes_estados();

$solicitud = new solicitudes($idSolicitud);
$estado = mysqli_fetch_assoc($solicitud->getestado());
$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : 1;



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

if (esMiembro($tipo, $permisos) and 
    (
		($tipo == 28 and  $estadoActual == '5') or
		($tipo == 27 and  $estadoActual == '6') or
		($tipo == 2 and  $estadoActual == '26') or
		($tipo == 26 and  $estadoActual == '2') or
		($tipo == 1 and  $estadoActual == '9')  or
		($tipo == 2 and ($estadoActual == '1' or $estadoActual == '3' or $estadoActual == '9')) or
		($tipo == 3 and ($estadoActual == '2')) or
		($tipo == 4 and ($estadoActual == '2')) or
		($tipo == 6 and ($estadoActual == '28' or $estadoActual == '7' or $estadoActual == '27')) or
		($tipo == 7 and ($estadoActual == '6')) or
		($tipo == 8 and ($estadoActual == '7')) or
		($tipo == 9 and ($estadoActual == '1' or $estadoActual == '2' or $estadoActual == '4' or $estadoActual == '6' or $estadoActual == '7')) or
		($tipo == 13 and ($estadoActual == '8')) or
		($tipo == 14 and ($estadoActual == '13')) or
		($tipo == 15 and ($estadoActual == '14'))  or
		($tipo == 23 and $estadoActual == '29') or
		($tipo == 29 and  $estadoActual == '15') or
		($tipo == 30 and  $estadoActual == '15') or
		($tipo == 29 and  $estadoActual == '30')
   )
) {
	$sol_estado->setid_estados($tipo);
	$sol_estado->setobservaciones($observacion);
	$sol_estado->setid_solicitudes($solicitud->getid());
	//if(existen Items y proveedores and idEstado==1) create else echo'notificar('')'

	$seguir = true;
	if ($tipo == 2 and  $estadoActual == '1') {
		$ok = 0;
		$objPr = new cotizacion_solic_prov();
		$proveedores = $objPr->SelectAllCotizaciones($idSolicitud);


		while ($x = mysqli_fetch_array($proveedores)) {
			$ok = 1;
		}

		if ($ok == 0) {
			echo '<script>
									notificar("Debe ingresar al menos un Proveedor antes de continuar.");
								</script>';
			$seguir = false;
		}

		$ok = 0;

		$objITem = new solicitudes_items();
		$items = $objITem->SelectSolicitudItems($idSolicitud);


		while ($y = mysqli_fetch_array($items)) {
			$ok = 1;
		}

		if ($ok == 0) {
			echo '<script>
									notificar("Debe ingresar al menos un Producto antes de continuar.");
								</script>';
			$seguir = false;
		}
	}


	//echo $seguir;

	if ($seguir == true) {


		$sol_estado->Create();

		if ($tipo == 2) {
			$csp = new cotizacion_solic_prov();
			$csp->setid_solicitudes($solicitud->getid());
			$csp->setuserModif($_SESSION['user']);
			$csp->QuitarAprobado();
		}


		if ($tipo == 1) {
			$csp = new cotizacion_solic_prov();
			$csp->setid_solicitudes($solicitud->getid());
			$csp->setuserModif($_SESSION['user']);
			$csp->VolverPendiente();
		}



		$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);

		if ($x = mysqli_fetch_assoc($lastStatus)) {
			$estadoActual = $x['id_estados'];
		}

		if ($estadoActual == 4) {
			//Enviar Correo a Proveedores
			include_once('enviarEmailsProveedores.php');
		}

		if ($estadoActual == 7) {
			//Enviar Correo a Afiliado confirmando la farmacia la recepcion de los medicamentos
			include_once('enviarEmailAfiliado.php');
		}

		echo '<script>
									notificar("Estado registrado correctamente");
								</script>';
	}
}


$arrayEstadosSolicitud = $sol_estado->SelectAll();


include_once '../../view/solicitudes/estadosSolicitud.php';
