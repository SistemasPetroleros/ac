<?php 

error_reporting(-1);
include_once '../config.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/solicitudes_items.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../../model/solicitudes_items_traza_estado.php';
include_once '../funciones.php';
include_once '../enviar_mail.php';


date_default_timezone_set('America/Argentina/Buenos_Aires');



$idSolicitud = isset($_POST['idSolicitud'])?$_POST['idSolicitud']:-1;

$objStatus = new solicitudes_estados();

$lastStatus=$objStatus -> SelectStatusRecent($idSolicitud);


if ($x = mysqli_fetch_assoc($lastStatus)) {
    $estadoActual=$x['id_estados'];
} 


if ($estadoActual==7 || $estadoActual==8 ){
   
    $objITem = new solicitudes_items();
	$items=$objITem -> SelectSolicitudItems($idSolicitud);
    $objTraza= new SolicitudesItemTraza('');

    $objTrazaEstado = new SolicitudesItemTrazaEstados();

    $param['idSolicitud']=$idSolicitud;
    $param['idEstado']='17,20';
    $res= $objTraza -> SelectItemsTraza($param);


    $html='
           <form id="formDispensa"   >
           <br/>';

    if($estadoActual==7) 
    {  
           
    $html.='<legend>Productos a Dispensar</legend>';

    //por cada item en la tabla solicitudes_items_traza, armar la tabla no recepcionados
    $tabla='<div class="table-responsive"><table class="table jambo_table" id="tablaDispensacion">';
    $tabla.="<thead>
                <th>Dispensar</th>
                <th>Id.</th>
                <th>Estado</th>
                <th>Traza</th>
                <th>Producto</th>
                <th>GTIN</th>
                <th>Nro. Serie</th>
                <th>Nro. Lote</th>
                <th>Fecha Venc.</th>
                <th>Fecha Remito</th>
                <th>Nro. Remito</th>
                <th>Id. Recepción ANMAT</th>
            </thead>";
    $tabla.="<tbody>";
    
     $i=0;
     while($row=mysqli_fetch_assoc($res)){

    
        $fechaV="";
        if($row['fechaVenc']!=null && $row['fechaVenc']!=""){
            $fechaV=date('d/m/Y',strtotime($row['fechaVenc']));
        }

        $fechaR="";
        if($row['fechaRemito']!=null && $row['fechaRemito']!=""){
            $fechaR=date('d/m/Y',strtotime($row['fechaRemito']));
        }

        if($row['esTrazable']==1){
            $esTrazable='SI';
        }
        else{
            $esTrazable='NO';
        } 

        $estado='<a onclick="abrirEstados('.$idSolicitud.','.$row['id_item'].','.$row['id'].');" id="popup_msg"><u>'.$row['estado'].'</u></a>';
       
        $tabla.="<tr>";

        $tabla.='<td><input type="checkbox" class="form-check-input" id="dispensar'.$i.'" checked></td>';
        $tabla.='<td>'.$row['id'].'</td>';
        $tabla.="<td>".$estado."</td>";
        $tabla.='<td style="text-align: left;">'.$esTrazable.'</td>';
        $tabla.="<td>".utf8_encode($row['nombreProd'])." ".$row['presentacion']."</td>";
        $tabla.='<td>'.$row['gtinItem'].'</td>';
        $tabla.='<td>'.$row['nroSerie'].'</td>';
        $tabla.='<td>'.$row['lote'].'</td>';
        $tabla.='<td>'.$fechaV.'</td>';
        $tabla.='<td>'.$fechaR.'</td>';
        $tabla.='<td>'.$row['nroRemito'].'</td>';
        $tabla.='<td>'.$row['id_recepcion'].'
        <input type="hidden" class="form-control" id="idItem'.$i.'" value="'.$row['id_item'].'" /> 
        <input type="hidden" class="form-control" id="idTabla'.$i.'" value="'.$row['id'].'" /> 
        <input type="hidden" class="form-control" id="esTrazable'.$i.'" value="'.$row['esTrazable'].'" /> 
        <input type="hidden" class="form-control" id="idTrazaEstado'.$i.'" value="'.$row['idTrazaEstado'].'" /> 
        </td>';
      
        $tabla2.="</tr>";

         $i++;

     }

    $tabla.="</tbody>";        
    $tabla.="</table> </div>";



    


    $html.= $tabla;
    $html.='<br/><br/><button class="btn btn-round btn-dark" id="informarDispensa" type="button" onclick="informarDispensas('.$idSolicitud.');" ><i class="fa fa-tasks" aria-hidden="true"></i>
    Informar Dispensa</button> <br/>
    <br/>';

    }

    //Buscar los informados
    $param1['idSolicitud']=$idSolicitud;
    $param1['idEstado']='19';
    $res1= $objTraza -> SelectItemsTraza($param1);

    $html.="
           <legend>Productos Dispensados</legend>";

 if($estadoActual==7){     
    $html.='<button class="btn btn-round btn-warning" id="cerrarDisp" onclick="cerrarDispensa('.$idSolicitud.')" type="button"  title="Cerrar Dispensa"><i class="fa fa-lock" aria-hidden="true"></i>
           Cerrar Dispensa</button> <br/><br/>
            ';
 }   
 
 if($estadoActual==8){     
    $html.='<button class="btn btn-round btn-primary" id="cerrarDisp" onclick="imprimirDispensa('.$idSolicitud.')" type="button"  title="Imprimir Dispensa"><i class="fa fa-print" aria-hidden="true"></i>
           Reporte Dispensa</button> <br/><br/>
            ';
 }  
              
    
    $tabla2='<div class="table-responsive"> <table class="table jambo_table" id="tablaDispensados">';
    $tabla2.="<thead>
                           <th>Id.</th>
                           <th>Estado</th>
                           <th>Traza</th>
                           <th>Producto</th>
                           <th>GTIN</th>
                           <th>Nro. Serie</th>
                           <th>Nro. Lote</th>
                           <th>Fecha Venc.</th>
                           <th>Fecha Remito</th>
                           <th>Nro. Remito</th>
                           <th>Id. Recepción ANMAT</th>
                           <th>Id. Dispensa ANMAT</th>
                          
                   </thead>";
    $tabla2.="<tbody>";

    while($rowi=mysqli_fetch_assoc($res1)){

        $fechaV="";
        if($rowi['fechaVenc']!=null && $rowi['fechaVenc']!=""){
            $fechaV=date('d/m/Y',strtotime($rowi['fechaVenc']));
        }

        $fechaR="";
        if($rowi['fechaRemito']!=null && $rowi['fechaRemito']!=""){
            $fechaR=date('d/m/Y',strtotime($rowi['fechaRemito']));
        }

        if($rowi['esTrazable']==1){
            $esTrazable='SI';
        }
        else{
            $esTrazable='NO';
        } 

        $estado='<a onclick="abrirEstados('.$idSolicitud.','.$rowi['id_item'].','.$rowi['id'].');" id="popup_msg"><u>'.$rowi['estado'].'</u></a>';
       
        $tabla2.="<tr>";

        $tabla2.='<td>'.$rowi['id'].'</td>';
        $tabla2.="<td>".$estado."</td>";
        $tabla2.='<td style="text-align: left;">'.$esTrazable.'</td>';
        $tabla2.="<td>".utf8_encode($rowi['nombreProd'])." ".$rowi['presentacion']."</td>";
        $tabla2.='<td>'.$rowi['gtinItem'].'</td>';
        $tabla2.='<td>'.$rowi['nroSerie'].'</td>';
        $tabla2.='<td>'.$rowi['lote'].'</td>';
        $tabla2.='<td>'.$fechaV.'</td>';
        $tabla2.='<td>'.$fechaR.'</td>';
        $tabla2.='<td>'.$rowi['nroRemito'].'</td>';
        $tabla2.='<td>'.$rowi['id_recepcion'].'</td>';
        $tabla2.='<td>'.$rowi['id_dispensa'].'</td>';
      
        $tabla2.="</tr>";

     

    }
           
        
       
    $tabla2.="</tbody>";        
    $tabla2.="</table> 
              </div>";   
    
    $html.=$tabla2;



    $html.='
          </form>';

    echo $html;

}
else{
    echo "";
}



?>