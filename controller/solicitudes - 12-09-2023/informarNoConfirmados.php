<?php

include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once('../../model/solicitudes_items_traza.php');
include_once('../../model/solicitudes_items_traza_estado.php');
include_once('../../model/productos.php');
include_once '../../model/puntos_dispensa.php';
require_once('../../lib/nusoap/nusoap.php');
set_time_limit(180);
date_default_timezone_set('America/Argentina/Buenos_Aires');
// Notificar todos los errores excepto E_NOTICE
error_reporting(E_ALL ^ E_NOTICE);

$error = '';


$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$data = $_POST;
$objTraza = new SolicitudesItemTraza('');
$objProductos = new productos('');

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


$total = $_POST['long'];

$i = 0;
$argumentos = "<arg0>" . $user . "</arg0>
             <arg1>" . $pass . "</arg1>";
$xml = "";
while ($i < $total) {


  if ($data['chequeado' . $i] == 1) { //Si se seleccionó el producto para informar

    $resultado = $objProductos->SelectForGtin($data['gtin' . $i]); //Recupero el id del producto desde la tabla producto, vacio si no existe.
    if ($row = mysqli_fetch_assoc($resultado)) {
      if ($row['id'] != null and $row['id'] != "")
        $idProducto = $row['id'];
      else
        $idProducto = '';
    }

    $fechaActual = date('d/m/Y');
    $xml .= "	
      <arg2>
           <f_operacion>" . $fechaActual . "</f_operacion>
           <p_ids_transac>" . $data['idTrans' . $i] . "</p_ids_transac>
      </arg2>";

    $param['idSolicitud'] = $idSolicitud;
    $param['nroSerie'] = $data['serial' . $i];
    $param['remito'] = $data['remito' . $i];

    //buscar item traza por gtin y remito, si no existe se inserta.
    $resultadoTraza = $objTraza->SelectItemsTraza($param);

    if (mysqli_num_rows($resultadoTraza) == 0) {
      //no existe, inserto. Si existe, no hago nada.


      //Se crea Producto en tabla Traza items

      $fecha1 = explode("/", $data['vencimiento' . $i]);
      $fecha2 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];
      $fvenc = date('Y-m-d', strtotime($fecha2));


      $fecha11 = explode("/", $data['fevento' . $i]);
      $fecha22 = $fecha1[2] . "-" . $fecha11[1] . "-" . $fecha11[0];
      $fevento = date('Y-m-d', strtotime($fecha22));


      //creo item 
      $objTraza->setid_solicitud($idSolicitud);
      $objTraza->setid_item('');
      $objTraza->setid_producto($idProducto);
      $objTraza->setgtin($data['gtin' . $i]);
      $objTraza->setlote($data['lote' . $i]);
      $objTraza->setnombre($data['nombrem' . $i]);
      $objTraza->setnroSerie($data['serial' . $i]);
      $objTraza->setfechaVenc($fvenc);
      $objTraza->setnroRemito($data['remito' . $i]);
      $objTraza->setlaboratorio($data['laboratorio' . $i]);
      $objTraza->setfechaEvento($fevento);
      $objTraza->setid_recepcion('');
      $objTraza->setid_dispensa('');
      $objTraza->setesTrazable(1);
      $objTraza->setuserAlta($_COOKIE['user']);

      //funcion para crear objeto
      $idObj = $objTraza->Create();

      if ($idObj != 0) {

        //seteo objeto estado en PENDIENTE
        $objTrazaEstado = new SolicitudesItemTrazaEstados('');

        $objTrazaEstado->setid_solicitud($idSolicitud);
        $objTrazaEstado->setid_item('');
        $objTrazaEstado->setid_estado('16');
        $objTrazaEstado->setid_item_traza($idObj);
        $objTrazaEstado->setfdesde(date('Y-m-d H:i:s'));
        $objTrazaEstado->setfhasta('');
        $objTrazaEstado->setobservaciones('Producto pasa a estado PENDIENTE.');
        $objTrazaEstado->setuserAlta($_COOKIE['user']);

        //creo estado del item en PENDIENTE
        $objTrazaEstado->Create();
      }
    }
  }


  $i++;
} //while

$argumentos .= $xml;



$oSoap = new nusoap_client($wsdl, false);
$oSoap->setHeaders(file_get_contents($XmlSeguridad));


//INICIO LOG
$file = fopen("logRecepcion.txt", "a");
fwrite($file, "\n ID SOLICITUD:" . $idSolicitud . "\n ENTRADA:" . date('Y-m-d H:i:s') . "-" . $argumentos . "\n\n");
fclose($file);

$resultado = $oSoap->call('sendConfirmaTransacc', $argumentos);

