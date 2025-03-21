<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_items.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/cotizacion_items.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/materiales_solicitudes.php';
include_once '../../model/materiales_solicitudes_items.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/productos.php';
include_once '../../model/materiales_productos.php';
include_once '../funciones.php';

error_reporting(0);

date_default_timezone_set('America/Argentina/Buenos_Aires');
$tomorrow = date("Y-m-d H:i", strtotime(date("Y-m-d H:i") . "+ 1 days"));

$idSolicitud = (isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : '');
$idCotizacion = (isset($_POST['idCotizacion']) ? $_POST['idCotizacion'] : '');
$idProveedor = (isset($_POST['idProveedor']) ? $_POST['idProveedor'] : '');
$idTipoSolicitud = (isset($_POST['idTipoSolicitud']) ? $_POST['idTipoSolicitud'] : '');

if ($idTipoSolicitud == 1) {
    $cotizacion = new cotizacion_solic_prov($idCotizacion);
} else {
    $cotizacion = new materiales_cotizacion_solic_prov($idCotizacion);
}

$idEstadoCotizacion = $cotizacion->getid_estados();

$objStatus = new  materiales_solicitudes_estados();
$lastStatus = $objStatus->SelectStatusRecent($idSolicitud);
if ($x = mysqli_fetch_assoc($lastStatus)) {
    $estadoActual = $x['id_estados'];
}

