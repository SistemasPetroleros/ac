<?php
include_once '../config.php';
include_once '../../model/cotizacion_solic_prov.php';
include_once '../../model/solicitudes_items.php';

$objPr = new cotizacion_solic_prov();
$idSolicitud =$_POST['idSolicitud'];

$proveedores= $objPr->SelectAllCotizaciones($idSolicitud);

$faltaProv=1;
while($x=mysqli_fetch_array($proveedores))
{
    $faltaProv=0;
}	

if($faltaProv==1){
	echo 1; //Faltan cargar Proveedores
}
else
{
		
	$objITem = new solicitudes_items();
	$items=$objITem -> SelectSolicitudItems($idSolicitud);

	$faltaItem=1;
	while($y=mysqli_fetch_array($items))
	{
		$faltaItem=0;
		
	}	
	
	if($faltaItem==1){
	  echo 2; //Faltan cargar Productos
	}
	else
	{
		echo 3; //Todo OK
	}	


	
}