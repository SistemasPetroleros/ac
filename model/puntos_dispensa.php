<?php
/******************************************************************************
* Class for ospepri_altocosto.puntos_dispensa
*******************************************************************************/

class puntos_dispensa
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
	private $habilitado;

	/**
	* @var 
	*/
	private $domicilio;

	/**
	* @var 
	*/
	private $telefonos;

	/**
	* @var 
	*/
	private $id_localidades;

	/**
	* @var 
	*/
	private $email;


		/**
	* @var 
	*/
	private $GLN;

	/**
	* @var 
	*/
	private $userAnmat;

	/**
	* @var 
	*/
	private $claveAnmat;



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
		$query = "SELECT * FROM puntos_dispensa WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
		
	}

	public function SelectAll($filtro=null)
	{
		Global $dblink;

		$where=" WHERE 1=1 ";

		if($filtro['idUsuario']!=""){
			$where.=" AND p.id IN (SELECT up.id_ptoDispensa
						FROM usuarios_ptosdispensa up
						WHERE up.id_usuarios=".$_SESSION['idUsuario'].")";
		}

		$query = "SELECT p.*, l.nombre localidad, pr.id id_provincia, pr.nombre provincia
        FROM puntos_dispensa p
             INNER JOIN localidades l ON p.id_localidades=l.id
             INNER JOIN provincias pr ON l.id_provincias=pr.id  ".$where;

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE puntos_dispensa SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "',
						`domicilio` = '" . mysqli_real_escape_string($dblink,$this->getdomicilio()) . "',
						`telefonos` = '" . mysqli_real_escape_string($dblink,$this->gettelefonos()) . "',
						`id_localidades` = '" . mysqli_real_escape_string($dblink,$this->getid_localidades()) . "',
						`email` = '" . mysqli_real_escape_string($dblink,$this->getemail()) . "' ,
						`GLN` = '" . mysqli_real_escape_string($dblink,$this->getGLN()) . "',
						`userAnmat` = '" . mysqli_real_escape_string($dblink,$this->getuserAnmat()) . "',
						`claveAnmat` = '" . mysqli_real_escape_string($dblink,$this->getclaveAnmat()) . "',
						`userModif` = '" . $_SESSION['user'] . "',
						`fechaModif` = now()
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

		
	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO puntos_dispensa (`nombre`,`habilitado`,`domicilio`,`telefonos`,`id_localidades`,`email`,`GLN`,`userAnmat`,`claveAnmat`,`userAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "','" . mysqli_real_escape_string($dblink,$this->getdomicilio()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefonos()) . "','" . mysqli_real_escape_string($dblink,$this->getid_localidades()) . "','" . mysqli_real_escape_string($dblink,$this->getemail()) . "','" . mysqli_real_escape_string($dblink,$this->getGLN()) . "','" . mysqli_real_escape_string($dblink,$this->getuserAnmat()) . "','" . mysqli_real_escape_string($dblink,$this->getclaveAnmat()) . "','".$_SESSION["user"]."');";
		mysqli_query($dblink,$query);
		
		//echo $query;

		
	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM puntos_dispensa
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

	public function sethabilitado($habilitado='')
	{
		$this->habilitado = $habilitado;
		return true;
	}

	public function gethabilitado()
	{
		return $this->habilitado;
	}

	public function setdomicilio($domicilio='')
	{
		$this->domicilio = $domicilio;
		return true;
	}

	public function getdomicilio()
	{
		return $this->domicilio;
	}

	public function settelefonos($telefonos='')
	{
		$this->telefonos = $telefonos;
		return true;
	}

	public function gettelefonos()
	{
		return $this->telefonos;
	}

	public function setid_localidades($id_localidades='')
	{
		$this->id_localidades = $id_localidades;
		return true;
	}

	public function getid_localidades()
	{
		return $this->id_localidades;
	}

	public function setemail($email='')
	{
		$this->email = $email;
		return true;
	}

	public function getemail()
	{
		return $this->email;
	}

	public function setGLN($GLN='')
	{
		$this->GLN = $GLN;
		return true;
	}

	public function getGLN()
	{
		return $this->GLN;
	}

	public function setuserAnmat($userAnmat='')
	{
		$this->userAnmat = $userAnmat;
		return true;
	}

	public function getuserAnmat()
	{
		return $this->userAnmat;
	}


	public function setclaveAnmat($claveAnmat='')
	{
		$this->claveAnmat = $claveAnmat;
		return true;
	}

	public function getclaveAnmat()
	{
		return $this->claveAnmat;
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

} // END class puntos_dispensa