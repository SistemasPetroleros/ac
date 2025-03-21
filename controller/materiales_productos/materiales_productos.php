<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/materiales_productos.php';
include_once '../funciones.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';


$rand = rand(100, 999);
$mensaje = '';

$obj = new materiales_productos($_POST['id']);


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




    if (isset($_POST['activo'])) {
        $activo = 1;
    } else {

        $activo = 0;
    }

    $obj->setnombre(strtoupper($_POST['nombre']));
    $obj->setdescripcion($_POST['descripcion']);
    $obj->setactivo($activo);
    $obj->setusrModif($_SESSION['user']);
    $obj->setusrAlta($_SESSION['user']);

    ///falta agregar el usuario que genera el alta y la modificacion
    if ($_POST['id'] > 0) {
        $x = $obj->Save();
        $mensaje = "<script>notificar('El registro se actualizo correctamente');</script>";
    } else {
        $x = $obj->Create();
        $mensaje = "<script>notificar('El registro se creó correctamente');</script>";
    }
}


$array = $obj->SelectAll();





include_once '../../view/materiales_productos/materiales_productos.php';
