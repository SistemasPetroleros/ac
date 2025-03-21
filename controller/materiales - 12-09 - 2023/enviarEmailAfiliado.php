<?php 

error_reporting(0);
include_once '../../model/solicitudes_items.php';

//Busco la cotización aprobada con todos sus datos
$objAp = new cotizacion_solic_prov();
$cotAprob = $objAp ->  SelectCotizacionAprobada($idSolicitud);

$emails=array();
if($c=mysqli_fetch_assoc($cotAprob))
{
    array_push($emails,$c['emailAf']);

    $objItems = new solicitudes_items();
    $resItems= $objItems -> SelectSolicitudItems($idSolicitud);

    $bodyProd="";
    while($item=mysqli_fetch_assoc($resItems))
    {
    
        $bodyProd.='<tr>';
        $bodyProd.='<td>'.$item['cantidad'].'</td>';
        $bodyProd.='<td>'.$item['nombre'].'</td>';
        $bodyProd.='<td>'.$item['presentacion'].'</td>';
      //  $bodyProd.='<td>'.$item['observaciones'].'</td>';
        $bodyProd.='</tr>';

    }


    $afiliado=(trim($c['nombre'])).' '.(trim($c['apellido']));
      

    $mensaje='<html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
        <p>Estimado/a '.$afiliado.', le informamos que los medicamentos solicitados ya se encuentran disponibles en la farmacia abajo detallada.
        Recuerde llevar para su retiro el DNI, la credencial de la obra social y el original de la prescripción médica.</p>
        

        <b><u>LISTADO PRODUCTOS SOLICITADOS</u></b>
        
        <table border="1">
            <thead>
            <th>Cantidad</th>
            <th>Producto/Monodroga</th>
            <th>Presentaci&oacute;n</th>
            
        </thead>
        <tbody>
        '.$bodyProd.'
        </tbody>
        
        </table>
        <br/>

        <b><u>LUGAR DE RETIRO</u></b>
       
       
           <p><b>Farmacia: </b>'.$c['farmacia'].'<br>
           <p><b>Direcci&oacute;n: </b>'.$c['domicilio'].'<br>
           <p><b>Tel&eacute;fono: </b>'.$c['telefonos'].'<br>
           <p><b>Localidad/Provincia: </b>'.($c['localidad']).' / '.($c['provincia']).'</p>
        
        <br/>

        <p> Lo/a esperamos. </p>
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
    $asunto = ("Solicitud de Medicamentos #".$idSolicitud.' en Farmacia');


    //enviarMail($destino, $asunto, $mensaje,'');

   


}
