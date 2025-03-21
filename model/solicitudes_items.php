<?php
/******************************************************************************
* Class for ospepri_altocosto.solicitudes_items
*******************************************************************************/

class solicitudes_items
{
	/**
	* @var 
	* Class Unique ID
	*/
	private $id;

	/**
	* @var 
	*/
	private $id_solicitudes;

	/**
	* @var 
	*/
	private $id_producto;

	/**
	* @var 
	*/
	private $cantidad;

	/**
	* @var 
	*/
	private $observaciones;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM solicitudes_items WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
		
	}


	public function SelectAll($len=1000000,$start=0, $search='', $order='1', $direccion='asc')
	{
		Global $dblink;
		$query = "SELECT si.id, si.observaciones, si.cantidad, p.nombre, p.presentacion, p.troquel, ifnull(m.descripcion,'') as monodroga FROM solicitudes_items si 
		inner join productos p on p.id=si.id_producto
		left join ab_monodro m on m.codigo=p.cod_droga
		where (p.nombre like '%".$search."%' or p.presentacion like '%".$search."%') and si.id_solicitudes=" . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) . " 
		order by ".$order." ".$direccion." limit ".$start.", ".$len;
		//echo $query;
		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function SelectAllCotizacion( $idProveedor='',$len=1000000,$start=0, $search='', $order='1', $direccion='asc')
	{
		Global $dblink;

		/*$where='';
		if($idEstado==11){
              $where.=" and ci.id_estados=11 ";
		}*/

		$query = "SELECT DISTINCT si.id, si.observaciones, si.cantidad, p.nombre, p.presentacion, p.troquel, ifnull(m.descripcion,'') as monodroga, ci.id idItemCot, ci.id_proveedores, 
		ifnull(ci.importe_unitario,0) importe_unitario, ifnull(ci.cantidad,si.cantidad) cantCotizada, ci.marca, ci.id_estados, e.nombre STATUS, ifnull(ci.cantidadAprob,0) cantidadAprob, 
		IFNULL(ci.cantidad,si.cantidad) * IFNULL(ci.importe_unitario,0) total 
FROM solicitudes_items si 
	INNER JOIN productos p on p.id=si.id_producto 
	LEFT JOIN ab_monodro m on m.codigo=p.cod_droga 
	LEFT JOIN cotizacion_solic_prov csp ON csp.id_solicitudes=si.id_solicitudes
	LEFT JOIN cotizacion_items ci ON ci.id_item=si.id AND csp.id_proveedores=ci.id_proveedores
	LEFT  JOIN estados e ON e.id=ci.id_estados 
where (p.nombre like '%%' or p.presentacion like '%%') and si.id_solicitudes=".mysqli_real_escape_string($dblink,$this->getid_solicitudes())." AND csp.id_proveedores=".$idProveedor."
AND (((ci.id_estados=39 OR ci.id_estados IS NULL) AND NOT EXISTS (SELECT ci3.id_item FROM cotizacion_items ci3 inner join solicitudes_items si3 ON si3.id=ci3.id_item WHERE csp.id_proveedores=ci3.id_proveedores AND si3.id_solicitudes = si.id_solicitudes AND ci3.id_estados=41)) OR (ci.id_estados=41 AND si.id IN (SELECT ci.id_item FROM cotizacion_items ci2 WHERE csp.id_proveedores=ci2.id_proveedores AND ci2.id_estados IN (41,39)))) 
order by 1 asc limit 0, 1000000 " ;
	//	order by ".$order." ".$direccion." limit ".$start.", ".$len;
	//  echo $query;
		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function SelectSolicitudItems($idSolicitud)
	{
		Global $dblink;
		$query = "  SELECT si.id, si.id_solicitudes, si.id_producto, si.cantidad, p.nombre, p.presentacion, si.observaciones
					FROM solicitudes_items si
						INNER JOIN productos p ON si.id_producto=p.id
					WHERE si.id_solicitudes=".$idSolicitud;
		//echo $query;
		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function Cnt($search)
	{
		Global $dblink;
		$query = "SELECT count(1) as recordsFiltered, count(1) as recordsTotal FROM solicitudes_items i inner join productos p on p.id=i.id_producto
                          where (p.nombre like '%".$search."%' or p.presentacion like '%".$search."%') and i.`id_solicitudes` = " . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) ; 

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE solicitudes_items SET 
						`id_solicitudes` = '" . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) . "',
						`id_producto` = '" . mysqli_real_escape_string($dblink,$this->getid_producto()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

		
	}

	public function Delete()
	{
		Global $dblink;
		$query = "delete from solicitudes_items WHERE `id`='{$this->getid()}' and `id_solicitudes` = " . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) ;
		//echo $query;
		mysqli_query($dblink,$query);
	}


	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO solicitudes_items (`id_solicitudes`,`id_producto`,`cantidad`,`observaciones`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink,$this->getid_producto()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "');";
		mysqli_query($dblink,$query);

		
	}

	public function setid($id='')
	{
		$this->id = $id;
		return true;
	}

	public function getid()
	{
		return $this->id;
	}

	public function setid_solicitudes($id_solicitudes='')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setid_producto($id_producto='')
	{
		$this->id_producto = $id_producto;
		return true;
	}

	public function getid_producto()
	{
		return $this->id_producto;
	}

	public function setcantidad($cantidad='')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
	}

	public function setobservaciones($observaciones='')
	{
		$this->observaciones = $observaciones;
		return true;
	}

	public function getobservaciones()
	{
		return $this->observaciones;
	}

} // END class solicitudes_items