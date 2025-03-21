<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(0);
session_start();
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../../model/puntos_dispensa.php';
require_once('../../lib/nusoap/nusoap.php');
include_once '../../model/solicitudes_items_traza_estado.php';
set_time_limit(300);
// Notificar todos los errores excepto E_NOTICE
error_reporting(0);


$error = "";

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$evento = $_POST['evt'];

if ($evento == 'R') {
    $idEvento = '';
    $idEstado = '3';
}
if ($evento == 'D') {
    $idEvento = '111';
    $idEstado = '5';
}


$solicitud = new Solicitudes($idSolicitud);

//Obtengo datos pto de dispensa
$idPtoDispensa = $solicitud->getid_puntos_dispensa();
$ptoDispensa = new puntos_dispensa($idPtoDispensa);
$glnOrigen = $ptoDispensa->getGLN();
$user = $ptoDispensa->getuserAnmat();
$pass = $ptoDispensa->getclaveAnmat();

//Testing
/*$wsdl = "https://servicios.pami.org.ar/trazamed.WebService";
$user = "pruebasws";
$pass = "Clave1234";
$glnOrigen = "glnws";*/


//Produccion
$wsdl = "https://trazabilidad.pami.org.ar:9050/trazamed.WebService";


$XmlSeguridad =  "Seguridad.xml";

$tipo = "SOAP";
$oSoap = new nusoap_client($wsdl, false);
$oSoap->setHeaders(file_get_contents($XmlSeguridad));


if ($oSoap->fault) {
    echo "Error: ";
    var_dump($oSoap);
}

$objTraza = new SolicitudesItemTraza('');
$param['idSolicitud'] = $_POST['idSolicitud'];
$resultado = $objTraza->SelectItemsTraza($param);

