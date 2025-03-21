<?php
include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/cotizaciones_estados.php';
include_once '../funciones.php';

$obj = new cotizacion_solic_prov();
$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
if (isset($_GET['idSolicitud'])) {
    $idSolicitud = $_GET['idSolicitud'];
}

$obj->setid_solicitudes($idSolicitud);


if (isset($_GET['agregar'])) {


    if (isset($_GET['idProvAgregar'])) {
        $obj->setid_solicitudes($_GET['idSolicitud']);
        $obj->setid_proveedores($_GET['idProvAgregar']);
        $obj->setuserAlta($_SESSION['user']);
        $obj->setid_estados(42);
        $id = $obj->Create(); //Creo Cotización

        $estadoCot = new cotizaciones_estados();
        $estadoCot->setid_cotizacion($id);
        $estadoCot->setid_estados(42);
        $estadoCot->setobservaciones('Registro Inicial Cotizacion #' . $id . ' de la solicitud #' . $idSolicitud);
        $estadoCot->Create(); //creo estado


    }
}
if (isset($_GET['quitar'])) {

    //obtengo cotizacion
    $obj->setid_solicitudes($_GET['idSolicitud']);
    $obj->setid_proveedores($_GET['idProvQuitar']);
    $r = $obj->getCotizacion();


    if ($r) {

        $row = mysqli_fetch_assoc($r);

        //quito  el estado
        $estadoCot = new cotizaciones_estados();
        $estadoCot->setid_cotizacion($row['id']);
        $estadoCot->DeleteAll();

        //Quito cotización
        $obj->Delete();
    }
}




if ($idSolicitud > 0) {
    $objStatus = new solicitudes_estados();
    $lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
    if ($x = mysqli_fetch_assoc($lastStatus)) {
        if ($x['id_estados'] > 1) {
            $rand = rand(100, 999);


            //BUSCO LAS COTIZACIONES DE LA SOLICITUD
            //$idSolicitud=$_SESSION['id_solicitud'];
            $obj = new cotizacion_solic_prov();
            $res = $obj->SelectAllCotizaciones($idSolicitud);



            //ESTADO ACTUAL DE LA SOLICITUD
            $objStatus = new solicitudes_estados();
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
			


            if ($idSolicitud > 371) //7371
                include_once '../../view/solicitudes/listarProveedores.php';
            else
                include_once '../../view/solicitudes/listarProveedores_OldVersion.php';
        } else {
            include_once '../../view/solicitudes/asignarProveedores.php';
        }
    }
}
