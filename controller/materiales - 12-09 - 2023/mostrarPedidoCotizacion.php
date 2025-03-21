<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/materiales_solicitudes_estados.php';

$citems = new materiales_cotizacion_item();

$resultado = $citems->getItemsPedido(@$idSolicitud);

$objStatus = new materiales_solicitudes_estados();
$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);

if ($x = mysqli_fetch_assoc($lastStatus)) {
    $idEstadoActualSol = $x['id_estados'];
}


?>


<table class="table jambo_table" id="pedidoCot">
    <thead>
        <th>Id. Item</th>
        <th>Proveedor</th>
        <th>Producto</th>

        <th>Marca</th>
        <th>Cant. Solicitada</th>
        <th>Cant. Cotizada</th>
        <th>Precio Unitario</th>
        <th>Total Cotizado</th>
        <th>Cant. Aprobada</th>
        <th>Total Aprobado</th>
        <th>Acción</th>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo '<td>' . $row['idItem'] . '<input type="hidden" id="id_item_' . $i . '" name="id_item_' . $i . '" class="form-control" readonly value="' . $row['idItem'] . '" /> </td>';
            echo '<td >' . $row['proveedor'] . '</td>';
            echo '<td >' . $row['nombre'] . '<input type="hidden" id="idItemCot_' . $i . '" name="idItemCot_' . $i . '" value="' . $row['idItemCot'] . '" /></td>';
            echo '<td >' . $row['marca'] . '</td>';
            echo '<td style="text-align:center;">' . $row['cantSolicitada'] . '<input type="hidden" id="cant_' . $i . '" name="cant_' . $i . '" class="form-control" readonly value="' . $row['cantidad'] . '" /></td>';
            echo '<td style="text-align:center;">' . $row['cantCotizada'] . '</td>';

            echo '<td> ' . number_format($row['importe_unitario'], 2, '.', '') . '</td>';
            echo '<td> ' . number_format($row['total'], 2, '.', '') . '</td>';
            echo '<td style="text-align:center;"><span class="badge badge-success">' . $row['cantidadAprob'] . '</span></td>';
            echo '<td> ' . number_format($row['totalAprob'], 2, '.', '') . '</td>';
            echo '<td><button id="quitar" class="btn btn-danger" onclick="quitar(' . $row['idItem'] . ',' . @$idSolicitud . ',' . $row['idItemCot'] . ');"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<?php if($idEstadoActualSol==34){ ?>

<button class="btn btn-primary btn-round" id="solicitarAut26" onclick="finalizarCotizacion(<?= @$idSolicitud ?>);"><i class="fa fa-check" aria-hidden="true"></i> Finalizar Cotización</button>
<button class="btn btn-danger btn-round" id="solicitarAut26" onclick="rechazarCotizaciones(<?= @$idSolicitud ?>);"><i class="fa fa-times" aria-hidden="true"></i> Rechazar Cotización</button>
    
<?php     
}
?>
