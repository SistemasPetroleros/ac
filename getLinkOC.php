<?php
include_once 'controller/funciones.php';

$cadenaEncriptada1=$_GET['value1'];
$cadenaEncriptada1 = str_replace('_', '/', $cadenaEncriptada1);
$cadenaEncriptada1 = str_replace('-', '+', $cadenaEncriptada1);

$cadenaEncriptada2=$_GET['value2'];
$cadenaEncriptada2 = str_replace('_', '/', $cadenaEncriptada2);
$cadenaEncriptada2 = str_replace('-', '+', $cadenaEncriptada2);


$idSolicitud = decryptWithKey($cadenaEncriptada1, 'altocosto2023encrypt');
$idProveedor = decryptWithKey($cadenaEncriptada2, 'altocosto2023encrypt');

//$archivo = 'controller/file_manager/adjuntosMateriales/' . $idSolicitud . '/Privado-' . $idSolicitud . '-' . $idProveedor . "-ospepri_orden_compra.pdf";
$archivo = 'C:/ArchivosAC/file_manager/adjuntosMateriales/' . $idSolicitud . '/Privado-' . $idSolicitud . '-' . $idProveedor . "-ospepri_orden_compra.pdf";


$archivos = glob($archivo);

if (count($archivos) > 0) {
    // Nombre del archivo ZIP
    $zipFileName = 'archivos_oc_'.$idSolicitud.'.zip';

    // Crear el archivo ZIP
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        foreach ($archivos as $archivo) {
          //  if (strpos($archivo, 'Privado-') === false)
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


