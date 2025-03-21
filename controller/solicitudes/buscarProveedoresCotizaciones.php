<?php
include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../funciones.php';
include_once '../../model/cotizacion_items.php';

include_once '../../model/solicitudes_items.php';
$items = new solicitudes_items();

$citems = new cotizacion_item();



$obj = new cotizacion_solic_prov();
$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
if (isset($_GET['idSolicitud'])) {
  $idSolicitud = $_GET['idSolicitud'];
}

$obj->setid_solicitudes($idSolicitud);
$items->setid_solicitudes(@$idSolicitud);
?>


<div class="col-md-12">

  <?php
  //Si la solicitud paso a proceso de compra/anulada no permite cambios 
  $disabled = "";
  if ($estadoActual > 4) {
    $disabled = 'disabled';
  }

  //Verifica si el usuario tiene permiso para modificar cotizaciones en el estado actual de la solicitud
  $tieneAcceso = FALSE;
  if (esMiembro($estadoActual, $permisos)) {
    $tieneAcceso = TRUE;
  }


  //filtros
  $data['idCotizacion'] = (isset($_POST['nroCotizacion']) ? $_POST['nroCotizacion'] : '');
  $data['idsProveedores'] = (isset($_POST['idsProveedores']) ? $_POST['idsProveedores'] : '');
  $data['cotiza'] = (isset($_POST['cotiza']) ? $_POST['cotiza'] : 'S');
  $data['idSolicitud'] = $idSolicitud;
  $res = $obj->SelectAllCotizacionesFiltros($data);



  while ($x = mysqli_fetch_assoc($res)) {
    $resProm = $obj->getCalificacionPromedio($x['idProveedor']);
    $rowprom = mysqli_fetch_assoc($resProm);
    $promedio = $rowprom['promedio'];

  ?>

    <div class="panel panel-info">
      <div class="panel-heading">
        <b>Proveedor</b>: <?= $x['nombreProv'] ?> - <b>Cotización</b> #<?= $x['id'] ?>
        <br />
        <b>Puntuación Promedio:</b>
        <?php
        $i = 1;
        while ($i <= 5) {
          if ($i <= $promedio) {
            echo '<span class="fa fa-star checkedStar"></span>';
          } else {
            echo '<span class="fa fa-star"></span>';
          }

          $i++;
        } ?>


      </div> <!--panel-heading-->
      <div class="panel-body"> <!--panel-body-->
        <div class="form-group">


          <div class="col-sm-4">
            <label><u>Validez Propuesta</u>:
              <?php if ($x['validez_propuesta'] != "" and $x['validez_propuesta'] != null)
                echo date('d/m/Y H:i', strtotime($x['validez_propuesta']));
              else echo "-";

              ?>

            </label>
          </div>
          <div class="col-sm-4">
            <label><u>Plazo de Entrega (En días)</u>: <?= $x['plazo_entrega_dias'] ?></label>

          </div>
          <div class="col-sm-4">
            <label><u>Incluye Flete</u>:
              <?php

              switch ($x['incluye_flete']) {
                case "1":
                  echo "Sí";
                  break;
                case "0":
                  echo "No";
                  break;
                default:
                  echo "-";
                  break;
              }

              ?>
            </label>
          </div>

        </div>

        <div class="form-group">
          <div class="col-sm-4">
            <label><u>Condiciones de Pago</u>: <?= $x['condiciones_pago'] ?>
          </div>

          <div class="col-sm-4">
            <label><u>Observaciones Proveedor</u>: <?= $x['observaciones'] ?>
          </div>

          <div class="col-sm-4">
            <label><u>Fecha Visualización</u>:
              <?php if ($x['fecha_visualizacion'] != "" and $x['fecha_visualizacion'] != null)
                echo date('d/m/Y H:i', strtotime($x['fecha_visualizacion'])) . ' (' . $x['usuario_visualizacion'] . ")";
              else echo "-";
              ?>

          </div>



        </div>

        <div class="form-group">
          <div class="col-sm-12">
            <br />
            <br />
            <legend>Items Cotizados</legend>
            <table class="table jambo_table">
              <thead>
                <th>Id. Item</th>
                <th>Producto</th>
                <th>Presentación</th>
                <th>Monodroga</th>
                <th>Marca</th>
                <th>Cant. Solicitada</th>
                <th>Cant. Cotizada</th>
                <th>Cant. Aprobada</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acción</th>
              </thead>
              <tbody>
                <?php



                $arrayItems = $items->SelectAllCotizacion($x['idProveedor']);


                $i = 0;
                $cont = 0; //cuenta cantidad items en estado borrador o sin cotizar
                while ($row = mysqli_fetch_assoc($arrayItems)) {

                  $ri = $citems->cantidadTotalAprobada($row['id']);
                  $rowi = mysqli_fetch_assoc($ri);



                  if ($row['id_estados'] != "30"  and $row['id_estados'] != '') {

                    echo '<tr>';
                    echo '<td>' . $row['id'] . '<input type="hidden" id="id_item_' . $i . '" name="id_item_' . $i . '" class="form-control" readonly value="' . $row['id'] . '" /> </td>';
                    echo '<td >' . $row['nombre'] . '</td>';
                    echo '<td>' . sanear_string($row['presentacion']) . ' <input type="hidden" id="idItemCot_' . $i . '" name="idItemCot_' . $i . '" value="' . $row['idItemCot'] . '" /></td>';
                    echo '<td >' . $row['monodroga'] . '</td>';
                    echo '<td >' . $row['marca'] . '</td>';
                    echo '<td style="text-align:center;">' . $row['cantidad'] . '<input type="hidden" id="cant_' . $i . '" name="cant_' . $i . '" class="form-control" readonly value="' . $row['cantidad'] . '" /></td>';
                    echo '<td style="text-align:center;">' . $row['cantCotizada'] . '</td>';
                    echo '<td style="text-align:center;">' . $row['cantidadAprob'] . '</td>';
                    echo '<td>' . number_format($row['importe_unitario'], 2, '.', '') . '</td>';
                    echo '<td>' . number_format($row['total'], 2, '.', '') . '</td>';
                    // echo '<td>' . $row['STATUS'] . '</td>';
                    echo '<td> ';
                    if ($row['cantCotizada'] > $row['cantidadAprob'] and $rowi['suma'] < $row['cantidad']) {
                      echo '<button class="btn btn-success" type="button" id="agregar" onclick="abrirModal2(' . $row['id'] . ',\'A\',' . $idSolicitud . ',' . $i . ');"><i class="fa fa-plus" aria-hidden="true"></i></button> ';
                    }
                    echo '</td>';
                    echo '</tr>';
                  } else {
                    $cont++;
                  }



                  $i++;
                }

                if ($i == $cont) {
                  echo '<tr>';
                  echo '<td style="text-align: center;" colspan="11">Sin ítems cotizados</td>';
                  echo '</tr>';
                }


                ?>
              </tbody>

            </table>
          </div>
        </div>






      </div>

    </div>

  <?php

    /*         $disabled = "";
      if ($x['nombreEst'] == "ANULADA") { //Si la cotización está anulada
        $disabled = 'disabled';
      }

      echo '<tr>';
      echo '</td>';
      echo '<td style="text-align:center;">' . $x['id'] . '</td>';
      echo '<td>' . $x['nombreProv'] . '</td>';
      echo '<td><input type="number" step="0.01" placeholder="0.00" ' . $disabled . ' class="form-control" value="' . $x['importe'] . '" id="importe' . $i . '"/></td>';

      echo '<td><textarea id="obs' . $i . '" ' . $disabled . ' class="form-control">' . $x['observaciones'] . '</textarea></td>';

      echo '<td><label>' . $x['nombreEst'] . '</label></td>';

      if ($estadoActual == 4 and $tieneAcceso) { //En PROCESO DE COTIZACION y el usuario con permiso de modificacion en este estado
        if ($x['nombreEst'] == "ANULADA") {
          echo '<td> ';
          echo '<button id="volver' . $i . '"  onclick="abrirModal(' . $x['id'] . ',\'R\',' . $idSolicitud . ',' . $i . ');" type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-warning btn-round" title="Volver a Pendiente"><span class="fa fa-undo"></span></button> ';
          echo '</td>';
        } else {
          if ($x['nombreEst'] == "PENDIENTE") {
            echo '<td> ';

            echo ' <button id="editar' . $i . '" onclick="editarCotizacion(' . $x['id'] . ',' . $i . ',\'agregar\');" type="button" class="btn btn-info btn-round" title="Guardar"><span class="fa fa-floppy-o"></span></button>';

            if (esMiembro("10", $permisos)) {
              echo ' <button id="aprobar' . $i . '" onclick="abrirModal(' . $x['id'] . ',\'A\',' . $idSolicitud . ',' . $i . ');" type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-success btn-round" title="Aprobar"><span class="fa fa-check"></span></button>';
            }

            if (esMiembro("12", $permisos) != FALSE) {
              echo ' <button id="anular' . $i . '"  onclick="abrirModal(' . $x['id'] . ',\'C\',' . $idSolicitud . ',' . $i . ');" type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-danger btn-round" title="Anular"><span class="fa fa-ban"></span></button>';
            }

            echo '</td>';
          } else {
            echo '<td></td>';
          }
        }
      } else {
        //CUALQUIER OTRO ESTADO NO PERMITE REALIZAR ACCIONES
        echo '<td></td>';
      }


      echo '</tr>';*/

  }

  ?>

  <div class="form-group">
    <legend>Lista de Ítems a Aprobar</legend>
      <?php include_once('mostrarPedidoCotizacion.php'); ?>
  </div>


</div>