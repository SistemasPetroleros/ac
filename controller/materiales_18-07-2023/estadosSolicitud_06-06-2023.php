<?php
//error_reporting(-1);
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../funciones.php';
include_once '../enviar_mail.php';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';


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

//echo "EL TIPO: ".$tipo." y EL ACTUAL: ".$estadoActual;

if (esMiembro($tipo, $permisos) and 
    (
		
		($tipo == 31 and  $estadoActual == '36')  or
		($tipo == 32 and ($estadoActual == '31' or $estadoActual == '33' or $estadoActual == '36')) or
		($tipo == 33 and ($estadoActual == '32')) or
		($tipo == 34 and ($estadoActual == '32')) or
		($tipo == 36 and ($estadoActual == '31' or $estadoActual == '32' or $estadoActual == '34')) or
		($tipo == 37 and  $estadoActual == '35') or
		($tipo == 32 and  $estadoActual == '38') or
		($tipo == 38 and  $estadoActual == '32') or
		($tipo == 43 and  $estadoActual == '37') or
		($tipo == 44 and  $estadoActual == '43')
   )
) {
	$sol_estado->setid_estados($tipo);
	$sol_estado->setobservaciones($observacion);
	$sol_estado->setid_solicitudes($solicitud->getid());
	//if(existen Items y proveedores and idEstado==1) create else echo'notificar('')'


	$seguir = true;
	if ($tipo == 32 and  $estadoActual == '31') {
		
		$ok = 0;
		$objPr = new materiales_cotizacion_solic_prov();
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

		$objITem = new materiales_solicitudes_items();
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

		if ($tipo == 32) {
			//si el estado a cambiar es a PENDIENTE DE AUDITORIA, debo crear los items de la cotizacion
			$csi = new materiales_cotizacion_item();
			$csi->insertarItemsCotizacionesPorQuery($idSolicitud);
			
			
			$csp = new materiales_cotizacion_solic_prov();
			$csp->setid_solicitudes($solicitud->getid());
			$csp->setuserModif($_SESSION['user']);
			$csp->QuitarAprobado();
		}


		if ($tipo == 31) {
			$csp = new materiales_cotizacion_solic_prov();
			$csp->setid_solicitudes($solicitud->getid());
			$csp->setuserModif($_SESSION['user']);
			$csp->VolverPendiente();
		}



		$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);

		if ($x = mysqli_fetch_assoc($lastStatus)) {
			$estadoActual = $x['id_estados'];
		}

		if ($estadoActual == 34) {
			//Enviar Correo a Proveedores
			//include_once('enviarEmailsProveedores.php');
		}

		/*if ($estadoActual == 7) {
			//Enviar Correo a Afiliado confirmando la farmacia la recepcion de los medicamentos
			include_once('enviarEmailAfiliado.php');
		}*/

		echo '<script>
									notificar("Estado registrado correctamente");
								</script>';
	}
}


$arrayEstadosSolicitud = $sol_estado->SelectAll();


include_once '../../view/materiales/estadosSolicitud.php';
