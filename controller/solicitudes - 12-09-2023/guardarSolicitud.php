<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/solicitudes_estados.php';
include_once '../funciones.php';

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
if ($solicitud->getid() > 0) {
    $solicitud->Save();
    $sol_estado->setobservaciones('Solicitud actualizada');
}
else {
    $solicitud->Create();
    $sol_estado->setobservaciones('Registro inicial');

}


$sol_estado->setid_solicitudes($solicitud->getid());
$sol_estado->Create();


$puntosdispensa = new puntos_dispensa();
$arrayPuntosDispensa = $puntosdispensa->SelectAll();

$rand = rand(100, 999);
include_once '../../view/solicitudes/iniciarSolicitud.php';
?>
<script>
$("ul.nav li").removeClass('disabledTab');
traerItems();
estadosSolicitud();
proveedoresSolicitud();
traerAdjuntos();
<?php
if (($idSolicitud < 0 or $idSolicitud == '') and $solicitud -> getid() > 0) {
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