<form action="" method="post">
    Archivo
    <input type="text" name="archivo" value="c:\valida\ospepri\rta\11.xml">
    <input type="submit" value="Leer Archivo">
</form>


<?php
$nombre_archivo = isset($_POST['archivo']) ? $_POST['archivo'] : '';

if ($nombre_archivo != '') {
    if (file_exists($nombre_archivo)) {
        $xml = simplexml_load_file($nombre_archivo);
        
        echo 'IdMensaje:'.$xml->EncabezadoMensaje->IdMsj.'<hr>';
        
        
        
        
        
        print_r($xml);
    } else {
        echo 'No se encontro el archivo.';
    }
}

