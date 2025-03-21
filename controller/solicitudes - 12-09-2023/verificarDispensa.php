<?php 

    error_reporting(0);
    include_once '../config.php';
    include_once '../../model/solicitudes_items_traza.php';
    include_once '../funciones.php';


    $idSolicitud = isset($_POST['idSolicitud'])?$_POST['idSolicitud']:-1;
    $objTraza= new SolicitudesItemTraza('');

    $param['idSolicitud']=$idSolicitud;
    $param['idEstado']='19'; //dispensados
    $res1= $objTraza -> SelectItemsTraza($param);
    $cantFilasR= mysqli_num_rows($res1);


    $param1['idSolicitud']=$idSolicitud;
    $param1['idEstado']='18,19'; //dispensados
    $res2= $objTraza -> SelectItemsTraza($param1);
    $cantFilasI= mysqli_num_rows($res2);


    if($cantFilasR==$cantFilasI) //si la cantidad de dispensados es igual a la cantidad de items.
        echo 1;
    else
        echo 0;
        
 ?>       