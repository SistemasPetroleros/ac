<?php
/******************************************************************************
* Class for ospepri_altocosto.productos
*******************************************************************************/

class materiales_productos
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
	private $descripcion;

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
		$query = "SELECT * FROM materiales_productos WHERE `id`='{$this->getid()}'";


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
		$query = "SELECT * FROM materiales_productos ";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}


	public function SelectForGtin($gtin)
	{
		Global $dblink;
		$query = "SELECT * FROM materiales_productos WHERE gtin='".$gtin."'";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function SelectAllItemBuscar($search)
	{
		Global $dblink;
		$query = "SELECT p.`id`, p.`nombre`, p.`descripcion`, p.`activo`, 
		p.`usrAlta`, p.`fechaAlta`, p.`usrModif`, p.`fechaModif`
		FROM `materiales_productos` as p 
		where p.activo=1 and p.nombre like'%".$search."%'  or p.descripcion like '%".$search."%'  
        order by nombre";
	//	echo $query;
		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
Global $dblink;
		$query = "UPDATE materiales_productos SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "',
						`activo` = '" . mysqli_real_escape_string($dblink,$this->getactivo()) . "',
						`usrModif` = '" . mysqli_real_escape_string($dblink,$this->getusrModif()) . "',
						`fechaModif` = now() 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

		
	}

	public function Create()
	{
Global $dblink;
		$query ="INSERT INTO materiales_productos (`nombre`,`descripcion`,`activo`,`usrAlta`,`fechaAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "','" . mysqli_real_escape_string($dblink,$this->getactivo()) . "','" . mysqli_real_escape_string($dblink,$this->getusrAlta()) . "', now());";
		mysqli_query($dblink,$query);

		
	}


	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM materiales_productos 
						WHERE `id`='{$this->getid()}'";
                                                
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

	public function setdescripcion($descripcion='')
	{
		$this->descripcion = $descripcion;
		return true;
	}

	public function getdescripcion()
	{
		return $this->descripcion;
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