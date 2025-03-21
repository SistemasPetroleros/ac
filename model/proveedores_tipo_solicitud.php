<?php

/******************************************************************************
* Class for ospepri_altocosto.proveedores_tipo_solicitud
*******************************************************************************/

class proveedores_tipo_solicitud
{
	/**
	* @var 
	* Class Unique id_proveedores
	*/
	private $id_proveedores;

	/**
	* @var 
	*/
	private $id_tipo_solicitudes;

			/**
	* @var 
	*/
	private $userAlta;

		/**
	* @var 
	*/
	private $fechaAlta;



	public function __construct()
	{
		
	}





	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO proveedores_tipo_solicitud (`id_proveedores`,`id_tipo_solicitud`,`userAlta`) VALUES ('".mysqli_real_escape_string($dblink,$this->getid_proveedores())."','" . mysqli_real_escape_string($dblink,$this->getid_tipo_solicitudes()) . "','".$_SESSION["user"]."');";
		mysqli_query($dblink,$query);

		//echo $query;

		
	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM proveedores_tipo_solicitud
						WHERE `id_proveedores`='{$this->getid_proveedores()}' and `id_tipo_solicitud`='{$this->getid_tipo_solicitudes()}'";

		mysqli_query($dblink,$query);

	}

	public function DeleteIdProveedor()
	{
		Global $dblink;
		$query = "DELETE FROM proveedores_tipo_solicitud
						WHERE `id_proveedores`='{$this->getid_proveedores()}'";

		mysqli_query($dblink,$query);

	}

	public function setid_proveedores($id_proveedores='')
	{
		$this->id_proveedores = $id_proveedores;
		return true;
	}

	public function getid_proveedores()
	{
		return $this->id_proveedores;
	}

	public function setid_tipo_solicitudes($id_tipo_solicitudes='')
	{
		$this->id_tipo_solicitudes = $id_tipo_solicitudes;
		return true;
	}

	public function getid_tipo_solicitudes()
	{
		return $this->id_tipo_solicitudes;
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



} // END class proveedores_tipo_solicitud