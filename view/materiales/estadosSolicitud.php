<br>

<?php
$arrayEstado = $solicitud->getestado();
$estado = mysqli_fetch_assoc($arrayEstado);
$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : -1;
echo '<div class="alert alert-dark btn-dark " role="alert"><span>ESTADO ACTUAL: <b>' . $estado['Estado'] . '</b></span></div>';


echo ' <button id="solicitarObsBt" onclick="abrirModalEstadosSolicitud(\'obs\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-dark btn-round" title="Solicitar Auditoria"><span class="fa fa-pencil-square-o"></span> Agregar Observación</button>';

if (esMiembro("32", $permisos) and ($estadoActual == '31' or $estadoActual == '33')) {
  echo ' <button id="solicitarAut2" onclick="abrirModalEstadosSolicitud(\'32\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-round btn-warning " title="Solicitar Auditoria"><span class="fa fa-calendar-check-o"></span> Solicitar Auditoria</button>';
}

if (esMiembro("38", $permisos) and ($estadoActual == '32')) {
  echo ' <button id="solicitarAut26" onclick="abrirModalEstadosSolicitud(\'38\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-round btn-warning " title="Observador por Auditoria"><span class="fa fa-calendar-check-o"></span> Observado por Auditoria</button>';
}

if (esMiembro("32", $permisos) and ($estadoActual == '38')) {
  echo ' <button id="solicitarAut2" onclick="abrirModalEstadosSolicitud(\'32\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-round btn-warning " title="Solicitar Auditoria"><span class="fa fa-calendar-check-o"></span> Solicitar Auditoria</button>';
}

if (esMiembro("31", $permisos) and ($estadoActual == '36')) {
  echo ' <button id="solicitarAut10" onclick="abrirModalEstadosSolicitud(\'31\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-round btn-warning " title="Volver a Nuevo"><span class="fa fa-back"></span>Volver a Nuevo</button>';
}

if (esMiembro("33", $permisos) and  ($estadoActual == '32')) {
  echo ' <button id="solicitarAut3" onclick="abrirModalEstadosSolicitud(\'33\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="Rechazar Auditoria"><span class="fa fa-calendar-check-o"></span> Rechazar Auditoria</button>';
}

if (esMiembro("34", $permisos) and  ($estadoActual == '32')) {
  echo ' <button id="solicitarAut4" onclick="abrirModalEstadosSolicitud(\'34\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Aprobar Auditoria"><span class="fa fa-calendar-check-o"></span> Aprobar Auditoria -> Cotizar</button>';
}

if (esMiembro("37", $permisos) and  ($estadoActual == '35')) {
  echo ' <button id="solicitarAut6" onclick="abrirModalEstadosSolicitud(\'37\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Adjudicado"><span class="fa fa-calendar-check-o"></span> Adjudicado</button>';
}

if (esMiembro("7", $permisos) and  ($estadoActual == '6')) {
  echo ' <button id="solicitarAut7" onclick="abrirModalEstadosSolicitud(\'7\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Recepcionar sin Trazabilidad"><span class="fa fa-calendar-check-o"></span> Recepcionar sin Trazabilidad</button>';

  //echo ' <button id="solicitarAut7" onclick="abrirModalEstadosSolicitud(\'7\','.$idSolicitud.');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Pedido Controlado"><span class="fa fa-calendar-check-o"></span> Pedido Controlado</button>';
  // echo ' <button id="solicitarAut7" onclick="abrirModalSolicitudRecep(\'7\','.$idSolicitud.');" type="button"  class="btn btn-warning btn-round" title="Recepción Trazabilidad"><span class="fa fa-calendar-check-o"></span> Recepción ANMAT</button>';
}

if (esMiembro("6", $permisos) and  ($estadoActual == '27')) {
  echo ' <button id="solicitarAut6" onclick="abrirModalEstadosSolicitud(\'6\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Recibido en Punto de Dispensa"><span class="fa fa-calendar-check-o"></span> Recibido en Punto de Dispensa</button>';

  //echo ' <button id="solicitarAut7" onclick="abrirModalEstadosSolicitud(\'7\','.$idSolicitud.');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Pedido Controlado"><span class="fa fa-calendar-check-o"></span> Pedido Controlado</button>';
  // echo ' <button id="solicitarAut7" onclick="abrirModalSolicitudRecep(\'7\','.$idSolicitud.');" type="button"  class="btn btn-warning btn-round" title="Recepción Trazabilidad"><span class="fa fa-calendar-check-o"></span> Recepción ANMAT</button>';
}

if (esMiembro("8", $permisos) and  ($estadoActual == '7')) {

  echo ' <button id="solicitarAut8" onclick="abrirModalEstadosSolicitud(\'8\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Dispensar Pedido"><span class="fa fa-calendar-check-o"></span> Dispensar Solicitud Sin Trazabilidad</button>';
}

if (esMiembro("36", $permisos) and   ($estadoActual == '31' or $estadoActual == '32' or $estadoActual == '34')) //or $estadoActual=='6' or $estadoActual=='7'
{
  echo ' <button id="solicitarAut9" onclick="abrirModalEstadosSolicitud(\'36\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="Anular Solicitud"><span class="fa fa-calendar-check-o"></span> Anular Solicitud</button>';
}

if (esMiembro("13", $permisos) and   ($estadoActual == '8')) //or $estadoActual=='6' or $estadoActual=='7'
{
  echo ' <button id="solicitarAut13" onclick="abrirModalEstadosSolicitud(\'13\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="ENVIADO A ADM. FARMACIAS"><span class="fa fa-calendar-check-o"></span> Enviado a ADM de farmacias</button>';
}

