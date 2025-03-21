



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Monodrogas</h3><?= $mensaje ?>
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
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        

                        while ($x = mysqli_fetch_assoc($array)) {
                            
                            
                            echo '<tr class="odd gradeX"><td>' . $x['PRIMARY'] . '</td><td>' . $x['codigo'] . '</td><td>' . $x['descripcion'] . '</td><td style="width:100px;">';
                            
                            echo '<button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-dark btn-round" onclick="editar(\'' . $x['PRIMARY'] . '\',\'' . $x['codigo'] . '\',\'' . $x['descripcion'] . '\',\'' . fecha4($x['fechaAlta']). '\',\'' . $x['userAlta'] . '\',\'' . fecha4($x['fechaModif']). '\',\'' . $x['userModif'] . '\');">+</button>';
                            
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
                        <label for="codigo" class="control-label">Codigo</label> 
                        <input type="hidden" id="PRIMARY" name="PRIMARY" value="">
                        <input class="form-control" id="codigo" name="codigo" type="text" required=""> 
                        <!--<p class="help-block">Este Nombre sera mostrado como submenú.</p>-->
                    </div> 
                    
                    <div class="form-group"> 
                        <label for="descripcion" class="control-label">Descripcion</label> 
                       
                        <input class="form-control" id="descripcion" name="descripcion" type="text" required=""> 
                        <!--<p class="help-block">Este Nombre sera mostrado como submenú.</p>-->
                    </div> 
                  

                    


    
                   
                   
                   <br/>
                   <br/>
                   
                        

                    <br/>
                   <br/>
                    
                <div class="modal-footer">
                   
                   
                    <button type="button" class="btn btn-default btn-round" data-dismiss="modal" >Cerrar</button> 
                    <!--<button type="button" class="btn btn-dark btn-round" onclick="guardarForm('controller/ab_monodro/ab_monodro.php', 'formulario');" name="guardar">Guardar</button> -->
                    <button type="button" class="btn btn-dark btn-round" data-dismiss="modal"  onclick="CrearProductoActivoMonodroga();" name="guardar">Registrar Como Producto Activo</button>
                </div> 
               </div> 
            </form> 
        </div>
    </div>
</div>
</div>
<script src="view/ab_monodro/ab_monodro.js"></script>