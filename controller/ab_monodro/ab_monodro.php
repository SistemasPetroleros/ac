<?php
include_once '../config.php';
include_once '../../model/ab_monodro.php';
include_once '../funciones.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';




$rand = rand(100, 999);
$mensaje = '';

$obj = new ab_monodro($_POST['PRIMARY']);



if (isset($_POST['eliminar'])) {
    
    if ($_POST['rand'] == $_POST['rand2']) {
        $obj->Delete();
        $mensaje = "<script>notificar('El registro se elimino correctamente');</script>";
    } else {
        $mensaje = '<div class="alert alert-danger">
                                Debe reingresar el numero que aparece a la izquierda del boton Eliminar
                            </div>';
    }
}

if (isset($_POST['guardar'])) {


    $obj->setcodigo($_POST['codigo']);
    $obj->setdescripcion($_POST['descripcion']);
    

    ///falta agregar el usuario que genera el alta y la modificacion
    if($_POST['PRIMARY']>0){
        $x = $obj->Save();
        $mensaje = "<script>notificar('El registro se actualizo correctamente');</script>";
    }else{
        $x = $obj->Create();
        $mensaje = "<script>notificar('El registro se creo correctamente');</script>";
    }

    
    
    
}


$array = $obj->SelectAll();






include_once '../../view/ab_monodro/ab_monodro.php';           