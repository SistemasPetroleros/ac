<?php 

error_reporting(0);
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


if ($estadoActual==6 || $estadoActual==7 || $estadoActual==8){
   
    $objITem = new solicitudes_items();
	$items=$objITem -> SelectSolicitudItems($idSolicitud);
    $objTraza= new SolicitudesItemTraza('');

    $objTrazaEstado = new SolicitudesItemTrazaEstados();

   

    
		
	while($y=mysqli_fetch_array($items))
	{
        
        // (1) verificar que si existe en la tabla solicitudes_items_traza la cantidad del item seleccionado 
        // (2) verificar la cantidad e insertar los restantes de ser necesario

        $parametros['idItem']=$y['id'];
        $resultado= $objTraza -> SelectItemsTraza($parametros);
        $cantidad= mysqli_num_rows($resultado);

        if($cantidad<$y['cantidad']){
            if($cantidad<$y['cantidad']){
            $i=$cantidad;
            while($i<$y['cantidad']){

                //creo item 
                $objTraza -> setid_solicitud($idSolicitud);
                $objTraza -> setid_item($y['id']);
                $objTraza -> setid_producto($y['id_producto']);
                $objTraza -> setgtin($y['gtin']);
                $objTraza -> setlote('');
                $objTraza -> setnroSerie('');
                $objTraza -> setlote('');
                $objTraza -> setfechaVenc(NULL);
                $objTraza -> setid_recepcion('');
                $objTraza -> setid_dispensa('');
                $objTraza -> setesTrazable(1);
                $objTraza -> setuserAlta($_COOKIE['user']);

                //funciona para crear objeto
                $idObj= $objTraza -> Create();

                
                 //seteo objeto estado
                $objTrazaEstado -> setid_solicitud($idSolicitud);
                $objTrazaEstado -> setid_item($y['id']);
                $objTrazaEstado -> setid_estado('16');
                $objTrazaEstado -> setid_item_traza($idObj);
                $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
                $objTrazaEstado -> setfhasta('');
                $objTrazaEstado -> setobservaciones('Se crea Producto en estado PENDIENTE.');
                $objTrazaEstado -> setuserAlta($_COOKIE['user']);

                //creo estado del item en Pendiente
                $objTrazaEstado -> Create();


                $i++;
            }//fin 2do while

        }//fin if

     
    }//fin 1er while

    $param['idSolicitud']=$idSolicitud;
    $param['idEstado']='16,18';
    $res= $objTraza -> SelectItemsTraza($param);


    $html='
           <form id="formRecep"   >
           <br/> ';

     if($estadoActual==6) 
     {     
           
     $html.='      <legend>Productos a Recepcionar</legend>';

    //por cada item en la tabla solicitudes_items_traza, armar la tabla no recepcionados
    $tabla='<table class="table jambo_table" id="tablaRecepcion">';
    $tabla.="<thead>
                    <th>Id.</th>
                    <th>Traza</th>
                    <th>Producto</th>
                    <th>GTIN</th>
                    <th>Datos Trazabilidad</th>
                    <th>Estado</th>
            </thead>";
    $tabla.="<tbody>";
    
     $i=0;
     while($row=mysqli_fetch_assoc($res)){

    
        $estado='<a onclick="abrirEstados('.$idSolicitud.','.$row['id_item'].','.$row['id'].');" id="popup_msg"><u>'.$row['estado'].'</u></a>';
      
         $tabla.="<tr>";
         $tabla.='<td>'.$row['id'].'</td>';
         $tabla.='<td><input type="checkbox" class="form-check-input" id="esTrazable'.$i.'" checked></td>';
         $tabla.="<td>".utf8_encode($row['nombreProd'])." ".$row['presentacion']."</td>";
         $tabla.='<td><input type="text" class="form-control" id="gtin'.$i.'" value="'.$row['gtinProd'].'"/></td>';
         $tabla.='<td>  <label class="form-group has-float-label">
                            <input type="text" placeholder="Nro. Serie" class="form-control" id="nroSerie'.$i.'" value="'.$row['nroSerie'].'" />
                            <span>Nro. Serie</span>
                         </label>
                         <label class="form-group has-float-label">
                            <input type="text" placeholder="Lote" class="form-control" id="lote'.$i.'"  value="'.$row['lote'].'" />
                            <span>Lote</span>
                         </label>
                         <label class="form-group has-float-label">
                            <input type="date" placeholder="Fecha Vencimiento" class="form-control" id="fVenc'.$i.'"value="'.$row['fechaVenc'].'"  />
                            <span>Fecha Vencimiento</span>
                         </label>   
                         <label class="form-group has-float-label">
                            <input type="date" placeholder="Fecha Remito" class="form-control" id="fremito'.$i.'"value="'.$row['fechaRemito'].'"  />
                            <span>Fecha Remito</span>
                         </label> 
                         <label class="form-group has-float-label">
                            <input type="text" placeholder="Remito" class="form-control" id="remito'.$i.'"value="'.$row['nroRemito'].'"  />
                            <span>Remito</span>
                         </label> 
                        <input type="hidden" class="form-control" id="idItem'.$i.'" value="'.$row['id_item'].'" /> 
                        <input type="hidden" class="form-control" id="idProducto'.$i.'" value="'.$row['id_producto'].'" /> 
                        <input type="hidden" class="form-control" id="idTabla'.$i.'" value="'.$row['id'].'" /> 
                        <input type="hidden" class="form-control" id="idTrazaEstado'.$i.'" value="'.$row['idTrazaEstado'].'" /> 
                 </td>';
        // $tabla.='<td><input type="text" class="form-control" id="lote'.$i.'"  value="'.$row['lote'].'" /></td>';
        /* $tabla.='<td><input type="date" class="form-control" id="fVenc'.$i.'"value="'.$row['fechaVenc'].'"  />
                      <input type="hidden" class="form-control" id="idItem'.$i.'" value="'.$row['id_item'].'" /> 
                      <input type="hidden" class="form-control" id="idProducto'.$i.'" value="'.$row['id_producto'].'" /> 
                      <input type="hidden" class="form-control" id="idTabla'.$i.'" value="'.$row['id'].'" /> 
                      <input type="hidden" class="form-control" id="idTrazaEstado'.$i.'" value="'.$row['idTrazaEstado'].'" /> 
                  </td>';*/
         $tabla.="<td>".$estado."</td>";
         $tabla.="</tr>";

         $i++;

     }

    $tabla.="</tbody>";        
    $tabla.="</table>";






    $html.= $tabla;

   
        $html.='<button class="btn btn-round btn-dark" id="informarBuscar" type="button" onclick="buscar_e_informar('.$idSolicitud.');" ><i class="fa fa-tasks" aria-hidden="true"></i>
        Informar Recepción</button> <br/>
        <br/>';
    }

    $html.="
           <legend>Productos Recepcionados</legend>";

 if($estadoActual==6){       
    $html.='<button class="btn btn-round btn-warning" id="habilitarDisp" onclick="habilitarDispensa('.$idSolicitud.')" type="button"  title="Habilitar Dispensa"><i class="fa fa-unlock" aria-hidden="true"></i>
           Habilitar Dispensa</button> <br/><br/>
            ';
 }     
 
     //Buscar los informados
     $param1['idSolicitud']=$idSolicitud;
     $param1['idEstado']='17,19,20';
     $res1= $objTraza -> SelectItemsTraza($param1);
              
    
    $tabla2='<div class="table-responsive"> <table class="table jambo_table" id="tablaRecepcionados">';
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