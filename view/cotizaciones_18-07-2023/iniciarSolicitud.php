<?php
include_once '../config.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../funciones.php';
include_once '../../model/proveedores.php';


date_default_timezone_set('America/Argentina/Buenos_Aires');

//PERMISOS DE ACCESO DEL USUARIO LOGEADO
$objPermisos = new usuario_permisos_estados();
$objPermisos->setidUsuario($_SESSION['idUsuario']);
$permisosUser = $objPermisos->SelectForUser();

$permisos = array();
while ($p = mysqli_fetch_assoc($permisosUser)) {
    array_push($permisos, $p['idEstado']);
}


$proveedores =  new proveedores('');
$resultadoProv = $proveedores->getProveedorUsuario();

$disabled='';
if($solicitud->getid()!="") $disabled='disabled';



?>

<div class="col-sm-4">
    <br>
    <form role="form" action="" method="post" id="formulario">

        <div class="form-group">
            <label for="dni" class="control-label">DNI</label>
            <input class="form-control" name="dni" id="dni" value="<?= $persona->getdni() ?>" required="" disabled>
        </div>

        <div class="form-group">
            <label for="apellido" class="control-label">Apellido</label>
            <input class="form-control" name="apellido" id="apellido" value="<?= $persona->getapellido() ?>" required="" disabled>
        </div>


        <div class="form-group">
            <label for="nombre" class="control-label">Nombre</label>
            <input type="hidden" id="idPersonac" name="idPersonac" value="<?= $persona->getid() ?>">
            <input type="hidden" id="idSolicitudc" name="idSolicitudc" value="<?= $solicitud->getid() ?>">
            <input type="hidden" id="idCotizacionc" name="idCotizacionc" value="<?= $cotizacion->getid() ?>">
            <input class="form-control" id="nombre" name="nombre" type="text" value="<?= $persona->getnombre() ?>" required="" disabled>
            <!--<p class="help-block">Este Nombre sera mostrado como submenú.</p>-->
        </div>

        <div class="form-group">
            <label for="email" class="control-label">Email</label>
            <input class="form-control" name="email" id="email" type="email" value="<?= $persona->getemail() ?>" <?php echo $disabled; ?>>
        </div>
        <div class="form-group">
            <label for="telefono" class="control-label">Telefono</label>
            <input class="form-control" name="telefono" id="telefono" value="<?= $persona->gettelefono() ?>" <?php echo $disabled; ?> >
        </div>












</div>
<div class="col-sm-6">
    <br>
    <div class="form-group">
        <label for="puntosDispensa" class="control-label">Punto de Dispensa</label>
        <select class="form-control" name="puntosDispensa" id="puntosDispensa" <?php echo $disabled; ?>>
            <option value="-1" selected="selected">Seleccione Opción</option>
            <?php
            while ($xp = mysqli_fetch_assoc($arrayPuntosDispensa)) {
                echo '<option value="' . $xp['id'] . '" ' . (($solicitud->getid_puntos_dispensa() == $xp['id']) ? ' selected ' : '') . '>' . $xp['nombre'] . '</option>';
            }
            ?>

        </select>
    </div>




    <div class="form-group ">
        <label>Proveedor</label>
        <select id="idProveedorc" name="idProveedorc" class="form-control" <?php echo $disabled; ?>>
            <?php
            while ($rowp = mysqli_fetch_assoc($resultadoProv)) {
                echo '<option value="' . $rowp['id'] . '">' . $rowp['nombre'] . '</option>';
            }

            ?>
        </select>
    </div>


    <div class="form-group">
        <label for="id_provincia" class="control-label">Observaciones</label>
        <textarea class="form-control" rows="3" id="observaciones" <?php echo $disabled; ?>><?= $solicitud->getobservaciones() ?></textarea>
    </div>





    <div class="form-group">
        <?php
        $arrayEstado = $solicitud->getestado();
        $estado = mysqli_fetch_assoc($arrayEstado);
        $idEstado = isset($estado['idEstado']) ? $estado['idEstado'] : -1;
        // if ($idEstado <= 1 or $idEstado == 3 or $idEstado == 9) {
        if (($idEstado <= 6  or $idEstado == 9 or $idEstado == 28 or $idEstado == 27) and (esMiembro(1, $permisos))) {
            echo '<button type="button" class="btn btn-dark btn-round" onclick="guardarSolicitud();" id="guardar" name="guardar" ' . (($activo == 'A') ? '' : ' disabled ') . '>' . (($solicitud->getid() > 0) ? 'Guardar' : 'Crear Solicitud') . '</button>';
            if ($activo != 'A') {
                echo "<script>notificar('Estado de Beneficiario no valido en SIA, no es posible continuar.');</script>";
            }
        }

        /* if ($solicitud->getid() > 0) {


            echo '<br><br><p>Creado:' . fecha($solicitud->getfecha()) . '</p>';
            echo '<p><b>Solicitud Nro: ' . $solicitud->getid() . '</b></p>';*/


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
<?php  ?>

<div class="col-sm-6">
    <div class="form-group">
        <?php
        if ($solicitud->getid() > 0) {


            echo '<br><br><p><b>Creado:</b> ' . fecha($solicitud->getfecha()) . '</p>';
            echo '<p><b>Solicitud Nro: ' . $solicitud->getid() . '</b></p>';
        }


        ?>
    </div>
</div>