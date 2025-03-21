<?php
include_once '../config.php';
include_once '../../model/puntos_dispensa.php';
include_once '../funciones.php';
include_once '../../model/provincias.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';




$rand = rand(100, 999);
$mensaje = '';

$obj = new puntos_dispensa($_POST['id']);
$objProv = new provincias();


if (isset($_POST['eliminar'])) {
    
    if ($_POST['rand'] == $_POST['rand2']) {
        $obj->Delete();
        echo '<script>
                                notificar("El registro se borró correctamente.");
                            </script>';
    } else {
        echo '<script>
                                notificar("Debe reingresar el numero que aparece a la izquierda del boton Eliminar");
                            </script>';
        
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
    $obj->setGLN($_POST['GLN']);
    $obj->setdomicilio($_POST['domicilio']);
    $obj->settelefonos($_POST['telefonos']);
    $obj->setemail($_POST['email']);
    $obj->sethabilitado($habilitado);
    $obj->setid_localidades($_POST['id_localidades']);
    

    ///falta agregar el usuario que genera el alta y la modificacion
    if($_POST['id']>0){
        $x = $obj->Save();
        echo '<script>
        notificar("El registro se actualizó correctamente.");
    </script>';
        
    }else{
        $x = $obj->Create();
        echo '<script>
        notificar("El registro se guardó correctamente.");
    </script>';
        
    }

    
    
    
}


$array = $obj->SelectAll();
$arrayProv= $objProv -> SelectAll();





include_once '../../view/puntos_dispensa/puntos_dispensa.php';           