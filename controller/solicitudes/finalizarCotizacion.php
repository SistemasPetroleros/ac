<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/cotizaciones_estados.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/cotizacion_items.php';
include_once '../../model/solicitudes.php';

$obs = $_POST['obs'];
$idSolicitud = $_POST['idSolicitud'];


//Primero busco todas las cotizaciones de la solicitud
$cotizacion = new cotizacion_solic_prov();
$items = new cotizacion_item();
$solestado = new solicitudes_estados('');
$resultadoPed = $items->getItemsPedido($idSolicitud);

if ($resultadoPed) {

    if (mysqli_num_rows($resultadoPed) > 0) {
        $resultado = $cotizacion->SelectAllCotizaciones($idSolicitud);
        while ($row = mysqli_fetch_assoc($resultado)) {
            $contAut = 0; //cuenta los autorizados por item
            $cantItem = 0; //cuenta la cantidad de items
            $resultadoI = $items->getItemsCotizacion($idSolicitud, $row['id_proveedores']);
            while ($rowI = mysqli_fetch_assoc($resultadoI)) {

                if ($rowI['cantidadAprob'] != "" and $rowI['cantidadAprob'] != "0" and $rowI['cantidadAprob'] != NULL) {
                    //cambiar a estado item a APROBADA y sumar autorizados
                    $items-> setcantidadAprob($rowI['cantidadAprob']);
                    $items->setid($rowI['idItemCot']);
                    $items->setid_estados(46);

                    $contAut++;
                } else {
                    //cambiar a estado item a RECHAZADO
                    $items-> setcantidadAprob($rowI['cantidadAprob']);
                    $items->setid($rowI['idItemCot']);
                    $items->setid_estados(47);
                }
                $items->Save();
                $cantItem++;
            } //while

            if ($contAut > 0 and $contAut == $cantItem) {
                //pasar la cotización  a estado APROBADA
                $estado = new cotizaciones_estados();
                $estado->setid_cotizacion($row['id']);
                $estado->setid_estados('10');
                $estado->setobservaciones('Cotización #' . $row['id'] . ' de la solicitud #' . $idSolicitud . ' pasa a estado APROBADA. <b>MOTIVO:</b>'.$obs);
                $estado->Create();

                $cotizacion->setid($row['id']);
                $cotizacion->setid_estados('10');
                $cotizacion->save();
            } else {
                if ($contAut > 0) {
                    //pasar la cotizacion a PARCIALMENTE APROBADA
                    //pasar la cotización  a estado APROBADA
                    $estado = new cotizaciones_estados();
                    $estado->setid_cotizacion($row['id']);
                    $estado->setid_estados('45');
                    $estado->setobservaciones('Cotización #' . $row['id'] . ' de la solicitud #' . $idSolicitud . ' pasa a estado PARCIALMENTE APROBADA. <b>MOTIVO:</b>'.$obs);
                    $estado->Create();

                    $cotizacion->setid($row['id']);
                    $cotizacion->setid_estados('45');
                    $cotizacion->save();
                } else {
                    //pasar la cotización a RECHAZADA
                    //pasar la cotización  a estado APROBADA
                    $estado = new cotizaciones_estados();
                    $estado->setid_cotizacion($row['id']);
                    $estado->setid_estados('40');
                    $estado->setobservaciones('Cotización #' . $row['id'] . ' de la solicitud #' . $idSolicitud . ' pasa a estado RECHAZADO AUDITORIA. <b>MOTIVO:</b>'.$obs);
                    $estado->Create();

                    $cotizacion->setid($row['id']);
                    $cotizacion->setid_estados('40');
                    $cotizacion->save();
                }
            }
        } //fin primer

        $est = new solicitudes_estados();
        $re = $est->getRolUserCarga();
        $rowes = mysqli_fetch_assoc($re);

         //cambiar estado a solicitud 
         $solestado->setid_solicitudes($idSolicitud);
         $solestado->setobservaciones($obs);

        if ($rowes['id'] == '8') {   //si la carga es desde un user de ortopedia, pasa a ADJUDICADO
            $solestado->setid_estados(37); //Adjudicado
        } else {
            //si la carga es desde un user de ospepri pasa a PENDIENTE DE COMPRA
            $solestado->setid_estados(5); //Pendiente de Compra 
        }

        $solestado->Create();

        echo "1";
    } else
        echo '-2';
} else
    echo "-1";