while ($row = mysqli_fetch_assoc($resultado)) {
	
	//echo "ESTADO: ".$row['id_estado']."<br/> Id Item: ".$row['id'];

    if ($row['id_estado'] == 16 || $row['id_estado'] == 24) {
        //PENDIENTE  - RECEPCION ANULADA

        $argumento = "	<arg0>" . $user . "</arg0>
            <arg1>" . $pass . "</arg1>
            <arg2></arg2> <!--id transaccion global-->
            <arg3></arg3> <!--gln origen-->
            <arg4></arg4> <!--gln destino-->
            <arg5>" . $row['gtin'] . "</arg5> <!--gtin-->
            <arg6>" . $idEvento . "</arg6> <!--ID Evento -->
            <arg7></arg7> <!--Fecha Operación Desde-->
            <arg8></arg8> <!--Fecha Operación Hasta-->
            <arg9></arg9> <!--Fecha Transacción Desde-->
            <arg10></arg10> <!--Fecha Transacción Hasta-->
            <arg11></arg11> <!--Fecha Vencimiento Desde-->
            <arg12></arg12> <!--Fecha Vencimiento Hasta-->
            <arg13>" . $row['nroRemito'] . "</arg13> <!--Nro. Remito -->
            <arg14></arg14> <!--Nro. Factura -->
            <arg15>" . $idEstado . "</arg15> <!--ID estado -->
            <arg16></arg16> <!-- Número de página -->
            <arg17></arg17> <!-- ID Programa --> ";

        $oSoap->timeout = 300; // 60 segundos
        $oSoap->response_timeout = 300;
        $oSoap->soap_defencoding = 'utf-8';
        $oSoap->encode_utf8 = false;
        $oSoap->decode_utf8 = false;
        $result = $oSoap->call('getTransaccionesWS', $argumento);


        //INICIO LOG
        $file = fopen("logSincronizacion.txt", "a");
        fwrite($file, "\n\n-------------------------------------------------------------------------------------------------\n\n");
        fwrite($file, "\n ID SOLICITUD:" . $idSolicitud . "\n OPERACION: " . $_POST['evt'] . " \n ENTRADA:" . date('Y-m-d H:i:s') . "-" . $argumento . "\n\n");
        fclose($file);




        if ($oSoap->fault) {
            $error = 'Fallo al enviar producto en los servicios de ANMAT.';
        } else {    // Chequea errores
            $err = $oSoap->getError();
            if ($err) {        // Muestra el error
                $error = '<b>Error: </b>' . $err;
            } else {


                if (gettype($result) == "array" and count($result) > 0) {
                    if (!array_key_exists('0', $result['list'])) {

                        if ($result['list']['_numero_serial'] == $row['nroSerie']) {
                            //Actualizar id recepcion
                            $objTraza = new SolicitudesItemTraza($row['id']);
                            $objTraza->setid_recepcion($result['list']['_id_transaccion_global']);
                            $objTraza->Save();

                            //finalizo el estado actual
                            $objTrazaEstado = new SolicitudesItemTrazaEstados($row['idTrazaEstado']);
                            $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                            $objTrazaEstado->setuserModif($_COOKIE['user']);
                            $objTrazaEstado->setid_item_traza($row['id']);
                            $objTrazaEstado->Save();


                            //creo estado del item en Recepcionado
                            $objTrazaEstadox = new SolicitudesItemTrazaEstados('');
                            $objTrazaEstadox->setid_solicitud($idSolicitud);
                            $objTrazaEstadox->setid_item('');
                            $objTrazaEstadox->setid_estado('17');
                            $objTrazaEstadox->setid_item_traza($row['id']);
                            $objTrazaEstadox->setfdesde(date('Y-m-d H:i:s'));
                            $objTrazaEstadox->setfhasta('');
                            $objTrazaEstadox->setobservaciones('Producto pasa a estado RECEPCIONADO. Nro. Transacción ANMAT:' . $result['list']['_id_transaccion_global']);
                            $objTrazaEstadox->setuserAlta($_COOKIE['user']);
                            $objTrazaEstadox->Create();
                        }
                    } else {
                        $size = count($result['list']);
                        $i = 0;
                        while ($i < $size) {
                            if ($result['list'][$i]['_numero_serial'] == $row['nroSerie']) {

                                //Actualizar id recepcion
                                $objTraza = new SolicitudesItemTraza($row['id']);
                                $objTraza->setid_recepcion($result['list'][$i]['_id_transaccion_global']);
                                $objTraza->Save();

                                //finalizo el estado actual
                                $objTrazaEstado = new SolicitudesItemTrazaEstados($row['idTrazaEstado']);
                                $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                                $objTrazaEstado->setuserModif($_COOKIE['user']);
                                $objTrazaEstado->setid_item_traza($row['id']);
                                $objTrazaEstado->Save();


                                //creo estado del item en Recepcionado
                                $objTrazaEstadox = new SolicitudesItemTrazaEstados('');
                                $objTrazaEstadox->setid_solicitud($idSolicitud);
                                $objTrazaEstadox->setid_item('');
                                $objTrazaEstadox->setid_estado('17');
                                $objTrazaEstadox->setid_item_traza($row['id']);
                                $objTrazaEstadox->setfdesde(date('Y-m-d H:i:s'));
                                $objTrazaEstadox->setfhasta('');
                                $objTrazaEstadox->setobservaciones('Producto pasa a estado RECEPCIONADO. Nro. Transacción ANMAT:' . $result['list'][$i]['_id_transaccion_global']);
                                $objTrazaEstadox->setuserAlta($_COOKIE['user']);
                                $objTrazaEstadox->Create();
                            }

                            $i++;
                        }
                    }
                } else {

                    $file = fopen("logSincronizacion.txt", "a");
                    fwrite($file, "\n\n-------------------------------------------------------------------------------------------------\n\n");
                    fwrite($file, "* * * * * * * SIN RESULTADOS RECEPCION * * * * * * * *  \n\n");
                    fclose($file);
                    $error = 'No se encontraron resultados para sincronizar.';
                }
            }
        }
    } // fin if idEstado=16
    else {

       // error_reporting(-1);

        if ($row['id_estado'] == 17 || $row['id_estado'] == 25) {
            //RECEPCIONADO - DISPENSA ANULADA
			
			//echo "ENTRA";

            $argumento = "	<arg0>" . $user . "</arg0>
            <arg1>" . $pass . "</arg1>
            <arg2></arg2> <!--id transaccion global-->
            <arg3></arg3> <!--gln origen-->
            <arg4></arg4> <!--gln destino-->
            <arg5>" . $row['gtin'] . "</arg5> <!--gtin-->
            <arg6>" . $idEvento . "</arg6> <!--ID Evento -->
            <arg7></arg7> <!--Fecha Operación Desde-->
            <arg8></arg8> <!--Fecha Operación Hasta-->
            <arg9></arg9> <!--Fecha Transacción Desde-->
            <arg10></arg10> <!--Fecha Transacción Hasta-->
            <arg11></arg11> <!--Fecha Vencimiento Desde-->
            <arg12></arg12> <!--Fecha Vencimiento Hasta-->
            <arg13>" . $row['nroRemito'] . "</arg13> <!--Nro. Remito -->
            <arg14></arg14> <!--Nro. Factura -->
            <arg15>" . $idEstado . "</arg15> <!--ID estado -->
            <arg16></arg16> <!-- Número de página -->
            <arg17></arg17> <!-- ID Programa --> ";

            $oSoap->timeout = 300; // 60 segundos
            $oSoap->response_timeout = 300;
            $oSoap->soap_defencoding = 'utf-8';
            $oSoap->encode_utf8 = false;
            $oSoap->decode_utf8 = false;
            $result = $oSoap->call('getTransaccionesWS', $argumento);


            //INICIO LOG
            $file = fopen("logSincronizacion.txt", "a");
            fwrite($file, "\n\n-------------------------------------------------------------------------------------------------\n\n");
            fwrite($file, "\n ID SOLICITUD:" . $idSolicitud . "\n OPERACION: " . $_POST['evt'] . " \n ENTRADA:" . date('Y-m-d H:i:s') . "-" . $argumento . "\n\n");
            fclose($file);




            if ($oSoap->fault) {
                $error = 'Fallo al enviar producto en los servicios de ANMAT.';
				        fwrite($file, "\n\n-------------------------------------------------------------------------------------------------\n\n");
                        fwrite($file, "* * * * * * * ERRORES: ".$error."  \n\n");
                        fclose($file);
            } else {    // Chequea errores
                $err = $oSoap->getError();
                if ($err) {        // Muestra el error
                    $error = '<b>Error: </b>' . $err;
					$file = fopen("logSincronizacion.txt", "a");
                        fwrite($file, "\n\n-------------------------------------------------------------------------------------------------\n\n");
                        fwrite($file, "* * * * * * * ERRORES: ".$err."  \n\n");
                        fclose($file);
                } else {

                  //  print_r($result);
                    if (gettype($result) == "array" and count($result) > 0) {
                        if (!array_key_exists('0', $result['list'])) {

                            if ($result['list']['_numero_serial'] == $row['nroSerie']) {
                                //Actualizar id dispmesa
                                $objTraza = new SolicitudesItemTraza($row['id']);
                                $objTraza->setid_dispensa($result['list']['_id_transaccion_global']);
                                $objTraza->Save();

                                //finalizo el estado actual
                                $objTrazaEstado = new SolicitudesItemTrazaEstados($row['idTrazaEstado']);
                                $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                                $objTrazaEstado->setuserModif($_COOKIE['user']);
                                $objTrazaEstado->setid_item_traza($row['id']);
                                $objTrazaEstado->Save();


                                //creo estado del item en Recepcionado
                                $objTrazaEstadox = new SolicitudesItemTrazaEstados('');
                                $objTrazaEstadox->setid_solicitud($idSolicitud);
                                $objTrazaEstadox->setid_item('');
                                $objTrazaEstadox->setid_estado('19');
                                $objTrazaEstadox->setid_item_traza($row['id']);
                                $objTrazaEstadox->setfdesde(date('Y-m-d H:i:s'));
                                $objTrazaEstadox->setfhasta('');
                                $objTrazaEstadox->setobservaciones('Producto pasa a estado DISPENSADO. Nro. Transacción ANMAT:' . $result['list']['_id_transaccion_global']);
                                $objTrazaEstadox->setuserAlta($_COOKIE['user']);
                                $objTrazaEstadox->Create();
                            }
                        } else {
                            $size = count($result['list']);
                            $i = 0;
                            while ($i < $size) {
                                if ($result['list'][$i]['_numero_serial'] == $row['nroSerie']) {

                                    //Actualizar id dispensa
                                    $objTraza = new SolicitudesItemTraza($row['id']);
                                    $objTraza->setid_dispensa($result['list'][$i]['_id_transaccion_global']);
                                    $objTraza->Save();

                                    //finalizo el estado actual
                                    $objTrazaEstado = new SolicitudesItemTrazaEstados($row['idTrazaEstado']);
                                    $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                                    $objTrazaEstado->setuserModif($_COOKIE['user']);
                                    $objTrazaEstado->setid_item_traza($row['id']);
                                    $objTrazaEstado->Save();


                                    //creo estado del item en Dispensado
                                    $objTrazaEstadox = new SolicitudesItemTrazaEstados('');
                                    $objTrazaEstadox->setid_solicitud($idSolicitud);
                                    $objTrazaEstadox->setid_item('');
                                    $objTrazaEstadox->setid_estado('19');
                                    $objTrazaEstadox->setid_item_traza($row['id']);
                                    $objTrazaEstadox->setfdesde(date('Y-m-d H:i:s'));
                                    $objTrazaEstadox->setfhasta('');
                                    $objTrazaEstadox->setobservaciones('Producto pasa a estado DISPENSADO. Nro. Transacción ANMAT:' . $result['list'][$i]['_id_transaccion_global']);
                                    $objTrazaEstadox->setuserAlta($_COOKIE['user']);
                                    $objTrazaEstadox->Create();
                                }

                                $i++;
                            }
                        }
                    } else {

                        $file = fopen("logSincronizacion.txt", "a");
                        fwrite($file, "\n\n-------------------------------------------------------------------------------------------------\n\n");
                        fwrite($file, "* * * * * * * SIN RESULTADOS DISPENSA * * * * * * * *  \n\n");
                        fclose($file);
                        $error = 'No se encontraron resultados para sincronizar.';
                    }
                }
            }
        }
    }
}


echo $error;
