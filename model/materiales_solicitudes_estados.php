<?php
/******************************************************************************
* Class for ospepri_altocosto.materiales_solicitudes_estados
*******************************************************************************/

class materiales_solicitudes_estados
{
	/**
	* @var 
	*/
	private $id_estados;

	/**
	* @var 
	*/
	private $id_solicitudes;

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
		$query ="INSERT INTO materiales_solicitudes_estados (`id_estados`,`id_solicitudes`,`observaciones`,`userAlta`,`fechaAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_estados()) . "','" . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$_COOKIE["user"]) . "',now());";
        //echo $query;
        $r= mysqli_query($dblink,$query);
		return $r;

		
	}



	public function SelectAll()
	{
		Global $dblink;
		$query = "SELECT se.*, e.nombre as estado FROM materiales_solicitudes_estados se inner join estados e on e.id=se.id_estados WHERE se.`id_solicitudes`='{$this->getid_solicitudes()}' order by fechaAlta desc";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}
	public function SelectStatusRecent($idSolicitud)
	{
		Global $dblink;
		$query = "  SELECT *
                    FROM materiales_solicitudes_estados as T1
                    WHERE NOT EXISTS (
                                        SELECT *
                                        FROM materiales_solicitudes_estados as T2
                                        WHERE T1.id_solicitudes = T2.id_solicitudes 
                                              AND T1.id_solicitudes=".$idSolicitud." AND T1.fechaAlta < T2.fechaAlta) 
                          AND T1.id_solicitudes=".$idSolicitud;

		$result = mysqli_query($dblink,$query);

		//echo $query;
		
	    return $result;

	}


	public function getRolUserCarga()
	{
		Global $dblink;
		$query = "select * from roles
					where id in (
						select id_roles from usuarios_roles
						where id_usuarios in (
						select id from usuarios where user in (
							select userAlta from materiales_solicitudes_estados
								where id_solicitudes='{$this->getid_solicitudes()}' and fechaAlta in (
								select min(fechaAlta)
									from materiales_solicitudes_estados
									where id_solicitudes='{$this->getid_solicitudes()}'
								)
							)
							)
					);";

		$result = mysqli_query($dblink,$query);

		
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

	public function setid_solicitudes($id_solicitudes='')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
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

} // END class materiales_solicitudes_estados