<?php 

error_reporting(0);
include_once '../config.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../../model/solicitudes_items_traza_estado.php';
include_once '../funciones.php';


    $idSolicitud = isset($_POST['idSolicitud'])?$_POST['idSolicitud']:-1;
    $objTraza= new SolicitudesItemTraza('');

    $param['idSolicitud']=$idSolicitud;
    $param['idEstado']='16'; //pendientes
    $res1= $objTraza -> SelectItemsTraza($param);
    $cantFilasR= mysqli_num_rows($res1);

    
   if ($cantFilasR>0) echo "0";
    else echo "1";
        
 ?>       