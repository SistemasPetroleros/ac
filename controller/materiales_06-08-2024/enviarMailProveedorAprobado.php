<?php 
include_once '../../model/solicitudes_items.php';
include_once '../enviar_mail.php';


//Busco la cotización aprobada con todos sus datos
$objAp = new cotizacion_solic_prov();
$objAp->setid($idSolicitudProv);
$objAp->setid_solicitudes($idSolicitud);
$cotAprob = $objAp ->  SelectCotizacionAprobada();

$emails=array();
if($c=mysqli_fetch_assoc($cotAprob))
{
    array_push($emails,$c['email']);

    $objItems = new solicitudes_items();
    $resItems= $objItems -> SelectSolicitudItems($idSolicitud);

    $bodyProd="";
    while($item=mysqli_fetch_assoc($resItems))
    {
    
        $bodyProd.='<tr>';
        $bodyProd.='<td>'.$item['cantidad'].'</td>';
        $bodyProd.='<td>'.$item['nombre'].'</td>';
        $bodyProd.='<td>'.$item['presentacion'].'</td>';
        $bodyProd.='<td>'.$item['observaciones'].'</td>';
        $bodyProd.='</tr>';

    }

    if($c['esB24']==1)
    {
        $afiliado='
        <p><b>Afiliado: </b>'.$c['codigoB24'].'<br>
        <p><b>Nro. Afiliado: </b>'.$c['dni'].'</p>';

    }
    else {
        $afiliado='
        <p><b>Afiliado: </b>'.(trim($c['apellido'])).' '.(trim($c['nombre'])).'<br>
        <p><b>Nro. Afiliado: </b>'.$c['dni'].'</p>';
    }


    


    $mensaje='<html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
        <p>Estimados/as, enviamos la siguiente solicitud de compra.</p>
        <p><b>Proveedor: </b>'.$c['proveedor'].'</p>
        <p><b>Importe Cotizado y Aprobado: </b> $'.$c['importe'].'</p>
        <br/>
        <b><u>DATOS AFILIADO</u></b>
        '.$afiliado.'
        <br/>

        <b><u>LISTADO PRODUCTOS SOLICITADOS</u></b>
        
        <table border="1">
            <thead>
            <th>Cantidad</th>
            <th>Producto/Monodroga</th>
            <th>Presentaci&oacute;n</th>
            <th>Observaciones</th>
        </thead>
        <tbody>
        '.$bodyProd.'
        </tbody>
        
        </table>
        <br/>

        <b><u>DATOS DE ENVIO</u></b>
       
       
           <p><b>Farmacia: </b>'.$c['farmacia'].'<br>
           <p><b>GLN: </b>'.$c['GLN'].'<br>
           <p><b>Direcci&oacute;n: </b>'.$c['domicilio'].'<br>
           <p><b>Tel&eacute;fono: </b>'.$c['telefonos'].'<br>
           <p><b>Localidad/Provincia: </b>'.($c['localidad']).' / '.($c['provincia']).'</p>
        
        <br/>

        <p> Muchas gracias. </p>
        <p> Saludos cordiales! </p>
        <br/>
        <p> <b>Sector Alto Costo</b> <br/>
    <b>Obra Social de Petroleros Privados (O.S.Pe.Pri.)</b></p>
        <br/>
        <hr>


        </body>
    </html>';



    $destino=$emails;
    $remitente = "Alto Costo (O.S.Pe.Pri.)";
    $asunto = ("Solicitud de Compra #".$idSolicitud);



    //enviarMail($destino, $asunto, $mensaje,'');


}

