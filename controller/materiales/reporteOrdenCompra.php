<?php


$idSolicitud = isset($_GET['idSolicitud']) ? $_GET['idSolicitud'] : @$idSolicitud;
$idProveedor = isset($_GET['idProveedor']) ? $_GET['idProveedor'] : @$idProveedor;


$solicitud = new materiales_solicitudes($idSolicitud);

$cotizacion = new materiales_cotizacion_solic_prov();
$cotizacion->setid_proveedores($idProveedor);
$cotizacion->setid_solicitudes($idSolicitud);

$resultadoCot = $cotizacion->getCotizacion();
if ($rowCot = mysqli_fetch_assoc($resultadoCot)) {
    $condicionPago = $rowCot['condiciones_pago'];
}

$items = new materiales_cotizacion_item();

//Busco todos los items aprobados del provevedor
$resultadoItems = $items->getItemsCotizacionPorEstado($idSolicitud, '46', $idProveedor);



$proveedor = new proveedores();

$resultadoProv = $proveedor->getProveedor($idProveedor);
$domicilio = "";
$cuit = "";
$prestador = "";
$iibb = "";

if ($rowp = mysqli_fetch_assoc($resultadoProv)) {
    $domicilio = $rowp['domicilio'] . ' - ' . $rowp['ciudad'] . ' (' . $rowp['provincia'] . ')';
    $cuit = $rowp['cuit'];
    $prestador = $rowp['nombre'];
    $iibb = $rowp['ingresosBrutos'];
}


$persona = new personas($solicitud->getid_personas());
$ptoDispensa = new puntos_dispensa($solicitud->getid_puntos_dispensa());


ob_start();



?>



<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom;  font-size: 9pt; ">
    <tr>
        <td width="34%" style="text-align:left;"><img src="../../img/ospepri.png" width="150px" height="100px" /></td>


    </tr>
    <tr>
        <td width="34%" style="text-align:left;">
            <p style="font-size: 10px;">Santa Cruz 255 </p>
            <p style="font-size: 10px;">Neuquén Capital (CP 8300) </p>
            <p style="font-size: 10px;">Tel:(0299) 4470806 Int: 158 </p>
            <p style="font-size: 10px;">E-mail: altacomplejidad@ospepri.org.ar </p>
            <p style="font-size: 10px;">CUIT: 30-71069148-3 </p>
        </td>
        <td width="83%" style="text-align:left;"> <span style="font-size:18pt;">Orden de Compra N° <?= $idSolicitud ?></span></td>
    </tr>
</table>
<br>


<table border="0">
    <tr>
        <td width="34%" style="text-align:left;"><b>Fecha:</b> <?= date('d/m/Y') ?> </td>
    </tr>
    <tr>
        <td width="34%" style="text-align:left;"><b>Prestador:</b> <?= $prestador ?> </td>
    </tr>
    <tr>
        <td width="34%" style="text-align:left;"><b>Domicilio:</b> <?= $domicilio; ?></td>
    </tr>

    <tr>
        <td width="34%" style="text-align:left;"><b>CUIT:</b> <?= $cuit; ?> </td>
    </tr>

    <tr>
        <td width="34%" style="text-align:left;"><b>IIBB:</b> <?= $iibb; ?> </td>
    </tr>

</table>
<br />
<br />

<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom;  font-size: 9pt; ">
    <tr>
        <td>
            <h3>Detalle de Productos</h3>
        </td>
    </tr>
</table>

<br />
<br />

