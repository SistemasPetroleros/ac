<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_estados.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../../model/solicitudes_items_traza_estado.php';
include_once '../funciones.php';


//PERMISOS DE ACCESO DEL USUARIO LOGEADO
$objPermisos = new usuario_permisos_estados();
$objPermisos->setidUsuario($_SESSION['idUsuario']);
$permisosUser = $objPermisos->SelectForUser();

$permisos = array();
while ($p = mysqli_fetch_assoc($permisosUser)) {
    array_push($permisos, $p['idEstado']);
}



$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$resultado = $solicitud->getestado();
$row = mysqli_fetch_assoc($resultado);

$idEstadoActual = $row['idEstado'];


$html = '';

if ($idEstadoActual == 6) {


    if (esMiembro("6", $permisos)) {



        $html = '
<br/>
<div class="panel panel-default" >
     <div class="panel-heading" style="background-color:#4B5F71; color: #f8fbfb;"><b>Búsqueda ANMAT</b></div>
     <div class="panel-body">
  ';



        $html .= '
<form>
   <div id="formBusqueda" >
 
     
         
    <div class="form-group">';

        $html .= '<div class="col-sm-4">';
        $html .= ' <label>#Serie</label>
                <input id="serieb" name="serieb"  class="form-control" type="text" />';
        $html .= '</div>';

        $html .= '<div class="col-sm-4">';
        $html .= ' <label>#Remito</label>
                <input id="remitob" name="remitob"  class="form-control" type="text" />';
        $html .= '</div>';


        $html .= '<div class="col-sm-4">';
        $html .= ' <br/><button type="button" class="btn btn-dark btn-round" onclick="buscarNoConfirmados();"><i class="fa fa-search" aria-hidden="true"></i>Buscar</button>';
        $html .= '</div>';

        $html .= '</div> 
       </div>
   </form>';

        $html .= '<br/>';

        $html .= '  <form id="tresultado">
<legend>Lista de Transacciones No Confirmadas</legend>';

        $tabla = ' 
  <div style="overflow-x:scroll;" id="tablaNR">
 
  <table class="table jambo_table" id="tablaRecepcion">';
        $tabla .= '<thead>
            <th class="column-title">¿Traza?</th>
            <th class="column-title">Id. Transacción</th>
            <th class="column-title">Fecha y Hora Transac.</th>
            <th class="column-title">Id. Transacción Global</th>
            <th class="column-title">Fecha Evento</th>
            <th class="column-title">Id. Evento</th>
            <th class="column-title">Descrip. Evento</th>
            <th class="column-title">GLN Origen</th>
            <th class="column-title">Laboratorio</th>
            <th class="column-title">GLN Destino</th>
            <th class="column-title">GTIN</th>
            <th class="column-title">Lote</th>
            <th class="column-title">Nro. Factura</th>
            <th class="column-title">Nro. Remito</th>
            <th class="column-title">Medicamento</th>
            <th class="column-title">Nro. Serie</th>
            <th class="column-title">Razón Social Origen</th>
            <th class="column-title">Razón Social Destino</th>
            <th class="column-title">Vencimiento</th>
        </thead>';
        $tabla .= '<tbody id="tbodynr">';

        $tabla .= "</tbody>";
        $tabla .= '</table> ';


        $tabla .= '  
              </div>
              </form>
              ';

        $html .= $tabla;
    }

    $objTraza = new SolicitudesItemTraza('');

    $param['idSolicitud'] = $_POST['idSolicitud'];
    $idSolicitud = $_POST['idSolicitud'];
    $resultado = $objTraza->SelectItemsTraza($param);

    $tbody = "";


    $html .= '
       </div>
        </div>
        <br/>
        <legend>Lista de Productos Informados</legend> ';
    if (esMiembro("6", $permisos)) {

        $html .= '       

                       <button class="btn btn-round btn-warning" id="habilitarDisp" onclick="habilitarDispensa(' . $idSolicitud . ')" type="button"  title="Habilitar Dispensa"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
                       Finalizar Recepción</button> 
                       <br/>
                       <br/>

        ';
    }

    $html .= '  <form id="trecep">';


    $mostrar = 'N';
    while ($row = mysqli_fetch_assoc($resultado)) {



        $idEstado = $row['id_estado'];
        $idEstadoS = $row['idEstadoSol'];

        $tbody .= "<tr>";
        if ($idEstado == "16") {
            $mostrar = 'S';
        }
        //or $idEstado == "16")
        if (($idEstado == "17" or $idEstado == "25") and esMiembro("6", $permisos)) {
            $tbody .= '<td><button type="button" class="btn btn-danger  btn-round" title="Anular Transacción" onclick="anularRecepcionANMAT(' . $row['id'] . ',\'' . $row['id_recepcion'] . '\');" ><i class="fa fa-times" aria-hidden="true"></i></button></td>';
        } else {
            $tbody .= '<td></td>';
        }

        $tbody .= '<td>  <a href="#" onclick="abrirEstados(' . $idSolicitud . ',\'' . $row['id_item'] . '\',' . $row['id'] . ');" id="popup_msg"><u><span class="badge badge-success">' . $row['estado'] . '</span></u></a> </td> ';
        $tbody .= '<td>' . $row['nombrePr'] . '</td>';
        $tbody .= '<td>' . $row['gtin'] . '</td>';
        $tbody .= '<td>' . $row['nroSerie'] . '</td>';
        $tbody .= '<td>' . $row['laboratorio'] . '</td>';
        $tbody .= '<td>' . $row['lote'] . '</td>';
        $tbody .= '<td>' . $row['fechaV'] . '</td>';
        $tbody .= '<td>' . $row['nroRemito'] . '</td>';
        $tbody .= '<td>' . $row['fechaR'] . '</td>';
        $tbody .= '<td>' . $row['id_recepcion'] . '</td>';
        $tbody .= "</tr>";
    }

    $tabla = ' 
    <small><b>Referencia de Estados:</b> <br/>
          <ul>
               <li>RECEPCIONADO: Medicamento informado correctamente.</li>
               <li>PENDIENTE: Medicamento informado con errores, volver a informar. Verifique detalle de errores haciendo clic en la columna estado.</li>
          </ul> </small> ';



    if ($mostrar == 'S') {
        $tabla .= ' <button class="btn btn-round btn-primary" type="button" onclick="sincronizarANMAT(\'R\');"><i class="fa fa-refresh" aria-hidden="true"></i> Sincronizar con ANMAT Recepciones (Informadas)</button>';
    }

    $tabla .= '     
      <br/><br/><br/>
          <div style="overflow-x:scroll;" id="tablaR">
         
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
    $tabla .= '<tbody id="tbodyr">' . $tbody;

    $tabla .= "</tbody>";
    $tabla .= '</table> ';


    $tabla .= '  
                      </div>
                      </form>
                       
                      ';

    $html .= $tabla;
} else {


    if ($idEstadoActual == 7 || $idEstadoActual == 8 || $idEstadoActual == 13 || $idEstadoActual == 14 || $idEstadoActual == 15 || $idEstadoActual == 23 || $idEstadoActual == 29 || $idEstadoActual == 30) {




        $objTraza = new SolicitudesItemTraza('');
        $objTrazaEstado = new SolicitudesItemTrazaEstados();

        $param['idSolicitud'] = $idSolicitud;
        $param['idEstado'] = '17,20,25';
        $res = $objTraza->SelectItemsTraza($param);


        if (esMiembro("7", $permisos)) {

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

                    $estado = ' <a href="#"  onclick="abrirEstados(' . $idSolicitud . ',\'' . $row['id_item'] . '\',' . $row['id'] . ');" id="popup_msg"><u> <span class="badge badge-success">' . $row['estado'] . '</span></u></a> ';

                    $tabla .= "<tr>";

                    $tabla .= '<td><input type="checkbox" class="form-check-input" id="check' . $row['nroSerie'] . '"  id="name' . $row['nroSerie'] . '" onclick="setInput(\'' . $row['nroSerie'] . '\');">
                        <input type="hidden" id="cheq' . $row['nroSerie'] . '" name="chequeado' . $i . '" readonly value="0" /></td>';
                    $tabla .= '<td>' . $row['id'] . '</td>';
                    $tabla .= "<td>" . $estado . "</td>";
                    //   $tabla.='<td style="text-align: left;">'.$esTrazable.'</td>';
                    $tabla .= "<td>" . utf8_encode($row['nombrePr']) . "</td>";
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
        }

        //Buscar los informados
        $param1['idSolicitud'] = $idSolicitud;
        $param1['idEstado'] = '19';
        $res1 = $objTraza->SelectItemsTraza($param1);

        $html .= "
              <br/>
               <legend>Productos Dispensados</legend>";



        if (in_array($idEstadoActual, ['8', '13', '14', '15', '23', '29', '30'])) {
            $html .= '<button class="btn btn-round btn-primary" id="cerrarDisp" onclick="imprimirDispensa(' . $idSolicitud . ')" type="button"  title="Imprimir Dispensa"><i class="fa fa-print" aria-hidden="true"></i>
               Reporte Dispensa</button> 
                ';

            if ($idEstadoActual != '13' and $idEstadoActual != '8') $html .= '<br/><br/>';
        }


        if (esMiembro("8", $permisos) and in_array($idEstadoActual, ['8', '13'])) {
            $html .= '<button class="btn btn-round btn-warning" id="revertirDisp" onclick="revertirEstado(' . $idSolicitud . ')" type="button"  title="Revertir Estado"><i class="fa fa-refresh" aria-hidden="true"></i>
               Revertir Estado</button> <br/><br/>
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

            $estado = ' <a href="#"  onclick="abrirEstados(' . $idSolicitud . ',\'' . $rowi['id_item'] . '\',' . $rowi['id'] . ');" id="popup_msg"><u><span class="badge badge-success">' . $rowi['estado'] . '</span></u></a>
            </button>';

            $tabla2 .= "<tr>";


            if ($rowi['id_estado'] == 19 and $rowi['idEstadoS'] == 7 and esMiembro("6", $permisos)) {
                $tabla2 .= '<td><button type="button" class="btn btn-danger  btn-round" title="Anular Transacción" onclick="anularDispensaANMAT(' . $rowi['id'] . ',\'' . $rowi['id_dispensa'] . '\');" ><i class="fa fa-times" aria-hidden="true"></i></button></td>';
            } else {
                $tabla2 .= '<td></td>';
            }


            $tabla2 .= '<td>' . $rowi['id'] . '</td>';
            $tabla2 .= "<td>" . $estado . "</td>";
            //$tabla2.='<td style="text-align: left;">'.$esTrazable.'</td>';
            $tabla2 .= "<td>" . utf8_encode($rowi['nombrePr']) . "</td>";
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


        if ($idEstadoActual == 7 and esMiembro("6", $permisos)) {



            $html .= ' <br/><br/><button class="btn btn-round btn-warning" id="cerrarDisp" onclick="cerrarDispensa(' . $idSolicitud . ')" type="button"  title="Finalizar Dispensa"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
               Finalizar Dispensa</button> 
               
               <button class="btn btn-round btn-info" id="revertir" type="button" onclick="habilitarRecepcion(' . $idSolicitud . ');" ><i class="fa fa-reply" aria-hidden="true"></i>
        Habilitar Recepción</button>
        
                ';
        }

        $html .= '
              </form>';
    }
}


echo $html;
