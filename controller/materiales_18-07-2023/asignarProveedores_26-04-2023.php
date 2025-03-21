<?php
include_once '../config.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/materiales_cotizaciones_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../funciones.php';



$obj = new materiales_cotizacion_solic_prov();
$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
if (isset($_GET['idSolicitud'])) {
    $idSolicitud = $_GET['idSolicitud'];
}

$obj->setid_solicitudes($idSolicitud);


if (isset($_GET['agregar'])) {



    if (isset($_GET['idProvAgregar'])) {
        $obj->setid_solicitudes($_GET['idSolicitud']);
        $obj->setid_proveedores($_GET['idProvAgregar']);
        $obj->setid_estados(42);
        $obj->setuserAlta($_SESSION['user']);
        $obj->Create();


        //crear estado 
        $estadoCot = new materiales_cotizaciones_estados();
        $estadoCot->setid_estados(42);
        $estadoCot->setid_cotizacion($obj->getid());
        $estadoCot->setobservaciones('Registro Inicial Cotizacion #' . $obj->getid() . ' de la solicitud #' . $idSolicitud);
        $estadoCot->Create();

        //Por cada ítem de la solicitud, crear los ítems de la cotizacion

        $itemsSol= new materiales_solicitudes_items();
        $resultadoSol= $itemsSol->SelectSolicitudItems($idSolicitud);

        while($row=mysqli_fetch_assoc($resultadoSol)){
            $itemc= new materiales_cotizacion_item();
            $itemc ->setid_item($row['id']);
            $itemc-> setid_proveedores($_GET['idProvAgregar']);
            $itemc-> setimporte_unitario(0);
            $itemc-> setcantidad($row['cantidad']);
            $itemc-> setmarca('');
            $itemc->setid_estados('39');
            $itemc->create();
        }

        


    }
}
if (isset($_GET['quitar'])) {
    $obj->setid_solicitudes($_GET['idSolicitud']);
    $obj->setid_proveedores($_GET['idProvQuitar']);
    $obj->Delete();

    //Por cada ítem de la cotización, eliminar ítem de la solicitud
}





if ($idSolicitud > 0) {
    $objStatus = new materiales_solicitudes_estados();
    $lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
    if ($x = mysqli_fetch_assoc($lastStatus)) {

        if ($x['id_estados'] > 31) {
            $rand = rand(100, 999);

            //BUSCO LAS COTIZACIONES DE LA SOLICITUD
            //$idSolicitud=$_SESSION['id_solicitud'];
            $obj = new materiales_cotizacion_solic_prov();
            $res = $obj->SelectAllCotizaciones($idSolicitud);

            //ESTADO ACTUAL DE LA SOLICITUD
            $objStatus = new materiales_solicitudes_estados();
            $lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
            if ($x = mysqli_fetch_assoc($lastStatus)) {
                $estadoActual = $x['id_estados'];
            }

            //PERMISOS DE ACCESO DEL USUARIO LOGEADO
            $objPermisos = new usuario_permisos_estados();
            $objPermisos->setidUsuario($_SESSION['idUsuario']);
            $permisosUser = $objPermisos->SelectForUser();

            $permisos = array();
            while ($p = mysqli_fetch_assoc($permisosUser)) {
                array_push($permisos, $p['idEstado']);
            }

            //  print_r($permisos);
           
            include_once '../../view/materiales/listarProveedores.php';
        } else {
            include_once '../../view/materiales/asignarProveedores.php';
        }
    } 
}
