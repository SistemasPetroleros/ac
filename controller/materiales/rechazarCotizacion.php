<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/materiales_cotizaciones_estados.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizacion_items.php';

$citems = new materiales_cotizacion_item();
$solestado = new materiales_solicitudes_estados('');
$cotestado = new materiales_cotizaciones_estados('');


$obs = $_POST['obs'];
$idSolicitud = $_POST['idSolicitud'];

$resultado = $citems->getItemsPedido($idSolicitud);
if ($resultado) {

    if (mysqli_num_rows($resultado) > 0) {

        while ($row = mysqli_fetch_assoc($resultado)) {

            //Cambiar estado a cotizacion (cuando se vaya a cotizar con varios proveedores, ver esto, creo que no funcionaria, xq se deberia rechazar los que no tienen ningun item aprobado)
            $cotestado->setid_cotizacion($row['idCotizacion']);
            $cotestado->setobservaciones($obs);
            $cotestado->setid_estados(40); //rechazado   
            $r = $cotestado->Create();

            if (!$r) {
                echo "-1";
            } else {

                $cotizacion = new materiales_cotizacion_solic_prov($row['idCotizacion']);
                $cotizacion->setuserModif($_SESSION['user']);
                $cotizacion->setid_estados(40); //rechazado
                $cotizacion->cambiarEstado();

                //cambiar estado a solicitud a RECHAZADO POR AUDITORIA 
                $solestado->setid_solicitudes($idSolicitud);
                $solestado->setobservaciones($obs);
                $solestado->setid_estados(33); //Rechazado   
                $solestado->Create();

                echo "1";
            }
        } //while
    } else {

         $itemsC= $citems->getItemsCotizacion($idSolicitud,'');
         while ($row = mysqli_fetch_assoc($itemsC)) {
            
            //Cambiar estado a cotizacion (cuando se vaya a cotizar con varios proveedores, ver esto, creo que no funcionaria, xq se deberia rechazar los que no tienen ningun item aprobado)
            $cotestado->setid_cotizacion($row['idCotizacion']);
            $cotestado->setobservaciones($obs);
            $cotestado->setid_estados(40); //rechazado   
            $r = $cotestado->Create();

            if (!$r) {
                echo "-1";
            } else {

                $cotizacion = new materiales_cotizacion_solic_prov($row['idCotizacion']);
                $cotizacion->setuserModif($_SESSION['user']);
                $cotizacion->setid_estados(40); //rechazado
                $cotizacion->cambiarEstado();

                //cambiar estado a solicitud a RECHAZADO POR AUDITORIA 
                $solestado->setid_solicitudes($idSolicitud);
                $solestado->setobservaciones($obs);
                $solestado->setid_estados(33); //Rechazado   
                $solestado->Create();

                echo "1";
            }

         }

    }
} else {
    echo "-1";
}
