<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/proveedores.php';
include_once '../funciones.php';


$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$idCotizacion = isset($_POST['idCotizacion']) ? $_POST['idCotizacion'] : -1;
$tipoSolicitud = isset($_POST['tipoS']) ? $_POST['tipoS'] : -1;





if ($tipoSolicitud == 1) {
     $sol_estado = new solicitudes_estados();
     $solicitud = new solicitudes($idSolicitud);
     $cotizacion = new cotizacion_solic_prov($idCotizacion);
} else {
     $sol_estado = new materiales_solicitudes_estados();
     $solicitud = new materiales_solicitudes($idSolicitud);
     $cotizacion = new materiales_cotizacion_solic_prov($idCotizacion);
}

$proveedor = new proveedores($cotizacion->getid_proveedores());
$persona = new personas($solicitud->getid_personas());
$puntosdispensa = new puntos_dispensa($solicitud->getid_puntos_dispensa());


$rand = rand(100, 999);
include_once '../../view/cotizaciones/mostrarCotizacion.php';
?>

<script>
     
     traerItems();
     traerEstadosCotizacion();
     traerAdjuntos(1,<?=$idSolicitud?>);
     actualizarVisualizacion(<?=$idCotizacion?>);
</script>