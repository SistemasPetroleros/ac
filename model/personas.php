<?php

/******************************************************************************
 * Class for ospepri_altocosto.personas
 *******************************************************************************/

class personas
{
	/**
	 * @var 
	 * Class Unique ID
	 */
	private $id;

	/**
	 * @var 
	 */
	private $apellido;

	/**
	 * @var 
	 */
	private $nombre;

	/**
	 * @var 
	 */
	private $dni;

	/**
	 * @var 
	 */
	private $email;

	/**
	 * @var 
	 */
	private $telefono;

	/**
	 * @var 
	 */
	private $nroInternoSIA;

	/**
	 * @var 
	 */
	private $estadoSIA;

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
	/**
	 * @var 
	 */
	private $esB24;

	/**
	 * @var 
	 */
	private $codigoB24;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		global $dblink;

		$query = "SELECT * FROM personas WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value)
			{
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);

			}

	}


	public function LoadxDni()
	{
		global $dblink;

		$query = "SELECT * FROM personas WHERE `dni`='{$this->getdni()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value)
			{
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);

			}

	}

	public function SelectAll()
	{
		global $dblink;
		$query = "SELECT * FROM personas ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink))
			mysqli_close($dblink);
		return $result;

	}

	public function Save()
	{
		global $dblink;
		$query = "UPDATE personas SET 
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "',
						`apellido` = '" . mysqli_real_escape_string($dblink, $this->getapellido()) . "',
						`email` = '" . mysqli_real_escape_string($dblink, $this->getemail()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink, $this->gettelefono()) . "',
						`userModif` = '" . $_SESSION['user'] . "',
						`fechaModif` = now(),
						`esB24` = '" . mysqli_real_escape_string($dblink, $this->getesB24()) . "',
						`codigoB24` =  '" . mysqli_real_escape_string($dblink, $this->getcodigoB24()) . "',
						`nroInternoSIA` =  '" . mysqli_real_escape_string($dblink, $this->getnroInternoSIA()) . "',
						`estadoSIA` =  '" . mysqli_real_escape_string($dblink, $this->getestadoSIA()) . "'
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink))
			mysqli_close($dblink);
	}

	public function Create()
	{
		global $dblink;
		$query = "INSERT INTO personas (`apellido`,`nombre`,`dni`,`email`,`telefono`,`nroInternoSIA`,`estadoSIA`,`userAlta`,`fechaAlta`,`userModif`,`fechaModif`,`esB24`,`codigoB24`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getapellido()) . "','" . mysqli_real_escape_string($dblink, $this->getnombre()) . "','" . mysqli_real_escape_string($dblink, $this->getdni()) . "','" . mysqli_real_escape_string($dblink, $this->getemail()) . "','" . mysqli_real_escape_string($dblink, $this->gettelefono()) . "','" . mysqli_real_escape_string($dblink, $this->getnroInternoSIA()) . "','" . mysqli_real_escape_string($dblink, $this->getestadoSIA()) . "','" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "',NOW(),'" . mysqli_real_escape_string($dblink, $this->getuserModif()) . "','" . mysqli_real_escape_string($dblink, $this->getfechaModif()) . "','". mysqli_real_escape_string($dblink,$this->getesB24()) . "','" . mysqli_real_escape_string($dblink,$this->getcodigoB24()) . "');";
		//echo $query;
		mysqli_query($dblink, $query);

		$id=mysqli_insert_id($dblink);
		$this->setid($id);
	}

	public function setid($id = '')
	{
		$this->id = $id;
		return true;
	}

	public function getid()
	{
		return $this->id;
	}

	public function setapellido($apellido = '')
	{
		$this->apellido = $apellido;
		return true;
	}

	public function getapellido()
	{
		return $this->apellido;
	}

	public function setnombre($nombre = '')
	{
		$this->nombre = $nombre;
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
	}

	public function setdni($dni = '')
	{
		$this->dni = $dni;
		return true;
	}

	public function getdni()
	{
		return $this->dni;
	}

	public function setemail($email = '')
	{
		$this->email = $email;
		return true;
	}

	public function getemail()
	{
		return $this->email;
	}

	public function settelefono($telefono = '')
	{
		$this->telefono = $telefono;
		return true;
	}

	public function gettelefono()
	{
		return $this->telefono;
	}

	public function setnroInternoSIA($nroInternoSIA = '')
	{
		$this->nroInternoSIA = $nroInternoSIA;
		return true;
	}

	public function getnroInternoSIA()
	{
		return $this->nroInternoSIA;
	}

	public function setestadoSIA($estadoSIA = '')
	{
		$this->estadoSIA = $estadoSIA;
		return true;
	}

	public function getestadoSIA()
	{
		return $this->estadoSIA;
	}

	public function setuserAlta($userAlta = '')
	{
		$this->userAlta = $userAlta;
		return true;
	}

	public function getuserAlta()
	{
		return $this->userAlta;
	}

	public function setfechaAlta($fechaAlta = '')
	{
		$this->fechaAlta = $fechaAlta;
		return true;
	}

	public function getfechaAlta()
	{
		return $this->fechaAlta;
	}

	public function setuserModif($userModif = '')
	{
		$this->userModif = $userModif;
		return true;
	}

	public function getuserModif()
	{
		return $this->userModif;
	}

	public function setfechaModif($fechaModif = '')
	{
		$this->fechaModif = $fechaModif;
		return true;
	}

	public function getfechaModif()
	{
		return $this->fechaModif;
	}
	public function setesB24($esB24 = '')
	{
		$this->esB24 = $esB24;
		return true;
	}

	public function getesB24()
	{
		return $this->esB24;
	}

	public function setcodigoB24($codigoB24 = '')
	{
		$this->codigoB24 = $codigoB24;
		return true;
	}

	public function getcodigoB24()
	{
		return $this->codigoB24;
	}

} // END class personas