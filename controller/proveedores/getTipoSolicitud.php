<?php
include_once '../config.php';
include_once '../../model/tipo_solicitud.php';

$obj = new tipo_solicitud();

$resultado=$obj -> SelectAll();
$options="";
while($row=mysqli_fetch_assoc($resultado)){
    $options.='<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
}


echo $options;


