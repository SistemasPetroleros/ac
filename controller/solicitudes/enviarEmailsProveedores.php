<?php
error_reporting(-1);
include_once '../../model/solicitudes_items.php';
include_once '../../model/solicitudes_emails.php';

$obj = new cotizacion_solic_prov();
$provCot = $obj->SelectAllCotizaciones($idSolicitud);

$bandera = 0;

$emails = array();
while ($row = mysqli_fetch_assoc($provCot)) {

    /////////modificado, solo proveedor 20 puede enviar mail
    //   if($row['idProveedor']==20 or $row['idProveedor']==23 or $row['idProveedor']==69){
    if ($row['enviarEmail'] == 1 and $row['email']!='') {
        array_push($emails, $row['email']);
        $bandera = 1;
    }
}



$objItems = new solicitudes_items();

$resItems = $objItems->SelectSolicitudItems($idSolicitud);

$body = "";
while ($item = mysqli_fetch_assoc($resItems)) {

    $body .= '<tr>';
    $body .= '<td>' . $item['cantidad'] . '</td>';
    $body .= '<td>' . $item['nombre'] . '</td>';
    $body .= '<td>' . $item['presentacion'] . '</td>';
    $body .= '<td>' . $item['observaciones'] . '</td>';
    $body .= '</tr>';
}

include_once('armarLinkDocumentos.php');



$mensaje = '<html>
<head>
 <title>HTML email</title>
</head>
 <body>
      <p>Estimados/as, enviamos el siguiente pedido de cotizaci&oacute;n, a continuaci&oacute;n el detalle de los productos solicitados:</p>
      <table border="1">
        <thead>
        <th>Cantidad</th>
        <th>Producto/Monodroga</th>
        <th>Presentaci&oacute;n</th>
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



$destino = $emails;
$remitente = "Alto Costo (O.S.Pe.Pri.)";
$asunto = ("Pedido de Cotización # " . $idSolicitud);


$objmail = new solicitudes_emails();



if ($bandera == 1) {
    $respuesta = enviarMail($destino, $asunto, $mensaje, '');
    if ($respuesta == "1") {
        #Guardar respuesta de exito
        $objmail->setid_solicitudes($idSolicitud);
        $objmail->settipo('1');
        $objmail->setok('1');
        $objmail->setdescripcion('Correos enviados correctamente.');
        $objmail->Create();
    } else {
        #Guardar respuesta de error
        $objmail->setid_solicitudes($idSolicitud);
        $objmail->settipo('1');
        $objmail->setok('0');
        $objmail->setdescripcion('Error al enviar los correos. Respuesta Server: ' . $respuesta);
        $objmail->Create();
    }
}
