<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(-1);
include_once '../config.php';
include_once('../../model/solicitudes_items_traza.php');
include_once('../../model/solicitudes_items_traza_estado.php');
include_once '../../model/puntos_dispensa.php';
include_once '../../model/solicitudes.php';
require_once('../../lib/nusoap/nusoap.php');

$error = '';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
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
    $error = "Error: ".var_dump($oSoap);
} else {

    $param = "  <arg0>" . $_POST['idTransaccion'] . "</arg0>
              <arg1>" . $user . "</arg1>
              <arg2>" . $pass . "</arg2>";

    //print_r( $param);					

    $oSoap->soap_defencoding = 'utf-8';
    $oSoap->encode_utf8 = false;
    $oSoap->decode_utf8 = false;
    $result = $oSoap->call('sendCancelacTransacc', $param);


   // print_r($result);
    

    if ($oSoap->fault) {
        $error = 'Fallo con Web Service ANMAT. Intente nuevamente en unos instantes.';
        //print_r($result);
    } else {    // Chequea errores
        $err = $oSoap->getError();
        if ($err) {        // Muestra el error
            $error = '<b>Error: </b>' . $err;
        } else {        // Muestra el resultado
            if ($result['resultado'] == "false") {
                if (!array_key_exists('0', $result['errores'])) {
                    $error = "Error " . $result['errores']['_c_error'] . ": " .  ($result['errores']['_d_error']);
                } else {
                    $errores = $result['errores'];
                    for ($i = 0; $i < sizeof($errores); $i++) {

                        $error .= "Error " . $errores[$i]['_c_error'] . ": " .  ($errores[$i]['_d_error']) . "<br><br>";
                    }
                }
            } else {

               // echo "ENTRA";
                $objTraza = new SolicitudesItemTraza($_POST['idItemTraza']);

                $paramx['idItem'] = $_POST['idItemTraza'];
                $paramx['idSolicitud'] = $idSolicitud;
                $resultadoTraza = $objTraza->SelectItemsTraza($paramx);
                $row = mysqli_fetch_assoc($resultadoTraza);

                $objTraza->setid_dispensa('');
                $objTraza->setuserModif($_COOKIE['user']);
                $objTraza->setid_solicitud($idSolicitud);
                $objTraza->setid($_POST['idItemTraza']);
                $idObj = $objTraza->Save();

                //Anula
                if ($idObj) {
                    //finalizo el estado actual
                    $objTrazaEstado = new SolicitudesItemTrazaEstados($row['idTrazaEstado']);
                    $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                    $objTrazaEstado->setuserModif($_COOKIE['user']);
                    $objTrazaEstado->setid_item_traza($_POST['idItemTraza']);
                    $objTrazaEstado->Save();

                    //seteo objeto estado
                    $objTrazaEstado->setid_solicitud($idSolicitud);
                    $objTrazaEstado->setid_item('');
                    $objTrazaEstado->setid_estado('25');
                    $objTrazaEstado->setid_item_traza($_POST['idItemTraza']);
                    $objTrazaEstado->setfdesde(date('Y-m-d H:i:s'));
                    $objTrazaEstado->setfhasta('');
                    $objTrazaEstado->setobservaciones('Producto pasa a estado DISPENSA ANULADA.');
                    $objTrazaEstado->setuserAlta($_COOKIE['user']);

                    //creo estado del item en Pendiente
                    $objTrazaEstado->Create();
                }
            }
        }
    }
}

echo $error;
