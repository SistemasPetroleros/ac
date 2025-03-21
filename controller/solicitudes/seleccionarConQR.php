<!-- Modal -->
<div class="modal fade" id="modalQR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Seleccionar con Código QR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <input type="text" id="code" name="code" class="form-control" onkeypress="handle(event);" />

                        </div>

                        <div class="form-group  col-sm-4">

                            <button type="button" class="btn btn-primary" onclick="buscarProductoQR();"><i class="fa fa-search" aria-hidden="true"></i>
                                Buscar</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="alert alert-success" role="alert" style="display:none;" id="divSuccess">
                           Seleccionado en la lista con Exito!
                        </div>
                    </div>    

                    <div class="form-group">
                        <div class="alert alert-danger" role="alert" style="display:none;" id="divError">
                           Producto no encontrado en la lista!
                        </div>
                    </div>    



                    <div class="alert alert-primary" role="alert">
                        <h4>GTIN: <span id="GTIN"></span></h4>
                        <h4>Fecha Vencimiento: <span id="FECVENC"></span></h4>
                        <h4>Lote: <span id="LOTE"></span></h4>
                        <h4>Serie: <span id="SERIE"></span></h4>
                    </div>



                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>