<table border="1" width="100%">
    <thead>
        <tr>
            <th bgcolor="#73879C" style="text-align:center;">Cantidad</th>
            <th bgcolor="#73879C" style="text-align:center;">Unidad</th>
            <th bgcolor="#73879C">Descripción</th>
            <th bgcolor="#73879C" style="text-align:center;">Precio Unit.</th>
            <th bgcolor="#73879C" style="text-align:center;">Precio Neto</th>
        </tr>
    </thead>
    <tbody>
        <?php


        $total = 0;
        while ($x = mysqli_fetch_assoc($resultadoItems)) {

            $total = $total + $x['totalAprob'];

            echo '<tr class="odd gradeX">';
            echo '<td style="text-align:center;">' . $x['cantidadAprob'] . '</td>';
            echo '<td style="text-align:center;"> UN </td>';
            echo '<td>' .  $x['nombre'] . '</td>';
            echo '<td style="text-align:center;">' . number_format($x['importe_unitario'], 2, ",", ".") . '</td> ';
            echo '<td style="text-align:center;">' . number_format($x['totalAprob'], 2, ",", ".") . '</td> ';
            echo "</tr>";
        }
        ?>


    </tbody>
</table>
<br>
<table border="0" width="100%" style="text-align: right;">
    <tr>
        <td width="100%"><b>Importe Total (ARS):</b> <?= '$' . number_format($total, 2, ",", ".") ?></td>
    </tr>
</table>

<br />
<br />

<?php $total = number_format($total, 2, ".", "") ?>

<table border="0" width="100%" style="text-align: left;">
    <tr>
        <td width="100%"><b><?= 'Son: ' . number_words($total, "pesos", "con", "centavos") ?></b></td>
    </tr>
</table>

<br />
<br />

<table border="0" width="100%" style="text-align: left;">
    <tr>
        <td width="100%"><b>Los montos están expresados en pesos e incluyen el Impuesto al Valor Agregado.</b></td>
    </tr>
</table>

<br />
<br />






<table border="0">
    <tr>
        <td width="100%" style="text-align:left;"><b>Beneficiario:</b> <?= $persona->getapellido() . ', ' . $persona->getnombre() . ' - DNI: ' . $persona->getdni() ?> </td>
    </tr>
    <tr>
        <td width="100%" style="text-align:left;"><b>Lugar de Entrega:</b> <?= $ptoDispensa->getnombre() ?> </td>
    </tr>
    <tr>
        <td width="%" style="text-align:left;"><b>Condición de Pago:</b> <?= $condicionPago ?></td>
    </tr>


</table>
<br />
<br />
<br />
<br />


<table border="0">
    <tr>
        <td width="100%" style="text-align:justify;">
            <p><b>Nota:</b> Las mercaderías enviadas que no se atengan a las condiciones especificadas, serán devueltas y no podrán ser sustituidas salvo contra recibo de instrucciones por escrito. Las Facturas y Remitos deberán indicar el N° de Orden de Compra. Las Facturas electrónicas, deben ser enviadas al siguiente mail: <a href="mailto:altacomplejidad@ospepri.org.ar">altacomplejidad@ospepri.org.ar</a>.</p>
        </td>
    </tr>
    <tr>


</table>


<?php


$html = ob_get_clean(); //Toma el codigo anterior
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'legal', 'orientation' => 'P', 'default_font' => 'helvetica', 'default_font_size' => 9]); //Creo el pdf y lo formateo
/*$mpdf->SetHTMLHeader('
<div>
    <p style="text-align: right; font-size:9px;">Fecha de Impresi&oacute;n: ' . date('d/m/Y H:i') . '
</div>');*/
$mpdf->setFooter('{PAGENO}');

$mpdf->WriteHTML($html); //Escribo en el pdf el c�digo anterior

// Genera el fichero y fuerza la descarga
$fecha_actual = date('YmdHis');
//$mpdf->Output('reporte_' . $fecha_actual . '.pdf', 'D');
$ruta = 'C:/ArchivosAc/file_manager/adjuntosMateriales/' . $idSolicitud . '/';
$mpdf->Output($ruta .'Privado-'.$idSolicitud . '-' . $idProveedor . '-ospepri_orden_compra.pdf', 'F'); //creo archivo en la ruta indicada
//$mpdf->Output('ospepri_orden_compra_' . $idSolicitud . '.pdf', 'I');