if ($oSoap->fault) {
  $error = 'Fallo al intentar informar recepción a ANMAT.';

  $file = fopen("logRecepcion.txt", "a");
  $entradaFile = "Fallo al intentar informar recepción a ANMAT:" . date('Y-m-d H:i:s') . " - " . $oSoap->fault . "\n" . "\n";
  fwrite($file, $entradaFile);
  fclose($file);

  // print_r($resultado);
} else {  // Chequea errores
  $err = $oSoap->getError();
  if ($err) {    // Muestra el error
    $error = '<b>Error: </b>' . $err;
    
    $file = fopen("logRecepcion.txt", "a");
    $entradaFile = "GETERROR:::: Fallo al intentar informar recepción a ANMAT:" . date('Y-m-d H:i:s') . " - " . $err . "\n" . "\n";
    fwrite($file, $entradaFile);
    fclose($file);

  } else {

    //LOG	
    $file = fopen("logRecepcion.txt", "a");
    $entradaFile = "RESULTADO:" . date('Y-m-d H:i:s') . " - " . $resultado['resultado'] . "\n" . "ID ASOCIADO:" . $resultado['id_transac_asociada'] . "\n";
    fwrite($file, $entradaFile);
    fclose($file);

    //print_r($resultado);
    //Array ( [resultado] => true [id_transac_asociada] => 37235744 ) 
    $exito = strval($resultado['resultado']);
    if ($exito == "true") {
      //si se ejecuta con exito, cambio de estado a los items a recepcionado. Actualizo id_recepcion

      $i = 0;
      while ($i < $total) {

        if ($data['chequeado' . $i] == 1) { //Si se seleccionó el producto para informar

          $param['idSolicitud'] = $idSolicitud;
          $param['nroSerie'] = $data['serial' . $i];
          $param['remito'] = $data['remito' . $i];

          //buscar item traza por gtin y remito, si existe se cambia de estado.
          $resultadoTraza = $objTraza->SelectItemsTraza($param);

          if (mysqli_num_rows($resultadoTraza) == 1) {

            $row = mysqli_fetch_assoc($resultadoTraza);

            //Actualizar id recepcion
            $objTraza = new SolicitudesItemTraza($row['id']);
            $objTraza->setid_recepcion($resultado['id_transac_asociada']);
            $objTraza->Save();


            //cambio de estado


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
            $objTrazaEstadox->setobservaciones('Producto pasa a estado RECEPCIONADO. Nro. Transacción ANMAT:' . $resultado['id_transac_asociada']);
            $objTrazaEstadox->setuserAlta($_COOKIE['user']);
            $objTrazaEstadox->Create();
          }
        }

        $i++;
      }
    } else {
      //si se ejecuta con error, dejo los items en estado pendiente y cargo un registro de error

      $listError = "";

      if (!array_key_exists('0', $resultado['errores'])) {
        $listError = "<b>Error " . $resultado['errores']['_c_error'] . "</b>: " . utf8_decode($resultado['errores']['_d_error']);
      } else {
        $errores = $resultado['errores'];
        for ($i = 0; $i < sizeof($errores); $i++) {
          $listError .= "<b>Error " . $errores[$i]['_c_error'] . ":</b>" . utf8_decode($errores[$i]['_d_error']) . "<br><br>";
        }
      }
	  
	  

      //LOG ERRORES
      $file = fopen("logRecepcion.txt", "a");
      $entradaFile = 'ERRORES:' . date('Y-m-d H:i:s') . " - " . $listError . "\n ID. ASOCIADO: " . $resultado['id_transac_asociada'] . "\n";
      fwrite($file, $entradaFile);
      fclose($file);


      $i = 0;
      while ($i < $total) {

        if ($data['chequeado' . $i] == 1) { //Si se seleccionó el producto para informar

          $param['idSolicitud'] = $idSolicitud;
          $param['nroSerie'] = $data['serial' . $i];
          $param['remito'] = $data['remito' . $i];

          //buscar item traza por gtin y remito, si existe se cambia de estado.
          $resultadoTraza = $objTraza->SelectItemsTraza($param);

          if (mysqli_num_rows($resultadoTraza) == 1) {

            $row = mysqli_fetch_assoc($resultadoTraza);

            //cambio de estado
            $objTrazaEstado = new SolicitudesItemTrazaEstados('');

            //seteo objeto estado
            $objTrazaEstado->setid_solicitud($idSolicitud);
            $objTrazaEstado->setid_item('');
            $objTrazaEstado->setid_estado('18');
            $objTrazaEstado->setid_item_traza($idObj);
            $objTrazaEstado->setfdesde(date('Y-m-d H:i:s'));
            $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
            $objTrazaEstado->setobservaciones($listError . ' <br/> Intente nuevamente informar desde Lista de Transacciones No Confirmadas.');
            $objTrazaEstado->setuserAlta($_COOKIE['user']);
            $objTrazaEstado->Create();
          }
        }

        $i++;
      }
    }
  }
}



echo $error;
