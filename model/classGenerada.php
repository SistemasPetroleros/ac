<?

define(DB_HOST, '127.0.0.1');
define(DB_USER, 'root');
define(DB_PASS, '');
define(DB_BASE, 'ospepri_altocosto');

class validate
{
	public function isstring($string)
	{
		return (is_string($string));
	}

	public function isint($int)
	{
		return (preg_match("/^([0-9.,-]+)$/", $int) > 0);
	}

	public function isbool($bool)
	{
		$b = 1 * $bool;
		return ($b == 1 || $b == 0);
	}
}

/******************************************************************************
 * Class for ospepri_altocosto.cotizacion_solic_prov
 *******************************************************************************/

class cotizacion_solic_prov
{
	/**
	 * @var 
	 */
	private $id_proveedores;

	/**
	 * @var 
	 */
	private $id_solicitudes;

	/**
	 * @var 
	 */
	private $aprobado;

	/**
	 * @var 
	 */
	private $observaciones;

	/**
	 * @var 
	 */
	private $importe;

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

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO cotizacion_solic_prov (`id_proveedores`,`id_solicitudes`,`aprobado`,`observaciones`,`importe`,`userAlta`,`fechaAlta`,`userModif`,`fechaModif`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink, $this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink, $this->getaprobado()) . "','" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink, $this->getimporte()) . "','" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "','" . mysqli_real_escape_string($dblink, $this->getfechaAlta()) . "','" . mysqli_real_escape_string($dblink, $this->getuserModif()) . "','" . mysqli_real_escape_string($dblink, $this->getfechaModif()) . "');";
		$res = mysqli_query($dblink, $query);



		if ($res) {
			$id = mysqli_insert_id($dblink);
			if (is_resource($dblink)) mysqli_close($dblink);
			return $id;
		} else {
			if (is_resource($dblink)) mysqli_close($dblink);
			return 0;
		}
	}

	public function setid_proveedores($id_proveedores = '')
	{
		$this->id_proveedores = $id_proveedores;
		return true;
	}

	public function getid_proveedores()
	{
		return $this->id_proveedores;
	}

	public function setid_solicitudes($id_solicitudes = '')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setaprobado($aprobado = '')
	{
		$this->aprobado = $aprobado;
		return true;
	}

	public function getaprobado()
	{
		return $this->aprobado;
	}

	public function setobservaciones($observaciones = '')
	{
		$this->observaciones = $observaciones;
		return true;
	}

	public function getobservaciones()
	{
		return $this->observaciones;
	}

	public function setimporte($importe = '')
	{
		$this->importe = $importe;
		return true;
	}

	public function getimporte()
	{
		return $this->importe;
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
} // END class cotizacion_solic_prov

/******************************************************************************
 * Class for ospepri_altocosto.documentos_adjuntos
 *******************************************************************************/

class documentos_adjuntos
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
	private $tipo;

	/**
	 * @var 
	 */
	private $imagen;

	/**
	 * @var 
	 */
	private $userAlta;

	/**
	 * @var 
	 */
	private $fechaAlta;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM documentos_adjuntos WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM documentos_adjuntos ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE documentos_adjuntos SET 
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "',
						`tipo` = '" . mysqli_real_escape_string($dblink, $this->gettipo()) . "',
						`imagen` = '" . mysqli_real_escape_string($dblink, $this->getimagen()) . "',
						`userAlta` = '" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "',
						`fechaAlta` = '" . mysqli_real_escape_string($dblink, $this->getfechaAlta()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO documentos_adjuntos (`nombre`,`tipo`,`imagen`,`userAlta`,`fechaAlta`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getnombre()) . "','" . mysqli_real_escape_string($dblink, $this->gettipo()) . "','" . mysqli_real_escape_string($dblink, $this->getimagen()) . "','" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "','" . mysqli_real_escape_string($dblink, $this->getfechaAlta()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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

	public function settipo($tipo = '')
	{
		$this->tipo = $tipo;
		return true;
	}

	public function gettipo()
	{
		return $this->tipo;
	}

	public function setimagen($imagen = '')
	{
		$this->imagen = $imagen;
		return true;
	}

	public function getimagen()
	{
		return $this->imagen;
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
} // END class documentos_adjuntos

