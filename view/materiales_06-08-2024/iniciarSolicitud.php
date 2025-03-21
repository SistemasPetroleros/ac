<?php
include_once '../config.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../funciones.php';


date_default_timezone_set('America/Argentina/Buenos_Aires');
$tomorrow = date("Y-m-d H:i", strtotime(date("Y-m-d H:i") . "+ 1 days"));

//PERMISOS DE ACCESO DEL USUARIO LOGEADO
$objPermisos = new usuario_permisos_estados();
$objPermisos->setidUsuario($_SESSION['idUsuario']);
$permisosUser = $objPermisos->SelectForUser();

$permisos = array();
while ($p = mysqli_fetch_assoc($permisosUser)) {
    array_push($permisos, $p['idEstado']);
}


?>


<br>
<form role="form" action="" method="post" id="formulario">

    <div class="form-row">
        <div class="form-group col-sm-4">
            <label for="dni" class="control-label">DNI</label>
            <input class="form-control" name="dni" id="dni" value="<?= $persona->getdni() ?>" required="" disabled>
        </div>

        <div class="form-group col-sm-4">
            <label for="apellido" class="control-label">Apellido</label>
            <input class="form-control" name="apellido" id="apellido" value="<?= ($persona->getapellido()) ?>" required="" disabled>
        </div>

        <div class="form-group col-sm-4">
            <label for="nombre" class="control-label">Nombre</label>
            <input type="hidden" id="idPersona" name="idPersona" value="<?= $persona->getid() ?>">
            <input type="hidden" id="idSolicitud" name="idSolicitud" value="<?= $solicitud->getid() ?>">
            <input class="form-control" id="nombre" name="nombre" type="text" value="<?= ($persona->getnombre()) ?>" required="" disabled>
            <!--<p class="help-block">Este Nombre sera mostrado como submenú.</p>-->
        </div>


    </div>

    <div class="form-row">
        <div class="form-group col-sm-4">
            <label class="control-label">Estado SIA</label>
            <input class="form-control" name="estadoSIA" id="estadoSIA" value="<?= (($persona->getestadoSIA() == 'A') ? 'Activo' : 'Inactivo') ?>" disabled>

        </div>

        <div class="form-group col-sm-4">
            <label for="email" class="control-label">Email</label>
            <input class="form-control" name="email" id="email" type="email" value="<?= $persona->getemail() ?>">
        </div>
        <div class="form-group col-sm-4">
            <label for="telefono" class="control-label">Teléfono</label>
            <input class="form-control" name="telefono" id="telefono" value="<?= $persona->gettelefono() ?>">
        </div>

    </div>


    <div class="form-row">

        <div class="form-group col-sm-4">
            <label for="puntosDispensa" class="control-label">Punto de Dispensa</label>
            <select class="form-control" name="puntosDispensa" id="puntosDispensa">
                <option value="-1" selected="selected">Seleccione Opción</option>
                <?php
                while ($xp = mysqli_fetch_assoc($arrayPuntosDispensa)) {
                    echo '<option value="' . $xp['id'] . '" ' . (($solicitud->getid_puntos_dispensa() == $xp['id']) ? ' selected ' : '') . '>' . $xp['nombre'] . '</option>';
                }
                ?>

            </select>
        </div>

        <div class="form-group col-sm-4">
            <label for="idCategoria" class="control-label">Categoría</label>
            <select class="form-control" name="idCategoria" id="idCategoria">
             <!--    <option value="-1" selected="selected">Seleccione Opción</option> -->
                <?php
                while ($xp = mysqli_fetch_assoc($arraycategorias)) {
                    echo '<option value="' . $xp['id'] . '" ' . (($solicitud->getidCategoria() == $xp['id']) ? ' selected ' : '') . '>' . $xp['nombre'] . '</option>';
                }
                ?>

            </select>
        </div>

        <div class="form-group col-sm-4">
            <label for="idUrgente" class="control-label">Urgente</label>
            <select class="form-control" name="idUrgente" id="idUrgente">
                <option value="0" <?php  if($solicitud->geturgente() == 0) echo ' selected ';?> >NO</option>
                <option value="1" <?php  if($solicitud->geturgente() == 1) echo ' selected ';?> >SI</option>
            </select>
        </div>


    </div>


    <div class="form-row">

        <div class="form-group col-sm-2">
            <label class="control-label">Fecha Vigencia Cotización</label>
            <input class="form-control" type="datetime-local" name="fecha_vigencia_cotiz" id="fecha_vigencia_cotiz" value="<?= (($solicitud->getfecha_vigencia_cotiz() != '' and $solicitud->getfecha_vigencia_cotiz() != null) ? $solicitud->getfecha_vigencia_cotiz() : $tomorrow) ?>" />

        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">Tipo Solicitud</label>
            <select class="form-control" name="id_tipo_solicitud" id="id_tipo_solicitud">
                <?php
                while ($xp = mysqli_fetch_assoc($arraytiposolicitud)) {
                    echo '<option value="' . $xp['id'] . '" ' . (($solicitud->getid_tipo_solicitud() == $xp['id']) ? ' selected ' : '') . '>' . $xp['nombre'] . '</option>';
                }
                ?>

            </select>
           
            

        </div>

        <div class="form-group col-sm-2">
            <br/>
            <br/>
            <input class="form-check-input" name="esSur" type="checkbox" id="esSur" <?= (($esSur == 1 or $solicitud->getesSur() == 1) ? ' checked ' : '') ?>>
            <label for="esSur" class="form-check-label">Es Sur?</label>
        </div>


        <div class="form-group col-sm-6">
            <label for="observaciones" class="control-label">Observaciones</label>
            <textarea class="form-control" rows="3" id="observaciones"><?= $solicitud->getobservaciones() ?></textarea>
        </div>
    </div>


    <div class="form-row">
       
    </div>
    <div class="form-row">
        <div class="form-group col-sm-12">
            <?php
            $arrayEstado = $solicitud->getestado();
            $estado = mysqli_fetch_assoc($arrayEstado);
            $idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : -1;
            //         if (($idEstado <= 6  or $idEstado == 9) and (esMiembro(1,$permisos)) ) {

            if (($idEstado == 31 or $idEstado == -1) and (esMiembro(31, $permisos))) {
                echo '<button type="button" class="btn btn-dark btn-round" onclick="guardarSolicitud();" name="guardar" ' . (($activo == 'A') ? '' : ' disabled ') . '>' . (($solicitud->getid() > 0) ? 'Guardar' : 'Crear Solicitud') . '</button>';
                if ($activo != 'A') {
                    echo "<script>notificar('Estado de Beneficiario no valido en SIA, no es posible continuar.');</script>";
                }
            }

            if ($solicitud->getid() > 0) {


                echo '<br><br><p><span class="badge"><b>Solicitud Nro:</b> ' . $solicitud->getid() . '</span</p>';
                echo '<p><span class="badge"><b>Creada: </b>' . fecha($solicitud->getfecha()) . '</span></p>';       
                echo '<p><span class="badge"><b>Último Visto:</b> ' . fecha4($solicitud->getultimovisto()) . ' (' . $solicitud->getuserultimovisto() . ')</span</p>';


            ?>
        </div>

    </div>




</form>


<script>
    $('#divsBuscarProducto').html(
        '    <div class="modal fade bs-example-modal-lg-newItem' + <?= $solicitud->getid() ?> + '" tabindex="-1" role="dialog"' +
        '                      aria-labelledby="Item Venta N°' + <?= $solicitud->getid() ?> + '">' +
        '                       <div class="modal-dialog modal-lg" role="document">' +
        '                           <div class="modal-content">' +
        '                               <input class="form-control" style="display: block; width: 100%; text-align: center; "' +
        '                                   placeholder="Busqueda de Productos (Presione Enter para buscar.)" type="text"' +
        '                                   id="nuevoItem' + <?= $solicitud->getid() ?> + '" onkeypress="return buscarProducto(event, ' + <?= $solicitud->getid() ?> + ');" />' +
        '                               <div class="suggest list-group" style="position:absolute;" id="suggestionsnuevoItem' + <?= $solicitud->getid() ?> + '">' +
        '                               </div>' +
        '                               <div id="newItemVenta' + <?= $solicitud->getid() ?> + '"> </div>' +
        '                           </div>' +
        '                       </div>' +
        '                   </div>' +


        '                   <div class="modal fade bs-example-modal-lg-pagar' + <?= $solicitud->getid() ?> + '" tabindex="-1" role="dialog"' +
        '                       aria-labelledby="Finalizar Venta N°' + <?= $solicitud->getid() ?> + '">' +
        '                       <div class="modal-dialog modal-lg" role="document">' +
        '                           <div class="modal-content">' +
        '                               <input class="form-control" style="display: block; width: 100%; text-align: center; "' +
        '                                   placeholder="Busqueda de Personas" type="text" id="nuevaPersona' + <?= $solicitud->getid() ?> + '"' +
        '                                   onkeypress="buscarPersona(' + <?= $solicitud->getid() ?> + ');" />' +
        '                               <div class="suggest list-group" style="position:absolute;" id="suggestionsnuevaPersona' + <?= $solicitud->getid() ?> + '">' +
        '                               </div>' +
        '                               <div id="nuevaPersona' + <?= $solicitud->getid() ?> + '"> </div>' +
        '                           </div>' +
        '                       </div>' +
        '                   </div>' +

        '                   <div class="modal fade bs-example-modal-lg-cancelar' + <?= $solicitud->getid() ?> + '" tabindex="-1" role="dialog"' +
        '                       aria-labelledby="Cancelar Venta">' +
        '                       <div class="modal-dialog modal-lg" role="document">' +
        '                           <div class="modal-content" style="padding:20px;">' +
        '                               <div id="eliminar" style="vertical-align: middle;">' +
        '                                   <small class="pull-left">Reingrese el numero para mayor' +
        '                                       seguridad&nbsp;&nbsp;&nbsp;&nbsp;</small>' +
        '                                   <form action="" method="post">' +
        '                                       <input id="rand' + <?= $solicitud->getid() ?> + '" name="rand" idventa="' + <?= $solicitud->getid() ?> + '" type="text"' +
        '                                           class="form-control pull-left rand" placeholder="' + <?= $rand ?> + '"' +
        '                                           style="width: 50px;">' +
        '                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
        '                                       <input id="rand2' + <?= $solicitud->getid() ?> + '" name="rand2" type="hidden" value="' + <?= $rand ?> + '">' +
        '                                       <input id="cancelarVenta" name="cancelarVenta" type="hidden" value="' + <?= $solicitud->getid() ?> + '">' +
        '                                       &nbsp;&nbsp;' +
        '                                       <button type="button" id="btnCancelar' + <?= $solicitud->getid() ?> + '" name="btnCancelar"' +
        '                                           class="btn btn-round btn-danger   btnCancelar" disabled=""><i' +
        '                                               class="fa fa-ban"></i> Confirma Cancelar Venta</button>' +
        '                                   </form>' +
        '                               </div>' +

        '                           </div>' +
        '                       </div>' +
        '                   </div>'
    );
</script>
<?php } ?>