<style>
    .disabledTab {
        pointer-events: none;
    }


    .suggest-element {
        margin-left: 1px;
        margin-top: 1px;
        width: 90%;
        cursor: pointer;

    }

    #suggestions {
        width: 90%;
        height: 150px;
        overflow: auto;

    }

    .suggest {
        width: 90%;
        height: 150px;
        overflow: auto;

        display: none;
        width: 100%;

    }


    .modal-open {
        overflow: scroll;
    }

    .modal-open .modal {
        overflow-x: scroll;
        overflow-y: auto;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        display: none;
        width: 100%;
        height: 100%;
        overflow: scroll;
        outline: 0;
    }

    .modal-lg {
        width: 90% !important;
    }
</style>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <legend> MATERIALES </legend>
                <form method="post" id="formulario">
                    <table style="border-spacing: 10px 5px; border-collapse: separate;">

                        <tr>
                            <td>
                                <label for="fechaDesde" class="control-label">Fecha Desde</label>
                            </td>
                            <td>
                                <input class="form-control fecha " type="text" id="fechaDesde" name="fechaDesde" placeholder="dd/mm/aaaa" value="<?= $hoyMenosUnMes ?>">

                            </td>
                            <td>
                                <label for="fechaHasta" class="control-label">Fecha Hasta</label>
                            </td>
                            <td>
                                <input class="form-control fecha " type="text" id="fechaHasta" name="fechaHasta" placeholder="dd/mm/aaaa" value="<?= $hoy ?>">

                            </td>
                            <td>
                                <label for="buscaProducto" class="control-label">Producto</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="buscaProducto" name="buscaProducto" placeholder="Cod / Nombre " value="<?= $prod ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="buscaEstado" class="control-label">Estado</label>
                            </td>
                            <td>
                                <select class="form-control" name="buscaEstado" id="buscaEstado" required="">
                                    <option value="-1">Todos</option>
                                    <?php


                                    while ($x = mysqli_fetch_assoc($arrayEstados)) {
                                        echo '<option value="' . $x['id'] . '">' . ($x['nombre']) . '</option>';
                                    }
                                    ?>

                                </select>
                            </td>
                            <td>
                                <label for="idSolicitudBuscar" class="control-label">Nro Solicitud</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="idSolicitudBuscar" name="idSolicitudBuscar" placeholder="" value="<?= $nroVenta ?>">
                            </td>
                            <td>
                                <label for="buscaBeneficiario" class="control-label">Beneficiario</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="buscaBeneficiario" name="buscaBeneficiario" placeholder="DNI / Nombre " value="">
                            </td>
                        </tr>


                        <tr>
                            <td> <label for="buscarEsSur" class="control-label">Es Sur?:</label> </td>

                            <td>
                                <select id="buscarEsSur" name="buscarEsSur" class="form-control">
                                    <option value="-1">Todos</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>

                                </select>
                            </td>

                            <td>
                                <label for="idPuntoDispensa" class="control-label">Punto de Dispensa</label>
                            </td>
                            <td>
                                <select class="form-control" name="idPuntoDispensa" id="idPuntoDispensa" required="">
                                    <option value="-1">Todos</option>
                                    <?php


                                    while ($x = mysqli_fetch_assoc($arrayPD)) {
                                        echo '<option value="' . $x['id'] . '">' . ($x['nombre']) . '</option>';
                                    }
                                    ?>

                                </select>
                            </td>
 <!--  
                            <td> <label for="buscarNroRemito" class="control-label">Nro. Remito:</label> </td>

                            <td>
                             <input type="text" class="form-control" id="buscarNroRemito" name="buscarNroRemito" placeholder="R00000000000000" value=""> -->
                            </td>

                        </tr>




                        <tr>

                            <td colspan="4">
                                <button button type="button" class="btn btn-dark btn-round pull-right" onclick="descargarExcel();">Descargar Excel</button>
                                <button type="button" class="btn btn-dark btn-round pull-right" onclick=" buscarSolicitudes();">Buscar</button>
                                <a href="index.php?r=278" class="btn btn-dark btn-round pull-right">Limpiar</a>


                            </td>
                        </tr>

                    </table>

                </form>
                <span class="pull-right">
                    <!--<button type="button" class="btn btn-primary btn-circle btn-lg " data-toggle="modal" data-target=".bs-example-modal-lg" onclick="editar();"><i class="fa fa-plus"></i>-->
                    </button>
                </span>
            </div>




            <!-- /.panel-heading -->
            <div id="grillaSolicitudes" class="panel-body">




                <!-- /.table-responsive -->
                <div class="well">
                    <!--<h4>Información sobre Ventas</h4>-->
                    <p></p>

                </div>
            </div>


            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

