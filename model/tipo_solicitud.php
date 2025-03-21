<?php

/******************************************************************************
* Class for ospepri_altocosto.tipo_solicitud
*******************************************************************************/

class tipo_solicitud
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
	private $seccion;

			/**
	* @var 
	*/
	private $userAlta;

		/**
	* @var 
	*/
	private $fechaAlta;

	/**
	* @var 
	*/
	private $userModif;

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
		$query = "SELECT * FROM tipo_solicitud WHERE `id`='{$this->getid()}'";

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

		$where="";
		if($this->getseccion()!="") 
		   $where.=" AND seccion='".mysqli_real_escape_string($dblink,$this->getseccion())."'"; 
		$query = "SELECT * FROM tipo_solicitud WHERE (1=1) ".$where;

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE tipo_solicitud SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`userModif` = '" . $_SESSION['user'] . "',
						`fechaModif` = now() 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

		
	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO tipo_solicitud (`nombre`,`userAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','".$_SESSION["user"]."');";

		//echo $query;
		mysqli_query($dblink,$query);

		
	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM tipo_solicitud
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

	public function setseccion($seccion='')
	{
		$this->seccion = $seccion;
		return true;
	}

	public function getseccion()
	{
		return $this->seccion;
	}

	public function setuserAlta($userAlta='')
	{
		$this->userAlta = $userAlta;
		return true;
	}

	public function getuserAlta()
	{
		return $this->userAlta;
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

	public function setuserModif($userModif='')
	{
		$this->userModif = $userModif;
		return true;
	}

	public function getuserModif()
	{
		return $this->userModif;
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

} // END class tipo_solicitud