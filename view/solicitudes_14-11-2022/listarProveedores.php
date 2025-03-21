<form id="tablaProv" >
  <br/>
  <legend>Listado de Cotizaciones</legend>
  <br/>

  <div class="col-md-12">        
      <table width="100%" class="table jambo_table">
          <thead>
            
            <th >#Cotización</th> 
            <th >Proveedor</th>
            <th >Importe</th> 
            <th >Observaciones</th>
            <th >Estado</th> 
            <th >Acciones</th>
            
          </thead>
          <tbody>  
              <?php 
                    //Si la solicitud paso a proceso de compra/anulada no permite cambios 
                    $disabled="";
                    if($estadoActual>4) {$disabled='disabled';}
                     
                     //Verifica si el usuario tiene permiso para modificar cotizaciones en el estado actual de la solicitud
                     $tieneAcceso=FALSE; 
                     if(esMiembro($estadoActual,$permisos))
                     {  
                        $tieneAcceso=TRUE;
                     }
                    

                     
                      $i=0;
                      while ($x = mysqli_fetch_assoc($res)) {

                             $disabled="";
                              if($x['nombreEst']=="ANULADA")   
                              { //Si la cotización está anulada
                                  $disabled='disabled';
                              }

                              echo '<tr>'; 
                              echo '</td>';
                              echo '<td style="text-align:center;">'.$x['id'].'</td>';
                              echo '<td>'.$x['nombreProv'].'</td>';
                              echo '<td><input type="number" step="0.01" placeholder="0.00" '.$disabled.' class="form-control" value="'.$x['importe'].'" id="importe'.$i.'"/></td>';
                             
                              echo '<td><textarea id="obs'.$i.'" '.$disabled.' class="form-control">'.$x['observaciones'].'</textarea></td>';

                              echo '<td><label>'.$x['nombreEst'].'</label></td>';

                              if($estadoActual==4 AND $tieneAcceso) 
                              {//En PROCESO DE COTIZACION y el usuario con permiso de modificacion en este estado
                                if($x['nombreEst']=="ANULADA")     
                                {
                                  echo '<td> ';
                                  echo '<button id="volver'.$i.'"  onclick="abrirModal('.$x['id'].',\'R\','.$idSolicitud.','.$i.');" type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-warning btn-round" title="Volver a Pendiente"><span class="fa fa-undo"></span></button> ';
                                  echo '</td>';
                                }
                                else
                                {
                                  if ($x['nombreEst']=="PENDIENTE")
                                  {
                                    echo '<td> ';
                                    
                                    echo ' <button id="editar'.$i.'" onclick="editarCotizacion('.$x['id'].','.$i.',\'agregar\');" type="button" class="btn btn-info btn-round" title="Guardar"><span class="fa fa-floppy-o"></span></button>';
                                   
                                    if(esMiembro("10",$permisos))
                                    {
                                      echo ' <button id="aprobar'.$i.'" onclick="abrirModal('.$x['id'].',\'A\','.$idSolicitud.','.$i.');" type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-success btn-round" title="Aprobar"><span class="fa fa-check"></span></button>';
                                    } 
                                   
                                    if(esMiembro("12",$permisos)!=FALSE)
                                    {
                                       echo ' <button id="anular'.$i.'"  onclick="abrirModal('.$x['id'].',\'C\','.$idSolicitud.','.$i.');" type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-danger btn-round" title="Anular"><span class="fa fa-ban"></span></button>';
                                    } 

                                    echo '</td>';
  
                                  }
                                  else
                                  {
                                    echo '<td></td>';   
                                  }
                                 
  
                                }

                              }
                              else{
                                //CUALQUIER OTRO ESTADO NO PERMITE REALIZAR ACCIONES
                                echo '<td></td>';   
                              }
                              

                              echo '</tr>';   
                          $i++;    
                      } 

            ?>
      </tbody>
    </table>

  </div>



</form>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
        <button type="button" class="close" onclick="cerrarModalProveedor();" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <input id="id_solicitud" class="form-control" type="hidden" />
            <input id="id_cotizacion" class="form-control" type="hidden" />
			<input id="importeCot" class="form-control" type="hidden" />
			<input id="observacionesCot" class="form-control" type="hidden" />
            <input id="operacion" class="form-control" type="hidden" />
        <div id="mensaje">
         
        </div> 
        <div id="mensaje2">
         
        </div> 
        <span><i class="fa fa-info" aria-hidden="true"></i> Antes de confirmar la acción ingrese el Número de Confirmación que se encuentra en el cuadro de texto siguiente:</span>
      </div>
      <div class="modal-footer">
          
        <div >   
                        <input id="randc" name="randc" type="text" class="form-control pull-left" placeholder="<?= $rand ?>" style="width: 50px;">
                        <input id="randc2" name="randc2" type="hidden" value="<?= $rand ?>">
                        &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                        <button type="button" class="btn btn-danger btn-round pull-left" id="Confirmar" name="Confirmar" onclick="onChangeStatus();">Confirmar</button>
                        <button type="button" class="btn btn-secondary btn-round pull-left" onclick="cerrarModalProveedor();" >Cancelar</button>            
           </div>
        
      </div>
    </div>
  </div>
</div>

 
 
