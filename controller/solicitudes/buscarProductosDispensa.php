<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../../model/solicitudes_items_traza_estado.php';
include_once '../funciones.php';


$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$resultado = $solicitud->getestado();
$row = mysqli_fetch_assoc($resultado);

$idEstadoActual = $row['idEstado'];


$html = '';

if ($idEstadoActual == 7 || $idEstadoActual == 8) {


    $objTraza = new SolicitudesItemTraza('');
    $objTrazaEstado = new SolicitudesItemTrazaEstados();

    $param['idSolicitud'] = $idSolicitud;
    $param['idEstado'] = '17,20,25';
    $res = $objTraza->SelectItemsTraza($param);


    $html = '
           <form id="formDispensa"   >
           <br/>';

    if ($idEstadoActual == 7) {

        $html .= '<legend>Productos a Dispensar</legend>';

        $html .= '
    <button type="button" class="btn btn-dark btn-round" onclick="seleccionarTodos(\'tablaDispensacion\');"><i class="fa fa-level-up" aria-hidden="true" ></i> Seleccionar Todos</button>
    <button type="button" class="btn btn-dark btn-round" onclick="deseleccionarTodos(\'tablaDispensacion\');"><i class="fa fa-level-down" aria-hidden="true" ></i> Deseleccionar Todos</button>

    <button type="button" class="btn btn-dark btn-round" onclick="abrirModalQR();"><i class="fa fa-qrcode" aria-hidden="true"></i> Seleccionar con QR</button>

    <button class="btn btn-round btn-success" id="informarDispensa" type="button" onclick="informarDispensas(' . $idSolicitud . ');" ><i class="fa fa-check" aria-hidden="true"></i>
    Informar Seleccionados</button> 

    <button class="btn btn-round btn-primary" type="button" onclick="sincronizarANMAT(\'D\');"><i class="fa fa-refresh" aria-hidden="true"></i> Sincronizar con ANMAT Dispensas Informadas</button>

   
    <br/>
    <br/>';

        //por cada item en la tabla solicitudes_items_traza, armar la tabla no recepcionados
        $tabla = '<div class="table-responsive"><table class="table jambo_table" id="tablaDispensacion">';
        $tabla .= "<thead>
                <th>¿Dispensa?</th>
                <th>Id.</th>
                <th>Estado</th>
                <th>Producto</th>
                <th>GTIN</th>
                <th>Nro. Serie</th>
                <th>Laboratorio</th>
                <th>Nro. Lote</th>
                <th>Fecha Venc.</th>
                <th>Fecha Remito</th>
                <th>Nro. Remito</th>
                <th>Id. Recepción ANMAT</th>
            </thead>";
        $tabla .= "<tbody>";

        $i = 0;
        while ($row = mysqli_fetch_assoc($res)) {


            $fechaV = "";
            if ($row['fechaVenc'] != null && $row['fechaVenc'] != "") {
                $fechaV = date('d/m/Y', strtotime($row['fechaVenc']));
            }

            $fechaR = "";
            if ($row['fechaRemito'] != null && $row['fechaRemito'] != "") {
                $fechaR = date('d/m/Y', strtotime($row['fechaRemito']));
            }

            if ($row['esTrazable'] == 1) {
                $esTrazable = 'SI';
            } else {
                $esTrazable = 'NO';
            }

            $estado = '<a href="#"  onclick="abrirEstados(' . $idSolicitud . ',\'' . $row['id_item'] . '\',' . $row['id'] . ');" id="popup_msg"><u><span class="badge badge-info">' . $row['estado'] . '</span></u></a>';

            $tabla .= "<tr>";

            $tabla .= '<td><input type="checkbox" class="form-check-input" id="check' . $row['nroSerie'] . '"  id="name' . $row['nroSerie'] . '" onclick="setInput(\'' . $row['nroSerie'] . '\');">
                    <input type="hidden" id="cheq' . $row['nroSerie'] . '" name="chequeado' . $i . '" readonly value="0" /></td>';
            $tabla .= '<td>' . $row['id'] . '</td>';
            $tabla .= "<td>" . $estado . "</td>";
            //   $tabla.='<td style="text-align: left;">'.$esTrazable.'</td>';
            $tabla .= "<td>" . utf8_encode($row['nombre']) . "</td>";
            $tabla .= '<td>' . $row['gtin'] . '</td>';
            $tabla .= '<td>' . $row['nroSerie'] . ' <input type="hidden" id="serial' . $i . '" name="serial' . $i . '" value="' . $row['nroSerie'] . '" /></td>';
            $tabla .= '<td>' . $row['laboratorio'] . '</td>';
            $tabla .= '<td>' . $row['lote'] . '</td>';
            $tabla .= '<td>' . $fechaV . '</td>';
            $tabla .= '<td>' . $fechaR . '</td>';
            $tabla .= '<td>' . $row['nroRemito'] . '</td>';
            $tabla .= '<td>' . $row['id_recepcion'] . '
        <input type="hidden" class="form-control" name="idTabla' . $i . '" id="idTabla' . $i . '" value="' . $row['id'] . '" /> 
        <input type="hidden" class="form-control" name="idTrazaEstado' . $i . '" id="idTrazaEstado' . $i . '" value="' . $row['idTrazaEstado'] . '" /> 
        </td>';

            $tabla2 .= "</tr>";

            $i++;
        }

        $tabla .= "</tbody>";
        $tabla .= '</table> 
      <input type="hidden" name="long" id="long" value="' . ($i) . '" />
    </div>';






        $html .= $tabla;
    }

    //Buscar los informados
    $param1['idSolicitud'] = $idSolicitud;
    $param1['idEstado'] = '19';
    $res1 = $objTraza->SelectItemsTraza($param1);

    $html .= "
           <legend>Productos Dispensados</legend>";

    if ($idEstadoActual == 7) {
    }

    if ($idEstadoActual == 8) {
        $html .= '<button class="btn btn-round btn-primary" id="cerrarDisp" onclick="imprimirDispensa(' . $idSolicitud . ')" type="button"  title="Imprimir Dispensa"><i class="fa fa-print" aria-hidden="true"></i>
           Reporte Dispensa</button> <br/><br/>
            ';
    }


    $tabla2 = '<div class="table-responsive"> <table class="table jambo_table" id="tablaDispensados">';
    $tabla2 .= "<thead>
                           <th>Acción</th>
                           <th>Id.</th>
                           <th>Estado</th>
                           <th>Producto</th>
                           <th>GTIN</th>
                           <th>Nro. Serie</th>
                           <th>Laboratorio</th>
                           <th>Nro. Lote</th>
                           <th>Fecha Venc.</th>
                           <th>Fecha Remito</th>
                           <th>Nro. Remito</th>
                           <th>Id. Recepción ANMAT</th>
                           <th>Id. Dispensa ANMAT</th>
                          
                   </thead>";
    $tabla2 .= "<tbody>";

    while ($rowi = mysqli_fetch_assoc($res1)) {



        $fechaV = "";
        if ($rowi['fechaVenc'] != null && $rowi['fechaVenc'] != "") {
            $fechaV = date('d/m/Y', strtotime($rowi['fechaVenc']));
        }

        $fechaR = "";
        if ($rowi['fechaRemito'] != null && $rowi['fechaRemito'] != "") {
            $fechaR = date('d/m/Y', strtotime($rowi['fechaRemito']));
        }

        if ($rowi['esTrazable'] == 1) {
            $esTrazable = 'SI';
        } else {
            $esTrazable = 'NO';
        }

        $estado = '<a href="#"  onclick="abrirEstados(' . $idSolicitud . ',\'' . $rowi['id_item'] . '\',' . $rowi['id'] . ');" id="popup_msg"><u><span class="badge badge-info">' . $rowi['estado'] . '</span></u></a>';

        $tabla2 .= "<tr>";

        if ($rowi['id_estado'] == 19 and $rowi['idEstadoS'] == 7) {
            $tabla2 .= '<td><button type="button" class="btn btn-danger  btn-round" title="Anular Transacción" onclick="anularDispensaANMAT(' . $rowi['id'] . ',\'' . $rowi['id_dispensa'] . '\');" ><i class="fa fa-times" aria-hidden="true"></i></button></td>';
        } else {
            $tabla2 .= '<td></td>';
        }

        $tabla2 .= '<td>' . $rowi['id'] . '</td>';
        $tabla2 .= "<td>" . $estado . "</td>";
        //$tabla2.='<td style="text-align: left;">'.$esTrazable.'</td>';
        $tabla2 .= "<td>" . utf8_encode($rowi['nombre']) . "</td>";
        $tabla2 .= '<td>' . $rowi['gtin'] . '</td>';
        $tabla2 .= '<td>' . $rowi['nroSerie'] . '</td>';
        $tabla2 .= '<td>' . $rowi['laboratorio'] . '</td>';
        $tabla2 .= '<td>' . $rowi['lote'] . '</td>';
        $tabla2 .= '<td>' . $fechaV . '</td>';
        $tabla2 .= '<td>' . $fechaR . '</td>';
        $tabla2 .= '<td>' . $rowi['nroRemito'] . '</td>';
        $tabla2 .= '<td>' . $rowi['id_recepcion'] . '</td>';
        $tabla2 .= '<td>' . $rowi['id_dispensa'] . '</td>';

        $tabla2 .= "</tr>";
    }



    $tabla2 .= "</tbody>";
    $tabla2 .= "</table> 
              </div>";

    $html .= $tabla2;

    $html .= ' <br/><br/><button class="btn btn-round btn-warning" id="cerrarDisp" onclick="cerrarDispensa(' . $idSolicitud . ')" type="button"  title="Finalizar Dispensa"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
           Finalizar Dispensa</button> 
           
           <button class="btn btn-round btn-info" id="revertir" type="button" onclick="habilitarRecepcion(' . $idSolicitud . ');" ><i class="fa fa-reply" aria-hidden="true"></i>
    Habilitar Recepción</button>
    
            ';



    $html .= '
          </form>';
}


echo $html;