/******************************************************************************
 * Class for ospepri_altocosto.documentos_personas
 *******************************************************************************/

class documentos_personas
{
	/**
	 * @var 
	 */
	private $id_personas;

	/**
	 * @var 
	 */
	private $id_documentos_adjuntos;

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO documentos_personas (`id_personas`,`id_documentos_adjuntos`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_personas()) . "','" . mysqli_real_escape_string($dblink, $this->getid_documentos_adjuntos()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function setid_personas($id_personas = '')
	{
		$this->id_personas = $id_personas;
		return true;
	}

	public function getid_personas()
	{
		return $this->id_personas;
	}

	public function setid_documentos_adjuntos($id_documentos_adjuntos = '')
	{
		$this->id_documentos_adjuntos = $id_documentos_adjuntos;
		return true;
	}

	public function getid_documentos_adjuntos()
	{
		return $this->id_documentos_adjuntos;
	}
} // END class documentos_personas

/******************************************************************************
 * Class for ospepri_altocosto.documentos_solicitudes
 *******************************************************************************/

class documentos_solicitudes
{
	/**
	 * @var 
	 */
	private $id_solicitudes;

	/**
	 * @var 
	 */
	private $id_documentos_adjuntos;

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO documentos_solicitudes (`id_solicitudes`,`id_documentos_adjuntos`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink, $this->getid_documentos_adjuntos()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function setid_solicitudes($id_solicitudes = '')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setid_documentos_adjuntos($id_documentos_adjuntos = '')
	{
		$this->id_documentos_adjuntos = $id_documentos_adjuntos;
		return true;
	}

	public function getid_documentos_adjuntos()
	{
		return $this->id_documentos_adjuntos;
	}
} // END class documentos_solicitudes

/******************************************************************************
 * Class for ospepri_altocosto.estados
 *******************************************************************************/

class estados
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

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM estados WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM estados ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE estados SET 
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO estados (`nombre`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getnombre()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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
} // END class estados

/******************************************************************************
 * Class for ospepri_altocosto.localidades
 *******************************************************************************/

class localidades
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
	private $id_provincias;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM localidades WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM localidades ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE localidades SET 
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "',
						`id_provincias` = '" . mysqli_real_escape_string($dblink, $this->getid_provincias()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO localidades (`nombre`,`id_provincias`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getnombre()) . "','" . mysqli_real_escape_string($dblink, $this->getid_provincias()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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

	public function setid_provincias($id_provincias = '')
	{
		$this->id_provincias = $id_provincias;
		return true;
	}

	public function getid_provincias()
	{
		return $this->id_provincias;
	}
} // END class localidades

/******************************************************************************
 * Class for ospepri_altocosto.manextra
 *******************************************************************************/

class manextra
{
	/**
	 * @var 
	 */
	private $id_productos;

