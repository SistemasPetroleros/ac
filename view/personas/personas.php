<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Personas</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- subtitulo -->
                <span class="pull-right">
                    <!--<button type="button" class="btn btn-dark btn-circle btn-lg " data-toggle="modal" data-target=".bs-example-modal-lg" onclick="editar();"><i class="fa fa-plus"></i>
                    </button> -->
                </span>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table jambo_table" id="dataTable1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Apellido</th>
                            <th>Nombre</th>

                            <th>Doc.</th>
                            <th>Estado SIA</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        

                        while ($x = mysqli_fetch_assoc($array)) {
                         //  $habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
                            $estadoSIA = '';
                            if ($x['estadoSIA'] == "I") {
                                $estadoSIA = 'Inactivo';
                            }
                            if ($x['estadoSIA'] == "A") {
                                $estadoSIA = 'Activo';
                            }
                           
                            echo '<tr class="odd gradeX"><td>' . $x['id'] . '</td><td>' . $x['apellido'] . '</td><td>' . $x['nombre'] . '</td><td>' . $x['dni'] . '</td><td>' .  $estadoSIA . '</td><td style="width:100px;">';
                            
                            echo '<button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-dark btn-round" onclick="editar(\'' . $x['id'] . '\',\'' . $x['apellido'] . '\',\'' . $x['nombre'] . '\',\'' . $x['dni'] . '\',\'' . $x['estadoSIA'] . '\',\'' . $x['email'] . '\',\'' . $x['telefono'] . '\',\'' . $x['nroInternoSIA'] . '\',\'' . fecha4($x['fechaAlta']) . '\',\'' . $x['userAlta'] . '\',\'' . fecha4($x['fechaModif']) . '\',\'' . $x['userModif'] . '\',\'' . $x['esB24'] . '\');"> Editar</button>';
                            
                            echo '</td></tr>';
                        }
                        ?>


                    </tbody>
                </table>

                <!-- /.table-responsive -->
                <!--    <div class="well">
                    <h4>Información sobre Personas</h4>
                    <p></p>
                    &nbsp;
                </div>  -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>




<!--MODAL-->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="Editar">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Agregar/Editar</h4>
            </div>
            <form role="form" action="" method="post" id="formulario">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="dni" class="control-label">DNI</label>
                        <input class="form-control" name="dni" id="dni" required="" disabled>
                    </div>

                    <div class="form-group">
                        <label for="apellido" class="control-label">Apellido</label>
                        <input class="form-control" name="apellido" id="apellido" required="" disabled>
                    </div>


                    <div class="form-group">
                        <label for="nombre" class="control-label">Nombre</label>
                        <input type="hidden" id="id" name="id" value="">
                        <input class="form-control" id="nombre" name="nombre" type="text" required="" disabled>
                        <!--<p class="help-block">Este Nombre sera mostrado como submenú.</p>-->
                    </div>

                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input class="form-control" name="email" id="email" type="email">
                    </div>
                    <div class="form-group">
                        <label for="telefono" class="control-label">Telefono</label>
                        <input class="form-control" name="telefono" id="telefono">
                    </div>

                    <div class="input-group">
                        <div class="col-sm-4">
                            <label class="control-label">Estado SIA</label>
                            <input class="form-control" name="estadoSIA" id="estadoSIA" disabled>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label">Nro. Interno SIA</label>
                            <input class="form-control" name="nroInternoSIA" id="nroInternoSIA" disabled>
                        </div>


                        <div class="col-sm-4">

                            <label for="b24" class="control-label">B24<input class="form-control" name="b24"
                                    type="checkbox" id="b24" disabled></label>


                        </div>

                    </div>



                    <br>
                    <br>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="control-label">Fecha Alta: <span style="color:#343a40;"
                                    id="fechaAlta"></span></label>
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label">Fecha Últ. Modificación: <span style="color:#343a40;"
                                    id="fechaModif"></span></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">

                            <label class="control-label">Usuario Alta: <span style="color:#343a40;"
                                    id="userAlta"></span></label>
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label">Usuario Modificación: <span style="color:#343a40;"
                                    id="userModif"></span></label>
                        </div>
                    </div>

                    <br>
                    <br>

                    <div class="modal-footer">


                        <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-dark btn-round"
                            onclick="guardarForm('controller/personas/personas.php', 'formulario');"
                            name="guardar">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="view/personas/personas.js"></script>