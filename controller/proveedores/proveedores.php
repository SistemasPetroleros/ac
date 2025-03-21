<?php
include_once '../config.php';
include_once '../../model/proveedores.php';
include_once '../funciones.php';
include_once '../../model/provincias.php';
include_once '../../model/proveedores_tipo_solicitud.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';



$tipoSolicitudes = explode(',', $_POST['hidden_prov']);

$rand = rand(100, 999);
$mensaje = '';

$obj = new proveedores($_POST['id']);
$objProv = new provincias();

$objTipo = new proveedores_tipo_solicitud();



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
    if (isset($_POST['habilitado'])) {
        $habilitado = 1;
    } else {

        $habilitado = 0;
    }


    if (isset($_POST['enviarEmail'])) {
        $enviarEmail = 1;
    } else {

        $enviarEmail = 0;
    }


    $obj->setnombre($_POST['nombre']);
    $obj->setdomicilio($_POST['domicilio']);
    $obj->settelefonos($_POST['telefonos']);
    $obj->setemail($_POST['email']);
    //$obj->settipo($_POST['tipoProv']);
    $obj->setcuit($_POST['cuit']);
    $obj->setingresosBrutos($_POST['iibb']);
    $obj->sethabilitado($habilitado);
    $obj->setenviarEmail($enviarEmail);
    $obj->setid_localidades($_POST['id_localidades']);
    //$obj->setid_localidades($_POST['id_localidades']);


    if ($_POST['id'] > 0) {
        $x = $obj->Save();
        if ($x) {
            $objTipo->setid_proveedores($_POST['id']);
            $objTipo->DeleteIdProveedor(); //borro todo
            $i = 0;
            while ($i < count($tipoSolicitudes)) {
                $objTipo->setid_tipo_solicitudes($tipoSolicitudes[$i]); //cargo los nuevos
                $objTipo->Create();
                $i++;
            }

            $mensaje = "<script>notificar('El registro se actualizo correctamente');</script>";
        } else {
            $mensaje = "<script>notificar('Ha ocurrido un problema. Por favor, intente nuevamente.');</script>";
        }
    } else {
        $idP = $obj->Create();
        if ($idP > 0) {

            $objTipo->setid_proveedores($idP);
            $i = 0;
            while ($i < count($tipoSolicitudes)) {
                $objTipo->setid_tipo_solicitudes($tipoSolicitudes[$i]);
                $objTipo->Create();
                $i++;
            }
            $mensaje = "<script>notificar('El registro se creó correctamente');</script>";
        } else {
            $mensaje = "<script>notificar('Ha ocurrido un problema. Por favor, intente nuevamente.');</script>";
        }
    }
}


$array = $obj->SelectAll();
$arrayProv = $objProv->SelectAll();





include_once '../../view/proveedores/proveedores.php';
