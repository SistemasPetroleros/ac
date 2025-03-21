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
    $cantFilasD= mysqli_num_rows($res1);


    if($cantFilasD>0) //si hay dispensados, no debe volver habilitar recepcion
        echo 0;
    else
        echo 1;
        
 ?>       