<?php
include_once '../config.php';
include_once '../../model/materiales_productos.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../funciones.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');
$tomorrow = date("Y-m-d H:i", strtotime(date("Y-m-d H:i") . "+ 1 days"));

$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');
$idCotizacion = (isset($_POST['idCotizacion']) ? $_POST['idCotizacion'] : '');
$idProveedor = (isset($_POST['idProveedor']) ? $_POST['idProveedor'] : '');


$mproducto = new materiales_productos('');
$resultadoProd = $mproducto->SelectAllOrtopedia($idSolicitud);

$objStatus = new  materiales_solicitudes_estados();
$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
if ($x = mysqli_fetch_assoc($lastStatus)) {
    $estadoActual = $x['id_estados'];
}

$items= new materiales_cotizacion_item();

?>

<form id="formItems">


    <br />
    <legend>Items</legend>

   <?php if($estadoActual==31) { ?>

    <div class="form-row">
        <div class="form-group col-sm-12">
            <label>Producto</label>
            <select id="idProductoc" name="idProductoc" class="form-control selectpicker" data-live-search="true" title="Seleccion Opción..." onchange="traerValorSugerido(this.value,'');">
                <?php
                while ($row = mysqli_fetch_assoc($resultadoProd)) {
                    echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                }

                ?>
            </select>
        </div>

    </div>


    <div class="form-row">
        <div class="form-group col-sm-2">
            <label>Cantidad</label>
            <input id="cantCot" name="cantCot" type="number" step="0.01" class="form-control" min="0" value="1" onblur="calcularTotalOrtopedia('');" />
        </div>

        <div class="form-group col-sm-2">
            <label>Importe Unitario</label>
            <input id="importeUnit" name="importeUnit" type="number" step="0.01" class="form-control" min="0" value="0" onblur="calcularTotalOrtopedia('');" />
            <div id="divSugerido" style="display:none;"><small>*Valor Sugerido</small></div>
        </div>

        <div class="form-group col-sm-2">
            <label>Total</label>
            <input id="totalCot" name="totalCot" type="number" step="0.01" class="form-control" readonly />
        </div>
    </div>

    <div class="form-row">

        <div class="form-group col-sm-3">
            <br />
            <button type="button" id="addPord" class="btn btn-primary" onclick="agregarItem(1);"><i class="fa fa-plus" aria-hidden="true"></i> </button>
        </div>


    </div>


    <hr />
    <?php } ?>



    <table class="table jambo_table" id="tablaitems">
        <thead>
            <th>Producto</th>
            <th>Cantidad </th>
            <th>Importe Unitario ($)</th>
            <th>Total ($)</th>
            <th>Acción</th>
        </thead>
        <tbody id="tbodyitemscot">
            <?php if ($estadoActual == 32 or $estadoActual == 37) {

                $resultado = $items->getItemsCotizacion($idSolicitud);
                $filas = "";
                while ($row = mysqli_fetch_assoc($resultado)) {
                    $filas .= "<tr>";
                    $filas .= "<td>" . $row['nombre'] . "</td>";
                    $filas .= "<td>" . $row['cantCotizada'] . "</td>";
                    $filas .= "<td>" . $row['importe_unitario'] . "</td>";
                    $filas .= "<td>" . number_format($row['cantCotizada'] * $row['importe_unitario'], 2, '.', '') . "</td>";
                    $filas .= '<td></td>';
                    $filas .= "</tr>";
                }

                echo $filas;
            } ?>

        </tbody>
        <table>


</form>

<div class="form-row">
    <div class="form-group col-sm-6">


        <?php if ($estadoActual == 31) { ?>

            <button class="btn btn-dark btn-round" id="guardarC" type="button" onclick="solicitarAutorizacion(1);"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                Solicitar Autorización</button>

        <?php } ?>


    </div>

</div>