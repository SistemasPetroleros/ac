
<?php 
    session_start();
    error_reporting(-1);
    include_once '../config.php';
    include_once '../../model/solicitudes_items_traza_estado.php';

    $estados= new SolicitudesItemTrazaEstados('');

    $param['idSolicitud']=$_POST['idSolicitud'];
    $param['idItem']=$_POST['idItem'];
    $param['idItemTraza']=$_POST['idItemTraza'];


    $resultado=$estados -> SelectEstadosItem($param);
  


?>


<!-- Modal -->
<div class="modal fade" id="modalErrores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Detalle de Cambios de Estados</h4>

      </div>
      <div class="modal-body">
         <table class="table jambo_table" >
            <thead>
               <th>Fecha/Hora</th>
               <th>Usuario</th>
               <th>Estado</th>
               <th>Descripción</th>
              
            </thead> 
            <tbody>
          <?php
                 while($row=mysqli_fetch_assoc($resultado)){
                   echo '<tr>';
                   echo '<td>'.$row['fechaDesde'].'</td>';
                   echo '<td>'.($row['userAlta']).'</td>';
                   echo '<td>'.($row['estado']).'</td>';
                   echo '<td>'.($row['observaciones']).'</td>';
                   echo '</tr>';
                    
                 }
          ?>
            </tbody>
         </table> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="cerrarModalEstados();">Cerrar</button>
    
      </div>
    </div>
  </div>
</div>