<?php

class proveedores
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
	private $domicilio;

	/**
	 * @var 
	 */
	private $telefonos;

	/**
	 * @var 
	 */
	private $email;

	/**
	 * @var 
	 */
	private $cuit;


	/**
	 * @var 
	 */
	private $tipo;




	/**
	 * @var 
	 */
	private $id_localidades;

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
	private $habilitado;


	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		global $dblink;
		$query = "SELECT * FROM proveedores WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
	}

	public function SelectAll()
	{
		global $dblink;
		$query = "SELECT p.*, l.nombre localidad, pr.id id_provincia, pr.nombre provincia,
		           (SELECT GROUP_CONCAT(ifnull(t.id_tipo_solicitud,'') SEPARATOR ',') idsTipos FROM proveedores_tipo_solicitud t WHERE t.id_proveedores=p.id) ids,
				   (SELECT DISTINCT GROUP_CONCAT(ifnull(ts.nombre,'') SEPARATOR '<br/>') listTipos  FROM  tipo_solicitud ts INNER JOIN proveedores_tipo_solicitud t  ON t.id_tipo_solicitud=ts.id WHERE  t.id_proveedores=p.id) listTipos
        FROM proveedores p
             INNER JOIN localidades l ON p.id_localidades=l.id
             INNER JOIN provincias pr ON l.id_provincias=pr.id ";

		$result = mysqli_query($dblink, $query);
		
		//echo $query;


		return $result;
	}


	public function getProveedorUsuario()
	{
		global $dblink;
		$query = "SELECT p.*
					FROM  proveedores p
						INNER JOIN usuarios_proveedor up ON p.id=up.id_proveedor
					WHERE up.id_usuarios=".$_SESSION['idUsuario'];

		$result = mysqli_query($dblink, $query);


		return $result;
	}

	public function Save()
	{
		global $dblink;
		$query = "UPDATE proveedores SET 
						`nombre` = '" . (mysqli_real_escape_string($dblink, $this->getnombre())) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink, $this->gethabilitado()) . "',
						`domicilio` = '" . mysqli_real_escape_string($dblink, $this->getdomicilio()) . "',
						`telefonos` = '" . mysqli_real_escape_string($dblink, $this->gettelefonos()) . "',
						`email` = '" . mysqli_real_escape_string($dblink, $this->getemail()) . "',
						`cuit` = '" . mysqli_real_escape_string($dblink, $this->getcuit()) . "',
						`id_localidades` = '" . mysqli_real_escape_string($dblink, $this->getid_localidades()) . "',
                        `userModif` = '" . $_SESSION['user'] . "',
						`fechaModif` = now()
						WHERE `id`='{$this->getid()}'";

		// echo $query;
		$x=mysqli_query($dblink, $query);
		return $x;
	}

	public function Create()
	{
		global $dblink;
		$query = "INSERT INTO proveedores (`nombre`,`habilitado`,`domicilio`,`telefonos`,`email`,`cuit`,`id_localidades`, `userAlta`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getnombre()) . "','" . mysqli_real_escape_string($dblink, $this->gethabilitado()) . "','" . mysqli_real_escape_string($dblink, $this->getdomicilio()) . "','" . mysqli_real_escape_string($dblink, $this->gettelefonos()) . "','" . mysqli_real_escape_string($dblink, $this->getemail()) . "','" . mysqli_real_escape_string($dblink, $this->getcuit()) . "','" . mysqli_real_escape_string($dblink, $this->getid_localidades()) . "','" . $_SESSION["user"] . "');";
		$r = mysqli_query($dblink, $query);
		return mysqli_insert_id($dblink);

		// echo $query;

	}

	public function Delete()
	{
		global $dblink;
		$query = "DELETE FROM proveedores
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);
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

	public function setnombre($nombre = '')
	{
		$this->nombre = $nombre;
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
	}

	public function setdomicilio($domicilio = '')
	{
		$this->domicilio = $domicilio;
		return true;
	}

	public function getdomicilio()
	{
		return $this->domicilio;
	}

	public function settelefonos($telefonos = '')
	{
		$this->telefonos = $telefonos;
		return true;
	}

	public function gettelefonos()
	{
		return $this->telefonos;
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

	public function setcuit($cuit = '')
	{
		$this->cuit = $cuit;
		return true;
	}

	public function getcuit()
	{
		return $this->cuit;
	}

	public function settipo($tipo = '')
	{
		$this->tipo = $tipo;
		return true;
	}

	public function gettipo()
	{
		return $this->tipo;
	}

	public function setid_localidades($id_localidades = '')
	{
		$this->id_localidades = $id_localidades;
		return true;
	}

	public function getid_localidades()
	{
		return $this->id_localidades;
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

	public function sethabilitado($habilitado = '')
	{
		$this->habilitado = $habilitado;
		return true;
	}

	public function gethabilitado()
	{
		return $this->habilitado;
	}
} // END class proveedores