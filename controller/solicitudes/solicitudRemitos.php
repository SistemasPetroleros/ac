<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/usuario_permisos_estados.php';
include_once '../funciones.php';

$idSolicitud = isset($_POST['idSolicitud']) ? $_POST['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);

$resultado = $solicitud->getestado();
$row = mysqli_fetch_assoc($resultado);

$idEstadoActual = $row['idEstado'];


//PERMISOS DE ACCESO DEL USUARIO LOGEADO
$objPermisos = new usuario_permisos_estados();
$objPermisos->setidUsuario($_SESSION['idUsuario']);
$permisosUser = $objPermisos->SelectForUser();

$permisos = array();
while ($p = mysqli_fetch_assoc($permisosUser)) {
    array_push($permisos, $p['idEstado']);
}



$remitosTraza = $solicitud->SelectAllRemitosTraza();


while ($rowr = mysqli_fetch_assoc($remitosTraza)) {

    $data['nroRemito'] = $rowr['nroRemito'];
    $data['fechaRemito'] = $rowr['fechaRemito'];
    $data['obs'] = 'Remito agregado automáticamente por trazabilidad.';
    $data['esTrazado'] = 1;
    if (!$solicitud->existeRemito($data) and strlen($rowr['nroRemito']) > 3 ) {
        //inserto
        $r = $solicitud->addSolicitudRemito($data);
        if ($r) {
            if ($r) {
                $solicitud->addSolictudRemitoDocs($r);
            }
        }
    }
}


$resultado = $solicitud->SelectAllRemitos();

$html = '
<br/>
<div class="panel panel-default" >
     <div class="panel-heading" style="background-color:#4B5F71; color: #f8fbfb;"><b>Remitos Asociados a la Solicitud</b></div>
     <div class="panel-body">
  ';

$html .= '<form id="tremitos">  ';

if (esMiembro($idEstadoActual, $permisos) and in_array($idEstadoActual, ['6', '7', '8', '13', '14'])) {

    $html .= '
<button class="btn btn-round btn-primary"  onclick="agregarRemitoModal(\'\',' . $idSolicitud . ',\'I\')" type="button"  title="Agregar Remito"><i class="fa fa-plus" aria-hidden="true"></i>
Agregar Remito</button> ';

    $html .= '
<br/>
<br/>';
}





$tbody = "";
while ($row = mysqli_fetch_assoc($resultado)) {
    $tbody .= '<tr>';
    $tbody .= '<td> ';
    $tbody .= '<button type="button" class="btn btn-primary  btn-round" title="Editar" onclick="agregarRemitoModal(' . $row['id'] . ',\'' . $row['id_solicitud'] . '\',\'U\');" ><i class="fa fa-edit" aria-hidden="true"></i></button>';

    if (esMiembro($idEstadoActual, $permisos) and in_array($idEstadoActual, ['6', '7', '8', '13', '14'])) {
        $tbody .= '<button type="button" class="btn btn-danger  btn-round" title="Eliminar" onclick="eliminarRemito(' . $row['id'] . ',\'' . $row['id_solicitud'] . '\',\'' . $row['nroRemito'] . '\');" ><i class="fa fa-trash" aria-hidden="true"></i></button>';
    }
    $tbody .= '</td>';
    $tbody .= '<td>' . $row['nroRemito'] . '</td>';
    $tbody .= '<td>' . $row['fremito'] . '</td>';
    $tbody .= '<td>' . $row['observaciones'] . '</td>';
    $tbody .= '<td>' . $row['userAlta'] . '</td>';
    $tbody .= '<td>' . $row['falta'] . '</td>';
    $tbody .= '</tr>';
}

$tabla = '<table class="table jambo_table" id="tremitos">';
$tabla .= '<thead>
          <th class="column-title">Acción</th>
          <th class="column-title">Nro. Remito</th>
          <th class="column-title">Fecha</th>
          <th class="column-title">Observaciones</th>
          <th class="column-title">Usuario Alta</th>
          <th class="column-title">Fecha Alta</th>
      </thead>';
$tabla .= '<tbody id="tbodyrem">' . $tbody;
$tabla .= "</tbody>";
$tabla .= '</table> ';


$html .= $tabla . '
  </form>
</div>
 </div>
 <br/>';


echo $html;
