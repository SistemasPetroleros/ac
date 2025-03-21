<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(0);
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once('../../model/solicitudes_items_traza.php');
include_once('../../model/solicitudes_items_traza_estado.php');
include_once('../../model/productos.php');
include_once '../../model/puntos_dispensa.php';
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
//$wsdl = "https://servicios.pami.org.ar/trazamed.WebService";

/*$user = "pruebasws";
$pass = "Clave1234";
$glnOrigen = "glnws";*/


//Produccion
$wsdl = "https://trazabilidad.pami.org.ar:9050/trazamed.WebService";

$XmlSeguridad =  "Seguridad.xml";

$tipo = "SOAP";

$oSoap = new nusoap_client($wsdl, false);
$oSoap->setHeaders(file_get_contents($XmlSeguridad));

if ($oSoap->fault) {
  $error = "Error: ";
  var_dump($oSoap);
} else {

  $data = $_POST;
  $objTraza = new SolicitudesItemTraza('');
  $objTrazaEstado = new SolicitudesItemTrazaEstados('');
  $objProductos = new productos('');




  $total = $_POST['long'];


  $i = 0;
  while ($i < $total) {



    if ($data['chequeado' . $i] == 1) { //Si se seleccionó el producto para informar

      $resultado = $objProductos->SelectForGtin($data['gtin' . $i]); //Recupero el id del producto desde la tabla producto, vacio si no existe.
      if ($row = mysqli_fetch_assoc($resultado)) {
        if ($row['id'] != null and $row['id'] != "")
          $idProducto = $row['id'];
        else
          $idProducto = '';
      }


      //Informar al ANMAT (RECEPCION)

      $fechaActual = date('d/m/Y');
      $paramConf = "	<arg0>" . $user . "</arg0>
      <arg1>" . $pass . "</arg1>
      <arg2>
           <f_operacion>" . $fechaActual . "</f_operacion>
           <p_ids_transac>" . $data['idTrans' . $i] . "</p_ids_transac>
      </arg2>";

      //  echo $paramConf;

      $resultConf = $oSoap->call('sendConfirmaTransacc', $paramConf);

      if ($oSoap->fault) {
        $error = 'Fallo al intentar informar recepción a ANMAT.';
        // print_r($resultConf);
      } else {  // Chequea errores
        $err = $oSoap->getError();
        if ($err) {    // Muestra el error
          $error = '<b>Error: </b>' . $err;
        } else {


          // print_r($resultConf);
          if ($resultConf['resultado'] == true) { //Es exitoso

            $param['idSolicitud'] = $idSolicitud;
            $param['nroSerie'] = $data['serial' . $i];
            $param['remito'] = $data['remito' . $i];

            //buscar item traza por gtin y remito, si no existe se inserta sino se actualiza. Para ambos casos, se agrega un estado nuevo. Para el caso que exista, el estado anterior fdesde=now()
            $resultadoTraza = $objTraza->SelectItemsTraza($param);

            if (mysqli_num_rows($resultadoTraza) == 0) {
              //no existe, inserto


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
              $objTraza->setfechaEvento($fevento);
              $objTraza->setlaboratorio($data['laboratorio' . $i]);
              $objTraza->setid_recepcion($resultConf['id_transac_asociada']);
              $objTraza->setid_dispensa('');
              $objTraza->setesTrazable(1);
              $objTraza->setuserAlta($_COOKIE['user']);

              //funcion para crear objeto
              $idObj = $objTraza->Create();

              if ($idObj != 0) {
                //seteo objeto estado
                $objTrazaEstado->setid_solicitud($idSolicitud);
                $objTrazaEstado->setid_item('');
                $objTrazaEstado->setid_estado('17');
                $objTrazaEstado->setid_item_traza($idObj);
                $objTrazaEstado->setfdesde(date('Y-m-d H:i:s'));
                $objTrazaEstado->setfhasta('');
                $objTrazaEstado->setobservaciones('Producto pasa a estado RECEPCIONADO. Nro. Transacción ANMAT: ' . $resultConf['id_transac_asociada']);
                $objTrazaEstado->setuserAlta($_COOKIE['user']);

                //creo estado del item en Recepcionado
                $objTrazaEstado->Create();


                //Creo el remito y los documentos del mismo
                $datax['fechaRemito'] = "";
                $datax['nroRemito'] = $data['remito' . $i];
                $datax['obs'] = 'Remito agregado automáticamente por trazabilidad';
                $r = $solicitud->addSolicitudRemito($datax);
                if ($r) $solicitud->addSolictudRemitoDocs($r);
                
              }
            } else {


              //Existe 
              $row = mysqli_fetch_assoc($resultadoTraza);

              $objTraza->setid_recepcion($resultConf['id_transac_asociada']);
              $objTraza->setid_solicitud($idSolicitud);
              $objTraza->setuserModif($_COOKIE['user']);
              $objTraza->setid($row['id']);

              //update id_recepcion 
              $idObj = $objTraza->Save();

              if ($idObj) {

                //Actualizo estado
                //finalizo el estado actual
                $objTrazaEstado = new SolicitudesItemTrazaEstados($row['idTrazaEstado']);
                $objTrazaEstado->setfhasta(date('Y-m-d H:i:s'));
                $objTrazaEstado->setuserModif($_COOKIE['user']);
                $objTrazaEstado->setid_item_traza($row['id']);
                $objTrazaEstado->Save();

                //seteo objeto estado
                $objTrazaEstado1 = new SolicitudesItemTrazaEstados('');
                $objTrazaEstado1->setid_solicitud($idSolicitud);
                $objTrazaEstado1->setid_item('');
                $objTrazaEstado1->setid_estado('17');
                $objTrazaEstado1->setid_item_traza($row['id']);
                $objTrazaEstado1->setfdesde(date('Y-m-d H:i:s'));
                $objTrazaEstado1->setfhasta('');
                $objTrazaEstado1->setobservaciones('Producto pasa a estado RECEPCIONADO. Nro. Transacción ANMAT: ' . $resultConf['id_transac_asociada']);
                $objTrazaEstado1->setuserAlta($_COOKIE['user']);

                //creo estado del item en Pendiente
                $objTrazaEstado1->Create();
              }
            }
          } else {
            if (!array_key_exists('0', $resultConf['errores'])) {
              $listError = "Error " . $resultConf['errores']['_c_error'] . ": " . $result['errores']['_d_error'];
            } else {
              $errores = $resultConf['errores'];
              for ($i = 0; $i < sizeof($errores); $i++) {

                $listError .= "Error " . $errores[$i]['_c_error'] . ": " . $errores[$i]['_d_error'] . "<br><br>";
              }


              //Se crea Producto en tabla Traza items, con estado CON ERRORES (R) =18

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

                //seteo objeto estado
                $objTrazaEstado->setid_solicitud($idSolicitud);
                $objTrazaEstado->setid_item('');
                $objTrazaEstado->setid_estado('17');
                $objTrazaEstado->setid_item_traza($idObj);
                $objTrazaEstado->setfdesde(date('Y-m-d H:i:s'));
                $objTrazaEstado->setfhasta('');
                $objTrazaEstado->setobservaciones('Producto pasa a estado CON ERRORES(R). ' . $listError);
                $objTrazaEstado->setuserAlta($_COOKIE['user']);

                //creo estado del item en Pendiente
                $objTrazaEstado->Create();

                 //Creo el remito y los documentos del mismo
                 $datax['fechaRemito'] = "";
                 $datax['nroRemito'] = $data['remito' . $i];
                 $datax['obs'] = 'Remito agregado automáticamente por trazabilidad';
                 $r = $solicitud->addSolicitudRemito($datax);
                 if ($r) $solicitud->addSolictudRemitoDocs($r);


              }
            }
          }
        }
      }
    }


    $i++;
  }
}

echo $error;
