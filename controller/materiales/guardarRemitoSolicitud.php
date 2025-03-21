<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../funciones.php';

$op = $_POST['op'];

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$data['nroRemito'] = $_POST['nroRemito'];
$data['fechaRemito'] = $_POST['fechaRemito'];
$data['obs'] = $_POST['obs'];
$data['idRemito'] = $_POST['idRemito'];



if ($op == "I") {
  $data['esTrazado'] = "0";
  if (!$solicitud->existeRemito($data)) {
    //inserto
    $r = $solicitud->addSolicitudRemito($data);

    if ($r) {

      $r1 = $solicitud->addSolictudRemitoDocs($r);
      if ($r1) echo "1";
      else echo "0";
    } else {
      echo "0";
    }
  } else echo "-1";
} else {
  //modifico
  $r = $solicitud->updateSolicitudRemito($data);
  if ($r)
    echo 1;
  else 0;
}