if (esMiembro("14", $permisos) and   ($estadoActual == '13')) //or $estadoActual=='6' or $estadoActual=='7'
{
  echo ' <button id="solicitarAut14" onclick="abrirModalEstadosSolicitud(\'14\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="PROCESO REVISION ADM. FARMACIAS"><span class="fa fa-calendar-check-o"></span> En Proceso de revisión ADM Farm.</button>';
}

if (esMiembro("15", $permisos) and   ($estadoActual == '14')) //or $estadoActual=='6' or $estadoActual=='7'
{
  echo ' <button id="solicitarAut15" onclick="abrirModalEstadosSolicitud(\'15\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="AUDITADO ADM. FARMACIAS"><span class="fa fa-calendar-check-o"></span> Auditado ADM. Farm.</button>';
}




if (esMiembro("23", $permisos) and   ($estadoActual == '29')) //or $estadoActual=='6' or $estadoActual=='7'
{
  echo ' <button id="solicitarAut23" onclick="abrirModalEstadosSolicitud(\'23\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="Pagado"><span class="fa fa-calendar-check-o"></span> Pagado</button>';
}


if (esMiembro("27", $permisos) and   ($estadoActual == '6')) //or $estadoActual=='6' or $estadoActual=='7'
{
  echo ' <button id="solicitarAut27" onclick="abrirModalEstadosSolicitud(\'27\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="Devolver a Droguería"><span class="fa fa-calendar-check-o"></span> Devolver a Droguería</button>';
}

if (esMiembro("6", $permisos) and   ($estadoActual == '28')) //or $estadoActual=='6' or $estadoActual=='7'
{
  echo ' <button id="solicitarAut27" onclick="abrirModalEstadosSolicitud(\'6\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Recibido en Punto de Dispensa"><span class="fa fa-calendar-check-o"></span> Recibido en Punto de Dispensa</button>';
}

if (esMiembro("29", $permisos) and   ($estadoActual == '15')) {
  echo ' <button id="solicitarAut29" onclick="abrirModalEstadosSolicitud(\'29\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Control Facturación AC Aprobado"><span class="fa fa-calendar-check-o"></span> Control de Facturación AC Aprobado</button>';
}

if (esMiembro("30", $permisos) and   ($estadoActual == '15')) {
  echo ' <button id="solicitarAut30" onclick="abrirModalEstadosSolicitud(\'30\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Control Facturación AC Pendiente"><span class="fa fa-calendar-check-o"></span> Control de Facturación AC Pendiente</button>';
}

if (esMiembro("29", $permisos) and   ($estadoActual == '30')) {
  echo ' <button id="solicitarAut29" onclick="abrirModalEstadosSolicitud(\'29\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Control Facturación AC Aprobado"><span class="fa fa-calendar-check-o"></span> Control de Facturación AC Aprobado</button>';
}



if (esMiembro("43", $permisos) and  ($estadoActual == '49' or $estadoActual == '37')) {
  echo ' <button id="solicitarAut43" onclick="abrirModalEstadosSolicitud(\'43\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Notificado"><span class="fa fa-calendar-check-o"></span> Notificado</button>';
}

if (esMiembro("44", $permisos) and  ($estadoActual == '43')) {
  echo ' <button id="solicitarAut44" onclick="abrirModalEstadosSolicitud(\'44\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Facturado"><span class="fa fa-calendar-check-o"></span> Facturado</button>';
}

if (esMiembro("36", $permisos) and  ($estadoActual == '37')) {
  echo ' <button id="solicitarAut45" onclick="abrirModalEstadosSolicitud(\'36\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-danger btn-round" title="Anular"><span class="fa fa-calendar-check-o"></span> Anular Solicitud</button>';
}


if (esMiembro("48", $permisos) and  ($estadoActual == '37')) {
  echo ' <button id="solicitarAut48" onclick="abrirModalEstadosSolicitud(\'48\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Pendiente de Entrega"><span class="fa fa-calendar-check-o"></span> Pendiente de Entrega</button>';
}

if (esMiembro("49", $permisos) and  ($estadoActual == '48')) {
  echo ' <button id="solicitarAut49" onclick="abrirModalEstadosSolicitud(\'49\',' . $idSolicitud . ');" type="button" data-toggle="modal" data-target="#modalComentariosSolicitud" class="btn btn-warning btn-round" title="Entregado"><span class="fa fa-calendar-check-o"></span> Entregado</button>';
}









?>
<!--
<button type="button" id="btnNuevoProducto" class="btn btn-round btn-dark btnNuevoProducto" data-toggle="modal"
    data-target=".bs-example-modal-lg-newItem<?= $solicitud->getid() ?>"
    onclick='foc("nuevoItem<?= $solicitud->getid() ?>" );'><i class="fa fa-plus"></i>
    Productos</button>&nbsp;&nbsp;


<button type="button" class="btn btn-round btn-danger btnCancelar" data-toggle="modal"
    data-target=".bs-example-modal-lg-cancelar<?= $solicitud->getid() ?>"
    onclick="foc('rand<?= $solicitud->getid() ?>');"><i class="fa fa-ban"></i>
    Cancelar</button>
-->
<br><br>



<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <?php
  $cnt = 0;
  while ($x = mysqli_fetch_assoc($arrayEstadosSolicitud)) {
    $cnt++;
  ?>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="heading<?= $cnt ?>">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $cnt ?>" aria-expanded="<?= (($cnt == 1) ? 'true' : 'false') ?>" aria-controls="collapse<?= $cnt ?>">
            <!-- true o false mostrar por defecto -->
            <?= fecha3($x['fechaAlta']) ?> |
            <?= ucwords($x['userAlta']) ?>
            <span class="badge badge-default float-right m-2"><?= $x['estado'] ?></span>
          </a>
        </h4>
      </div>
      <div id="collapse<?= $cnt ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
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