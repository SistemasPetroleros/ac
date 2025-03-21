<?php

include('../class.php');
$fechaMax = isset($_GET['fechaMax']) ? $_GET['fechaMax'] : '';
$id_usuarios = isset($_GET['id_usuarios']) ? $_GET['id_usuarios'] : '';
$u = new pedidos();
$u->setid_usuarios($id_usuarios);
//$u->setfm($fechaMax);

$array = $u->SelectAllxFecha();



Header('Content-type: text/xml');
echo mysql_XML($array);
//echo mysql_XML($array, 'AAA', 'BBB');
