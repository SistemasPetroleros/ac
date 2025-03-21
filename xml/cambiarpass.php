<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
include('../class.php');
$usuario=isset($_GET['usuario']) ? $_GET['usuario'] : '';
$passwordnuevo=isset($_GET['passwordnuevo']) ? $_GET['passwordnuevo'] : '';
$passwordnuevo2=isset($_GET['passwordnuevo2']) ? $_GET['passwordnuevo2'] : '';
$passwordanterior=isset($_GET['passwordanterior']) ? $_GET['passwordanterior'] : '';
$u = new usuarios();
$u->setusuario($usuario);
$u->setpass($passwordanterior);
$u->Validar();
if ($passwordnuevo==$passwordnuevo2 and $u->getid()>0)
{
    $u->setpass($passwordnuevo);
    $u->Save();
}
$array = $u->SelectAllxUsuarioyPass();    

$rawdata = array(); //creamos un array
$i = 0;

while ($x = mysql_fetch_assoc($array)) {
    $rawdata[$i] = $x;
    $i++;
}
//$rawdata['fechaMax'] = $fechaMax;
if (isset($_GET['callback'])) { // Si es una petición cross-domain  
    echo $_GET['callback'] . '(' . json_encode($rawdata) . ')';
} else // Si es una normal, respondemos de forma normal  
    echo json_encode($rawdata);

