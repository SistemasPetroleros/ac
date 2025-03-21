<?php
header('Content-Type: application/json');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Max-Age: 1000');

include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/personas.php';
include_once '../funciones.php';

error_reporting(0);

$salida = array(
    "comentarios" => '',
    "autoriza" => ''
);


$icotizacion = new materiales_cotizacion_item();

$idSolicitud = (isset($_GET['idSolicitud']) ? $_GET['idSolicitud'] : 0);
$solicitud = new materiales_solicitudes($idSolicitud);
$idPersona = $solicitud->getid_personas();
$persona = new personas($idPersona);
$dni = $persona->getdni();


$items = new materiales_solicitudes_items();

//Primero: recupero items solicitud
$resultado = $items->SelectSolicitudItems($idSolicitud);
$cantItems = mysqli_num_rows($resultado);

$obs = "";
$aut = 0;
while ($row = mysqli_fetch_assoc($resultado)) {


    $param['codigos'] = $row['id_producto']; //plantilla
    $param['dni'] = $dni;
    //verifica si se valido para el afiliado una plantilla en los ultimos 365 dias
    $resultado2 = $items->validarPrestacionesOrtopedia($param);
    if (mysqli_num_rows($resultado2) > 0) {
        $cant = $row['cantidad'];
        $cantMax = 0;
        while ($row2 = mysqli_fetch_assoc($resultado2)) {
            $cant = $cant + $row2['cantidad'];
            $cantMax = $row2['cantMax'];
        }

        if ($cant <= $cantMax and $cantMax != 0) {
            $data['idSolicitud'] = $idSolicitud;
            $data['idItem'] = $row['id'];
            $resic = $icotizacion->getItemCotizacion($data);
            $rowci = mysqli_fetch_assoc($resic);
            if ($rowci['total'] > $rowci['montoMax']) {
                $obs .= "<p> Item nro." . $row['id'] . " - Producto: " . $row['nombre'] . " - Supera monto total máximo configurado, requiere AUDITORIA.</p>";
            } else {
                $obs .= "<p> Item nro." . $row['id'] . " - Producto: " . $row['nombre'] . " con todos los requisitos correctos.";
                $aut++;
            }
        } else {
            $obs .= "<p> Item nro." . $row['id'] . " - Producto: " . $row['nombre'] . " - Supera cantidad máxima permitida en un periodo o topes no configurados, requiere AUDITORIA.</p>";
        }
    } else {
        //Si no hubieron, hay que ver si se validó por SIA en los ultimos 365 dias una plantilla (válido hasta Marzo/2024)
        if ($row['id_producto'] == 910) {
            $resultado3 = $items->getCantPlantillas($dni);
            if (mysqli_num_rows($resultado2) > 0) {
                $obs .= "<p>El afiliado posee una validación en SIA dentro de los últimos 365 días.</p>";
            } else {
                $data['idSolicitud'] = $idSolicitud;
                $data['idItem'] = $row['id'];
                $resic = $icotizacion->getItemCotizacion($data);
                $rowci = mysqli_fetch_assoc($resic);
                if ($rowci['total'] > $rowci['montoMax']) {
                    $obs .= "<p> Item nro." . $row['id'] . " - Producto: " . $row['nombre'] . " - Supera monto total máximo configurado, requiere AUDITORIA.</p>";
                } else {
                    $obs .= "<p> Item nro." . $row['id'] . " - Producto: " . $row['nombre'] . " con todos los requisitos correctos.";
                    $aut++;
                }
            }
        } else {
            $obs .= "<p> Item nro." . $row['id'] . " - Producto: " . $row['nombre'] . " - Supera cantidad máxima permitida en un periodo o topes no configurados, requiere AUDITORIA.</p>";
        }
    }
} //fin while


if ($cantItems == $aut) {
    $salida['autoriza'] = 'S';
    $salida['comentarios'] = $obs;
} else {
    $salida['autoriza'] = 'N';
    $salida['comentarios'] = $obs;
}




$salida = json_encode($salida);


if (isset($_GET['direct'])) {
    echo $salida;
} else {
    $salida = base64_encode($salida);
    if (isset($_GET['callback'])) { // Si es una peticiï¿½n cross-domain  
        echo $_GET['callback'] . '(' . json_encode($salida) . ')';
    } else // Si es una normal, respondemos de forma normal  
        echo json_encode($salida);
}
