<?php


date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(0);
session_start();
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../../model/puntos_dispensa.php';
require_once('../../lib/nusoap/nusoap.php');
include_once '../../model/solicitudes_items_traza_estado.php';


$error = "";

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$gtin = isset($_POST['gtin']) ? $_POST['gtin'] : '';
$remito = isset($_POST['remito']) ? $_POST['remito'] : '';
$serie = isset($_POST['serie']) ? $_POST['serie'] : '';


$solicitud = new Solicitudes($idSolicitud);

//Obtengo datos pto de dispensa
$idPtoDispensa = $solicitud->getid_puntos_dispensa();
$ptoDispensa = new puntos_dispensa($idPtoDispensa);
$glnOrigen = $ptoDispensa->getGLN();
$user = $ptoDispensa->getuserAnmat();
$pass = $ptoDispensa->getclaveAnmat();

//Testing
/*$wsdl = "https://servicios.pami.org.ar/trazamed.WebService";
$user = "pruebasws";
$pass = "Clave1234";
$glnOrigen = "glnws";*/


//Produccion
$wsdl = "https://trazabilidad.pami.org.ar:9050/trazamed.WebService";


$XmlSeguridad =  "Seguridad.xml";

$tipo = "SOAP";
$oSoap = new nusoap_client($wsdl, false);
$oSoap->setHeaders(file_get_contents($XmlSeguridad));


if ($oSoap->fault) {
    echo "Error: ";
    var_dump($oSoap);
}


$param = "	<arg0>" . $user . "</arg0>
            <arg1>" . $pass . "</arg1>
            <arg2>-1</arg2>
            <arg3></arg3>
            <arg4></arg4>
            <arg5></arg5>
            <arg6>" . $gtin . "</arg6>
            <arg7>-1</arg7>
            <arg8></arg8>
            <arg9></arg9>
            <arg10></arg10>
            <arg11></arg11>
            <arg12></arg12>
            <arg13></arg13>
            <arg14>" . $remito . "</arg14>
            <arg15></arg15>
            <arg16>-1</arg16>
            <arg17></arg17>
            <arg18>$serie</arg18>
            <arg19>1</arg19>
            <arg20>500</arg20>";

$param = "	<arg0>" . $user . "</arg0>
            <arg1>" . $pass . "</arg1>
            <arg2>-1</arg2>
            <arg3></arg3>
            <arg4></arg4>
            <arg5></arg5>
            <arg6>" . $gtin . "</arg6>
            <arg7>1</arg7>
            <arg8></arg8>
            <arg9></arg9>
            <arg10></arg10>
            <arg11></arg11>
            <arg12></arg12>
            <arg13></arg13>
            <arg14>" . $remito . "</arg14>
            <arg15></arg15>
            <arg16>-1</arg16>
            <arg17></arg17>
            <arg18>$serie</arg18>
            <arg19>1</arg19>
            <arg20>50</arg20>";

//print_r($param);

$oSoap->soap_defencoding = 'utf-8';
$oSoap->encode_utf8 = false;
$oSoap->decode_utf8 = false;
$result = $oSoap->call('getTransaccionesNoConfirmadas', $param);




