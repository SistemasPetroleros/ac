{
    "draw": <?=$_POST['draw']?>,
    "data": [
<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_items.php';
include_once '../../model/productos.php';
include_once '../funciones.php';
set_time_limit(180);
ini_set('max_execution_time', 3600);

$coma='';
$rand = rand(100, 999);
$mensaje = '';
//$cnt=0;

$idSolicitud = isset($_GET['idSolicitud']) ? $_GET['idSolicitud'] : 0;

$iDisplayLength=isset($_POST['length'])?$_POST['length']:10;
$iDisplayStart=isset($_POST['start'])?$_POST['start']:0;
$order=isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:0;
$order +=1;
$direccion=isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:'';
$search=isset($_POST['search']['value']) ? $_POST['search']['value']:'';

$solicitud = new solicitudes($idSolicitud);
$arrayEstado = $solicitud->getestado();
$estado = mysqli_fetch_assoc($arrayEstado);

$idEstado = isset($estado['idEstado'])?$estado['idEstado']:-1;
//echo $idEstado;
$items = new solicitudes_items();
$items->setid_solicitudes($idSolicitud);
$arrayItems = $items->SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion);



                     //   $objVI = new ventaitem();
                      //  $venta = new venta($idSolicitud);
                        
                        //$objVI->setid_venta($idSolicitud);
                        //$arrayVI = $objVI->SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion);
                        while ($x = mysqli_fetch_assoc($arrayItems)) {
                            echo $coma;
                            //$habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
                            $botonEliminar = (($x['id'] > 1) AND $idEstado==1) ? '<button class=\'btn btn-circle btn-danger \' onclick=\'eliminarItem('.$idSolicitud.','.$x['id'].');\'><i class=\'fa fa-eraser\'></i></button>':'';
                            //$fecha = substr($x['fecha'],6,2).'/'.substr($x['fecha'],4,2).'/'.substr($x['fecha'],0,4);
                                echo '["' . $x['cantidad'] . '", "' . $x['nombre'] . '", "' . $x['presentacion'] . '", "' . $x['observaciones'] . '", "' . $x['monodroga'] . '","'.$botonEliminar.'"]';
                            $coma=',';
                            //$cnt +=1;
                            
                            
                        }
                        /*
                        $receta = new recetas();
                        $receta->setid_venta($idSolicitud);
                        $arrayRE = $receta->SelectResumenXVenta();
                        while ($x = mysqli_fetch_assoc($arrayRE)) {
                            echo $coma;
                            //$habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
                            $precio = number_format($x['precioUnitario'], 2, ',', '');
                            $subtotal = number_format($x['cantidad'] * $x['precioUnitario'], 2, ',', '');
                            $botonEliminar = '';
                            echo '["' . $x['nombre'] . '", "' . $x['cantidad'] . ' ' . $x['tipoCantidad'] . '", "' . $precio . '", "' . $subtotal . '", "' . $botonEliminar . '"]';
                            $coma=',';
                        }
                        */
                        
                        $cnt = $items->Cnt($search);
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

