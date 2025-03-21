<?php 
 include 'class.php';
 //include_once 'model/usuarios.php';
 include_once 'controller/login.php';
?>

<table class="table table-condensed table-hover">
    <tr>
        <th>Troquel</th>
        <th>Nombre</th>
        <th>Presentación</th>
        <th>Valor</th>
        <th>Cod. Barra</th>
        <th>Droga</th>
    </tr>
    
    

<?php


$rand = rand(100, 999);
$mensaje = '';

$iDisplayLength=isset($_POST['length'])?$_POST['length']:10;
$iDisplayStart=isset($_POST['start'])?$_POST['start']:0;
$order =2;
$direccion='asc';
$search=isset($_POST['search']) ? $_POST['search']:'';



                        $obj = new ab_manualdat();
                        $array = $obj->SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion);
                        while ($x = mysqli_fetch_assoc($array)) {
                            
                            $precio = number_format($x['precio']/100, 2, ',', '');
                            $fecha = substr($x['fecha'],6,2).'/'.substr($x['fecha'],4,2).'/'.substr($x['fecha'],0,4);
                            echo '<tr><td>' . $x['troquel'] . '</td><td>' . $x['nombre'] . '</td><td>' . $x['presentacion'] . '</td><td>' . $precio . '</td><td>' . $x['codbarra'] . '</td><td>' . $x['droga'] . '</td></tr>';
                            
                            
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


// $xx['recordsTotal']
// $xx['recordsFiltered']
                        
    if($xx['recordsFiltered']<=$iDisplayLength){
        echo '<tr><td colspan="6">Mostrando '.$xx['recordsFiltered'].' registros. ('.$search.')</td>';
    }else{
        echo '<tr><td colspan="6">Mostrando '.$iDisplayLength.' de '.$xx['recordsFiltered'].' filtrados. ('.$search.')</td></tr>';
    }                    
                        


?>
    
</table>  