	/**
	 * @var 
	 */
	private $id_monodrogas;

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO manextra (`id_productos`,`id_monodrogas`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_productos()) . "','" . mysqli_real_escape_string($dblink, $this->getid_monodrogas()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function setid_productos($id_productos = '')
	{
		$this->id_productos = $id_productos;
		return true;
	}

	public function getid_productos()
	{
		return $this->id_productos;
	}

	public function setid_monodrogas($id_monodrogas = '')
	{
		$this->id_monodrogas = $id_monodrogas;
		return true;
	}

	public function getid_monodrogas()
	{
		return $this->id_monodrogas;
	}
} // END class manextra

/******************************************************************************
 * Class for ospepri_altocosto.monodrogas
 *******************************************************************************/

class monodrogas
{
	/**
	 * @var 
	 * Class Unique ID
	 */
	private $id;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM monodrogas WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM monodrogas ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE monodrogas SET  
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO monodrogas () VALUES ();";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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
} // END class monodrogas

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

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM personas WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM personas ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE personas SET 
						`apellido` = '" . mysqli_real_escape_string($dblink, $this->getapellido()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "',
						`dni` = '" . mysqli_real_escape_string($dblink, $this->getdni()) . "',
						`email` = '" . mysqli_real_escape_string($dblink, $this->getemail()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink, $this->gettelefono()) . "',
						`nroInternoSIA` = '" . mysqli_real_escape_string($dblink, $this->getnroInternoSIA()) . "',
						`estadoSIA` = '" . mysqli_real_escape_string($dblink, $this->getestadoSIA()) . "',
						`userAlta` = '" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "',
						`fechaAlta` = '" . mysqli_real_escape_string($dblink, $this->getfechaAlta()) . "',
						`userModif` = '" . mysqli_real_escape_string($dblink, $this->getuserModif()) . "',
						`fechaModif` = '" . mysqli_real_escape_string($dblink, $this->getfechaModif()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO personas (`apellido`,`nombre`,`dni`,`email`,`telefono`,`nroInternoSIA`,`estadoSIA`,`userAlta`,`fechaAlta`,`userModif`,`fechaModif`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getapellido()) . "','" . mysqli_real_escape_string($dblink, $this->getnombre()) . "','" . mysqli_real_escape_string($dblink, $this->getdni()) . "','" . mysqli_real_escape_string($dblink, $this->getemail()) . "','" . mysqli_real_escape_string($dblink, $this->gettelefono()) . "','" . mysqli_real_escape_string($dblink, $this->getnroInternoSIA()) . "','" . mysqli_real_escape_string($dblink, $this->getestadoSIA()) . "','" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "','" . mysqli_real_escape_string($dblink, $this->getfechaAlta()) . "','" . mysqli_real_escape_string($dblink, $this->getuserModif()) . "','" . mysqli_real_escape_string($dblink, $this->getfechaModif()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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
} // END class personas

/******************************************************************************
 * Class for ospepri_altocosto.productos
 *******************************************************************************/

class productos
{
	/**
	 * @var 
	 * Class Unique ID
	 */
	private $id;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM productos WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM productos ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE productos SET  
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO productos () VALUES ();";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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
} // END class productos

/******************************************************************************
 * Class for ospepri_altocosto.proveedores
 *******************************************************************************/

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
	private $id_localidades;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM proveedores WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM proveedores ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE proveedores SET 
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "',
						`domicilio` = '" . mysqli_real_escape_string($dblink, $this->getdomicilio()) . "',
						`telefonos` = '" . mysqli_real_escape_string($dblink, $this->gettelefonos()) . "',
						`email` = '" . mysqli_real_escape_string($dblink, $this->getemail()) . "',
						`cuit` = '" . mysqli_real_escape_string($dblink, $this->getcuit()) . "',
						`id_localidades` = '" . mysqli_real_escape_string($dblink, $this->getid_localidades()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO proveedores (`nombre`,`domicilio`,`telefonos`,`email`,`cuit`,`id_localidades`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getnombre()) . "','" . mysqli_real_escape_string($dblink, $this->getdomicilio()) . "','" . mysqli_real_escape_string($dblink, $this->gettelefonos()) . "','" . mysqli_real_escape_string($dblink, $this->getemail()) . "','" . mysqli_real_escape_string($dblink, $this->getcuit()) . "','" . mysqli_real_escape_string($dblink, $this->getid_localidades()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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

	public function setid_localidades($id_localidades = '')
	{
		$this->id_localidades = $id_localidades;
		return true;
	}

	public function getid_localidades()
	{
		return $this->id_localidades;
	}
} // END class proveedores

/******************************************************************************
 * Class for ospepri_altocosto.provincias
 *******************************************************************************/

