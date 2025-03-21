<?php
/******************************************************************************
* Class for ospepri_altocosto.cotizaciones_estados
*******************************************************************************/

class cotizaciones_estados
{
	/**
	* @var 
	*/
	private $id_estados;

	/**
	* @var 
	*/
	private $id_cotizacion;

	/**
	* @var 
	*/
	private $observaciones;

	/**
	* @var 
	*/
	private $userAlta;

	/** 
	* @var 
	*/
	private $fechaAlta;

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO cotizaciones_estados (`id_estados`,`id_cotizacion`,`observaciones`,`userAlta`,`fechaAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_estados()) . "','" . mysqli_real_escape_string($dblink,$this->getid_cotizacion()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$_COOKIE["user"]) . "',now());";
     //  echo $query;
        $r= mysqli_query($dblink,$query);
		return $r;

		
	}


	public function DeleteAll()
	{
		Global $dblink;
		$query ="DELETE FROM cotizaciones_estados WHERE `id_cotizacion`='{$this->getid_cotizacion()}'";
     //   echo $query;
        $r= mysqli_query($dblink,$query);
		
		return $r;

		
	}

	public function getestado()
	{
		global $dblink;

		$query = "SELECT e.nombre as Estado, e.id as idEstado, se.fechaAlta, se.userAlta, se.observaciones 
		FROM `estados` e
		inner join `cotizaciones_estados` se on se.id_estados=e.id
		where `id_cotizacion`='{$this->getid_cotizacion()}' order by fechaAlta desc limit 1";



		$result = mysqli_query($dblink, $query);

		//echo $query;

		return $result;
	}



	public function SelectAll()
	{
		Global $dblink;
		$query = "SELECT se.*, e.nombre as estado FROM cotizaciones_estados se inner join estados e on e.id=se.id_estados WHERE se.`id_cotizacion`='{$this->getid_cotizacion()}' order by fechaAlta desc";

		$result = mysqli_query($dblink,$query);

	//echo $query;

		
	return $result;

	}
	public function SelectStatusRecent($idSolicitud)
	{
		Global $dblink;
		$query = "  SELECT *
                    FROM cotizaciones_estados as T1
                    WHERE NOT EXISTS (
                                        SELECT *
                                        FROM cotizaciones_estados as T2
                                        WHERE T1.id_cotizacion = T2.id_cotizacion 
                                              AND T1.id_cotizacion=".$idSolicitud." AND T1.fechaAlta < T2.fechaAlta) 
                          AND T1.id_cotizacion=".$idSolicitud;

		$result = mysqli_query($dblink,$query);

		//echo $query;
		
	    return $result;

	}


	public function setid_estados($id_estados='')
	{
		$this->id_estados = $id_estados;
		return true;
	}

	public function getid_estados()
	{
		return $this->id_estados;
	}

	public function setid_cotizacion($id_cotizacion='')
	{
		$this->id_cotizacion = $id_cotizacion;
		return true;
	}

	public function getid_cotizacion()
	{
		return $this->id_cotizacion;
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

} // END class cotizaciones_estados