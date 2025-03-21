<?php
include_once '../../model/materiales_solicitudes_emails.php';
// Require composer autoload
require_once '../../vendor/autoload.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/proveedores.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../funciones.php';

require("../../lib/PHPMailer-master/src/PHPMailer.php");
require("../../lib/PHPMailer-master/src/SMTP.php");
require("../../lib/PHPMailer-master/src/Exception.php");

error_reporting(0);




//Recuperar todas Cotizaciones aprobadas o parcialmente aprobadas
$cotizacion = new materiales_cotizacion_solic_prov();
$cotizacion->setid_solicitudes($idSolicitud);
$resultadoCotA = $cotizacion->getCotizacionesAprobada();

$emails=[];
//Por cada una de las cotizaciones, armar PDF e enviarlas por Email
while ($rowca = mysqli_fetch_assoc($resultadoCotA)) {
    $idProveedor = $rowca['id_proveedores'];
    //creo PDF
    include 'reporteOrdenCompra.php';
   

    //Armar Link
    $idSolicitudEncrypt = encryptWithKey($idSolicitud, 'altocosto2023encrypt');
    $idProveedorEncrypt = encryptWithKey($idProveedor, 'altocosto2023encrypt');

    $idSolicitudEncrypt = str_replace('/', '_', $idSolicitudEncrypt);
    $idSolicitudEncrypt = str_replace('+', '-', $idSolicitudEncrypt);
    $idSolicitudEncrypt = str_replace('=', '', $idSolicitudEncrypt);

    $idProveedorEncrypt = str_replace('/', '_', $idProveedorEncrypt);
    $idProveedorEncrypt = str_replace('+', '-', $idProveedorEncrypt);
    $idProveedorEncrypt = str_replace('=', '', $idProveedorEncrypt);


    $link = 'http://200.5.226.210:22922/ac/getLinkOC.php?value1=' . $idSolicitudEncrypt . '&value2=' . $idProveedorEncrypt;



    $mensaje = '<html>
                    <head>
                    <title>HTML email</title>
                    </head>
                    <body>
                        <p>Estimados/as,  enviamos la Orden de Compra de la Solicitud # ' . $idSolicitud . '. La misma se puede descargar del siguiente Link en formato .ZIP: <br/>
                        <a href="' . $link . '" >Link de Descarga</a>
                        <br/>
                        <p> Saludos cordiales! </p>
                        <br/>
                        <p> <b>Sector Alto Costo</b> <br/>
                    <b>Obra Social de Petroleros Privados (O.S.Pe.Pri.)</b></p>
                        <br/>
                        <hr>


                        </body>
                    </html>';


                   

    array_push($emails, $rowca['email']);
    $destino = $emails;
    $remitente = "Alto Costo (O.S.Pe.Pri.)";
    $asunto = ("OSPEPRI: Envío Orden de Compra # " . $idSolicitud);

  

    $objmail = new materiales_solicitudes_emails();

    $respuesta = enviarMail2($destino, $asunto, $mensaje, '');
    if ($respuesta == "1") {
        #Guardar respuesta de exito
        $objmail->setid_solicitudes($idSolicitud);
        $objmail->settipo('1');
        $objmail->setok('1');
        $objmail->setdescripcion('Orden Compra ' . $idSolicitud . ' del proveedor id. ' . $idProveedor . ' enviado correctamente.');
        $objmail->Create();
    } else {
        #Guardar respuesta de error
        $objmail->setid_solicitudes($idSolicitud);
        $objmail->settipo('1');
        $objmail->setok('0');
        $objmail->setdescripcion('Error al enviar los correos Orden Compra ' . $idSolicitud . ' del proveedor id. ' . $idProveedor . ' -  Respuesta Server: ' . $respuesta);
        $objmail->Create();
    }
}//while
