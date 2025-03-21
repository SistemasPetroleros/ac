<?php 
error_reporting(0);

$draw= (isset($_POST['draw'])? $_POST['draw'] : 0 );
header('Content-Type: application/json; charset=utf-8'); ?>
{
    "draw": <?=$draw?>,
    "data": [ 
<?php
function RemoveSpecialChar($str)
{
    $res = preg_replace('/[0-9\@\.\;\" "]+/', '', $str);
    return $res;
}

include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_productos.php';
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

$solicitud = new materiales_solicitudes($idSolicitud);
$arrayEstado = $solicitud->getestado();
$estado = mysqli_fetch_assoc($arrayEstado);

$idEstado = isset($estado['idEstado'])?$estado['idEstado']:-1;
$items = new materiales_solicitudes_items();
$items->setid_solicitudes($idSolicitud);
$arrayItems = $items->SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion);

                        while ($x = mysqli_fetch_assoc($arrayItems)) {
                            echo $coma;

                            $botonEliminar = (($x['id'] > 0) AND $idEstado==31) ? '<button class=\'btn btn-circle btn-danger \' onclick=\'eliminarItem('.$idSolicitud.','.$x['id'].');\'><i class=\'fa fa-eraser\'></i></button>':'';
                                echo '["' . $x['cantidad'] . '", "' . $x['nombre'] . '", "' . ($x['descripcion']) . '", " ' . ($x['observaciones']) . '","'.$botonEliminar.'"]';
                            $coma=',';
                            //$cnt +=1;
                            
                            
                        }
         
                        
                        $cnt = $items->Cnt($search);
                        $xx = mysqli_fetch_assoc($cnt);

?>
],
"recordsTotal": <?=$xx['recordsTotal']?>,
"recordsFiltered": <?=$xx['recordsFiltered']?>
}                        

