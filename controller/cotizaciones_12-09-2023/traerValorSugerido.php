<?php

include_once '../config.php';
include_once '../../model/materiales_productos.php';
include_once '../funciones.php';


$productos = new materiales_productos();
$resultado = $productos->getProductoMax($_POST['id']);

if ($row = mysqli_fetch_assoc($resultado)) {
    echo $row['montoMax'];
} else
    echo "0";