if ($idEstadoCotizacion == "42") {
    //SI ESTA PENDIENTE DE COTIZACION AUN ESTA EDITABLE
    $mproducto = new materiales_productos('');
    $resultadoProd = $mproducto->SelectAllOrtopedia($idSolicitud);

    $items = new materiales_cotizacion_item();

?>

    <form id="formItems">


        <br />
        <legend>Items</legend>



        <div class="form-row">
            <div class="form-group col-sm-12">
                <label>Producto</label>
                <select id="idProductoc1" name="idProductoc1" class="form-control selectpicker" data-live-search="true" title="Seleccion Opción..." onchange="traerValorSugerido(this.value,'1');">
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
                <input id="cantCot1" name="cantCot1" type="number" step="0.01" class="form-control" min="0" value="1" onblur="calcularTotalOrtopedia(1);" />
            </div>

            <div class="form-group col-sm-2">
                <label>Importe Unitario</label>
                <input id="importeUnit1" name="importeUnit1" type="number" step="0.01" class="form-control" min="0" value="0" onblur="calcularTotalOrtopedia(1);" />
                <div id="divSugerido" style="display:none;"><small>*Valor Sugerido</small></div>
            </div>

            <div class="form-group col-sm-2">
                <label>Total</label>
                <input id="totalCot1" name="totalCot1" type="number" step="0.01" class="form-control" readonly />
            </div>
        </div>

        <div class="form-row">

            <div class="form-group col-sm-3">
                <br />
                <button type="button" id="addPord1" class="btn btn-primary" onclick="agregarItem(2);"><i class="fa fa-plus" aria-hidden="true"></i> </button>
            </div>


        </div>


        <hr />




        <table class="table jambo_table" id="tablaitems">
            <thead>
                <th>Producto</th>
                <th>Cantidad </th>
                <th>Importe Unitario ($)</th>
                <th>Total ($)</th>
                <th>Acción</th>
            </thead>
            <tbody id="tbodyitemscot1">

                <?php
                $resultado = $items->getItemsCotizacion($idSolicitud);
                $filas = "";
                while ($row = mysqli_fetch_assoc($resultado)) {
                   
                        $filas.="<tr>";
                        $filas.="<td>".$row['nombre']."</td>";
                        $filas.="<td>".$row['cantCotizada']."</td>";
                        $filas.="<td>".$row['importe_unitario']."</td>";
                        $filas.="<td>".number_format($row['cantCotizada']*$row['importe_unitario'],2,'.','')."</td>";
                        $filas.='<td><button type="button" class="btn btn-danger" onclick="eliminarItem('.$row['idItem'].','.$row['idItemCot'].',2);"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                        $filas.="</tr>";
                    
                }

                echo $filas;
                ?>
            </tbody>
            <table>


    </form>

    <div class="form-row">
        <div class="form-group col-sm-6">


            <?php if ($estadoActual == 31) { ?>

                <button class="btn btn-dark btn-round" id="guardarC" type="button" onclick="solicitarAutorizacion(2);"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                    Solicitar Autorización</button>

            <?php } ?>


        </div>

    </div>

<?php
} else {



    $fechavalidez =  (($cotizacion->getvalidez_propuesta() != "" and $cotizacion->getvalidez_propuesta() != null) ?  date('Y-m-d H:i', strtotime($cotizacion->getvalidez_propuesta())) :  '');
    $plazo_entrega_dias = (($cotizacion->getplazo_entrega_dias() != "" and $cotizacion->getplazo_entrega_dias() != null) ? $cotizacion->getplazo_entrega_dias() : '0');

    if ($cotizacion->getincluye_flete() != "" and $cotizacion->getincluye_flete() != null) {
        if ($cotizacion->getincluye_flete() == 1) {
            $checkedYes = 'checked';
            $checkedNo = '';
        } else {

            $checkedYes = '';
            $checkedNo = 'checked';
        }
    } else {
        $checkedYes = '';
        $checkedNo = 'checked';
    }

    $condiciones_pago = (($cotizacion->getcondiciones_pago() != "" and $cotizacion->getcondiciones_pago() != null) ? $cotizacion->getcondiciones_pago() : '');
    $observaciones = (($cotizacion->getobservaciones() != "" and $cotizacion->getobservaciones() != null) ? $cotizacion->getobservaciones() : '');

?>

    <form id="formItems">
        <br />

        <?php if ($idTipoSolicitud == 2) { ?>
            <legend>Datos Propuesta</legend>

            <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="validezPropuesta">Validez Propuesta</label>
                    <input type="datetime-local" class="form-control validar" id="validezPropuesta" name="validezPropuesta" value="<?php echo $fechavalidez; ?>" required />
                    <div id="div_validezPropuesta" style="display:none;">
                        <small style="color:red;"> Requerido </small>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label for="plazo_entrega_dias">Plazo de Entrega (en días)</label>
                    <input type="number" class="form-control validar" id="plazo_entrega_dias" name="plazo_entrega_dias" value="<?php echo $plazo_entrega_dias; ?>" required />
                    <div id="div_plazo_entrega_dias" style="display:none;">
                        <small style="color:red;"> Requerido </small>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label for="incluye_flete">Incluye Flete:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="incluye_flete" id="incluye_flete1" <?php echo $checkedYes; ?>>
                        <label class="form-check-label" for="incluye_flete1">
                            Sí
                        </label>
                        <input class="form-check-input" type="radio" name="incluye_flete" id="incluye_flete2" <?php echo $checkedNo; ?>>
                        <label class="form-check-label" for="incluye_flete2">
                            No
                        </label>
                    </div>

                </div>


                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="condiciones_pago">Condiciones de Pago</label>
                        <textarea class="form-control" id="condiciones_pago" name="condiciones_pago" rows="3"> <?php echo $condiciones_pago; ?></textarea>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="observaciones_cotizacion">Observaciones: </label>
                        <textarea class="form-control" rows="3" id="observaciones_cotizacion" name="observaciones_cotizacion"> <?php echo $observaciones; ?> </textarea>
                    </div>

                </div>

            <?php } ?>

            </div>
            <br />
            <legend>Items</legend>

            <?php if ($idTipoSolicitud == 2) { ?>

                <div class="alert alert-info" role="alert">
                    Los ítems con cantidad cotizada y precio unitario igual a cero no serán cotizados.
                </div>

            <?php } ?>


            <?php

            if ($idTipoSolicitud == 1) {
                $solicitud = new solicitudes($idSolicitud);
                $items = new solicitudes_items();
                $itemsCotizacion = new cotizacion_item('');
            } else {
                $solicitud = new materiales_solicitudes($idSolicitud);
                $items = new materiales_solicitudes_items();
                $itemsCotizacion = new materiales_cotizacion_item('');
            }


            $items->setid_solicitudes($solicitud->getid());

            $data['idSolicitud'] = $idSolicitud;
            $data['idProveedor'] = $idProveedor;

            $resEstado =  $itemsCotizacion->devolverEstadoItems($data);

            $idEstado = 39;
            if ($r = mysqli_fetch_assoc($resEstado)) {
                $idEstado = $r['id_estados'];
            }

            $disabled = "";
            if ($idEstado == 41) $disabled = 'disabled';



            $arrayItems = $items->SelectAllCotizacion($idProveedor);



            if ($idTipoSolicitud == 1) { ?>


                <table class="table jambo_table" id="tablaitems">
                    <thead>
                        <th>Producto</th>
                        <th>Presentación</th>
                        <th>Monodroga</th>
                        <th>Cantidad Solicitada</th>
                        <th>Cantidad Cotizada</th>
                        <?php // if ($idEstado == 41) {
                        echo '<th>Cantidad Aprobada</th>';
                        //} 
                        ?>
                        <th>Importe Unitario ($)</th>
                        <th>Total Cotizado ($)</th>
                        <th>Marca</th>
                    </thead>
                    <tbody>




                        <?php
                        $i = 0;
                        $disabled = '';
                        while ($row = mysqli_fetch_assoc($arrayItems)) {
                            if ($row['id_estados'] == 41) $disabled = 'disabled';


                            echo '<tr>';
                            echo '<td>' . $row['nombre'] . '<input type="hidden" id="id_item_' . $i . '" name="id_item_' . $i . '" class="form-control" readonly value="' . $row['id'] . '" /> </td>';
                            echo '<td>' . sanear_string($row['presentacion']) . ' <input type="hidden" id="idItemCot_' . $i . '" name="idItemCot_' . $i . '" value="' . $row['idItemCot'] . '" /></td>';
                            echo '<td >' . $row['monodroga'] . '</td>';
                            echo '<td style="text-align:center;">' . $row['cantidad'] . '<input type="hidden" id="cant_' . $i . '" name="cant_' . $i . '" class="form-control" readonly value="' . $row['cantidad'] . '" /></td>';
                            echo '<td><input type="number" class="form-control validar" id="cantCot_' . $i . '" min="0" name="cantCot_' . $i . '"  onblur="calcularTotal(' . $i . '); verificaCantidad(' . $i . ');" value="' . $row['cantCotizada'] . '" required ' . $disabled . ' /> <div id="div_cantCot_' . $i . '" style="display:none;">
                <small style="color:red;"> Requerido </small>
              </div></td>';

                            //       if ($idEstado == 11) {
                            echo '<td><input type="number" step="0.01" class="form-control validar" readonly id="cantidadAprob' . $i . '" name="cantidadAprob' . $i . '" value="' . $row['cantidadAprob'] . '"/></td>';
                            //             }

                            echo '<td><input type="number" step="0.01" class="form-control validar" id="precioUnit' . $i . '" name="precioUnit' . $i . '" value="' . $row['importe_unitario'] . '" onblur="calcularTotal(' . $i . '); verificaImporte(' . $i . ');" required ' . $disabled . ' /> <div id="div_precioUnit' . $i . '" style="display:none;">
                <small style="color:red;"> Requerido </small>
              </div></td>';
                            echo '<td><input type="number" class="form-control" id="total' . $i . '" readonly name="total' . $i . '" value="' . number_format($row['total'], 2, '.', '') . '"/></td>';
                            echo '<td><input type="text"  class="form-control validar" id="marca' . $i . '" name="marca' . $i . '"   value="' . $row['marca'] . '" ' . $disabled . '/> <div id="div_marca' . $i . '" style="display:none;">
                <small style="color:red;"> Requerido </small>
              </div></td>';
                            echo '</tr>';

                            $i++;
                        }

                        ?>

                    </tbody>
                    <table>
                        <input id="long" name="long" type="hidden" value="<?php echo $i++; ?>">
                    <?php    } else { ?>

                        <table class="table jambo_table" id="tablaitems">
                            <thead>
                                <th>Producto</th>
                                <th>Cantidad Solicitada</th>
                                <th>Cantidad Cotizada</th>
                                <th>Importe Unitario ($)</th>
                                <th>Total Cotizado ($)</th>
                                <th>Cantidad Aprobada</th>
                                <th>Total Aprobado ($)</th>
                                <th>Marca</th>
                            </thead>
                            <tbody>




                                <?php
                                $i = 0;
                                $disabled = '';
                                while ($row = mysqli_fetch_assoc($arrayItems)) {
                                    if ($row['id_estados'] == 41) $disabled = 'disabled';


                                    echo '<tr>';
                                    echo '<td>' . $row['nombre'] . '<input type="hidden" id="id_item_' . $i . '" name="id_item_' . $i . '" class="form-control" readonly value="' . $row['id'] . '" /> </td>';
                                    echo '<td style="text-align:center;">' . $row['cantidad'] . '<input type="hidden" id="cant_' . $i . '" name="cant_' . $i . '" class="form-control" readonly value="' . $row['cantidad'] . '" /></td>';
                                    echo '<td><input type="number" class="form-control validar" id="cantCot_' . $i . '" min="0" name="cantCot_' . $i . '"  onblur="calcularTotal(' . $i . '); verificaCantidad(' . $i . ');" value="' . $row['cantCotizada'] . '" required ' . $disabled . ' /> <div id="div_cantCot_' . $i . '" style="display:none;">
                <small style="color:red;"> Requerido </small>
              </div></td>';

                                    //       if ($idEstado == 11) {
                                   
                                    //             }

                                    echo '<td><input type="number" step="0.01" class="form-control validar" id="precioUnit' . $i . '" name="precioUnit' . $i . '" value="' . $row['importe_unitario'] . '" onblur="calcularTotal(' . $i . '); verificaImporte(' . $i . ');" required ' . $disabled . ' /> <div id="div_precioUnit' . $i . '" style="display:none;">
                <small style="color:red;"> Requerido </small>
              </div></td>';
                                    echo '<td><input type="number" class="form-control" id="total' . $i . '" readonly name="total' . $i . '" value="' . number_format($row['total'], 2, '.', '') . '"/></td>';
                                    echo '<td><input type="number" step="0.01" class="form-control validar" readonly id="cantidadAprob' . $i . '" name="cantidadAprob' . $i . '" value="' . $row['cantidadAprob'] . '"/></td>';
                                    echo '<td><input type="number" step="0.01" class="form-control validar" readonly id="totalAprob' . $i . '" name="totalAprob' . $i . '" value="' . $row['totalAprob'] . '"/></td>';
                                    echo '<td><input type="text"  class="form-control validar" id="marca' . $i . '" name="marca' . $i . '"   value="' . $row['marca'] . '" ' . $disabled . '/> <div id="div_marca' . $i . '" style="display:none;">
                <small style="color:red;"> Requerido </small>
              </div></td>';
                                    echo '</tr>';

                                    $i++;
                                }

                                ?>

                            </tbody>
                            <table>


                            <?php } ?>

    </form>

    <div class="form-row">
        <div class="form-group col-sm-6">

            <?php if ($idEstado == "39") { ?>
                <button class="btn btn-dark btn-round" id="guardarB" type="button" onclick="validaGuarda('B');"><i class="fa fa-eraser" aria-hidden="true"></i>
                    Guardar Borrador</button>


                <button class="btn btn-dark btn-round" id="guardarC" type="button" onclick="validaGuarda('P');"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                    Enviar Cotización</button>

            <?php } ?>

        </div>

    </div>

<?php } ?>