<?php
include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/cotizacion_items.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/materiales_cotizaciones_estados.php';
include_once '../funciones.php';

error_reporting(0);

$data = $_POST;

if ($data['idTipoSolicitud'] == 2 or $data['idTipoSolicitud'] == 3) {

    //ver cuantos items tiene la solicitud, si existe al menos 1 continuar
    $item = new materiales_cotizacion_item('');
    $res = $item->getItemsCotizacion($data['idSolicitud']);

    if (mysqli_num_rows($res) > 0) {

        //Actualizo cabecera cotizacion
        $cotizacion = new materiales_cotizacion_solic_prov($data['idCotizacion']);

        $item = new materiales_cotizacion_item('');

        $cotizacion->setvalidez_propuesta($data['validezPropuesta']);
        $cotizacion->setplazo_entrega_dias($data['plazo_entrega_dias']);
        $incluye_flete = ($data['incluye_flete'] == 'on' ? 1 : 0);
        $cotizacion->setincluye_flete($incluye_flete);
        $cotizacion->setcondiciones_pago($data['condiciones_pago']);
        $cotizacion->setobservaciones(trim($data['observaciones_cotizacion']));


        $tipo = $_POST['tipo'];
        $idEstado = 0;
        if ($tipo == 'B') $idEstado = 39;
        if ($tipo == 'P') $idEstado = 41;

        $resultado = $cotizacion->Save();

        if ($resultado) {
            //si no error al guardar

            $long = mysqli_num_rows($res);
            $i = 0;
            $exito = true;
            while ($i < $long) {
                //cantidad cotizada mayor a la solicitada
                if ($data['cant_' . $i] < $data['cantCot_' . $i]) {
                    echo 'error1_' . ($i + 1);
                    $exito = false;
                    break;
                }

                //cantidad cotizada menor a cero
                if ($data['cantCot_' . $i] < 0) {
                    echo 'error2_' . ($i + 1);
                    $exito = false;
                    break;
                }


                //precio unitario menor a cero
                if ($data['precioUnit' . $i] < 0) {
                    echo 'error3_' . ($i + 1);
                    $exito = false;
                    break;
                }

                //guardar info de los items con cantidad mayor a cero
                if ($data['cantCot_' . $i] > 0 and $data['precioUnit' . $i] > 0) {


                    $estadoCot = new materiales_cotizaciones_estados();

                    $item->setid_item($data['id_item_' . $i]);
                    $item->setid_proveedores($data['idProveedor']);
                    $item->setcantidad($data['cantCot_' . $i]);
                    $item->setimporte_unitario($data['precioUnit' . $i]);
                    $item->setmarca($data['marca' . $i]);
                    $item->setid_estados($idEstado);

                    if (!$item->existeItem()) {
                        $item->create();
                    } else {

                        $item->setid($data['idItemCot_' . $i]);
                        $item->save();
                    }
                }



                $i++;
            } //while

            if ($tipo == 'P' && $exito == true) {
                //cambio estado a la cotizacion
                $estadoCot->setid_cotizacion($data['idCotizacion']);
                $estadoCot->setid_estados('11'); //Pendiente
                $estadoCot->setobservaciones('Cambio de estado Cotizacion #'.$data['idCotizacion'].' de la solicitud #' . $data['idSolicitud'] . ' a PENDIENTE.<br/> <b>MOTIVO:</b>Realizado por proveedor.');
                $estadoCot->Create();


                $cotizacion->setid_estados("11");
                $cotizacion->save();
            }
        } else {
            echo 'error5';
        }
    } else {
        echo 'error4';
        exit;
    }
} else {

    /********************************ESTO ES PARA MEDICAMENTOS DE ALTO COSTO ************************************** */


    if ($data['long'] > 0) {


        //Actualizo cabecera cotizacion
        $cotizacion = new cotizacion_solic_prov($data['idCotizacion']);

        $item = new cotizacion_item('');


        $cotizacion->setvalidez_propuesta($data['validezPropuesta']);
        $cotizacion->setplazo_entrega_dias($data['plazo_entrega_dias']);
        $incluye_flete = ($data['incluye_flete'] == 'on' ? 1 : 0);
        $cotizacion->setincluye_flete($incluye_flete);
        $cotizacion->setcondiciones_pago($data['condiciones_pago']);
        $cotizacion->setobservaciones(trim($data['observaciones_cotizacion']));
        $cotizacion->setid_estados("11");

        $tipo = $_POST['tipo'];

        $idEstado = 0;
        if ($tipo == 'B') $idEstado = 39;
        if ($tipo == 'P') $idEstado = 41;


        $resultado = $cotizacion->save();

        if ($resultado) {


            $i = 0;
            while ($i < $data['long']) {


                //cantidad cotizada mayor a la solicitada
                if ($data['cant_' . $i] < $data['cantCot_' . $i]) {
                    echo 'error1_' . ($i + 1);
                    break;
                }

                //cantidad cotizada menor a cero
                if ($data['cantCot_' . $i] < 0) {
                    echo 'error2_' . ($i + 1);
                    break;
                }

                //precio unitario menor a cero
                if ($data['precioUnit' . $i] < 0) {
                    echo 'error3_' . ($i + 1);
                    break;
                }




                //guardar info de los items con cantidad mayor a cero
                if ($data['cantCot_' . $i] > 0 and $data['precioUnit' . $i] > 0) {

                    $item->setid_item($data['id_item_' . $i]);
                    $item->setid_proveedores($data['idProveedor']);
                    $item->setcantidad($data['cantCot_' . $i]);
                    $item->setimporte_unitario($data['precioUnit' . $i]);
                    $item->setmarca($data['marca' . $i]);
                    $item->setid_estados($idEstado);

                    if (!$item->existeItem()) {
                        $item->create();
                    } else {

                        $item->setid($data['idItemCot_' . $i]);
                        $item->save();
                    }
                }


                $i++;
            }
        } else {
            echo 'error5';
        }
    } else {
        echo 'error4';
    }
}
