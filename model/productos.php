<?php
/******************************************************************************
* Class for ospepri_altocosto.productos
*******************************************************************************/

class productos
{
	/**
	* @var 
	* Class Unique ID
	*/
	private $id;

	/**
	* @var 
	*/
	private $nombre;

	/**
	* @var 
	*/
	private $presentacion;

	/**
	* @var 
	*/
	private $activo;

	/**
	* @var 
	*/
	private $usrAlta;

	/**
	* @var 
	*/
	private $fechaAlta;

	/**
	* @var 
	*/
	private $usrModif;

	/**
	* @var 
	*/
	private $fechaModif;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
Global $dblink;
		$query = "SELECT * FROM productos WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
		
	}

	public function SelectAll()
	{
Global $dblink;
		$query = "SELECT * FROM productos ";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}


	public function SelectForGtin($gtin)
	{
		Global $dblink;
		$query = "SELECT * FROM productos WHERE gtin='".$gtin."'";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function SelectAllItemBuscar($search)
	{
		Global $dblink;
		$query = "SELECT p.`id`, p.`nombre`, p.`presentacion`, p.`troquel`, p.`cod_droga`, p.`activo`, 
		p.`usrAlta`, p.`fechaAlta`, p.`usrModif`, p.`fechaModif`, ifnull(m.descripcion,'') as monodroga 
		FROM `productos` as p 
		left join ab_monodro m on m.codigo = p.cod_droga
		where p.activo=1 and p.nombre like'%".$search."%' or p.presentacion like '%".$search."%' or p.troquel like '".$search."'  or m.descripcion like '%".$search."%'  
        order by nombre";
		//echo $query;
		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
Global $dblink;
		$query = "UPDATE productos SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`presentacion` = '" . mysqli_real_escape_string($dblink,$this->getpresentacion()) . "',
						`activo` = '" . mysqli_real_escape_string($dblink,$this->getactivo()) . "',
						`usrAlta` = '" . mysqli_real_escape_string($dblink,$this->getusrAlta()) . "',
						`fechaAlta` = '" . mysqli_real_escape_string($dblink,$this->getfechaAlta()) . "',
						`usrModif` = '" . mysqli_real_escape_string($dblink,$this->getusrModif()) . "',
						`fechaModif` = '" . mysqli_real_escape_string($dblink,$this->getfechaModif()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

		
	}

	public function Create()
	{
Global $dblink;
		$query ="INSERT INTO productos (`nombre`,`presentacion`,`activo`,`usrAlta`,`fechaAlta`,`usrModif`,`fechaModif`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getpresentacion()) . "','" . mysqli_real_escape_string($dblink,$this->getactivo()) . "','" . mysqli_real_escape_string($dblink,$this->getusrAlta()) . "','" . mysqli_real_escape_string($dblink,$this->getfechaAlta()) . "','" . mysqli_real_escape_string($dblink,$this->getusrModif()) . "','" . mysqli_real_escape_string($dblink,$this->getfechaModif()) . "');";
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

	public function setnombre($nombre='')
	{
		$this->nombre = $nombre;
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
	}

	public function setpresentacion($presentacion='')
	{
		$this->presentacion = $presentacion;
		return true;
	}

	public function getpresentacion()
	{
		return $this->presentacion;
	}

	public function setactivo($activo='')
	{
		$this->activo = $activo;
		return true;
	}

	public function getactivo()
	{
		return $this->activo;
	}

	public function setusrAlta($usrAlta='')
	{
		$this->usrAlta = $usrAlta;
		return true;
	}

	public function getusrAlta()
	{
		return $this->usrAlta;
	}

	public function setfechaAlta($fechaAlta='')
	{
		$this->fechaAlta = $fechaAlta;
		return true;
	}

	public function getfechaAlta()
	{
		return $this->fechaAlta;
	}

	public function setusrModif($usrModif='')
	{
		$this->usrModif = $usrModif;
		return true;
	}

	public function getusrModif()
	{
		return $this->usrModif;
	}

	public function setfechaModif($fechaModif='')
	{
		$this->fechaModif = $fechaModif;
		return true;
	}

	public function getfechaModif()
	{
		return $this->fechaModif;
	}

} // END class productos