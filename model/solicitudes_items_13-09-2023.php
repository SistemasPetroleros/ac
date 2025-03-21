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