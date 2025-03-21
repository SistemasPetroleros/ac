<?php
error_reporting(-1);
set_time_limit(30);
date_default_timezone_set('America/Argentina/Rio_Gallegos');
/*
  error_reporting(-1);
  error_reporting(E_ALL);
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', '1');
 */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');

/////////////////////////////////////////////////////////////////////////////
//$destinatario = $_GET['destinatario'];
//$asunto = $_GET['asunto'];
//$mensaje = $_GET['mensaje'];
//$mensaje_nohtml = $_GET['mensaje_nohtml'];
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
//print_r($_GET);

function enviarMail($destinatario, $asunto, $mensaje, $mensaje_nohtml = '') {
    //require_once('../../lib/PHPMailer/PHPMailerAutoload.php');
    require("../../lib/PHPMailer-master/src/PHPMailer.php");
    require("../../lib/PHPMailer-master/src/SMTP.php");
    require("../../lib/PHPMailer-master/src/Exception.php");

    $mail = new PHPMailer\PHPMailer\PHPMailer();  // defaults to using php "mail()"
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Timeout = 30;
    $mail->isSMTP();
//$mail->Mailer = 'smtp';
    $mail->isHTML(true);
    $mail->Host = 'smtp.office365.com';
    $mail->Port = '587';
    $mail->SMTPSecure = 'tls';
    
    $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
        );

    $mail->SMTPAuth = true;
    $mail->Username = 'notificaciones-alto-costo@ospepri.org.ar';
    $mail->Password = 'Kaz08350';
    //$mail->Username = 'leonela.fuentes@ospepri.org.ar';
    //$mail->Password = 'Milo2016';
    $mail->SetFrom('notificaciones-alto-costo@ospepri.org.ar', 'Alto Costo - OSPEPRI');
    $mail->AddReplyTo('altacomplejidad@ospepri.org.ar', 'Alto Costo - OSPEPRI');
    //$mail->AddBCC('daniel.rojas@ospepri.org.ar', 'daniel.rojas@ospepri.org.ar');
    $mail->addAddress('altacomplejidad@ospepri.org.ar', 'altacomplejidad@ospepri.org.ar');
    for($i=0;$i<count($destinatario);$i++)
    {
       
         //$mail->addAddress($destinatario[$i], $destinatario[$i]);
		 $mail->AddBCC($destinatario[$i], $destinatario[$i]);
 
  
    }

    
    

//Añado un asunto al mensaje
    $mail->Subject = $asunto;


    $mail->Body = $mensaje;

//inserto el texto del mensaje en formato TXT
    $mail->AltBody = $mensaje_nohtml;

    $mail->CharSet = 'UTF-8';

    $exito = $mail->Send(); // Envía el correo.

    if ($exito) {
        return '1';
    } else {
        logTxt( 'Mailer Error: ' . $mail->ErrorInfo );
        return '0';
        //echo '<br>';
        
        //echo $mail->ErrorInfo;
        //echo "<br><b>Error al enviar el mensaje</b><br>";
    }
}