class provincias
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

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM provincias WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM provincias ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE provincias SET 
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO provincias (`nombre`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getnombre()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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
} // END class provincias

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

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM puntos_dispensa WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM puntos_dispensa ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE puntos_dispensa SET 
						`nombre` = '" . mysqli_real_escape_string($dblink, $this->getnombre()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink, $this->gethabilitado()) . "',
						`domicilio` = '" . mysqli_real_escape_string($dblink, $this->getdomicilio()) . "',
						`telefonos` = '" . mysqli_real_escape_string($dblink, $this->gettelefonos()) . "',
						`id_localidades` = '" . mysqli_real_escape_string($dblink, $this->getid_localidades()) . "',
						`email` = '" . mysqli_real_escape_string($dblink, $this->getemail()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO puntos_dispensa (`nombre`,`habilitado`,`domicilio`,`telefonos`,`id_localidades`,`email`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getnombre()) . "','" . mysqli_real_escape_string($dblink, $this->gethabilitado()) . "','" . mysqli_real_escape_string($dblink, $this->getdomicilio()) . "','" . mysqli_real_escape_string($dblink, $this->gettelefonos()) . "','" . mysqli_real_escape_string($dblink, $this->getid_localidades()) . "','" . mysqli_real_escape_string($dblink, $this->getemail()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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

	public function sethabilitado($habilitado = '')
	{
		$this->habilitado = $habilitado;
		return true;
	}

	public function gethabilitado()
	{
		return $this->habilitado;
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

	public function setid_localidades($id_localidades = '')
	{
		$this->id_localidades = $id_localidades;
		return true;
	}

	public function getid_localidades()
	{
		return $this->id_localidades;
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
} // END class puntos_dispensa

/******************************************************************************
 * Class for ospepri_altocosto.solicitudes
 *******************************************************************************/

class solicitudes
{
	/**
	 * @var 
	 * Class Unique ID
	 */
	private $id;

	/**
	 * @var 
	 */
	private $id_personas;

	/**
	 * @var 
	 */
	private $fecha;

	/**
	 * @var 
	 */
	private $observaciones;

	/**
	 * @var 
	 */
	private $id_puntos_dispensa;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM solicitudes WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM solicitudes ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE solicitudes SET 
						`id_personas` = '" . mysqli_real_escape_string($dblink, $this->getid_personas()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink, $this->getfecha()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "',
						`id_puntos_dispensa` = '" . mysqli_real_escape_string($dblink, $this->getid_puntos_dispensa()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO solicitudes (`id_personas`,`fecha`,`observaciones`,`id_puntos_dispensa`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_personas()) . "','" . mysqli_real_escape_string($dblink, $this->getfecha()) . "','" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink, $this->getid_puntos_dispensa()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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

	public function setid_personas($id_personas = '')
	{
		$this->id_personas = $id_personas;
		return true;
	}

	public function getid_personas()
	{
		return $this->id_personas;
	}

	public function setfecha($fecha = '')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setobservaciones($observaciones = '')
	{
		$this->observaciones = $observaciones;
		return true;
	}

	public function getobservaciones()
	{
		return $this->observaciones;
	}

	public function setid_puntos_dispensa($id_puntos_dispensa = '')
	{
		$this->id_puntos_dispensa = $id_puntos_dispensa;
		return true;
	}

	public function getid_puntos_dispensa()
	{
		return $this->id_puntos_dispensa;
	}
} // END class solicitudes

/******************************************************************************
 * Class for ospepri_altocosto.solicitudes_estados
 *******************************************************************************/

class solicitudes_estados
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
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO solicitudes_estados (`id_estados`,`id_solicitudes`,`observaciones`,`userAlta`,`fechaAlta`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_estados()) . "','" . mysqli_real_escape_string($dblink, $this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "','" . mysqli_real_escape_string($dblink, $this->getfechaAlta()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function setid_estados($id_estados = '')
	{
		$this->id_estados = $id_estados;
		return true;
	}

	public function getid_estados()
	{
		return $this->id_estados;
	}

	public function setid_solicitudes($id_solicitudes = '')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setobservaciones($observaciones = '')
	{
		$this->observaciones = $observaciones;
		return true;
	}

	public function getobservaciones()
	{
		return $this->observaciones;
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
} // END class solicitudes_estados

/******************************************************************************
 * Class for ospepri_altocosto.solicitudes_items
 *******************************************************************************/

class solicitudes_items
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
	private $id_monodrogas;

	/**
	 * @var 
	 */
	private $cantidad;

	/**
	 * @var 
	 */
	private $observaciones;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM solicitudes_items WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function SelectAll()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "SELECT * FROM solicitudes_items ";

		$result = mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
		return $result;
	}

	public function Save()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "UPDATE solicitudes_items SET 
						`id_solicitudes` = '" . mysqli_real_escape_string($dblink, $this->getid_solicitudes()) . "',
						`id_monodrogas` = '" . mysqli_real_escape_string($dblink, $this->getid_monodrogas()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink, $this->getcantidad()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO solicitudes_items (`id_solicitudes`,`id_monodrogas`,`cantidad`,`observaciones`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink, $this->getid_monodrogas()) . "','" . mysqli_real_escape_string($dblink, $this->getcantidad()) . "','" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
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

	public function setid_solicitudes($id_solicitudes = '')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setid_monodrogas($id_monodrogas = '')
	{
		$this->id_monodrogas = $id_monodrogas;
		return true;
	}

	public function getid_monodrogas()
	{
		return $this->id_monodrogas;
	}

	public function setcantidad($cantidad = '')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
	}

	public function setobservaciones($observaciones = '')
	{
		$this->observaciones = $observaciones;
		return true;
	}

	public function getobservaciones()
	{
		return $this->observaciones;
	}
} // END class solicitudes_items

