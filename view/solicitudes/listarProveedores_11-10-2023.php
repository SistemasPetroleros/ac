<?php

error_reporting(0);
include_once '../../model/solicitudes_items.php';
include_once '../../model/cotizacion_items.php';
$items = new solicitudes_items();
$items->setid_solicitudes(@$idSolicitud);

$citems = new cotizacion_item();


$resultadoProv = $obj->SelectAllAsignados($idSolicitud);



if ($estadoActual == 3 or $estadoActual >= 5) {

  $arrayItems = $citems->getItemsCotizacion($idSolicitud, ''); ?>


  <br>
  <br> 
  <legend>Items Cotizados</legend>
  <table class="table jambo_table" id="tablaitems">
    <thead>

      <th>Producto</th>
      <!-- <th>Marca</th> -->
      <th>Cantidad Solicitada</th>
      <th>Cantidad Cotizada</th> 
      <th>Importe Unitario ($)</th>
      <th>Total Cotizado ($)</th>
      <th>Cantidad Aprobada</th>
      <th>Total Aprobado ($)</th>
      <th>Proveedor</th>
      <th>Estado</th>

    </thead>
    <tbody>




      <?php
      $i = 0;
      $disabled = '';
      while ($row = mysqli_fetch_assoc($arrayItems)) {

        echo '<tr>';
        echo '<td>' . $row['nombre'] . ' </td>';
        echo '<td style="text-align:center;">' . $row['cantSolicitada'] . '</td>';
        echo '<td style="text-align:center;">' . $row['cantCotizada'] . '</td>';
        echo '<td>' . $row['importe_unitario'] . '</td>';
        echo '<td>' . $row['total'] . '</td>';
        echo '<td>' . $row['cantidadAprob'] . '</td>';
        echo '<td>' . $row['totalAprob'] . '</td>';
        echo '<td>' . $row['proveedor'] . '</td>';
        echo '<td><span class="badge badge-secondary">' . $row['estadoItem'] . '</span></td>';
        echo '</tr>';

        $i++;
      }

      ?>

    </tbody>
    <table>


      <?php
    } else {
      if ($estadoActual == 4) {
        //Si esta en proceso de cotizacion

      ?>



        <form id="tablaProv">
          <br />
          <div class="panel panel-info">
            <div class="panel-heading">BÚSQUEDA</div>
            <div class="panel-body">
              <form id="formB">
                <div class="form-group">
                  <div class="col-sm-3">
                    <label>#Cotización:</label>
                    <input type="number" id="nroCotizacion" name="nroCotizacion" class="form-control" />
                  </div>


                  <div class="col-sm-6">
                    <label>Proveedor:</label>
                    <!--  <select id="proveedorCot" name="proveedorCot" class="form-control selectpicker" data-live-search="true" title="Seleccione..." multiple> -->
                    <select id="proveedorCot" name="proveedorCot" class="form-control">
                      <option value="T">Todos</option>
                      <?php
                      while ($rowp = mysqli_fetch_assoc($resultadoProv)) {
                        echo '<option value="' . $rowp['id'] . '">' . $rowp['nombre'] . '</option>';
                      }
                      ?>
                    </select>

                  </div>

                  <div class="col-sm-3">
                    <label>Proveedor Cotiza?</label>
                    <select id="cotiza" name="cotiza" class="form-control" d>
                      <option value="T">Todos</option>
                      <option value="S">Sí</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">

                    <button id="buscarprov" class="btn btn-round btn-dark" type="button" onclick="buscarCotizaciones();"><i class="fa fa-search" aria-hidden="true"></i>
                      Buscar</button>
                  </div>
                </div>

              </form>

            </div>
          </div>
          <br />
          <legend>Listado de Cotizaciones</legend>
          <br />

          <div id="divresultado">
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
                //while 2


                $resProm = $obj->getCalificacionPromedio($x['idProveedor']);
                $rowprom = mysqli_fetch_assoc($resProm);
                $promedio = $rowprom['promedio'];

              ?>

                <div class="panel panel-info">
                  <div class="panel-heading">
                    <b>Proveedor</b>: <?= $x['nombreProv'] ?> - <b>Cotización</b> #<?= $x['id'] ?>
                    <br />
                    <b>Puntuación Promedio: </b>
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

                            <th>Marca</th>
                            <th>Cant. Solicitada</th>
                            <th>Cant. Cotizada</th>
                            <th>Cant. Aprobada</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                            <!--    <th>Estado</th>  -->
                            <th>Acción</th>
                          </thead>
                          <tbody>
                            <?php



                            $arrayItems = $items->SelectAllCotizacion($x['idProveedor']);


                            $i = 0;
                            $cont = 0; //cuenta cantidad items en estado borrador o sin cotizar
                            while ($row = mysqli_fetch_assoc($arrayItems)) {
                              //while 1

                              $ri = $citems->cantidadTotalAprobada($row['id']);
                              $rowi = mysqli_fetch_assoc($ri);


                              if ($row['id_estados'] != "30"  and $row['id_estados'] != '') {

                                echo '<tr>';
                                echo '<td>' . $row['id'] . '<input type="hidden" id="id_item_' . $i . '" name="id_item_' . $i . '" class="form-control" readonly value="' . $row['id'] . '" /> </td>';
                                echo '<td >' . $row['nombre'] . ' <input type="hidden" id="idItemCot_' . $i . '" name="idItemCot_' . $i . '" value="' . $row['idItemCot'] . '" /></td>';
                                echo '<td >' . $row['marca'] . '</td>';
                                echo '<td style="text-align:center;">' . $row['cantidad'] . '<input type="hidden" id="cant_' . $i . '" name="cant_' . $i . '" class="form-control" readonly value="' . $row['cantidad'] . '" /></td>';
                                echo '<td style="text-align:center;">' . $row['cantCotizada'] . '</td>';
                                echo '<td style="text-align:center;">' . $row['cantidadAprob'] . '</td>';
                                echo '<td>' . number_format($row['importe_unitario'], 2, '.', '') . '</td>';
                                echo '<td>' . number_format($row['total'], 2, '.', '') . '</td>';
                                //  echo '<td>' . $row['STATUS'] . '</td>';
                                echo '<td> ';

                                if ($row['cantCotizada'] > $row['cantidadAprob'] and $rowi['suma'] < $row['cantidad']) {
                                  echo '<button class="btn btn-success" type="button" id="agregar" onclick="abrirModal2(' . $row['id'] . ',\'A\',' . $idSolicitud . ',' . $row['idItemCot'] . ');" ><i class="fa fa-plus" aria-hidden="true"></i></button> ';
                                }

                                echo '</td>';
                                echo '</tr>';
                              } else {
                                $cont++;
                              }



                              $i++;
                            } // while 1

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



              } //while 2

              ?>


            </div>

            <div class="form-group">
              <legend>Lista de Ítems a Aprobar</legend>
              <?php include_once('mostrarPedidoCotizacion.php'); ?>
            </div>


          </div>





        </form>





      <?php


      } else {

        $data['idSolicitud'] = $idSolicitud;
        $data['cotiza'] = 'T';
        $res = $obj->SelectAllCotizacionesFiltros($data);



      ?>

        <form id="tablaProv">
          <br />
          <legend>Listado de Proveedores Habilitados a Cotizar</legend>

          <table width="100%" class="table jambo_table">
            <thead>

              <th>Id.</th>
              <th>Nombre</th>
              <th>Tipo</th>
            </thead>
            <tbody>
              <?php
              while ($x = mysqli_fetch_assoc($res)) {

                echo '<tr>';
                echo '<td>' . $x['idProveedor'] . '</td>';
                echo '<td>' . $x['nombreProv'] . '</td>';
                echo '<td>' . $x['tipo'] . '</td>';
                echo '</tr>';
              }

              ?>

            </tbody>
          </table>
        </form>



    <?php }  



    } ?>


