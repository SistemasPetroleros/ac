<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(0);
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/personas.php';
include_once('../../model/solicitudes_items_traza.php');
include_once('../../model/solicitudes_items_traza_estado.php');
include_once('../../model/productos.php');
include_once '../../model/puntos_dispensa.php';
require_once('../../lib/nusoap/nusoap.php');

$error = '';

//obtengo datos solicitud
$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);
$esB24 = $solicitud->getesB24();

//obtengo datos de la persona asociada a la solicitud
$idPersona = $solicitud->getid_personas();
$persona = new Personas($idPersona);
$dni = $persona->getdni();
$apellido = $persona->getapellido();
$nombres = $persona->getnombre();
$codigoB24 = $persona->getcodigoB24();


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
    $error = "Error: " . var_dump($oSoap);
} else {

    $data = $_POST;

    $total = $data['long'];
    $i = 0;
    while ($i < $total) {
        if ($data['chequeado' . $i] == 1) { //Si se seleccionó el producto para informar
            $solicitudItemTraza = new SolicitudesItemTraza($data['idTabla' . $i]);

            $nroSerie = $solicitudItemTraza->getnroSerie();
            $fechaTrans = date('d/m/Y');
            $gtin = $solicitudItemTraza->getgtin();
            $horaTrans = date('H:i');
            $idEvento = 111; //dispensa (111 de farmacia a paciente, prueba: )
            $idOS = 127901;
            $nroLote = $solicitudItemTraza->getlote();
            $nroRemito = $solicitudItemTraza->getnroRemito();
            $tipoDoc = "";
            $time = strtotime($solicitudItemTraza->getfechaVenc());
            $fechaVenc = date('d/m/Y', $time);

            if ($esB24 == 1) {
                $apellido = $codigoB24;
                $nombres = "";
            }


            $argumentos = "<arg0>	
                                <f_evento>" . $fechaTrans . "</f_evento>
                                <h_evento>" . $horaTrans . "</h_evento>
                                <gln_origen>" . trim($glnOrigen) . "</gln_origen>
                                <gln_destino></gln_destino>
                                <n_remito>" . trim($nroRemito) . "</n_remito>
                                <n_factura></n_factura>
                                <vencimiento>" . $fechaVenc . "</vencimiento>
                                <gtin>" . trim($gtin) . "</gtin>
                                <lote>" . trim($nroLote) . "</lote>
                                <numero_serial>" . trim($nroSerie) . "</numero_serial>
                                <id_evento>" . $idEvento . "</id_evento>
                                <apellido>" . utf8_decode($apellido) . "</apellido>
                                <nombres>" . utf8_decode($nombres) . "</nombres>
                                <n_documento>" . $dni . "</n_documento>
                                <sexo></sexo>
                                <tipo_documento>" . $tipoDoc . "</tipo_documento>
                                <direccion></direccion>
                                <localidad></localidad> 
                                <numero></numero>
                                <piso></piso>
                                <dpto></dpto>
                                <n_postal></n_postal>
                                <telefono></telefono>
                                <id_obra_social>" . $idOS . "</id_obra_social>
                                <nro_asociado>" . $dni . "</nro_asociado>
                                <id_motivo_devolucion></id_motivo_devolucion>
                                <otro_motivo_devolucion></otro_motivo_devolucion>
                                <id_motivo_reposicion></id_motivo_reposicion>
                                <id_programa></id_programa>
                            </arg0>
                            <arg1>" . $user . "</arg1>
                            <arg2>" . $pass . "</arg2>";

            $oSoap->soap_defencoding = 'utf-8';
            $oSoap->encode_utf8 = false;
            $oSoap->decode_utf8 = false;
            $result = $oSoap->call('sendMedicamentos', $argumentos);

            if ($oSoap->fault) {
                $error = 'Fallo al enviar producto en los servicios de ANMAT.';
                //  print_r($result);
            } else {    // Chequea errores
                $err = $oSoap->getError();
                if ($err) {        // Muestra el error
                    $error = '<b>Error: </b>' . $err;
                } else {

                  //  print_r($result);

              

                    if ($result['resultado']=="true") {
                        //si se realizo con éxito la dispensa

                        

                        $objTraza = new SolicitudesItemTraza($data['idTabla' . $i]);
                        $objTraza->setid_dispensa($result['codigoTransaccion']);
                        $objTraza->setid_solicitud($idSolicitud);
                        $objTraza->setuserModif($_COOKIE['user']);

                        //update id_dispensa 
                        $idObj = $objTraza->Save();

                        if ($idObj) {
                            //Actualizo estado
                            //Finalizo el estado actual
                            $objTrazaEstado = new SolicitudesItemTrazaEstados($data['idTrazaEstado' . $i]);
                            $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                            $objTrazaEstado->setuserModif($_COOKIE['user']);
                            $objTrazaEstado->setid_item_traza($data['idTabla' . $i]);
                            $objTrazaEstado->Save();

                            //seteo objeto estado
                            $objTrazaEstado1 = new SolicitudesItemTrazaEstados('');
                            $objTrazaEstado1->setid_solicitud($idSolicitud);
                            $objTrazaEstado1->setid_item('');
                            $objTrazaEstado1->setid_estado('19');
                            $objTrazaEstado1->setid_item_traza($data['idTabla' . $i]);
                            $objTrazaEstado1->setfdesde(date('Y-m-d H:i:s'));
                            $objTrazaEstado1->setfhasta('');
                            $objTrazaEstado1->setobservaciones('Producto pasa a estado DISPENSADO. Nro. Transacción ANMAT: ' . $result['codigoTransaccion']);
                            $objTrazaEstado1->setuserAlta($_COOKIE['user']);

                            //creo estado del item en Dispensado
                            $objTrazaEstado1->Create();
                          }
                        } else {

                           

                            //error al intentar dispensar el producto al ANMAT
                            if (!array_key_exists('0', $result['errores'])) {
                                $listError = "Error " . $result['errores']['_c_error'] . ": " . $result['errores']['_d_error'];
                            } else {
                                $errores = $result['errores'];
                                for ($i = 0; $i < sizeof($errores); $i++) {

                                    $listError .= "Error " . $errores[$i]['_c_error'] . ": " . $errores[$i]['_d_error'] . "<br><br>";
                                }
                            }

                          

                            //Actualizo estado
                            //Finalizo el estado actual
                            $objTrazaEstado = new SolicitudesItemTrazaEstados($data['idTrazaEstado' . $i]);
                            $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                            $objTrazaEstado->setuserModif($_COOKIE['user']);
                            $objTrazaEstado->setid_item_traza($data['idTabla' . $i]);
                            $objTrazaEstado->Save();

                            //seteo objeto estado
                            $objTrazaEstado1 = new SolicitudesItemTrazaEstados('');
                            $objTrazaEstado1->setid_solicitud($idSolicitud);
                            $objTrazaEstado1->setid_item('');
                            $objTrazaEstado1->setid_estado('20');
                            $objTrazaEstado1->setid_item_traza($data['idTabla' . $i]);
                            $objTrazaEstado1->setfdesde(date('Y-m-d H:i:s'));
                            $objTrazaEstado1->setfhasta('');
                            $objTrazaEstado1->setobservaciones('Producto pasa a estado CON ERRORES(D). '.$listError);
                            $objTrazaEstado1->setuserAlta($_COOKIE['user']);

                            //creo estado del item en Con ERRORES (D)
                            $objTrazaEstado1->Create();
                        }
                    
                }
            }
        }//chequeado

        $i++;
    }//fin while
}


echo $error;