/******************************************************************************
 * Class for ospepri_altocosto.solicitudes_productos_especif
 *******************************************************************************/

class solicitudes_productos_especif
{
	/**
	 * @var 
	 */
	private $id_solicitudes;

	/**
	 * @var 
	 */
	private $id_solicitudes_items;

	/**
	 * @var 
	 */
	private $id_productos;

	public function Create()
	{
		$dblink = null;

		try {
			$dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
			mysqli_select_db($dblink, DB_BASE);
		} catch (Exception $ex) {
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		}
		$query = "INSERT INTO solicitudes_productos_especif (`id_solicitudes`,`id_solicitudes_items`,`id_productos`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink, $this->getid_solicitudes_items()) . "','" . mysqli_real_escape_string($dblink, $this->getid_productos()) . "');";
		mysqli_query($dblink, $query);

		if (is_resource($dblink)) mysqli_close($dblink);
	}

	public function setid_solicitudes($id_solicitudes = '')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setid_solicitudes_items($id_solicitudes_items = '')
	{
		$this->id_solicitudes_items = $id_solicitudes_items;
		return true;
	}

	public function getid_solicitudes_items()
	{
		return $this->id_solicitudes_items;
	}

	public function setid_productos($id_productos = '')
	{
		$this->id_productos = $id_productos;
		return true;
	}

	public function getid_productos()
	{
		return $this->id_productos;
	}
} // END class solicitudes_productos_especif


echo "<pre>\n";
$cotizacion_solic_prov = new cotizacion_solic_prov(1);
print_r($cotizacion_solic_prov);

$documentos_adjuntos = new documentos_adjuntos(1);
print_r($documentos_adjuntos);

$documentos_personas = new documentos_personas(1);
print_r($documentos_personas);

$documentos_solicitudes = new documentos_solicitudes(1);
print_r($documentos_solicitudes);

$estados = new estados(1);
print_r($estados);

$localidades = new localidades(1);
print_r($localidades);

$manextra = new manextra(1);
print_r($manextra);

$monodrogas = new monodrogas(1);
print_r($monodrogas);

$personas = new personas(1);
print_r($personas);

$productos = new productos(1);
print_r($productos);

$proveedores = new proveedores(1);
print_r($proveedores);

$provincias = new provincias(1);
print_r($provincias);

$puntos_dispensa = new puntos_dispensa(1);
print_r($puntos_dispensa);

$solicitudes = new solicitudes(1);
print_r($solicitudes);

$solicitudes_estados = new solicitudes_estados(1);
print_r($solicitudes_estados);

$solicitudes_items = new solicitudes_items(1);
print_r($solicitudes_items);

$solicitudes_productos_especif = new solicitudes_productos_especif(1);
print_r($solicitudes_productos_especif);
