<?php
// La URL de la API
$url = 'http://172.26.0.150/notificaciones/apiWhatsapp.php';
date_default_timezone_set('America/Argentina/Buenos_Aires');


// Los datos JSON que se enviarán en el POST
$data = array(
    "nroDocumento" => $dni,
    "asunto" => 'Solicitud de Medicamentos #'.$idSolicitud.' en Farmacia',
    "nombreContacto" => $afiliado,
    "mensaje" => $mensajeWP,
    "telefono" => $telefono,
    "email" => $email,
    "fechaEnvio" => date('Y-m-d H:i:s')
);

// Convertir el array a formato JSON
$jsonData = json_encode($data);

// Inicializar cURL
$ch = curl_init($url);

// Configurar las opciones de cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Devuelve la respuesta como string
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
));
curl_setopt($ch, CURLOPT_POST, true);  // Establece el método POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);  // Datos a enviar en el cuerpo

// Ejecutar la solicitud y almacenar la respuesta
$response = curl_exec($ch);

// Verificar si hubo un error
if(curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
} else {
    // Mostrar la respuesta de la API
   // echo 'Respuesta de la API: ' . $response;
}

// Cerrar la sesión cURL
curl_close($ch);
?>
