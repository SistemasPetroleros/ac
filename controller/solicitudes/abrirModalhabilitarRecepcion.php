    <!-- Modal -->
    <div class="modal fade" id="modalHabDispensa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Habilitar Recepción de Productos</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

          <!--     <div class="alert btn-warning" role="alert"> 
                  
                    
                    ATENCIÓN: Se enviará Comprobante a AFILIADO.
                </div> -->
        

            <div class="alert btn-dark" role="alert"> 
                   ¿Desea habilitar la Recepción de los Productos?
                   <br/> Tenga en cuenta que dantes de realizar esta acción no deben haber productos dispensados. 
                   <br/> Esta acción cambia la solicitud al estado PENDIENTE REVISION EN FARMACIA. 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="cambiarEstadoBtn" class="btn btn-round btn-dark" onclick="verificarVolverRecep(6);">Confirmar</button>
                <button type="button" class="btn  btn-round btn-danger" data-dismiss="modal" >Cancelar</button>
                
            </div>
            </div>
        </div>
        </div>