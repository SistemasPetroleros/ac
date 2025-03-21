<?php 
include_once 'controller/funciones.php';


$cadenaEncriptada1=$_GET['value'];
$cadenaEncriptada1 = str_replace('_', '/', $cadenaEncriptada1);
$cadenaEncriptada1 = str_replace('-', '+', $cadenaEncriptada1);



$idSolicitud=decryptWithKey($cadenaEncriptada1 ,'altocosto2023encrypt');
//$carpeta = 'controller/file_manager/adjuntosMateriales/'.$idSolicitud;
$carpeta='C:/ArchivosAC/file_manager/adjuntosMateriales/'.$idSolicitud;


$archivos = glob($carpeta . '/*');


if (count($archivos) > 0) {
    // Nombre del archivo ZIP
    $zipFileName = 'archivos_'.$idSolicitud.'.zip';

    // Crear el archivo ZIP
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        foreach ($archivos as $archivo) {
            if (strpos($archivo, 'Privado-') === false)
              $zip->addFile($archivo, basename($archivo));
        }
        $zip->close();
    }

    // Descargar el archivo ZIP
    if (file_exists($zipFileName)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: ' . filesize($zipFileName));
        readfile($zipFileName);
        unlink($zipFileName); // Elimina el archivo ZIP después de la descarga
    } else {
        echo 'Error al crear el archivo ZIP';
    }
} else {
    echo 'No se encontraron archivos para descargar.';
}


