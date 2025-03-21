<?php
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../funciones.php';


$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;


$sol_estado = new materiales_solicitudes_estados();

$solicitud = new materiales_solicitudes($idSolicitud);
$persona = new personas($solicitud->getid_personas());
$estado = mysqli_fetch_assoc($solicitud->getestado());
$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : 1;

$activo = $persona->getestadoSIA();

$puntosdispensa = new puntos_dispensa();
$arrayPuntosDispensa = $puntosdispensa->SelectAll();

$rand = rand(100, 999);
include_once '../../view/materiales/iniciarSolicitud.php';
?>
<script>
    setTimeout(function() {
       // $("ul.nav li").removeClass('disabledTab');
       $("#itemsTab").removeClass('disabledTab');
       $("#proveedoresTab").removeClass('disabledTab');
       $("#adjuntosTab").removeClass('disabledTab');
       $("#estadoTab").removeClass('disabledTab');
      
      

        traerItems();
        estadosSolicitud();
        proveedoresSolicitud();
        traerAdjuntos();
      //  traerTrazaProductos();
     //   traerRemitos();
                }, 300);

        $('.tab-pane').removeClass('active in');
        $("#solicitudTab").tab('show');
        $("#solicitud").addClass('active in');
</script>