if ($oSoap->fault) {
    echo 'Fallo';
    print_r($result);
} else {    // Chequea errores
    $err = $oSoap->getError();
    if ($err) {        // Muestra el error
        echo '<b>Error: </b>' . $err;
    } else {

        /* echo 'Resultado';
			print_r ($result);*/

        $lista = $result['list'];
        $longitud = count($lista);
        $filas = "";

        if (!array_key_exists('0', $lista) and $longitud != 0) {
            //cuando es un unico valor devuelto, no lo devuelve en una matriz
            $longitud = 1;
            $i = 0;
            $filas .= '<tr> 
                                  <td style="text-align: center;"><input type="checkbox" id="check' . $lista['_numero_serial'] . '" name="check' . $lista['_numero_serial'] . '"   class="form-check-input" onclick="setInput(\'' . $lista['_numero_serial'] . '\');" />
                                         <input type="hidden" id="cheq' . $lista['_numero_serial'] . '" name="chequeado' . $i . '" readonly value="0" /></td>
                                  <td> <input id="idTrans' . $i . '" name="idTrans' . $i . '" value="' . $lista['_id_transaccion'] . '" readonly style="border:0;background-color:transparent;"/></td></td>
                                  <td>' . $lista['_f_transaccion'] . '</td>
                                  <td>' . $lista['_id_transaccion_global'] . '</td>
                                  <td> <input id="fevento' . $i . '" name="fevento' . $i . '" value="' . $lista['_f_evento'] . '" readonly style="border:0;background-color:transparent; "/></td>
                                  <td>' . $lista['_id_evento'] . '</td>
                                  <td>' . $lista['_d_evento'] . '</td>
                                  <td>' . $lista['_gln_origen'] . '</td>
                                  <td>' . $lista['_razon_social_origen'] . ' <input type="hidden" id="laboratorio' . $i . '" name="laboratorio' . $i . '" value="' . $lista['_razon_social_origen'] . '" readonly /></td>
                                  <td>' . $lista['_gln_destino'] . '</td>
                                  <td>' . $lista['_gtin'] . '<input type="hidden" id="gtin' . $i . '" name="gtin' . $i . '" value="' . $lista['_gtin'] . '" readonly /></td>
                                  <td>' . $lista['_lote'] . '<input type="hidden" id="lote' . $i . '" name="lote' . $i . '" value="' . $lista['_lote'] . '" readonly /> </td>
                                  <td>' . $lista['_n_factura'] . '</td>
                                  <td>' . $lista['_n_remito'] . '<input type="hidden" value="' . $lista['_n_remito'] . '" name="remito' . $i . '" readonly id="remito' . $i . '" /> </td>
                                  <td><textarea id="nombre' . $i . '" name="nombre' . $i . '" readonly disabled="disabled" style="border:0;background-color:transparent;  ">' . $lista['_nombre'] . '</textarea>
                                    <input id="nombrem' . $i . '"  name="nombrem' . $i . '"  readonly type="hidden" value="' . $lista['_nombre'] . '"  ">
                                      </td>
                                  <td><input id="serial' . $i . '" name="serial' . $i . '" value="' . $lista['_numero_serial'] . '" readonly style="border:0;background-color:transparent;  padding: 10px; margin: 0px;"/></td>
                                  <td><textarea id="origen' . $i . '" name="origen' . $i . '"  readonly style="border:0;background-color:transparent;  padding: 10px; margin: 0px;"/>' . $lista['_razon_social_origen'] . '</textarea></td>
                                  <td>' . $lista['_razon_social_destino'] . '</td>
                                  <td>' . $lista['_vencimiento'] . ' <input type="hidden" value="' . $lista['_vencimiento'] . '" readonly id="vencimiento' . $i . '" name="vencimiento' . $i . '" /></td>
                               </tr>';
        } else {
            for ($i = 0; $i < $longitud; $i++) {
                $filas .= '<tr> 
                                  <td style="text-align: center;" ><input type="checkbox" class="form-check-input" id="check' . $lista[$i]['_numero_serial'] . '" name="check' . $lista[$i]['_numero_serial'] . '" onclick="setInput(\'' . $lista[$i]['_numero_serial'] . '\');"/>
                                        <input type="hidden" id="cheq' . $lista[$i]['_numero_serial'] . '" name="chequeado' . $i . '" readonly value="0" /></td>
                                  <td > <input id="idTrans' . $i . '" name="idTrans' . $i . '" value="' . $lista[$i]['_id_transaccion'] . '" readonly style="border:0;background-color:transparent; width:100%;"/></td>
                                  <td>' . $lista[$i]['_f_transaccion'] . '</td>
                                  <td>' . $lista[$i]['_id_transaccion_global'] . '</td>
                                  <td> <input id="fevento' . $i . '" name="fevento' . $i . '" value="' . $lista[$i]['_f_evento'] . '" readonly style="border:0;background-color:transparent;"/></td>
                                  <td>' . $lista[$i]['_id_evento'] . '</td>
                                  <td>' . $lista[$i]['_d_evento'] . '</td>
                                  <td>' . $lista[$i]['_gln_origen'] . '</td>
                                  <td>' . $lista[$i]['_razon_social_origen'] . ' <input type="hidden" id="laboratorio' . $i . '" name="laboratorio' . $i . '" value="' . $lista[$i]['_razon_social_origen'] . '" readonly /></td>
                                  <td>' . $lista[$i]['_gln_destino'] . '</td>
                                  <td>' . $lista[$i]['_gtin'] . '<input type="hidden" id="gtin' . $i . '" name="gtin' . $i . '" value="' . $lista[$i]['_gtin'] . '" readonly /> </td>
                                  <td>' . $lista[$i]['_lote'] . '<input type="hidden" id="lote' . $i . '" name="lote' . $i . '" value="' . $lista[$i]['_lote'] . '" readonly /> </td>
                                  <td>' . $lista[$i]['_n_factura'] . '</td>
                                  <td>' . $lista[$i]['_n_remito'] . '<input type="hidden" value="' . $lista[$i]['_n_remito'] . '" readonly id="remito' . $i . '" name="remito' . $i . '" /> </td>
                                  <td> <textarea id="nombre' . $i . '"  name="nombre' . $i . '"  readonly disabled="disabled" style="border:0;background-color:transparent;  ">' . $lista[$i]['_nombre'] . '</textarea>
                                  <input id="nombrem' . $i . '"  name="nombrem' . $i . '"  readonly type="hidden" value="' . $lista[$i]['_nombre'] . '"  "></td>
                                  <td> <input id="serial' . $i . '" name="serial' . $i . '" value="' . $lista[$i]['_numero_serial'] . '" readonly style="border:0;background-color:transparent;  padding: 10px; margin: 0px;"/></td>
                                  <td> <textarea id="origen' . $i . '"  name="origen' . $i . '"  readonly style="border:0;background-color:transparent;  padding: 10px; margin: 0px;"/>' . $lista[$i]['_razon_social_origen'] . '</textarea></td>
                                  <td>' . $lista[$i]['_razon_social_destino'] . '</td>
                                  <td>' . $lista[$i]['_vencimiento'] . '<input type="hidden" value="' . $lista[$i]['_vencimiento'] . '" readonly id="vencimiento' . $i . '" name="vencimiento' . $i . '"  /> </td>
                               </tr>';
            }
        }

        $tabla = ' 
      <input id="long" name="long"  type="hidden" value="' . $longitud . '" /> 
     <p>' . $longitud . ' registro(s) encontrado(s).</p> ';

        if ($longitud != 0) {
            $tabla .= '
                    <div class="form-group">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-dark btn-round" onclick="seleccionarTodos(\'tablaRecepcion\');"><i class="fa fa-level-up" aria-hidden="true" ></i> Seleccionar Todos</button>

                            <button type="button" class="btn btn-dark btn-round" onclick="deseleccionarTodos(\'tablaRecepcion\');"><i class="fa fa-level-down" aria-hidden="true" ></i> Deseleccionar Todos</button>

                            <button type="button" class="btn btn-dark btn-round" onclick="abrirModalQR();"><i class="fa fa-qrcode" aria-hidden="true"></i> Seleccionar con QR</button>

                            <button type="button" id="informarNCS" class="btn btn-success btn-round" onclick="informarSeleccionadosNC();"><i class="fa fa-check" aria-hidden="true"></i> Informar Seleccionados</button>
                        </div>
                    </div>';
        }
        $tabla .= '
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
        $tabla .= '<tbody id="tbodynr">' . $filas;

        $tabla .= "</tbody>";
        $tabla .= '</table>
                    ';


        $tabla .= '
              </div>
              ';
    }

    echo $tabla;
}
