<?php
include_once('../../model/productos.php');

$objProductos = new productos('');
$data = $_POST;

$total = $_POST['long'];
$idSolicitud = $_POST['idSolicitud'];

$i = 0;
$exito = 1;
while ($i < $total) {

    if ($data['chequeado' . $i] == 1) {

        $resultado = $objProductos->SelectForGtin($data['gtin' . $i]);
        if ($row = mysqli_fetch_assoc($resultado)) {
            if ($row['id'] == null or $row['id'] == "") {
                $exito = 0;
                break;
            }
        }
    }

    $i++;
}

echo $exito;
