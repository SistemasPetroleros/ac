<?php

include_once '../config.php';
include_once('../../model/solicitudes_items_traza.php');

$objTraza = new SolicitudesItemTraza('');

$param['idSolicitud']=$_POST['idSolicitud'];
$idSolicitud=$_POST['idSolicitud'];
$resultado = $objTraza->SelectItemsTraza($param);

$tbody="";

while($row=mysqli_fetch_assoc($resultado)){

 

    $idEstado=$row['id_estado'];
    $idEstadoS=$row['idEstadoSol'];
    
    $tbody.="<tr>";
    if($idEstado=="17"){
        $tbody.='<td><button type="button" class="btn btn-danger  btn-round" title="Anular Transacción" onclick="anularRecepcionANMAT('.$row['id'].',\''.$row['id_recepcion'].'\');" ><i class="fa fa-times" aria-hidden="true"></i></button></td>';
    }
    else{
        $tbody.='<td></td>';
    }
   
    $tbody.='<td> <a href="#" onclick="abrirEstados('.$idSolicitud.',\''.$row['id_item'].'\','.$row['id'].');" id="popup_msg"><u>'.$row['estado'].'</u></a></td>';
    $tbody.='<td>'.$row['nombre'].'</td>';
    $tbody.='<td>'.$row['gtin'].'</td>';
    $tbody.='<td>'.$row['nroSerie'].'</td>';
    $tbody.='<td>'.$row['laboratorio'].'</td>';
    $tbody.='<td>'.$row['lote'].'</td>';
    $tbody.='<td>'.$row['fechaV'].'</td>';
    $tbody.='<td>'.$row['nroRemito'].'</td>';
    $tbody.='<td>'.$row['fechaR'].'</td>';
    $tbody.='<td>'.$row['id_recepcion'].'</td>';
    $tbody.="</tr>";

}

$tabla = '
<table class="table jambo_table" id="trecepcion">';
$tabla .= '<thead>
                    <th class="column-title">Acción</th>
                    <th class="column-title">Estado</th>
                    <th class="column-title">Medicamento</th>
                    <th class="column-title">GTIN</th>
                    <th class="column-title">Nro. Serie</th>
                    <th class="column-title">Laboratorio</th>
                    <th class="column-title">Lote</th>
                    <th class="column-title">Vencimiento</th>
                    <th class="column-title">Remito</th>
                    <th class="column-title">Fecha Remito</th>     
                    <th class="column-title">Id. Recepción ANMAT</th>
                </thead>';
$tabla .= '<tbody id="tbodyr"> '.$tbody;

$tabla .= "</tbody>";
$tabla .= '</table> ';

echo $tabla;