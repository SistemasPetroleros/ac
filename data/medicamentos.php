{
    "draw": <?=$_POST['draw']?>,
    "data": [
<?php
include '../class.php';
set_time_limit(120);
ini_set('max_execution_time', 3600);
//include 'login.php';
//include 'header.php';
$coma='';
$rand = rand(100, 999);
$mensaje = '';
//$cnt=0;

$iDisplayLength=isset($_POST['length'])?$_POST['length']:10;
$iDisplayStart=isset($_POST['start'])?$_POST['start']:0;
$order=isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:0;
$order +=1;
$direccion=isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:'';
$search=isset($_POST['search']['value']) ? $_POST['search']['value']:'';



                        $obj = new ab_manualdat();
                        $array = $obj->SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion);
                        while ($x = mysqli_fetch_assoc($array)) {
                            echo $coma;
                            $habi = ($x['troquel'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
                            $precio = number_format($x['precio']/100, 2, ',', '');
                            $fecha = substr($x['fecha'],6,2).'/'.substr($x['fecha'],4,2).'/'.substr($x['fecha'],0,4);
                            echo '["' . $x['troquel'] . '", "' . $x['nombre'] . '", "' . $x['presentacion'] . '", "' . $precio . '", "' . $fecha . '", "' . $x['codbarra'] . '", "' . $x['droga'] . '"]';
                            $coma=',';
                            //$cnt +=1;
                            
                            
                        }
                        $cnt = $obj->Cnt($search);
                        $xx = mysqli_fetch_assoc($cnt);
/*
$file = fopen("archivo.txt", "a+");
fwrite($file, "POST:".json_encode($_POST) . PHP_EOL);
fwrite($file, "GET:".json_encode($_GET) . PHP_EOL);
fwrite($file, "POST Search:".$_POST['search']['value'] . PHP_EOL);
fwrite($file, "Order:".$_POST['order'][0]['column'] . " - ". $_POST['order'][0]['dir'] . PHP_EOL);


fwrite($file, PHP_EOL . "--------------------------" . PHP_EOL);
fclose($file);
                        */
?>
],
"recordsTotal": <?=$xx['recordsTotal']?>,
"recordsFiltered": <?=$xx['recordsFiltered']?>
}                        