<?php

include_once '../config.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../funciones.php';

$solicitudItem = new materiales_solicitudes_items('');
$items = new materiales_cotizacion_item('');

$control=$_POST['control'];


//agrego primeros items a solicitud

$solicitudItem->setid_solicitudes($_POST['idSolicitud']);
$solicitudItem->setid_producto($_POST['idProducto']);
$solicitudItem->setobservaciones('Solicitud creada por Proveedor.');
$solicitudItem->setcantidad($_POST['cantCotizada']);
$solicitudItem->Create();

if ($solicitudItem->getid() != "") {
    //Agrego Items a cotizacion   

    $items->setid_proveedores($_POST['idProveedor']);
    $items->setid_item($solicitudItem->getid());
    $items->setimporte_unitario($_POST['importeUnit']);
    $items->setcantidad($_POST['cantCotizada']);
    $items->setmarca('');
    $items->setid_estados('39');
    $r = $items->create();

    if ($r) {
            $resultado=$items->getItemsCotizacion($_POST['idSolicitud']);
            $filas="";
            while($row= mysqli_fetch_assoc($resultado)){
               $filas.="<tr>";
               $filas.="<td>".$row['nombre']."</td>";
               $filas.="<td>".$row['cantCotizada']."</td>";
               $filas.="<td>".$row['importe_unitario']."</td>";
               $filas.="<td>".number_format($row['cantCotizada']*$row['importe_unitario'],2,'.','')."</td>";
               $filas.='<td><button type="button" class="btn btn-danger" onclick="eliminarItem('.$row['idItem'].','.$row['idItemCot'].','.$control.');"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
               $filas.="</tr>";
            }

            echo $filas;
    } else {
        echo -1;
    }
} else
    echo -2;
