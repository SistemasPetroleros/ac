<?php
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../funciones.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizaciones_estados.php';


$persona = new personas($_POST['idPersona']);
$persona->settelefono($_POST['telefono']);
$persona->setemail($_POST['email']);
$persona->Save();

$activo = $persona->getestadoSIA();

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$idProveedor = isset($_POST['idProveedor']) ? $_POST['idProveedor'] : -1;


$sol_estado = new materiales_solicitudes_estados();
$cotizacion = new materiales_cotizacion_solic_prov();

$solicitud = new materiales_solicitudes($idSolicitud);
$estado = mysqli_fetch_assoc($solicitud->getestado());
$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : 31;

$sol_estado->setid_estados($idEstado);



$solicitud->setid_personas($_POST['idPersona']);
$solicitud->setobservaciones($_POST['observaciones']);
$solicitud->setid_puntos_dispensa($_POST['puntoDispensa']);
$solicitud->setesSur($_POST['esSur']);
$solicitud->setfecha_vigencia_cotiz(date('Y/m/d'));
$solicitud->setid_tipo_solicitud(3);
$solicitud->setidCategoria('5');
$solicitud->seturgente('1');

if ($solicitud->getid() > 0) {
    $solicitud->Save();
    $sol_estado->setobservaciones('Solicitud actualizada');
} else {
    $solicitud->Create();
    $sol_estado->setobservaciones('Registro inicial');

    $cotizacion->setid_estados(42);
    $cotizacion->setid_solicitudes($solicitud->getid());
    $cotizacion->setid_proveedores($idProveedor);
    $cotizacion->setuserAlta($_SESSION['user']);
    $cotizacion->Create();

    //crear estado 
    $estadoCot = new materiales_cotizaciones_estados();
    $estadoCot->setid_estados(42);
    $estadoCot-> setid_cotizacion($cotizacion->getid());
    $estadoCot->setobservaciones('Registro Inicial Cotizacion #'.$cotizacion->getid().' de la solicitud #'.$solicitud->getid());
    $estadoCot->Create();



}


$sol_estado->setid_solicitudes($solicitud->getid());
$sol_estado->Create();


$puntosdispensa = new puntos_dispensa();
$arrayPuntosDispensa = $puntosdispensa->SelectAll();

$rand = rand(100, 999);
include_once '../../view/cotizaciones/iniciarSolicitud.php';
?>
<script>
    $("ul.nav li").removeClass('disabledTab');
    traerItemsMateriales(1);
    estadosSolicitud(1);
    traerAdjuntos(2,<?=$solicitud->getid()?>);
    <?php
    if (($idSolicitud < 0 or $idSolicitud == '') and $solicitud->getid() > 0) {
    ?>
        setTimeout(function() {
            $('#pestItemsc').tab('show');
        }, 1000);
  

    <?php
    }
    ?>
</script>





<?php
/*
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/solicitudes_estados.php';
include_once '../funciones.php';
//error_reporting(-1);

$persona = new personas($_POST['idPersona']);
$persona->settelefono($_POST['telefono']);
$persona->setemail($_POST['email']);
$persona->Save();

$activo = $persona->getestadoSIA();

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;


$sol_estado = new solicitudes_estados();

$solicitud = new solicitudes($idSolicitud);
$estado = mysqli_fetch_assoc($solicitud->getestado());
$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : 1;

$sol_estado->setid_estados($idEstado);



$solicitud->setid_personas($_POST['idPersona']);
$solicitud->setobservaciones($_POST['observaciones']);
$solicitud->setid_puntos_dispensa($_POST['puntoDispensa']);
$solicitud->setesB24($_POST['esB24']);
$solicitud->setesSur($_POST['esSur']);
$solicitud->setfecha_vigencia_cotiz($_POST['fecha_vigencia_cotiz']);
$solicitud->setid_tipo_solicitud(3);
if ($solicitud->getid() > 0) {
    $solicitud->Save();
    $sol_estado->setobservaciones('Solicitud actualizada');
} else {
    $solicitud->Create();
    $sol_estado->setobservaciones('Registro inicial');
}


$sol_estado->setid_solicitudes($solicitud->getid());
$sol_estado->Create();


$puntosdispensa = new puntos_dispensa();
$arrayPuntosDispensa = $puntosdispensa->SelectAll();

$rand = rand(100, 999);
include_once '../../view/cotizaciones/iniciarSolicitud.php';
?>
<script>
    $("ul.nav li").removeClass('disabledTab');
    //traerItems();
    estadosSolicitud();
    //proveedoresSolicitud();
    traerAdjuntos();
    <?php
    if (($idSolicitud < 0 or $idSolicitud == '') and $solicitud->getid() > 0) {
    ?>
        setTimeout(function() {
            $('#pestItems').tab('show');
        }, 1000);
        setTimeout(function() {
            $('#btnNuevoProducto').click();
        }, 1100);

    <?php
    }
    ?>
</script>
*/
?>