<?php
include_once '../config.php';
include_once '../../model/proveedores.php';
include_once '../funciones.php';
include_once '../../model/provincias.php';
//include_once '../../model/localidades.php';





$rand = rand(100, 999);
$mensaje = '';

$obj = new proveedores($_POST['id']);
$objProv = new provincias();


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
    if(isset($_POST['habilitado']))
    {
        $habilitado=1;
    }
    else
    {
 
        $habilitado=0;
    }
   
    
    $obj->setnombre($_POST['nombre']);
    $obj->setdomicilio($_POST['domicilio']);
    $obj->settelefonos($_POST['telefonos']);
    $obj->setemail($_POST['email']);
    $obj->settipo($_POST['tipoProv']);
    $obj->setcuit($_POST['cuit']);
    $obj->sethabilitado($habilitado);
    $obj->setid_localidades($_POST['id_localidades']);
    //$obj->setid_localidades($_POST['id_localidades']);

    ///falta agregar el usuario que genera el alta y la modificacion
    if($_POST['id']>0){
        $x = $obj->Save();
        $mensaje = "<script>notificar('El registro se actualizo correctamente');</script>";
    }else{
        $x = $obj->Create();
        $mensaje = "<script>notificar('El registro se creó correctamente');</script>";
    }

    
    
    
}


$array = $obj->SelectAll();
$arrayProv= $objProv -> SelectAll();





include_once '../../view/proveedores/proveedores.php';           