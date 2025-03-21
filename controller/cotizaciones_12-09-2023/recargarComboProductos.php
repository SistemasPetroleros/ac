<?php
include_once '../config.php';
include_once '../../model/materiales_productos.php';
include_once '../funciones.php';

$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');

$mproducto = new materiales_productos('');
$resultadoProd = $mproducto->SelectAllOrtopedia($idSolicitud);

$options="";
while($row=mysqli_fetch_assoc($resultadoProd)){
    $options.='<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
}

echo $options;