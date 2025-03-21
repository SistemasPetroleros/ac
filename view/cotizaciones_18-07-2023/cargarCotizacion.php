<form role="form" action="" method="post" id="formularioC">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Solicitud Cotización</h3>

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">


                            <?php

                            $active = '';
                            if ($active == ' active ') {
                                echo "<script>tabSelected=;</script>";
                            }
                            $active = ' active ';
                            $estado = '';
                            ?>
                            <!--//$habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';-->
                            <!--<li class="<?= $active ?>"><a href="#solicitud" data-toggle="tab" aria-expanded="false" onclick="tabSelected='';">Beneficiario</a></li>-->
                            <li role="presentation" class="active" id="solicitudTab1"><a href="#cargarCotizacion" aria-controls="solicitud" role="tab" data-toggle="tab">Solicitud</a></li>
                            <li class="disabledTab" role="presentation" id="itemsTab1"><a href="#itemsc" aria-controls="items" role="tab" data-toggle="tab" id="pestItemsc"> Cotización</a></li>
                            <li class="disabledTab" role="presentation" id="adjuntosTab1"><a href="#adjuntosc" aria-controls="adjuntos" role="tab" data-toggle="tab">Adjuntos</a></li>
                             <li class="disabledTab" role="presentation" id="estadoTab1"><a href="#estadoc" aria-controls="estado" role="tab" data-toggle="tab">Estado</a></li>
                            
                        </ul>


                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="cargarCotizacion">
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <label>DNI Afiliado:</label>
                                        <input type="text" class="form-control" name="dniCotizado" id="dniCotizado" onkeypress="handle(event);"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <br/>
                                        <button class="btn btn-dark btn-round pull-left" type="button" id="btnbuscaraf" onclick="iniciarSolicitud();">Iniciar Solicitud</button>
                                    </div>


                                </div>



                            </div>



                            <div class="tab-pane fade" id="itemsc">
                                Cargando...
                            </div>

                            <div class="tab-pane fade" id="adjuntosc">
                                Cargando...
                            </div>

                            <div class="tab-pane fade" id="estadoc">
                                Cargando...
                            </div>



                            <!--       <div class="tab-pane fade" id="dispensacionTraza">
                        Cargando...
                        </div> -->
                        </div>



                        <div id="divsBuscarProducto"></div>




                        <?php
                        $active = '';
                        $rand = rand(100, 999);
                        ?>









                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>

                    </div>



                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>






</form>