<?php
include_once '../config.php';
include_once '../../model/materiales_solicitudes_emails.php';
include_once '../funciones.php';

$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');

$objmail = new materiales_solicitudes_emails();

$objmail->settipo('1');
$objmail->setid_solicitudes($idSolicitud);

$resultado = $objmail->SelectAll();

$hmtl = '';
while ($row = mysqli_fetch_assoc($resultado)) {

    $estado = '';
    if ($row['ok'] == 1) $estado = 'Enviado';
    else $estado = 'No Enviado';
    $html .= '<tr>';
    $html .= '<td>' . $row['fAlta'] . '</td>';
    $html .= '<td>' . $row['userAlta'] . '</td>';
    $html .= '<td>' . $estado . '</td>';
    $html .= '<td>' . $row['descripcion'] . '</td>';
    $html .= '</tr>';
}


echo $html;
