<?php 

/*function encryptWithKey($data, $key) {
    $cipher = 'AES-256-CBC'; // Puedes elegir un algoritmo de cifrado adecuado
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}*/



 $encriptado= encryptWithKey($idSolicitud,'altocosto2023encrypt');

 
 $encriptado = str_replace('/', '_', $encriptado);
 $encriptado = str_replace('+', '-', $encriptado);
 $encriptado = str_replace('=', '', $encriptado);

 $link='http://200.5.226.210:22922/ac/getLinksMat.php?value='.$encriptado; 

