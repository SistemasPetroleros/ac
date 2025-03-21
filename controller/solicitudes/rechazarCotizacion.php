<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/cotizaciones_estados.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/cotizacion_items.php';

$citems = new cotizacion_item();
$solestado = new solicitudes_estados('');
$cotestado = new cotizaciones_estados('');

$obs = $_POST['obs'];
$idSolicitud = $_POST['idSolicitud'];


$resultado = $citems->getItemsCotizacion($idSolicitud, '');
if (mysqli_num_rows($resultado) <= 0) {
    echo "-1";
    exit;
} else {

    while ($row = mysqli_fetch_assoc($resultado)) {

        //Cambiar estado a cotizacion (cuando se vaya a cotizar con varios proveedores, ver esto, creo que no funcionaria, xq se deberia rechazar los que no tienen ningun item aprobado)
        $cotestado->setid_cotizacion($row['idCotizacion']);
        $cotestado->setobservaciones($obs);
        $cotestado->setid_estados(40); //rechazado   
        $r = $cotestado->Create();

        if (!$r) {
            echo "-1";
            exit;
        } else {

            $cotizacion = new cotizacion_solic_prov($row['idCotizacion']);
            $cotizacion->setuserModif($_SESSION['user']);
            $cotizacion->setid_estados(40); //rechazado
            $cotizacion->cambiarEstado();


            //cambiar el estado al item a rechazado
            $citems->setcantidadAprob('');
            $citems->setid($row['idItemCot']);
            $citems->setid_estados(47);
            $citems->save();


            //cambiar estado a solicitud a RECHAZADO POR AUDITORIA 
            $solestado->setid_solicitudes($idSolicitud);
            $solestado->setobservaciones($obs);
            $solestado->setid_estados(3); //Rechazado   
            $solestado->Create();
        }
    } //while
    echo "1";
}





/*$resultado = $citems->getItemsPedido($idSolicitud);
if ($resultado) {

    if (mysqli_num_rows($resultado) > 0) {
        //rechazo todo el peido   

        while ($row = mysqli_fetch_assoc($resultado)) {


            //Cambiar estado a cotizacion (cuando se vaya a cotizar con varios proveedores, ver esto, creo que no funcionaria, xq se deberia rechazar los que no tienen ningun item aprobado)
            $cotestado->setid_cotizacion($row['idCotizacion']);
            $cotestado->setobservaciones($obs);
            $cotestado->setid_estados(40); //rechazado   
            $r = $cotestado->Create();

            if (!$r) {
                echo "-1";
                exit;
            } else {

                //cambiar al campo id_estados el estado
                $cotizacion = new cotizacion_solic_prov($row['idCotizacion']);
                $cotizacion->setuserModif($_SESSION['user']);
                $cotizacion->setid_estados(40); //rechazado
                $cotizacion->cambiarEstado();


                //cambiar el estado al item a rechazado
                $citems->setcantidadAprob('');
                $citems->setid($row['idItemCot']);
                $citems->setid_estados(47);
                $citems->save();

                //cambiar estado a solicitud a RECHAZADO POR AUDITORIA 
                $solestado->setid_solicitudes($idSolicitud);
                $solestado->setobservaciones($obs);
                $solestado->setid_estados(3); //Rechazado   
                $solestado->Create();
            }
        } //while
        echo "1";
    } else {

        $itemsC = $citems->getItemsCotizacion($idSolicitud, '');
        if (mysqli_num_rows($resultado) <= 0) {
            echo "-1";
            exit;
        } else {

            while ($row = mysqli_fetch_assoc($itemsC)) {

                //Cambiar estado a cotizacion (cuando se vaya a cotizar con varios proveedores, ver esto, creo que no funcionaria, xq se deberia rechazar los que no tienen ningun item aprobado)
                $cotestado->setid_cotizacion($row['idCotizacion']);
                $cotestado->setobservaciones($obs);
                $cotestado->setid_estados(40); //rechazado   
                $r = $cotestado->Create();

                if (!$r) {
                    echo "-1";
                    exit;
                } else {

                    $cotizacion = new cotizacion_solic_prov($row['idCotizacion']);
                    $cotizacion->setuserModif($_SESSION['user']);
                    $cotizacion->setid_estados(40); //rechazado
                    $cotizacion->cambiarEstado();


                    //cambiar el estado al item a rechazado
                    $citems->setcantidadAprob('');
                    $citems->setid($row['idItemCot']);
                    $citems->setid_estados(47);
                    $citems->save();


                    //cambiar estado a solicitud a RECHAZADO POR AUDITORIA 
                    $solestado->setid_solicitudes($idSolicitud);
                    $solestado->setobservaciones($obs);
                    $solestado->setid_estados(3); //Rechazado   
                    $solestado->Create();
                }
            } //while
            echo "1";
        }
    }
} else {
    echo "-1";
}*/
