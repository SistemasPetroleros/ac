<?php 
error_reporting(0);
include_once 'controller/funciones.php';

$cadenaEncriptada=$_GET['value'];
$cadenaEncriptada = str_replace('_', '/', $cadenaEncriptada);
$cadenaEncriptada = str_replace('-', '+', $cadenaEncriptada);


$idSolicitud=decryptWithKey($cadenaEncriptada ,'altocosto2023encrypt');

//$carpeta = 'controller/file_manager/adjuntos/'.$idSolicitud;
$carpeta='C:/ArchivosAC/file_manager/adjuntos/'.$idSolicitud;

$archivos = glob($carpeta . '/*');

if (count($archivos) > 0) {
    // Nombre del archivo ZIP
    $zipFileName = 'archivos_'.$idSolicitud.'.zip';
	
	//echo $zipFileName;

    // Crear el archivo ZIP
    $zip = new ZipArchive();
	$i=0;
    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        foreach ($archivos as $archivo) {
            
            if (strpos($archivo, 'Privado-') === false){
			  $i++;
              $zip->addFile($archivo, basename($archivo));
			}
        }
        $zip->close();
    }
	
	if ($i==0)
	{
		echo "No existen archivos para descargar.";
		exit;
	}
	
    // Descargar el archivo ZIP
	
    if (file_exists($zipFileName)) {
		$nombre= "archivos_$idSolicitud.zip";
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $nombre. '"');
        header('Content-Length: ' . filesize($zipFileName));
        readfile($zipFileName);
        unlink($zipFileName); // Elimina el archivo ZIP después de la descarga
    } else {
        echo 'Error al crear el archivo ZIP';
    }
} else {
    echo 'No se encontraron archivos para descargar.';
}


