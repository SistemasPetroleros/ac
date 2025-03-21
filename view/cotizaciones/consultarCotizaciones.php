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
                <legend> SOLICITUDES </legend>
                <form method="post" id="formulariocot">

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label for="fechaDesde" class="control-label">Fecha Desde</label>
                            <input class="form-control" type="date" id="fechaDesde" name="fechaDesde" placeholder="dd/mm/aaaa" value="<?= date('Y-m-d', strtotime('-31 day', strtotime(date('Y-m-d')))); ?>">
                        </div>

                        <div class="col-sm-4">
                            <label for="fechaHasta" class="control-label">Fecha Hasta</label>
                            <input class="form-control" type="date" id="fechaHasta" name="fechaHasta" placeholder="dd/mm/aaaa" value="<?= date('Y-m-d'); ?>">
                        </div>

                        <div class="col-sm-4">
                            <label for="buscaBeneficiario" class="control-label">Afiliado</label>
                            <input type="text" class="form-control" id="buscaBeneficiario" name="buscaBeneficiario" placeholder="DNI, Apellido o Nombre">
                        </div>


                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label for="idSolicitudBuscar" class="control-label">Id. Solicitud</label>
                            <input type="number" class="form-control" id="idSolicitudBuscar" name="idSolicitudBuscar">
                        </div>

                        <div class="col-sm-4">
                            <label for="idPuntoDispensa" class="control-label">Punto Dispensa</label>
                            <input type="text" class="form-control" id="idPuntoDispensa" name="idPuntoDispensa" placeholder="Nombre o GLN">
                        </div>

                        <div class="col-sm-4">
                            <label for="buscaEstado" class="control-label">Estado</label>
                            <select class="form-control selectpicker" id="buscaEstado" name="buscaEstado[]" multiple title="Seleccione opcion(es)">
                                <option value="-1">Todos</option>
                                <?php while ($rowe = mysqli_fetch_assoc(@$resultadoEstados)) {
                                    echo '<option value="' . $rowe['id'] . '">' . $rowe['nombre'] . '</option>';
                                }
                                ?>


                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <br />
                        <div class="col-sm-6" style="display: flex;justify-content: left; padding: 1em;">

                            <button type="button" class="btn btn-dark btn-round pull-right" onclick=" buscarCotizaciones();"><i class="fa fa-search" aria-hidden="true"></i>
                                Buscar</button>


                            <?php

                            //si tiene permiso para cargar solicitudes de ortopedia
                            if (in_array("3", $tspermisos)) { ?>
                                <button type="button" data-toggle="modal" data-target="#modalCargarCotizacion" data-backdrop="false" class="btn btn-dark btn-round pull-right" onclick="cargarCotizacion();"><i class="fa fa-plus" aria-hidden="true"></i>
                                    Nueva Solicitud</button>

                            <?php } ?>
                            <button type="button" class="btn btn-dark btn-round pull-right" onclick=" generarReporte();"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                Resumen Facturación</button>

                            <a href="index.php?r=280" class="btn btn-dark btn-round pull-right"><i class="fa fa-undo" aria-hidden="true"></i>
                                Limpiar</a>
                        </div>
                    </div>


                    <!--              <table style="border-spacing: 10px 5px; border-collapse: separate;">

                        <tr>
                            <td>
                                <label for="fechaDesde" class="control-label">Fecha Desde</label>
                            </td>
                            <td>
                                <input class="form-control" type="date" id="fechaDesde" name="fechaDesde" placeholder="dd/mm/aaaa" value="<?= date('Y-m-d', strtotime('-31 day', strtotime(date('Y-m-d')))); ?>">

                            </td>
                            <td>
                                <label for="fechaHasta" class="control-label">Fecha Hasta</label>
                            </td>
                            <td>
                                <input class="form-control" type="date" id="fechaHasta" name="fechaHasta" placeholder="dd/mm/aaaa" value="<?= date('Y-m-d'); ?>">

                            </td>
                            <td>
                                <label for="buscaIdSolicitud" class="control-label">Id. Solicitud</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="buscaIdSolicitud" name="buscaIdSolicitud" >
                            </td>
                        </tr>
 



                        <tr>

                            <td colspan="4">
                      
                                <button type="button" class="btn btn-dark btn-round pull-right" onclick=" buscarCotizaciones();">Buscar</button>
                                <a href="index.php?r=280" class="btn btn-dark btn-round pull-right">Limpiar</a>


                            </td>
                        </tr>

                    </table> -->

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
                                <h3>SOLICITUD</h3>

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
                                        <li role="presentation" class="active" id="solicitudTab"><a href="#solicitud" aria-controls="solicitud" role="tab" data-toggle="tab">Datos Generales</a></li>
                                        <li role="presentation" id="itemsTab"><a href="#items" aria-controls="items" role="tab" data-toggle="tab" id="pestItems"> Cotización</a></li>
                                        <!--   <li class="disabledTab" role="presentation" id="proveedoresTab"><a href="#proveedores" aria-controls="proveedores" role="tab" data-toggle="tab">Proveedores</a></li>-->
                                        <li role="presentation" id="adjuntosTab"><a href="#adjuntos" aria-controls="adjuntos" role="tab" data-toggle="tab">Adjuntos</a></li>
                                        <li role="presentation" id="estadoTab"><a href="#estado" aria-controls="estado" role="tab" data-toggle="tab">Estado</a></li>
                                        <!--     <li class="disabledTab" role="presentation" id="recepcionTab"><a href="#recepcionTraza" onclick="traerTrazaProductos();" aria-controls="recepcionTraza" role="tab" data-toggle="tab">Trazabilidad</a></li>

                                        <li class="disabledTab" role="presentation" id="remitosTab"><a href="#remitosTraza" onclick="traerRemitos();" ; aria-controls="remitosTraza" role="tab" data-toggle="tab">Remitos</a></li>

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
                                        <div class="tab-pane fade" id="recepcionTraza">
                                            Cargando...
                                        </div>

                                        <div class="tab-pane fade" id="remitosTraza">
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



<!--MODAL-->
<div class="modal fade bs-example-modal-lg" id="modalCargarCotizacion" tabindex="-1" role="dialog" aria-labelledby="Editar">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" onclick="cerrarModalCotizacion();"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" id="divCargarCotizacion">


            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>

</div>



<script src="view/cotizaciones/cotizaciones.js?v=1"></script>

<script>
    $(document).on('hidden.bs.modal', '#modalVerSolicitud', function() {

        //var esVisible = $("#modalVerSolicitud").is(":visible");
        if ($('#modalVerSolicitud').css('display') == 'none' || $('#modalVerSolicitud').css('opacity') == 0) {
            $(".modal-backdrop").css("z-index", "-4");
        }


    });

    $(document).ready(function() {
        $('#buscaEstado').selectpicker();
    });


    buscarCotizaciones();
</script>