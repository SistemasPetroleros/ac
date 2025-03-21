<style>
.disabledTab {
    pointer-events: none;
}


    .suggest-element{
        margin-left:1px;
        margin-top:2px;
        width:90%;
        cursor:pointer;

    }
    #suggestions {
        width:90%;
        height:150px;
        overflow: auto;

    }
    .suggest{
        width:90%;
        height:150px;
        overflow: auto;

        display: none; 
        width: 100%;

    }

    .table-wrapper {
  width: 100%;
  height: 200px; /* Altura de ejemplo */
  overflow: auto;
}

.table-wrapper table {
  border-collapse: separate;
  border-spacing: 0;
}

.table-wrapper table thead {
  position: -webkit-sticky; /* Safari... */
  position: sticky;
  top: 0;
  left: 0;
}

.table-wrapper table thead th,
.table-wrapper table tbody td {
  border: 1px solid #000;
  background-color: #FFF;
}

.modal-lg { max-width: 100% !important; }


</style>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Solicitud de Materiales</h3>
                

            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="panel-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">


                        <?php

                        $active = '';
                        if($active == ' active '){echo "<script>tabSelected=;</script>";}
                        $active = ' active ';
                        $estado = '';
                        ?>
                        <!--//$habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';-->
                        <!--<li class="<?=$active?>"><a href="#solicitud" data-toggle="tab" aria-expanded="false" onclick="tabSelected='';">Beneficiario</a></li>-->
                        <li role="presentation" class="active"><a href="#solicitud" aria-controls="solicitud" role="tab"
                                data-toggle="tab">Solicitud</a></li>
                        <li class="disabledTab" role="presentation"><a href="#items" aria-controls="items" role="tab"
                                data-toggle="tab" id="pestItems"> Items</a></li>
                        <li class="disabledTab" role="presentation"><a href="#proveedores" aria-controls="proveedores" role="tab"
                                data-toggle="tab">Proveedores</a></li>
                        <li class="disabledTab" role="presentation"><a href="#adjuntos" aria-controls="adjuntos" role="tab"
                                data-toggle="tab">Adjuntos</a></li>
                        <li class="disabledTab" role="presentation"><a href="#estado" aria-controls="estado" role="tab"
                                data-toggle="tab" onclick="estadosSolicitud();">Estado</a></li>
                   <!--      <li class="disabledTab" role="presentation"><a href="#historial" aria-controls="historial" role="tab"
                                data-toggle="tab">Historial</a></li> -->
                      <!--   <li class="disabledTab" role="presentation"><a href="#recepcionTraza" aria-controls="recepcionTraza" role="tab"
                                data-toggle="tab" >Recepción de Productos</a></li>        -->

                    </ul>


                    <div class="tab-content" >
                        <div class="tab-pane fade in active" id="solicitud">
                            <br>
                            <form class="form-inline" id="formBuscarBeneficiario">
                                <div class="form-group">
                                    <label for="dniBeneficiario"></label>
                                    <input type="text" class="form-control" id="dniBeneficiario"
                                        placeholder="DNI Beneficiario">
                                </div>
                                &nbsp;&nbsp;&nbsp;

                                <button type="button" name="nuevaVenta" id="nuevaVenta" onclick="iniciarSolicitud();"
                                    class="btn btn-round btn-dark ">Iniciar Solicitud</button>
                            </form>



                        </div>



                        <div class="tab-pane fade" id="items">
                            Cargando...
                        </div>

                        <div class="tab-pane fade" id="proveedores">
                            Cargando...
                        </div>
                        <div class="tab-pane fade" id="adjuntos">
                        Cargando...
                        </div>
                        <div class="tab-pane fade" id="estado">
                        Cargando...
                        </div>
                      <!--   <div class="tab-pane fade" id="historial">
                        Cargando...
                        </div> -->
                    <!--    <div class="tab-pane fade" id="recepcionTraza">
                        Cargando...
                        </div> -->
                    </div>

                    
                    <div id="divsBuscarProducto"></div>


                    




                    <?php
                        $active = '';
                        $rand = rand(100, 999);
                        ?>






                    <!--
                                        <form class="form-inline">
                                            <div class="form-group">
                                                <label for="codigo">Código</label>
                                                <input type="text" class="form-control" id="codigo" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="cbarras">C. Barras</label>
                                                <input type="text" class="form-control" id="cbarras" placeholder="">
                                            </div>
                                            <br>
                                            <button type="submit" class="btn btn-default"><i class="fa fa-plus"></i></button>
                                        </form>
                    -->





                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                </div>


                <!--
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTable1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        


                    </tbody>
                </table>
                -->
                <!-- /.table-responsive -->
                <div class="well">
                    <!--
                    <h4>Atajos de Teclado</h4>
                    <p>
                        F1 Nueva Venta<br>
                        F2 Agregar Producto<br>
                        F4 Cancelar Venta<br>
                        F5 Imprimir<br>
                        F7 Ir a Recetas<br>
                    </p>
-->
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>



<script src="view/materiales/solicitudes.js"></script>