</div>




<!--MODAL-->
<div class="modal fade bs-example-modal-lg" id="modalVerSolicitud" tabindex="-1" role="dialog" aria-labelledby="Editar">
    <div class="modal-dialog modal-lg" role="document">




        <div class="modal-content">
            <div class="modal-header">
                <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>  -->
                <button type="button" class="close" onclick="cerrarModal();"><span aria-hidden="true">×</span></button>
                <!-- <h4 class="modal-title" id="exampleModalLabel">Solicitud Alto Costo</h4>-->
            </div>

            <div class="modal-body" id="mostrarSolicitud">

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
                                        if ($active == ' active ') {
                                            echo "<script>tabSelected=;</script>";
                                        }
                                        $active = ' active ';
                                        $estado = '';
                                        ?>
                                        <!--//$habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';-->
                                        <!--<li class="<?= $active ?>"><a href="#solicitud" data-toggle="tab" aria-expanded="false" onclick="tabSelected='';">Beneficiario</a></li>-->
                                        <li role="presentation" class="active" id="solicitudTab"><a href="#solicitud" aria-controls="solicitud" role="tab" data-toggle="tab">Solicitud</a></li>
                                        <li class="disabledTab" role="presentation" id="itemsTab"><a href="#items" aria-controls="items" role="tab" data-toggle="tab" id="pestItems"> Items</a></li>
                                        <li class="disabledTab" role="presentation" id="proveedoresTab"><a href="#proveedores" aria-controls="proveedores" role="tab" data-toggle="tab">Proveedores</a></li>
                                        <li class="disabledTab" role="presentation" id="adjuntosTab"><a href="#adjuntos" aria-controls="adjuntos" role="tab" data-toggle="tab">Adjuntos</a></li>
                                        <li class="disabledTab" role="presentation" id="estadoTab"><a href="#estado" aria-controls="estado" role="tab" data-toggle="tab">Estado</a></li>
                                     <!--    <li class="disabledTab" role="presentation" id="recepcionTab"><a href="#recepcionTraza" onclick="traerTrazaProductos();" aria-controls="recepcionTraza" role="tab" data-toggle="tab">Trazabilidad</a></li>

                                        <li class="disabledTab" role="presentation" id="remitosTab"><a href="#remitosTraza" onclick="traerRemitos();" ; aria-controls="remitosTraza" role="tab" data-toggle="tab">Remitos</a></li> -->

                                        <!--        <li class="disabledTab" role="presentation" id="dispensaTab"><a href="#dispensacionTraza" aria-controls="dispensacionTraza" role="tab"
                                data-toggle="tab">Dispensación de Productos</a></li> -->

                                    </ul>


                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="solicitud">




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
                                     <!--    <div class="tab-pane fade" id="recepcionTraza">
                                            Cargando...
                                        </div>

                                        <div class="tab-pane fade" id="remitosTraza">
                                            Cargando...
                                        </div>  -->

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


                                <!-- /.table-responsive -->
                                <div class="well">


                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>






            </div>

        </div>
    </div>
</div>

<div id="divModalRecepcionProd" class="panel-body">
</div>


<div id="divModalHabDisp" class="panel-body">

</div>

<div id="divModalCerrarDisp" class="panel-body">

</div>

<div id="divModalQR" class="panel-body">

</div>
<div id="divModalAR" class="panel-body">

</div>




<script src="view/materiales/solicitudes.js"></script>

<script>
    $(document).on('hidden.bs.modal', '#modalVerSolicitud', function() {
        //var esVisible = $("#modalVerSolicitud").is(":visible");
        if ($('#modalVerSolicitud').css('display') == 'none' || $('#modalVerSolicitud').css('opacity') == 0) {
            $(".modal-backdrop").css("z-index", "-4");
        }


    });
    buscarSolicitudes();
</script>