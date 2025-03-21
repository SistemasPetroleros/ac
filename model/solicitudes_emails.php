<?php

/******************************************************************************
* Class for ospepri_altocosto.solicitudes_emails
*******************************************************************************/

class solicitudes_emails
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
	private $tipo;

			/**
	* @var 
	*/
	private $ok;

		/**
	* @var 
	*/
	private $descripcion;

	/**
	* @var 
	*/
	private $userAlta;

		/**
	* @var 
	*/

	private $fechaAlta;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM solicitudes_emails WHERE `id`='{$this->getid()}'";

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


		$query = "SELECT *, DATE_FORMAT(fechaAlta, '%d/%m/%Y %H:%i:%s') fAlta FROM solicitudes_emails WHERE id_solicitudes='".mysqli_real_escape_string($dblink,$this->getid_solicitudes())."' AND tipo='".mysqli_real_escape_string($dblink,$this->gettipo())."' ORDER BY fechaAlta";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO solicitudes_emails (`id_solicitudes`,`tipo`, `ok`, `descripcion`, `userAlta`) 
                 VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) . "','"
                            . mysqli_real_escape_string($dblink,$this->gettipo())."','"
                            . mysqli_real_escape_string($dblink,$this->getok())."','"
                            . mysqli_real_escape_string($dblink,$this->getdescripcion())."','"
                            .$_SESSION["user"]."');";
		
        mysqli_query($dblink,$query);

		
	}


	public function ultimoRegistro()
	{
		Global $dblink;


		$query = "  SELECT *
					FROM solicitudes_emails se
					WHERE se.id_solicitudes='".mysqli_real_escape_string($dblink,$this->getid_solicitudes())."' AND fechaAlta IN (
							SELECT MAX(se.fechaAlta)
							FROM solicitudes_emails se
							WHERE se.id_solicitudes='".mysqli_real_escape_string($dblink,$this->getid_solicitudes())."')";

		$result = mysqli_query($dblink,$query);

		
	return $result;

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

	public function settipo($tipo='')
	{
		$this->tipo = $tipo;
		return true;
	}

	public function gettipo()
	{
		return $this->tipo;
	}

    
	public function setok($ok='')
	{
		$this->ok = $ok;
		return true;
	}

	public function getok()
	{
		return $this->ok;
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

	

} // END class tipo_solicitud