<!-- Modal -->
<div class="modal fade modal-vertical-center" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
        <button type="button" class="close" onclick="cerrarModalProveedor();" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input id="id_solicitud" class="form-control" type="hidden" />
        <input id="id_cotizacion" class="form-control" type="hidden" />
        <input id="importeCot" class="form-control" type="hidden" />
        <input id="observacionesCot" class="form-control" type="hidden" />
        <input id="operacion" class="form-control" type="hidden" />
        <div id="mensaje">

        </div>
        <div id="mensaje2">

        </div>
        <span><i class="fa fa-info" aria-hidden="true"></i> Antes de confirmar la acción ingrese el Número de Confirmación que se encuentra en el cuadro de texto siguiente:</span>
      </div>
      <div class="modal-footer">

        <div>
          <input id="randc" name="randc" type="text" class="form-control pull-left" placeholder="<?= $rand ?>" style="width: 50px;">
          <input id="randc2" name="randc2" type="hidden" value="<?= $rand ?>">
          &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
          <button type="button" class="btn btn-danger btn-round pull-left" id="Confirmar" name="Confirmar" onclick="onChangeStatus();">Confirmar</button>
          <button type="button" class="btn btn-secondary btn-round pull-left" onclick="cerrarModalProveedor();">Cancelar</button>
        </div>

      </div>
    </div>
  </div>
</div>