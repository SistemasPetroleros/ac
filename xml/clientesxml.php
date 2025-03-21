<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
include('../class.php');
$fechaMax = isset($_GET['fechaMax']) ? $_GET['fechaMax'] : '';
$u = new clientes();
if (strlen($fechaMax)>1)
{
$array = $u->SelectAllxFecha($fechaMax);    
}else{
$array = $u->SelectAll();    
}


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

