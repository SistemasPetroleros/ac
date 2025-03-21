<?php
include_once '../config.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../funciones.php';
include_once '../../model/usuarios.php';
include_once '../../login.php';

$buscaBeneficiario = isset($_POST['dni']) ? $_POST['dni'] : '';
$fechaDesde = isset($_POST['fechaDesde']) ? $_POST['fechaDesde'] : '';
$fechaHasta = isset($_POST['fechaHasta']) ? $_POST['fechaHasta'] : '';
$buscaProducto = isset($_POST['buscaProducto']) ? $_POST['buscaProducto'] : '';
$estado = isset($_POST['buscaEstado']) ? $_POST['buscaEstado'] : '-1';
$idSolicitud = isset($_POST['idSolicitudBuscar']) ? $_POST['idSolicitudBuscar'] : '';
$buscaBeneficiarioB24 = isset($_POST['buscaBeneficiarioB24']) ? $_POST['buscaBeneficiarioB24'] : '';
$idPuntoDispensa = isset($_POST['idPuntoDispensa']) ? $_POST['idPuntoDispensa'] : '-1';
$buscarNroRemito = isset($_POST['buscarNroRemito']) ? $_POST['buscarNroRemito'] : '';
$buscarEsSur = isset($_POST['buscarEsSur']) ? $_POST['buscarEsSur'] : '-1';
$buscarNroRemito = isset($_POST['buscarNroRemito']) ? $_POST['buscarNroRemito'] : '';
$urgenteBuscar = isset($_POST['urgenteBuscar']) ? $_POST['urgenteBuscar'] : '-1';
$idCategoriaBuscar = isset($_POST['idCategoriaBuscar']) ? $_POST['idCategoriaBuscar'] : '-1';
$idTipoSBuscar = isset($_POST['idTipoSBuscar']) ? $_POST['idTipoSBuscar'] : '-1';
$idExcluir=isset($_POST['idSolicitudEx']) ? $_POST['idSolicitudEx'] : ''; //todas las solicitudes menos la que estoy viendo


$solicitud = new materiales_solicitudes();
$arraySolicitudes = $solicitud->SelectAllFiltros($fechaDesde, $fechaHasta, $buscaProducto, $estado, $idSolicitud, $buscaBeneficiario, $buscaBeneficiarioB24, $idPuntoDispensa, $buscarNroRemito, $buscarEsSur, $urgenteBuscar, $idCategoriaBuscar, $idTipoSBuscar, $idExcluir);

?>


<br>
<br>

<legend>Historial de Solicitudes de Materiales</legend>

<table width="100%" class="table jambo_table" id="thistorialSolicitudes">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Dni</th>
            <th>Nombre</th>
            <th>Punto Dispensa</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php

        while ($x = mysqli_fetch_assoc($arraySolicitudes)) {

            echo '<tr class="odd gradeX">';
            echo '<td>' . $x['id'] . '</td>';
            echo '<td>' . fecha($x['fecha']) . '</td>';
            echo '<td>' . $x['dni'] . '</td><td>' . $x['nombre'] . '</td>';
            echo '<td>' . $x['puntoDispensa'] . '</td> ';
            echo '<td>' . $x['estado'] . '</td>';
            echo '<td><button type="button" data-toggle="modal" data-target="#modalVerSolicitud" class="btn btn-dark btn-round" onclick="mostrarSolicitud(\'' . $x['id'] . '\');"><i class="fa fa-eye" aria-hidden="true"></i></button> </td>';
            echo '</tr>';
        }
        ?>

    </tbody>
</table>