<br>

<?php
$arrayEstado = $sol_estado->getestado();
$estado = mysqli_fetch_assoc($arrayEstado);
$idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : -1;
echo '<div class="alert alert-dark btn-dark " role="alert"><span>ESTADO ACTUAL: <b>' . $estado['Estado'] . '</b></span></div>';

$rand= rand(0,1000);
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


