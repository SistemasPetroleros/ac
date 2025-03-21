<?php

include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../funciones.php';
include_once '../../model/solicitudes_items.php';
include_once '../../model/cotizacion_items.php';



$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');
$idItem = (isset($_POST['idItem']) ? $_POST['idItem'] : '');
$idItemCotizacion = (isset($_POST['idItemCotizacion']) ? $_POST['idItemCotizacion'] : '');
$accion = (isset($_POST['accion']) ? $_POST['accion'] : '');
$cantAprobada = (isset($_POST['cantAprobada']) ? $_POST['cantAprobada'] : '');
$importe = (isset($_POST['importe']) ? $_POST['importe'] : '0');


if ($accion == 'A') {

    $citems = new cotizacion_item($idItemCotizacion);

    if ($cantAprobada != "") {
        if ($cantAprobada > 0) {

            //verificar si ya esta agregado

            $cantCotizada = $citems->getcantidad();
            $importeUnitario = $citems->getimporte_unitario();

            if ($cantCotizada < $cantAprobada) {
                echo "-4";
            } else {
                $ri = $citems->cantidadTotalAprobada($idItem);
                $rowi = mysqli_fetch_assoc($ri);
                if (($rowi['suma'] + $cantAprobada) > $rowi['cantidad']) { //si la sumatoria de los items agregados + cantidad aprobada es mayor a la cantidad solicitada, error.
                    echo "-5";
                } else {
                    //echo "IMPORTE".$importe;
                    if (floatval($importeUnitario) == 0 && floatval($importe) == 0)
                        echo "-6"; //El producto no se puede agregar al pedido con importe = 0
                    else {
                        if(floatval($importe) != 0) $citems->setimporte_unitario($importe);
                        $citems->setcantidadAprob($cantAprobada);
                        $resultado = $citems->save(); //almaceno cantidad aprobada
                        if (!$resultado) {
                            echo "-3"; //error al actualizar
                        } else {
                            echo "1";
                        }
                    }
                }
            }
        } else {
            echo "-2"; //cantidad aprobada igual a cero
        }
    } else {
        echo "-1"; //cantidad aprobada vacia
    }
}
