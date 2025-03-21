<?php
include_once '../config.php';
include_once '../../model/personas.php';
include_once '../funciones.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';



$rand = rand(100, 999);
$mensaje = '';

$obj = new personas($_POST['id']);

if (isset($_POST['eliminar'])) {
    
    if ($_POST['rand'] == $_POST['rand2']) {
        $obj->Delete();
        $mensaje = '<script>
        notificar("El registro se borro correctamente");
    </script';
    } else {
        $mensaje = '<script>
                                notificar("Debe reingresar el numero que aparece a la izquierda del boton Eliminar");
                            </script';
    }
}

if (isset($_POST['guardar'])) {

   
    
    //$obj->setapellido($_POST['apellido']);
    //$obj->setnombre($_POST['nombre']);
    //$obj->setdni($_POST['dni']);
    $obj->settelefono($_POST['telefono']);
    $obj->setemail($_POST['email']);

    
    ///falta agregar el usuario que genera el alta y la modificacion
    if($_POST['id']>0){
        $x = $obj->Save();
        $mensaje = '<script>
                            notificar("El registro se actualizó correctamente");
                        </script';                            
    }else{
        $x = $obj->Create();
        $mensaje = '<script>
        notificar("El registro se guardó correctamente.");
    </script';   
       
    }

    
    
    
}


$array = $obj->SelectAll();




include_once '../../view/personas/personas.php';