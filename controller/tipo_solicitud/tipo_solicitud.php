<?php
include_once '../config.php';
include_once '../funciones.php';
include_once '../../model/tipo_solicitud.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';



$rand = rand(100, 999);
$mensaje = '';

$obj = new tipo_solicitud($_POST['id']);



if (isset($_POST['eliminar'])) {
    
    if ($_POST['rand'] == $_POST['rand2']) {
        $obj->Delete();
        $mensaje = '<div class="alert alert-success">
                                El registro se borró correctamente.
                            </div>';
    } else {
        $mensaje = '<div class="alert alert-danger">
                                Debe reingresar el numero que aparece a la izquierda del boton Eliminar
                            </div>';
    }
}

if (isset($_POST['guardar'])) {


    $obj->setnombre($_POST['nombre']);
    

    ///falta agregar el usuario que genera el alta y la modificacion
    if($_POST['id']>0){
        $x = $obj->Save();
        $mensaje = '<div class="alert alert-success">
                                El registro se actualizó correctamente.
                            </div><br>';
    }else{
        $x = $obj->Create();
        $mensaje = '<div class="alert alert-success">
                                El registro se guardó correctamente.
                            </div><br>';
    }

    
    
    
}


$array = $obj->SelectAll();



include_once '../../view/tipo_solicitud/tipo_solicitud.php';           