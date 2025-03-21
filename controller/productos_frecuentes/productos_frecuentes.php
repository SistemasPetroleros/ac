<?php

error_reporting(0);
include_once '../config.php';
include_once '../../model/productos_frecuentes.php';
include_once '../funciones.php';
include_once '../../model/ab_monodro.php';
//include_once '../../model/localidades.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';



$rand = rand(100, 999);
$mensaje = '';

$obj = new productos($_POST['id']);
$objMonodro = new ab_monodro(); //se usa monodro


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
	

	

    if(isset($_POST['activo']))
    {
        $activo=1;
    }
    else
    {
 
        $activo=0;
    }
  
    $obj->setnombre(strtoupper($_POST['nombre']));
    $obj->setpresentacion($_POST['presentacion']);
    $obj->settroquel($_POST['troquel']);
	$obj->setgtin($_POST['gtin']);
    $obj->setcod_droga($_POST['cod_droga']);
    $obj->setactivo($activo);
    
    $idControlTroquel=-1;
    if(strlen($_POST['troquel'])>0){
        $objControl = new productos();
        $objControl->settroquel($_POST['troquel']);
        $objControl->LoadxTroquel();
        $idControlTroquel = ($objControl->getid()>0)?$objControl->getid():-1;
    }

    if($_POST['id']>0){
        if($idControlTroquel < 0 or $idControlTroquel==$_POST['id']){
        $obj->setusrModif($_SESSION["user"]);
        $x = $obj->Save();
        $mensaje = "<script>notificar('El registro se actualizo correctamente');</script>";
        }else{
            $mensaje = "<script>notificar('El troquel indicado ya existe, no es posible guardar el producto.');</script>";
        }
    }else{
        if($idControlTroquel < 0){
        $obj->setusrAlta($_SESSION["user"]);
        
        $x = $obj->Create();
        $mensaje = "<script>notificar('El registro se creó correctamente');</script>";
    }else{
        $mensaje = "<script>notificar('El troquel indicado ya existe, no es posible guardar el producto.');</script>";
    }
    }

    
    
    
}


$array = $obj->SelectAll();
$arrayMonodro = $objMonodro->SelectAll();






include_once '../../view/productos_frecuentes/productos_frecuentes.php';           