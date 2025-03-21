<?php

/******************************************************************************
* Class for ospepri_altocosto.usuario_tipo_solicitud
*******************************************************************************/

class usuario_tipo_solicitud
{
	/**
	* @var 
	* Class Unique ID
	*/
	private $id;

	/**
	* @var 
	*/
	private $idUsuario;

    /**
	* @var 
	*/
	private $idTipoSolicitud;



	/**
     * 
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
		$query = "SELECT * FROM usuario_tipo_solicitud WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM usuario_tipo_solicitud ";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function SelectForUser()
	{
		Global $dblink;
		$query = "SELECT * FROM usuario_tipo_solicitud WHERE idUsuario={$this->getidUsuario()}";

		//echo $query;

		$result = mysqli_query($dblink,$query);
  
		
	return $result;

	}

	public function SelectAllRelacionados()
	{
		Global $dblink;
		$query = "SELECT * FROM tipo_solicitud where id in (select distinct idTipoSolicitud from usuario_tipo_solicitud where idUsuario= " . mysqli_real_escape_string($dblink,$this->getidUsuario()) . ") ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllNoRelacionados()
	{
		Global $dblink;
		$query = "SELECT * FROM tipo_solicitud where id not in (select distinct idTipoSolicitud from usuario_tipo_solicitud where idUsuario= " . mysqli_real_escape_string($dblink,$this->getidUsuario()) . ") ";
		$result = mysqli_query($dblink,$query);

	//	echo $query;

	return $result;

	}


	  

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO usuario_tipo_solicitud (`idUsuario`,`idTipoSolicitud`,`userAlta`,`fechaAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getidUsuario()) . "','" . mysqli_real_escape_string($dblink,$this->getidTipoSolicitud()) . "','".$_SESSION["user"]."',now());";
		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM usuario_tipo_solicitud
						WHERE idUsuario= " . mysqli_real_escape_string($dblink,$this->getidUsuario()) . " and idTipoSolicitud= " . mysqli_real_escape_string($dblink,$this->getidTipoSolicitud()) . "";

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

	public function setidTipoSolicitud($idTipoSolicitud='')
	{
		$this->idTipoSolicitud = $idTipoSolicitud;
		return true;
	}

	public function getidTipoSolicitud()
	{
		return $this->idTipoSolicitud;
	}


	public function setidUsuario($idUsuario='')
	{
		$this->idUsuario = $idUsuario;
		return true;
	}

	public function getidUsuario()
	{
		return $this->idUsuario;
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

} // END class usuario_tipo_solicitud