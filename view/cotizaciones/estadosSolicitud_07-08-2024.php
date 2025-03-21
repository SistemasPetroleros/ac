<br>

<?php
$arrayEstado = $sol_estado->getestado();
$estado = mysqli_fetch_assoc($arrayEstado);

$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : -1;
echo '<div class="alert alert-dark btn-dark " role="alert"><span>ESTADO ACTUAL: <b>' . $estado['Estado'] . '</b></span></div>';

$rand= rand(0,1000);


if (($idEstado == '10' ||  $idEstado == '42')) {

  echo ' <button id="solicitarAut8" onclick="abrirModalEstadosSolicitud(\'36\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="Anular"><span class="fa fa-calendar-check-o"></span> Anular Solicitud</button> <br><br><br>';
}


?>



  
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <?php
  $cnt = 0;
  while ($x = mysqli_fetch_assoc($arrayEstadosSolicitud)) {
    $cnt++;
  ?>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="heading<?= $cnt ?>">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $cnt."_".$rand ?>" aria-expanded="<?= (($cnt == 1) ? 'true' : 'false') ?>" aria-controls="collapse<?= $cnt ?>">
            <!-- true o false mostrar por defecto -->
            <?= fecha3($x['fechaAlta']) ?> |
            <?= ucwords($x['userAlta']) ?>
            <span class="badge badge-default float-right m-2"><?= $x['estado'] ?></span>
          </a>
        </h4>
      </div>
      <div id="collapse<?= $cnt."_".$rand ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <?= $x['observaciones'] ?>
        </div>
      </div>
    </div>

  <?php
  }
  ?>


</div>



<!-- Modal Comentarios -->
<div class="modal fade" id="modalComentariosSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalComentariosSolicitud" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!--<h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>-->
        <button type="button" class="close" aria-label="Close" onclick="cerrarModalEstado();">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="mensajeEstados"></div>
        <input id="idEstadoNuevo33" name="idEstadoNuevo33" type="hidden" value="obs">

        <div class="form-group">
          <label for="observacionEstado">Observación</label>
          <textarea id="observacionEstado" class="form-control" rows="3"></textarea>

        </div>
        <!--<span><i class="fa fa-info" aria-hidden="true"></i> Antes de confirmar la acción ingrese el Número de
                    Confirmación que se encuentra en el cuadro de texto siguiente:</span>-->
      </div>
      <div class="modal-footer">

        <div>
          <?php if (esMiembro("15", $permisos) and   ($estadoActual == '14')) { ?>

            <button type="button" id="cambiarEstado" class="btn btn-round btn-dark pull-left" onclick="verificarCheckLists(<?php echo $idSolicitud; ?>);"><i class="fa fa-floppy-o"></i>
              Guardar</button>&nbsp;&nbsp;

          <?php } else { ?>

            <button type="button" id="cambiarEstado" class="btn btn-round btn-dark pull-left" onclick="onChangeStatusSolicitud();"><i class="fa fa-floppy-o"></i>
              Guardar</button>&nbsp;&nbsp;

          <?php


          } ?>
          <!--
                    <input id="randc" name="randc" type="text" class="form-control pull-left" placeholder="<?= $rand ?>"
                        style="width: 50px;">
                    <input id="randc2" name="randc2" type="hidden" value="<?= $rand ?>">
                    &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                    <button type="button" class="btn btn-danger btn-round pull-left" id="Confirmar" name="Confirmar"
                        onclick="onChangeStatusSolicitud('obs');">Confirmar</button>-->
          <button type="button" class="btn btn-secondary btn-round pull-left" onclick="cancelarModalEstado();">Cancelar</button>
        </div>

      </div>
    </div>
  </div>
</div>


