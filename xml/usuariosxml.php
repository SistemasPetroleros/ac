<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
include('../class.php');
$usuario=isset($_GET['usuario']) ? $_GET['usuario'] : '';
$pass=isset($_GET['pass']) ? $_GET['pass'] : '';
$pass = md5($pass);
$u = new usuarios();
$u->setuser($usuario);
$u->setpass($pass);
$array = $u->SelectAllAppAndroid();    

$rawdata = array(); //creamos un array
$i = 0;

while ($x = mysqli_fetch_assoc($array)) {
    $rawdata[$i] = $x;
    $i++;
}
//$rawdata['fechaMax'] = $fechaMax;
if (isset($_GET['callback'])) { // Si es una petición cross-domain  
    echo $_GET['callback'] . '(' . json_encode($rawdata) . ')';
} else // Si es una normal, respondemos de forma normal  
    echo json_encode($rawdata);

    
