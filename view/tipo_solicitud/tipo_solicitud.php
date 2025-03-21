



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Tipo Solicitudes</h3><?= $mensaje ?>
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
                    <button type="button" class="btn btn-dark btn-circle btn-lg " data-toggle="modal" data-target=".bs-example-modal-lg" onclick="editar();"><i class="fa fa-plus"></i>
                    </button>
                </span>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table jambo_table" id="dataTable1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        

                        while ($x = mysqli_fetch_assoc($array)) {
                            
                            
                            echo '<tr class="odd gradeX"><td>' . $x['id'] . '</td><td>' . $x['nombre'] . '</td><td style="width:100px;">';
                            
                            echo '<button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-dark btn-round" onclick="editar(\'' . $x['id'] . '\',\'' . $x['nombre'] . '\',\'' . fecha4($x['fechaAlta']). '\',\'' . $x['userAlta'] . '\',\'' . fecha4($x['fechaModif']). '\',\'' . $x['userModif'] . '\');"> Editar</button>';
                            
                            echo '</td></tr>';
                        }
                        ?>


                    </tbody>
                </table>

                <!-- /.table-responsive -->
             <!--   <div class="well">
                    <h4>Información sobre Proveedores</h4>
                    <p></p>
                    &nbsp;
                </div> -->
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
                <h4 class="modal-title" id="exampleModalLabel">Agregar/Editar</h4> </div> 
            <form role="form" action="" method="post" id="formulario"> 
                <div class="modal-body"> 

                    <div class="form-group"> 
                        <label for="nombre" class="control-label">Nombre</label> 
                        <input type="hidden" id="id" name="id" value="">
                        <input class="form-control" id="nombre" name="nombre" type="text" required=""> 
                        <!--<p class="help-block">Este Nombre sera mostrado como submenú.</p>-->
                    </div> 
                    
     
                   <br/>
                   <br/>
                    <div class="form-group">
                       <div class="col-sm-6">
                         <label class="control-label">Fecha Alta: <span style="color:#343a40;" id="fechaAlta"></span</label> 
                       </div>

                       <div class="col-sm-6">
                       <label class="control-label">Fecha Últ. Modificación: <span style="color:#343a40;" id="fechaModif"></span</label> 
                       </div> 
                    </div>

                    <div class="form-group">
                       <div class="col-sm-6">
                        
                         <label class="control-label">Usuario Alta: <span style="color:#343a40;" id="userAlta"></span</label>
                       </div>

                       <div class="col-sm-6">
                          <label class="control-label">Usuario Modificación: <span style="color:#343a40;" id="userModif"></span</label>
                       </div> 
                    </div>
                        

                    <br/>
                   <br/>
                    
                <div class="modal-footer">
                    <div id="eliminar">
                        <input id="rand" name="rand" type="text" class="form-control pull-left" placeholder="<?= $rand ?>" style="width: 50px;">
                        <input id="rand2" name="rand2" type="hidden" value="<?= $rand ?>">
                        <button type="button" class="btn btn-danger pull-left btn-round" name="eliminar" onclick="guardarForm('controller/tipo_solicitud/tipo_solicitud.php', 'formulario', 'eliminar');">Eliminar</button>
                    </div>
                    <button type="reset" class="btn btn-default btn-round" onclick="editar();">Limpiar</button>
                    <button type="button" class="btn btn-default btn-round" data-dismiss="modal" >Cerrar</button> 
                    <button type="button" class="btn btn-dark btn-round" onclick="guardarForm('controller/tipo_solicitud/tipo_solicitud.php', 'formulario');" name="guardar">Guardar</button> 
                </div> 
               </div> 
            </form> 
        </div>
    </div>
</div>
</div>
<script src="view/tipo_solicitud/tipo_solicitud.js"></script>