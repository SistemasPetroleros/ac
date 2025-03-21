<?php

include_once '../config.php';
include_once '../funciones.php';
include_once '../enviar_mail.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_solicitudes_emails.php';
error_reporting(0);


$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');
$tipoEnvio = (isset($_POST['tipo']) ? $_POST['tipo'] : 'P');


$obj = new materiales_cotizacion_solic_prov();
$provCot = $obj->SelectAllCotizaciones($idSolicitud);

$bandera = 0;


$emails = array();
while ($row = mysqli_fetch_assoc($provCot)) {

    if ($tipoEnvio == 'T') {
        array_push($emails, $row['email']);
        $bandera = 1;
    } else {

        if ($tipoEnvio == 'P') {
            if ($row['enviarEmail'] == 1 and $row['email'] != '') {
                array_push($emails, $row['email']);
                $bandera = 1;
            }
        }
    }
}


$objItems = new materiales_solicitudes_items();

$resItems = $objItems->SelectSolicitudItems($idSolicitud);

$body = "";
while ($item = mysqli_fetch_assoc($resItems)) {

    $body .= '<tr>';
    $body .= '<td>' . $item['cantidad'] . '</td>';
    $body .= '<td>' . $item['nombre'] . '</td>';
    $body .= '<td>' . $item['observaciones'] . '</td>';
    $body .= '</tr>';
}


include_once('armarLinkDocumentos.php');

if($tipoEnvio=='P'){

    $mensaje = '<html>
    <head>
     <title>HTML email</title>
    </head>
     <body>
          <p>Estimados/as, enviamos el siguiente pedido de cotizaci&oacute;n, a continuaci&oacute;n el detalle de los productos solicitados:</p>
          <table border="1">
            <thead>
            <th>Cantidad</th>
            <th>Producto</th>
            <th>Observaciones</th>
           </thead>
           <tbody>
           ' . $body . '
           </tbody>
           
           </table>
        <br/>
    
       Además, enviamos el link para descargar los archivos adjuntos (formato .zip) de la solicitud #'.$idSolicitud.': <br/>
         <a href="'.$link.'" >Link de Descarga</a>
        <p> Muchas gracias. </p>
        <p> Saludos cordiales! </p>
        <br/>
        <p> <b>Sector Alto Costo</b> <br/>
       <b>Obra Social de Petroleros Privados (O.S.Pe.Pri.)</b></p>
        <br/>
        <hr>
    
    
        </body>
    </html>';

}
else{


    if($tipoEnvio=='T'){


    $mensaje = '<html>
    <head>
     <title>HTML email</title>
    </head>
     <body>
          <p>Estimados/as, recordamos que tienen el siguiente pedido de cotizaci&oacute;n pendiente con Nro. de Solicitud '.$idSolicitud.'. A continuaci&oacute;n el detalle de los productos solicitados:</p>
          <table border="1">
            <thead>
            <th>Cantidad</th>
            <th>Nombre</th>
            <th>Observaciones</th>
           </thead>
           <tbody>
           ' . $body . '
           </tbody>
           
           </table>
        <br/>
        Además, enviamos el link para descargar los archivos adjuntos (formato .zip) de la solicitud #'.$idSolicitud.': <br/>
     <a href="'.$link.'" >Link de Descarga</a>
     <br/>
        <p> Por favor, no responda el correo. Las cotizaciones válidas serán las realizadas por el sistema que tiene tal fin.</p>
        <br>
    
        <p> Muchas gracias. </p>
        <p> Saludos cordiales! </p>
        <br/>
        <p> <b>Sector Alto Costo</b> <br/>
       <b>Obra Social de Petroleros Privados (O.S.Pe.Pri.)</b></p>
        <br/>
        <hr>
    
    
        </body>
    </html>';

    }


}





$destino = $emails;
$remitente = "Alto Costo (O.S.Pe.Pri.)";
$asunto = ("Pedido de Cotización # " . $idSolicitud);


$objmail = new materiales_solicitudes_emails();



if ($bandera == 1) {
    $respuesta = enviarMail($destino, $asunto, $mensaje, '');
    if ($respuesta == "1") {
        #Guardar respuesta de exito
        $objmail->setid_solicitudes($idSolicitud);
        $objmail->settipo('1');
        $objmail->setok('1');
        $objmail->setdescripcion('Correos enviados correctamente.');
        $objmail->Create();
        echo 1;
    } else {
        #Guardar respuesta de error
        $objmail->setid_solicitudes($idSolicitud);
        $objmail->settipo('1');
        $objmail->setok('0');
        $objmail->setdescripcion('Error al enviar los correos. Respuesta Server: ' . $respuesta);
        $objmail->Create();
        echo 0;
    }
}
