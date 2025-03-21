<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(0);
require_once '..\..\lib\mpdf\vendor\autoload.php';
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/solicitudes_items_traza.php';
include_once '../funciones.php';

ob_start();

$idSolicitud = isset($_GET['idSolicitud']) ? $_GET['idSolicitud'] : -1;
$solicitud = new Solicitudes($idSolicitud);
$persona = new personas($solicitud->getid_personas());
$pdispensa = new puntos_dispensa($solicitud->getid_puntos_dispensa());

$items = new SolicitudesItemTraza('');

$data['idSolicitud'] = $idSolicitud;
$data['idEstado'] = '19';
$resultadoReporte = $items->SelectItemsTraza($data);


?>



<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family:
serif; font-size: 9pt; color: #000088;">
	<tr>
		<td width="34%" style="text-align:center;"><img src="../../img/ospepri.png" width="200px" height="100px" /></td>
		<td width="83%" style="text-align:center;"> <span style="font-size:18pt;">Sistema de Medicamentos de Alto Costo</span></td>


	</tr>
</table>
<br>



<table width="100%">

	<tr>
		<td width="50%">
			<b>Agente de Salud:</b> (127901) OBRA SOCIAL DE PETROLEROS PRIVADOS
		</td>
	</tr>
	<tr>
		<td width="50%">
			<b>Farmacia:</b> <?php echo $pdispensa->getnombre(); ?>
		</td>
		<td width="50%">
			<b>GLN Farmacia:</b> <?php echo $pdispensa->getGLN(); ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
			<b>Evento:</b> <?php echo "(111) - DISPENSACION DEL PRODUCTO AL PACIENTE"; ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
			<b>Destino:</b> <?php echo "PACIENTE"; ?>
		</td>
	</tr>

	<tr>
		<td width="50%">
			<br>
		</td>
	</tr>
	<tr>
		<td width="50%">
			<b>Id. Solicitud Interna: </b> <?php echo $idSolicitud; ?>
		</td>
		<td width="50%">
			<b>Fecha: </b> <?php echo date('d/m/Y', strtotime($solicitud->getfecha())); ?>
		</td>

	</tr>


	<?php

	if ($persona->getesB24() == 1) {

		$beneficiario = $persona->getcodigoB24();
	} else {
		$beneficiario = $persona->getapellido() . ", " . $persona->getnombre();
	}

	?>


	<tr>
		<td width="50%">
			<b>Afiliado:</b> <?php echo $beneficiario; ?>
		</td>
		<td width="50%">
			<b>Nro. Afiliado:</b> <?php echo $persona->getdni(); ?>
		</td>
	</tr>



</table>

<br>
<br>


<?php
$trazables = "";
$noTrazables = "";

while ($row = @mysqli_fetch_assoc($resultadoReporte)) {


	$trazables .= ' <tr>
			 <td>' . $row['id_dispensa'] . '</td>
			 <td>' . $row['fechaD'] . '</td>
			 <td>' . $row['nroRemito'] . '</td>
			 <td>' . $row['fechaR'] . '</td>
			 <td>' . $row['nombrePr'] . '</td>
			 <td>' . $row['gtin'] . '</td>
			 <td>' . $row['nroSerie'] . '</td>
			 <td>' . $row['lote'] . '</td>
			 <td>' . $row['fechaV'] . '</td>
		  </tr>';
}

?>

<h3>Productos Trazados</h3>
<hr />
<table border="1" width="100%">
	<thead>
		<tr>
			<th bgcolor="#73879C">Nro. Transac. ANMAT</th>
			<th bgcolor="#73879C">Fecha y Hora</th>
			<th bgcolor="#73879C">Remito</th>
			<th bgcolor="#73879C">Fecha Remito</th>
			<th bgcolor="#73879C">Nombre</th>
			<th bgcolor="#73879C">GTIN</th>
			<th bgcolor="#73879C">Nro. Serie</th>
			<th bgcolor="#73879C">Nro.Lote</th>
			<th bgcolor="#73879C">Fecha Venc.</th>

		</tr>
	</thead>
	<tbody>
		<?php


		echo $trazables;

		?>

	</tbody>
</table>

<!-- 

<br>
<br>


<h3>Productos No Trazables</h3>
<hr/>
<table border="1" width="100%">
<thead >
      <tr>
	  
	  	<th  bgcolor="#73879C">Fechay Hora</th>	
		<th  bgcolor="#73879C">Remito</th>
		<th  bgcolor="#73879C">Fecha Remito</th>
		<th  bgcolor="#73879C">Nombre</th>
		<th  bgcolor="#73879C">Presentaci&oacute;n</th>
		<th  bgcolor="#73879C">GTIN</th>
		<th  bgcolor="#73879C">Nro. Serie</th>
		<th  bgcolor="#73879C">Nro.Lote</th>
		<th  bgcolor="#73879C">Fecha Venc.</th>		
								
	</tr>	
</thead>
	<tbody> 
	<?php


	//echo $noTrazables;

	?>
									     
	 </tbody>
</table>  -->

<br>
<br>
<hr />
<table width="100%">
	<tr>
		<td width="50%">[R.O. Social&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td>
		<td width="50%">[Conforme Beneficiario&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td>
	</tr>
</table>



<br />
<br />
<div>

</div>



<?php


date_default_timezone_set('America/Argentina/Buenos_Aires');
$html = ob_get_clean(); //Toma el codigo anterior
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L', 'default_font' => 'helvetica', 'default_font_size' => 9]); //Creo el pdf y lo formateo
$mpdf->SetHTMLHeader('
<div>
    <p style="text-align: right; font-size:9px;">Fecha de Impresi&oacute;n: ' . date('d/m/Y H:i') . '
</div>');
$mpdf->setFooter('{PAGENO}');

$mpdf->WriteHTML($html); //Escribo en el pdf el c�digo anterior

// Genera el fichero y fuerza la descarga
$mpdf->Output('reporteDispensa.pdf', 'D');
exit;



?>