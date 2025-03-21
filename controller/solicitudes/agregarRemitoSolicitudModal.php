<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../funciones.php';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$resultado = $solicitud->getestado();
$row = mysqli_fetch_assoc($resultado);

$idEstadoActual = $row['idEstado'];


//PERMISOS DE ACCESO DEL USUARIO LOGEADO
$objPermisos = new usuario_permisos_estados();
$objPermisos->setidUsuario($_SESSION['idUsuario']);
$permisosUser = $objPermisos->SelectForUser();

$permisos = array();
while ($p = mysqli_fetch_assoc($permisosUser)) {
    array_push($permisos, $p['idEstado']);
}


$op = $_POST['op'];
$disabled = "";

if ($op == "U") {
    $idRemito = $_POST['id'];
    $resultado = $solicitud->getRemitoSolicitud($idRemito);
    if ($row = mysqli_fetch_assoc($resultado)) {
        if ($row['esTrazado']) $disabled = 'disabled';
        else $disabled = '';
?>

        <script>
            $('#nroRemito').val('<?php echo $row['nroRemito']; ?>');
            $('#fechaRemito').val('<?php echo $row['fechaRemito']; ?>');
            $('#obsRemito').val('<?php echo json_encode($row['observaciones']); ?>');
            $('#idRemito').val('<?php echo $idRemito; ?>');
        </script>
<?php    }

    $resultado1 = $solicitud->getRemitoSolicitudDocs($idRemito);

    $filas = "";
    $contFilas = 0;
    while ($rowd = mysqli_fetch_assoc($resultado1)) {
        $chequeado = "";
        if ($rowd['chequeado'] == 1)
            $chequeado = 'checked';


        $filas .= "<tr>";
        $filas .= '<td>' . $rowd['idDoc'] . '
                   <input id="idDoc' . $rowd['idDoc'] . '" name="idDoc' . $rowd['idDoc'] . '" type="hidden" value="' . $rowd['idDoc'] . '" />    
                   </td>';
        $filas .= "<td>" . $rowd['nombre'] . "</td>";
        $filas .= '<td><textarea id="obs' . $rowd['idDoc'] . '" name="obs' . $rowd['idDoc'] . '" class="form-control">' . $rowd['observaciones'] . '</textarea></td>';
        $filas .= '<td style="text-align: center;">
                          <input type="checkbox" class="form-check-input" id="check2' . $rowd['idDoc'] . '" name="check2' . $rowd['idDoc'] . '" onclick="changeInput(' . $rowd['idDoc'] . ');" ' . $chequeado . '>
                          <input type="hidden"  id="chequear' . $rowd['idDoc'] . '" name="chequear' . $rowd['idDoc'] . '" value="'.$rowd['chequeado'].'" >
                          
                          </td>';
        $filas .= "</tr>";
        $contFilas++;
    }
} ?>


<!-- Modal -->
<div class="modal fade" id="modalAgregarRemito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php if ($_POST['op'] == "I") { ?>
                    <h3 class="modal-title" id="exampleModalLabel">Agregar Remito</h3>
                <?php } else { ?>
                    <h3 class="modal-title" id="exampleModalLabel">Editar Remito</h3>
                <?php } ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <input type="hidden" id="idRemito" value="" />
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-row">
                        <div class=" form-group col-sm-6">
                            <label for="nroRemito">Nro. Remito</label>
                            <input id="nroRemito" type="text" class="form-control" required <?php echo $disabled; ?> />
                        </div>

                        <div class=" form-group col-sm-6">
                            <label for="fechaRemito">Fecha</label>
                            <input id="fechaRemito" type="date" class="form-control" />
                        </div>
                    </div>


                    <div class="form-row">
                        <div class=" form-group col-sm-12">
                            <label for="obsRemito">Observaciones:</label>
                            <textarea id="obsRemito" class="form-control" rows="3"> </textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class=" form-group col-sm-12">
                            <?php if (esMiembro($idEstadoActual, $permisos) and in_array($idEstadoActual, ['6', '7', '8', '13', '14'])) { ?>
                                <button type="button" class="btn btn-primary btn-round" onclick="guardarRemito(<?php echo $_POST['idSolicitud']; ?>,'<?php echo $op; ?>');"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                                    Guardar Remito</button>

                            <?php   } ?>
                            <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal"><i class="fa fa-sign-out" aria-hidden="true"></i>
                                Cerrar</button>
                        </div>
                    </div>

                </form>
                <form id="idForm">
                    <?php if ($op == "U") { ?>
                        <legend>Documentación a Chequear</legend>

                        <div class="form-row">
                            <div class=" form-group col-sm-12">
                                <table class="table jambo_table" id="tabladocs">
                                    <thead>
                                        <th class="column-title">Id.</th>
                                        <th class="column-title">Documento</th>
                                        <th class="column-title">Observaciones</th>
                                        <th class="column-title" style=" text-align: center;">Chequeado</th>
                                    </thead>
                                    <tbody>
                                        <?php echo $filas; ?>
                                    </tbody>
                                </table>
                                <input type="hidden" id="size" name="size" value="<?php echo $contFilas; ?>" />


                            </div>
                        </div>
                        <?php if (esMiembro($idEstadoActual, $permisos) and in_array($idEstadoActual, ['6', '7', '8', '13', '14'])) { ?>
                            <div style="text-align: right;">
                                <button type="button" class="btn btn-info btn-round" onclick="guardarDocsRemito(<?php echo $idSolicitud; ?>,'<?php echo $_POST['id']; ?>');"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                                    Guardar Cambios Documentos</button>

                            </div>
                        <?php } ?>
                    <?php   } ?>
                </form>

            </div>
            <div class="modal-footer">


            </div>
        </div>
    </div>
</div>