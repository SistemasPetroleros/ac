<?php
include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/cotizaciones_estados.php';

$obj = new cotizacion_solic_prov();
$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$accion = $_POST['accion'];

if ($accion == 'agregar') {

    $resultado = $obj->SelectAllNoAsignados($idSolicitud);

    while ($row = mysqli_fetch_assoc($resultado)) {
    
        $obj->setid_solicitudes($idSolicitud);
        $obj->setid_proveedores($row['id']);
        $obj->setuserAlta($_SESSION['user']);
        $obj->setid_estados(42);
        $id=$obj->Create();


        $estadoCot = new cotizaciones_estados();
        $estadoCot->setid_cotizacion($id);
        $estadoCot->setid_estados(42);
        $estadoCot->setobservaciones('Registro Inicial Cotizacion #' . $id . ' de la solicitud #' . $idSolicitud);
        $estadoCot->Create(); //creo estado


    }
} else {
    if ($accion == 'quitar') {
        $resultado = $obj->SelectAllAsignados($idSolicitud);

        while ($row = mysqli_fetch_assoc($resultado)) {
           
        
            $obj->setid_solicitudes($idSolicitud);
            $obj->setid_proveedores($row['id']);
            $r = $obj->getCotizacion();


            if ($r) {
                $rowx = mysqli_fetch_assoc($r);


                //quito  el estado
                $estadoCot = new cotizaciones_estados();
                $estadoCot->setid_cotizacion($rowx['id']);
                $estadoCot->DeleteAll();

                //Quito cotización
                $obj->Delete();
            }
        }
    }
}





include_once '../../view/solicitudes/asignarProveedores.php';
