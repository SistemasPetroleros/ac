<?php
set_time_limit(10);
date_default_timezone_set('America/Argentina/Rio_Gallegos');
error_reporting(0);
//error_reporting(-1); error_reporting(E_ALL); ini_set('error_reporting', E_ALL);
//if (isset($_GET['error'])){error_reporting(-1); error_reporting(E_ALL); ini_set('error_reporting', E_ALL);}else{error_reporting(0);}
session_start();

$hoy = date("d/m/Y");
/*
define('DB_HOST','127.0.0.1');
define('DB_USER','root');
define('DB_PASS','');
define('DB_BASE','ospepri_altocosto');

$dblink = null;
$dblink = new mysqli(DB_HOST, DB_USER, DB_PASS,DB_BASE);
if ($dblink->connect_errno) {
printf("Error: %s\n", $dblink->connect_error);
exit();
}
$dblink->set_charset("utf8");*/



function archivo($nombreArchivo, $linea) {
    $fecha = date("Y-m-d H:i:s");
    $file = fopen($nombreArchivo, "a+");
    fwrite($file, $fecha . ": " . $linea . PHP_EOL);
//fwrite($file, PHP_EOL . "--------------------------" . PHP_EOL);
    fclose($file);
}

function logTxt($linea) {
    $nombreArchivo='log\log.txt';
    $fecha = date("Y-m-d H:i:s");
    $file = fopen($nombreArchivo, "a+");
    fwrite($file, $fecha. " (".basename($_SERVER['PHP_SELF']).") " . ": " . $linea . PHP_EOL);
//fwrite($file, PHP_EOL . "--------------------------" . PHP_EOL);
    fclose($file);
}

function Query($query) {
    Global $dblink;
    $result = mysqli_query($dblink, $query);
    logc("Resultado Query:::" . $result." [".substr($query, 0, 40)."...]");
    return $result;
}


/*		try
		{
			$dblink = mysql_connect(DB_HOST,DB_USER,DB_PASS);
			mysql_select_db(DB_BASE,$dblink);
		}
		catch(Exception $ex)
		{
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
		} */  




function logc($query){
    echo '<script>console.log("'.$query.'");</script>';
}


function fecha($fecha) {
  return substr($fecha, 8, 2).'/'.substr($fecha, 5, 2).'/'.substr($fecha, 0, 4);
}

function fecha3($fecha) {
  return substr($fecha, 8, 2).'/'.substr($fecha, 5, 2).'/'.substr($fecha, 0, 4).' <FONT SIZE=-2>'.substr($fecha, 11, 2).':'.substr($fecha, 14, 2).'</font>';
}

function fecha4($fecha) {
  return substr($fecha, 8, 2).'/'.substr($fecha, 5, 2).'/'.substr($fecha, 0, 4).' '.substr($fecha, 11, 2).':'.substr($fecha, 14, 2);
}

function fecha2($fecha) {
  return substr($fecha, 6, 4).'-'.substr($fecha, 3, 2).'-'.substr($fecha, 0, 2);
}


function diferenciafecha($fecha_f) {
$fecha_i= date("Y-m-d H:i:s");
$minutos = (strtotime($fecha_i)-strtotime($fecha_f))/60;
$minutos = abs($minutos); $minutos = floor($minutos);
return $minutos;
}

function diferenciafechaensegundos($fecha_i, $fecha_f) {
if ($fecha_i==''){$fecha_i= date("Y-m-d H:i:s");}
$segundos = (strtotime($fecha_i)-strtotime($fecha_f));
$segundos = abs($segundos); $segundos = floor($segundos);
return $segundos;
}

function ValidaMail2($pMail) {
   	//if (ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$", $pMail ) ) {
        if (preg_match("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$", $pMail ) ) {
      	return true;
   	} else {
      	return false;
   	}
}
function ValidaMail($email) {
    if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$email)) {
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		return false;
	}
        return true;
    }
    return false;

}

function b64($tipo, $string){
    if ($tipo=='e'){
        //return base64_encode($string);
        $x=serialize($string);
        return urlencode($x);
    }elseif ($tipo=='d') {
        //return base64_decode($string);
        $x=stripslashes($string);
        $x=urldecode($x);
        return unserialize($x);
    }else{
        return '';
    }

}
function redir($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
function redirJS($url)
{
    echo ' <script type="text/javascript">
                setTimeout("location.href=\''.$url.'\'", 4000);
          </script> ';
}
function redirJS0($url)
{
    echo ' <script type="text/javascript">
                location.href=\''.$url.'\';
          </script> ';
}
function alert($mensaje){
    echo '<script>alert("'.$mensaje.'");</script>';
}
function code(){
	$an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$su = strlen($an) - 1;
	return substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1);
}
function url() {
  $s=$_SERVER;
  $use_forwarded_host=false;
  $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
  $sp = strtolower($s['SERVER_PROTOCOL']);
  $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');

  $port = $s['SERVER_PORT'];
  $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;

  $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
  $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;

  return $protocol . '://' . $host;

}

function urlfull() {
  $s=$_SERVER;
  $completa= url() . $s['REQUEST_URI'];
  $carpeta= str_replace(basename($completa), "", $completa);

  return $carpeta;
}


//logc(url().' // '. urlfull().' // '. getRealIP().' // '. array_pop(explode('/', $_SERVER['PHP_SELF'])).' // '.$_SERVER['SCRIPT_NAME']);


FUNCTION ScriptActual(){
    $script = basename($_SERVER['PHP_SELF']);
    return $script;
}



function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    return $_SERVER['REMOTE_ADDR'];
}

if(isset($_GET['ip'])){
    alert(getRealIP());
}

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

function primeraMayuscula($x){
    return ucwords(strtolower($x));
}



function sanear_string($string)
{   
    $string = ($string);
    $string = str_replace(chr(189), '?', $string);
    $string = str_replace('Â ', ' ', $string);
    $string = str_replace('AÃ‚', ' ', $string);
    $string = str_replace('Â¢', '?', $string);
    $string = str_replace('Ã‚Â¢', '?', $string);
 
    
 
    $string = str_replace(
        array('Ã¡', 'Ã ', 'Ã¤', 'Ã¢', 'Âª', 'Ã�', 'Ã€', 'Ã‚', 'Ã„'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('Ã©', 'Ã¨', 'Ã«', 'Ãª', 'Ã‰', 'Ãˆ', 'ÃŠ', 'Ã‹'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('Ã­', 'Ã¬', 'Ã¯', 'Ã®', 'Ã�', 'ÃŒ', 'Ã�', 'ÃŽ'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('Ã³', 'Ã²', 'Ã¶', 'Ã´', 'Ã“', 'Ã’', 'Ã–', 'Ã”'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('Ãº', 'Ã¹', 'Ã¼', 'Ã»', 'Ãš', 'Ã™', 'Ã›', 'Ãœ'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('Ã±', 'Ã‘', 'Ã§', 'Ã‡'),
        array('n', 'N', 'c', 'C'),
        $string
    );
    
 
    //Esta parte se encarga de eliminar cualquier caracter extraÃ±o
    $string = str_replace(
        array("\\", "Â¨", "Âº", "-", "~",
             "#", "@", "|", "!", "\"",
             "Â·", "$", "%", "&", "/",
             "(", ")", "?", "'", "Â¡",
             "Â¿", "[", "^", "<code>", "]",
             "+", "}", "{", "Â¨", "Â´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        ' ',
        $string
    );
 
    $string = trim($string);
    
    return $string;
}











/******************************************************************************
* Class for farmacia.ab_log
*******************************************************************************/

class ab_log
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var string
	*/
	private $archivo;

	/**
	* @var int
	*/
	private $cantidad;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_log WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM ab_log where DATEDIFF(now(),fecha) < 90 order by id desc";
		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_log SET 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`archivo` = '" . mysqli_real_escape_string($dblink,$this->getarchivo()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_log 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_log (`id`,`fecha`,`archivo`,`cantidad`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "',now(),'" . mysqli_real_escape_string($dblink,$this->getarchivo()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_log (`id`,`fecha`,`archivo`,`cantidad`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getarchivo()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "') ON DUPLICATE KEY UPDATE 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`archivo` = '" . mysqli_real_escape_string($dblink,$this->getarchivo()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "';";
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

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setarchivo($archivo='')
	{
		$this->archivo = $archivo;
		return true;
	}

	public function getarchivo()
	{
		return $this->archivo;
	}

	public function setcantidad($cantidad='')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
	}

} // END class ab_log

/******************************************************************************
* Class for farmacia.ab_acciofar
*******************************************************************************/

class ab_acciofar
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_acciofar WHERE `codigo`='{$this->getcodigo()}'";

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
		$query = "SELECT * FROM ab_acciofar ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_acciofar SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_acciofar 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_acciofar (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_acciofar (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
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

} // END class ab_acciofar

/******************************************************************************
* Class for farmacia.ab_barextra
*******************************************************************************/

class ab_barextra
{
	/**
	* @var int
	*/
	private $nroreg;

	/**
	* @var string
	*/
	private $codigobarra;

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_barextra (`nroreg`,`codigobarra`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getcodigobarra()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_barextra (`nroreg`,`codigobarra`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getcodigobarra()) . "') ON DUPLICATE KEY UPDATE ;";
		mysqli_query($dblink,$query);

	}

	public function setnroreg($nroreg='')
	{
		$this->nroreg = $nroreg;
		return true;
	}

	public function getnroreg()
	{
		return $this->nroreg;
	}

	public function setcodigobarra($codigobarra='')
	{
		$this->codigobarra = $codigobarra;
		return true;
	}

	public function getcodigobarra()
	{
		return $this->codigobarra;
	}

} // END class ab_barextra

/******************************************************************************
* Class for farmacia.ab_formas
*******************************************************************************/

class ab_formas
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_formas WHERE `codigo`='{$this->getcodigo()}'";

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
		$query = "SELECT * FROM ab_formas ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_formas SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_formas 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_formas (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_formas (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
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

} // END class ab_formas

/******************************************************************************
* Class for farmacia.ab_gtin1
*******************************************************************************/

class ab_gtin1
{
	/**
	* @var int
	*/
	private $nroreg;

	/**
	* @var string
	* Class Unique ID
	*/
	private $gtin;

	public function __construct($gtin='')
	{
		$this->setgtin($gtin);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_gtin1 WHERE `gtin`='{$this->getgtin()}'";

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
		$query = "SELECT * FROM ab_gtin1 ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_gtin1 SET 
						`nroreg` = '" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "' 
						WHERE `gtin`='{$this->getgtin()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_gtin1 
						WHERE `gtin`='{$this->getgtin()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_gtin1 (`nroreg`,`gtin`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getgtin()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_gtin1 (`nroreg`,`gtin`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getgtin()) . "') ON DUPLICATE KEY UPDATE 
						`nroreg` = '" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setnroreg($nroreg='')
	{
		$this->nroreg = $nroreg;
		return true;
	}

	public function getnroreg()
	{
		return $this->nroreg;
	}

	public function setgtin($gtin='')
	{
		$this->gtin = $gtin;
		return true;
	}

	public function getgtin()
	{
		return $this->gtin;
	}

} // END class ab_gtin1

/******************************************************************************
* Class for farmacia.ab_manextra
*******************************************************************************/

class ab_manextra
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $nroregistro;

	/**
	* @var int
	*/
	private $codtamano;

	/**
	* @var int
	*/
	private $codaccionfar;

	/**
	* @var int
	*/
	private $coddroga;

	/**
	* @var int
	*/
	private $codformafar;

	/**
	* @var string
	*/
	private $potencia;

	/**
	* @var int
	*/
	private $codunipotencia;

	/**
	* @var int
	*/
	private $codtipounidad;

	/**
	* @var int
	*/
	private $codviaadministracion;

	public function __construct($nroregistro='')
	{
		$this->setnroregistro($nroregistro);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_manextra WHERE `nroregistro`='{$this->getnroregistro()}'";

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
		$query = "SELECT * FROM ab_manextra ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_manextra SET 
						`codtamano` = '" . mysqli_real_escape_string($dblink,$this->getcodtamano()) . "',
						`codaccionfar` = '" . mysqli_real_escape_string($dblink,$this->getcodaccionfar()) . "',
						`coddroga` = '" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "',
						`codformafar` = '" . mysqli_real_escape_string($dblink,$this->getcodformafar()) . "',
						`potencia` = '" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "',
						`codunipotencia` = '" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "',
						`codtipounidad` = '" . mysqli_real_escape_string($dblink,$this->getcodtipounidad()) . "',
						`codviaadministracion` = '" . mysqli_real_escape_string($dblink,$this->getcodviaadministracion()) . "' 
						WHERE `nroregistro`='{$this->getnroregistro()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_manextra 
						WHERE `nroregistro`='{$this->getnroregistro()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_manextra (`nroregistro`,`codtamano`,`codaccionfar`,`coddroga`,`codformafar`,`potencia`,`codunipotencia`,`codtipounidad`,`codviaadministracion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroregistro()) . "','" . mysqli_real_escape_string($dblink,$this->getcodtamano()) . "','" . mysqli_real_escape_string($dblink,$this->getcodaccionfar()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "','" . mysqli_real_escape_string($dblink,$this->getcodformafar()) . "','" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "','" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "','" . mysqli_real_escape_string($dblink,$this->getcodtipounidad()) . "','" . mysqli_real_escape_string($dblink,$this->getcodviaadministracion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_manextra (`nroregistro`,`codtamano`,`codaccionfar`,`coddroga`,`codformafar`,`potencia`,`codunipotencia`,`codtipounidad`,`codviaadministracion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroregistro()) . "','" . mysqli_real_escape_string($dblink,$this->getcodtamano()) . "','" . mysqli_real_escape_string($dblink,$this->getcodaccionfar()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "','" . mysqli_real_escape_string($dblink,$this->getcodformafar()) . "','" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "','" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "','" . mysqli_real_escape_string($dblink,$this->getcodtipounidad()) . "','" . mysqli_real_escape_string($dblink,$this->getcodviaadministracion()) . "') ON DUPLICATE KEY UPDATE 
						`codtamano` = '" . mysqli_real_escape_string($dblink,$this->getcodtamano()) . "',
						`codaccionfar` = '" . mysqli_real_escape_string($dblink,$this->getcodaccionfar()) . "',
						`coddroga` = '" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "',
						`codformafar` = '" . mysqli_real_escape_string($dblink,$this->getcodformafar()) . "',
						`potencia` = '" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "',
						`codunipotencia` = '" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "',
						`codtipounidad` = '" . mysqli_real_escape_string($dblink,$this->getcodtipounidad()) . "',
						`codviaadministracion` = '" . mysqli_real_escape_string($dblink,$this->getcodviaadministracion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setnroregistro($nroregistro='')
	{
		$this->nroregistro = $nroregistro;
		return true;
	}

	public function getnroregistro()
	{
		return $this->nroregistro;
	}

	public function setcodtamano($codtamano='')
	{
		$this->codtamano = $codtamano;
		return true;
	}

	public function getcodtamano()
	{
		return $this->codtamano;
	}

	public function setcodaccionfar($codaccionfar='')
	{
		$this->codaccionfar = $codaccionfar;
		return true;
	}

	public function getcodaccionfar()
	{
		return $this->codaccionfar;
	}

	public function setcoddroga($coddroga='')
	{
		$this->coddroga = $coddroga;
		return true;
	}

	public function getcoddroga()
	{
		return $this->coddroga;
	}

	public function setcodformafar($codformafar='')
	{
		$this->codformafar = $codformafar;
		return true;
	}

	public function getcodformafar()
	{
		return $this->codformafar;
	}

	public function setpotencia($potencia='')
	{
		$this->potencia = $potencia;
		return true;
	}

	public function getpotencia()
	{
		return $this->potencia;
	}

	public function setcodunipotencia($codunipotencia='')
	{
		$this->codunipotencia = $codunipotencia;
		return true;
	}

	public function getcodunipotencia()
	{
		return $this->codunipotencia;
	}

	public function setcodtipounidad($codtipounidad='')
	{
		$this->codtipounidad = $codtipounidad;
		return true;
	}

	public function getcodtipounidad()
	{
		return $this->codtipounidad;
	}

	public function setcodviaadministracion($codviaadministracion='')
	{
		$this->codviaadministracion = $codviaadministracion;
		return true;
	}

	public function getcodviaadministracion()
	{
		return $this->codviaadministracion;
	}

} // END class ab_manextra

/******************************************************************************
* Class for farmacia.ab_manualdat
*******************************************************************************/

class ab_manualdat
{
	/**
	* @var string
	*/
	private $troquel;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $presentacion;

	/**
	* @var int
	*/
	private $ioma1;

	/**
	* @var string
	*/
	private $ioma2;

	/**
	* @var string
	*/
	private $ioma3;

	/**
	* @var string
	*/
	private $laboratorio;

	/**
	* @var int
	*/
	private $precio;

	/**
	* @var string
	*/
	private $fecha;

	/**
	* @var string
	*/
	private $prodcontrolado;

	/**
	* @var string
	*/
	private $importado;

	/**
	* @var string
	*/
	private $tipoventa;

	/**
	* @var string
	*/
	private $iva;

	/**
	* @var string
	*/
	private $coddescpami;

	/**
	* @var int
	*/
	private $codlab;

	/**
	* @var int
	* Class Unique ID
	*/
	private $nroregistro;

	/**
	* @var string
	*/
	private $baja;

	/**
	* @var string
	*/
	private $codbarra;

	/**
	* @var int
	*/
	private $unidades;

	/**
	* @var string
	*/
	private $tamano;

	/**
	* @var string
	*/
	private $heladera;

	/**
	* @var string
	*/
	private $sifar;

	/**
	* @var string
	*/
	private $gravamen;

	public function __construct($nroregistro='')
	{
		$this->setnroregistro($nroregistro);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_manualdat WHERE `nroregistro`='{$this->getnroregistro()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}
        
        public function Cnt($search)
	{
		Global $dblink;
		$query = "SELECT count(1) as recordsFiltered, (SELECT count(1) FROM ab_manualdat where length(troquel)>0) as recordsTotal FROM ab_manualdat md
                          left join ab_manextra me on me.nroregistro=md.nroregistro
                          left join ab_monodro mo on mo.codigo=me.coddroga
                          where length(md.troquel)>0 and (mo.descripcion like '%".$search."%' or md.troquel like '%".$search."%' or md.nombre like '%".$search."%' or md.codbarra like '%".$search."%' or md.laboratorio like '%".$search."%')";

		$result = mysqli_query($dblink,$query);

	return $result;

	}


	
	public function SelectAll($len,$start, $search, $order, $direccion)
	{
		Global $dblink;
		$query = "SELECT md.troquel,md.nombre,md.presentacion,md.precio,md.fecha, md.codbarra, md.unidades, me.coddroga, mo.descripcion as droga FROM ab_manualdat md
left join ab_manextra me on me.nroregistro=md.nroregistro
left join ab_monodro mo on mo.codigo=me.coddroga

where length(md.troquel)>0 and md.baja=0
and (mo.descripcion like '%".$search."%' or md.troquel like '%".$search."%' or md.nombre like '%".$search."%' or md.codbarra like '%".$search."%' or md.laboratorio like '%".$search."%')
order by ".$order." ".$direccion." limit ".$start.", ".$len;
//echo $query;
		$result = mysqli_query($dblink,$query);

	return $result;

	}
    
    public function SelectAllFraccionados($len,$start, $search, $order, $direccion)
	{
		Global $dblink;
		$query = "SELECT md.troquel,md.nombre,md.presentacion,md.precio,md.fecha, md.codbarra, md.unidades, mo.descripcion as droga FROM ab_manualdat md
left join ab_manextra me on me.nroregistro=md.nroregistro
left join ab_monodro mo on mo.codigo=me.coddroga
where length(md.troquel)>0 and md.troquel in (select distinct troquel from medicamentosfraccionados)
and (mo.descripcion like '%".$search."%' or md.troquel like '%".$search."%' or md.nombre like '%".$search."%' or md.codbarra like '%".$search."%' or md.laboratorio like '%".$search."%')
order by ".$order." ".$direccion." limit ".$start.", ".$len;

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllReceta($search)
	{
		Global $dblink;
		$query = "SELECT md.troquel,md.nombre,md.presentacion,md.precio,md.fecha, md.codbarra, md.unidades, mo.descripcion as droga FROM ab_manualdat md
left join ab_manextra me on me.nroregistro=md.nroregistro
left join ab_monodro mo on mo.codigo=me.coddroga
where (md.nombre like '%".$search."%')
order by md.nombre ";
                //echo $query;
		$result = mysqli_query($dblink,$query);

	return $result;

	}
        public function SelectAllRecetaMonodroga($search)
	{
		Global $dblink;
		$query = "SELECT md.troquel,md.nombre,md.presentacion,md.precio,md.fecha, md.codbarra, md.unidades, mo.descripcion as droga FROM ab_manualdat md
left join ab_manextra me on me.nroregistro=md.nroregistro
left join ab_monodro mo on mo.codigo=me.coddroga
where (mo.descripcion like '%".$search."%')
order by md.nombre ";
                //echo $query;
		$result = mysqli_query($dblink,$query);

	return $result;

	}
        public function SelectAllRecetaTroquel($search)
	{
		Global $dblink;
		$query = "SELECT md.troquel,md.nombre,md.presentacion,md.precio,md.fecha, md.codbarra, md.unidades, mo.descripcion as droga FROM ab_manualdat md
left join ab_manextra me on me.nroregistro=md.nroregistro
left join ab_monodro mo on mo.codigo=me.coddroga
where (md.troquel like '%".$search."%')
order by md.nombre ";
                //echo $query;
		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllRecetaCodBarra($search)
	{
		Global $dblink;
		$query = "SELECT md.troquel,md.nombre,md.presentacion,md.precio,md.fecha, md.codbarra, md.unidades, mo.descripcion as droga FROM ab_manualdat md
left join ab_manextra me on me.nroregistro=md.nroregistro
left join ab_monodro mo on mo.codigo=me.coddroga
where md.codbarra = '".$search."' 
order by md.nombre ";
                //echo $query;
		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAll2()
	{
		Global $dblink;
		$query = "SELECT md.*, mo.descripcion as droga FROM ab_manualdat md
left join ab_manextra me on me.nroregistro=md.nroregistro
left join ab_monodro mo on mo.codigo=me.coddroga
where length(md.troquel)>0
order by md.nombre limit 20";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        
        public function SelectTodosCantidades()
	{
		Global $dblink;
		$query = "SELECT count(1) as manualdat, "
                        . "(SELECT count(1) from ab_acciofar) as acciofar, "
                        . "(SELECT count(1) from ab_barextra) as barextra, "
                        . "(SELECT count(1) from ab_formas) as formas, "
                        . "(SELECT count(1) from ab_gtin1) as gtin1, "
                        . "(SELECT count(1) from ab_manextra) as manextra, "
                        . "(SELECT count(1) from ab_monodro) as monodro, "
                        . "(SELECT count(1) from ab_multidro) as multidro, "
                        . "(SELECT count(1) from ab_nuevadro) as nuevadro, "
                        . "(SELECT count(1) from ab_regnueva) as regnueva, "
                        . "(SELECT count(1) from ab_tamanos) as tamanos, "
                        . "(SELECT count(1) from ab_tipounid) as tipounid, "
                        . "(SELECT count(1) from ab_upotenci) as upotenci, "
                        . "(SELECT count(1) from ab_vias) as vias  "
                        . "FROM ab_manualdat ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_manualdat SET 
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`presentacion` = '" . mysqli_real_escape_string($dblink,$this->getpresentacion()) . "',
						`ioma1` = '" . mysqli_real_escape_string($dblink,$this->getioma1()) . "',
						`ioma2` = '" . mysqli_real_escape_string($dblink,$this->getioma2()) . "',
						`ioma3` = '" . mysqli_real_escape_string($dblink,$this->getioma3()) . "',
						`laboratorio` = '" . mysqli_real_escape_string($dblink,$this->getlaboratorio()) . "',
						`precio` = '" . mysqli_real_escape_string($dblink,$this->getprecio()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`prodcontrolado` = '" . mysqli_real_escape_string($dblink,$this->getprodcontrolado()) . "',
						`importado` = '" . mysqli_real_escape_string($dblink,$this->getimportado()) . "',
						`tipoventa` = '" . mysqli_real_escape_string($dblink,$this->gettipoventa()) . "',
						`iva` = '" . mysqli_real_escape_string($dblink,$this->getiva()) . "',
						`coddescpami` = '" . mysqli_real_escape_string($dblink,$this->getcoddescpami()) . "',
						`codlab` = '" . mysqli_real_escape_string($dblink,$this->getcodlab()) . "',
						`baja` = '" . mysqli_real_escape_string($dblink,$this->getbaja()) . "',
						`codbarra` = '" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "',
						`unidades` = '" . mysqli_real_escape_string($dblink,$this->getunidades()) . "',
						`tamano` = '" . mysqli_real_escape_string($dblink,$this->gettamano()) . "',
						`heladera` = '" . mysqli_real_escape_string($dblink,$this->getheladera()) . "',
						`sifar` = '" . mysqli_real_escape_string($dblink,$this->getsifar()) . "',
						`gravamen` = '" . mysqli_real_escape_string($dblink,$this->getgravamen()) . "' 
						WHERE `nroregistro`='{$this->getnroregistro()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_manualdat 
						WHERE `nroregistro`='{$this->getnroregistro()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_manualdat (`troquel`,`nombre`,`presentacion`,`ioma1`,`ioma2`,`ioma3`,`laboratorio`,`precio`,`fecha`,`prodcontrolado`,`importado`,`tipoventa`,`iva`,`coddescpami`,`codlab`,`nroregistro`,`baja`,`codbarra`,`unidades`,`tamano`,`heladera`,`sifar`,`gravamen`) VALUES ('" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getpresentacion()) . "','" . mysqli_real_escape_string($dblink,$this->getioma1()) . "','" . mysqli_real_escape_string($dblink,$this->getioma2()) . "','" . mysqli_real_escape_string($dblink,$this->getioma3()) . "','" . mysqli_real_escape_string($dblink,$this->getlaboratorio()) . "','" . mysqli_real_escape_string($dblink,$this->getprecio()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getprodcontrolado()) . "','" . mysqli_real_escape_string($dblink,$this->getimportado()) . "','" . mysqli_real_escape_string($dblink,$this->gettipoventa()) . "','" . mysqli_real_escape_string($dblink,$this->getiva()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddescpami()) . "','" . mysqli_real_escape_string($dblink,$this->getcodlab()) . "','" . mysqli_real_escape_string($dblink,$this->getnroregistro()) . "','" . mysqli_real_escape_string($dblink,$this->getbaja()) . "','" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "','" . mysqli_real_escape_string($dblink,$this->gettamano()) . "','" . mysqli_real_escape_string($dblink,$this->getheladera()) . "','" . mysqli_real_escape_string($dblink,$this->getsifar()) . "','" . mysqli_real_escape_string($dblink,$this->getgravamen()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_manualdat (`troquel`,`nombre`,`presentacion`,`ioma1`,`ioma2`,`ioma3`,`laboratorio`,`precio`,`fecha`,`prodcontrolado`,`importado`,`tipoventa`,`iva`,`coddescpami`,`codlab`,`nroregistro`,`baja`,`codbarra`,`unidades`,`tamano`,`heladera`,`sifar`,`gravamen`) VALUES ('" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getpresentacion()) . "','" . mysqli_real_escape_string($dblink,$this->getioma1()) . "','" . mysqli_real_escape_string($dblink,$this->getioma2()) . "','" . mysqli_real_escape_string($dblink,$this->getioma3()) . "','" . mysqli_real_escape_string($dblink,$this->getlaboratorio()) . "','" . mysqli_real_escape_string($dblink,$this->getprecio()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getprodcontrolado()) . "','" . mysqli_real_escape_string($dblink,$this->getimportado()) . "','" . mysqli_real_escape_string($dblink,$this->gettipoventa()) . "','" . mysqli_real_escape_string($dblink,$this->getiva()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddescpami()) . "','" . mysqli_real_escape_string($dblink,$this->getcodlab()) . "','" . mysqli_real_escape_string($dblink,$this->getnroregistro()) . "','" . mysqli_real_escape_string($dblink,$this->getbaja()) . "','" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "','" . mysqli_real_escape_string($dblink,$this->gettamano()) . "','" . mysqli_real_escape_string($dblink,$this->getheladera()) . "','" . mysqli_real_escape_string($dblink,$this->getsifar()) . "','" . mysqli_real_escape_string($dblink,$this->getgravamen()) . "') ON DUPLICATE KEY UPDATE 
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`presentacion` = '" . mysqli_real_escape_string($dblink,$this->getpresentacion()) . "',
						`ioma1` = '" . mysqli_real_escape_string($dblink,$this->getioma1()) . "',
						`ioma2` = '" . mysqli_real_escape_string($dblink,$this->getioma2()) . "',
						`ioma3` = '" . mysqli_real_escape_string($dblink,$this->getioma3()) . "',
						`laboratorio` = '" . mysqli_real_escape_string($dblink,$this->getlaboratorio()) . "',
						`precio` = '" . mysqli_real_escape_string($dblink,$this->getprecio()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`prodcontrolado` = '" . mysqli_real_escape_string($dblink,$this->getprodcontrolado()) . "',
						`importado` = '" . mysqli_real_escape_string($dblink,$this->getimportado()) . "',
						`tipoventa` = '" . mysqli_real_escape_string($dblink,$this->gettipoventa()) . "',
						`iva` = '" . mysqli_real_escape_string($dblink,$this->getiva()) . "',
						`coddescpami` = '" . mysqli_real_escape_string($dblink,$this->getcoddescpami()) . "',
						`codlab` = '" . mysqli_real_escape_string($dblink,$this->getcodlab()) . "',
						`baja` = '" . mysqli_real_escape_string($dblink,$this->getbaja()) . "',
						`codbarra` = '" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "',
						`unidades` = '" . mysqli_real_escape_string($dblink,$this->getunidades()) . "',
						`tamano` = '" . mysqli_real_escape_string($dblink,$this->gettamano()) . "',
						`heladera` = '" . mysqli_real_escape_string($dblink,$this->getheladera()) . "',
						`sifar` = '" . mysqli_real_escape_string($dblink,$this->getsifar()) . "',
						`gravamen` = '" . mysqli_real_escape_string($dblink,$this->getgravamen()) . "';";
		mysqli_query($dblink,$query);

	}

	public function settroquel($troquel='')
	{
		$this->troquel = $troquel;
		return true;
	}

	public function gettroquel()
	{
		return $this->troquel;
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

	public function setpresentacion($presentacion='')
	{
		$this->presentacion = $presentacion;
		return true;
	}

	public function getpresentacion()
	{
		return $this->presentacion;
	}

	public function setioma1($ioma1='')
	{
		$this->ioma1 = $ioma1;
		return true;
	}

	public function getioma1()
	{
		return $this->ioma1;
	}

	public function setioma2($ioma2='')
	{
		$this->ioma2 = $ioma2;
		return true;
	}

	public function getioma2()
	{
		return $this->ioma2;
	}

	public function setioma3($ioma3='')
	{
		$this->ioma3 = $ioma3;
		return true;
	}

	public function getioma3()
	{
		return $this->ioma3;
	}

	public function setlaboratorio($laboratorio='')
	{
		$this->laboratorio = $laboratorio;
		return true;
	}

	public function getlaboratorio()
	{
		return $this->laboratorio;
	}

	public function setprecio($precio='')
	{
		$this->precio = $precio;
		return true;
	}

	public function getprecio()
	{
		return $this->precio;
	}

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setprodcontrolado($prodcontrolado='')
	{
		$this->prodcontrolado = $prodcontrolado;
		return true;
	}

	public function getprodcontrolado()
	{
		return $this->prodcontrolado;
	}

	public function setimportado($importado='')
	{
		$this->importado = $importado;
		return true;
	}

	public function getimportado()
	{
		return $this->importado;
	}

	public function settipoventa($tipoventa='')
	{
		$this->tipoventa = $tipoventa;
		return true;
	}

	public function gettipoventa()
	{
		return $this->tipoventa;
	}

	public function setiva($iva='')
	{
		$this->iva = $iva;
		return true;
	}

	public function getiva()
	{
		return $this->iva;
	}

	public function setcoddescpami($coddescpami='')
	{
		$this->coddescpami = $coddescpami;
		return true;
	}

	public function getcoddescpami()
	{
		return $this->coddescpami;
	}

	public function setcodlab($codlab='')
	{
		$this->codlab = $codlab;
		return true;
	}

	public function getcodlab()
	{
		return $this->codlab;
	}

	public function setnroregistro($nroregistro='')
	{
		$this->nroregistro = $nroregistro;
		return true;
	}

	public function getnroregistro()
	{
		return $this->nroregistro;
	}

	public function setbaja($baja='')
	{
		$this->baja = $baja;
		return true;
	}

	public function getbaja()
	{
		return $this->baja;
	}

	public function setcodbarra($codbarra='')
	{
		$this->codbarra = $codbarra;
		return true;
	}

	public function getcodbarra()
	{
		return $this->codbarra;
	}

	public function setunidades($unidades='')
	{
		$this->unidades = $unidades;
		return true;
	}

	public function getunidades()
	{
		return $this->unidades;
	}

	public function settamano($tamano='')
	{
		$this->tamano = $tamano;
		return true;
	}

	public function gettamano()
	{
		return $this->tamano;
	}

	public function setheladera($heladera='')
	{
		$this->heladera = $heladera;
		return true;
	}

	public function getheladera()
	{
		return $this->heladera;
	}

	public function setsifar($sifar='')
	{
		$this->sifar = $sifar;
		return true;
	}

	public function getsifar()
	{
		return $this->sifar;
	}

	public function setgravamen($gravamen='')
	{
		$this->gravamen = $gravamen;
		return true;
	}

	public function getgravamen()
	{
		return $this->gravamen;
	}

} // END class ab_manualdat

/******************************************************************************
* Class for farmacia.ab_monodro
*******************************************************************************/

class ab_monodro
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_monodro WHERE `codigo`='{$this->getcodigo()}'";

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
		$query = "SELECT * FROM ab_monodro order by descripcion";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_monodro SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_monodro 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}
        
        
        
        
        public function CreateTemp()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro_temp (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
                logc($query);
		mysqli_query($dblink,$query);

	}
        public function BorrarTemporal()
	{
		Global $dblink;
		$query ="Delete from ab_monodro_temp;";
                logc($query);
		mysqli_query($dblink,$query);
	}
        public function Actualizar()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro "
                        . "SELECT * FROM ab_monodro_temp t "
                        . "ON DUPLICATE KEY UPDATE descripcion = t.descripcion; ";
                logc($query);
		mysqli_query($dblink,$query);
	}
        
        
        
        
        public function CreateRegTemporal()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
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

} // END class ab_monodro

/******************************************************************************
* Class for farmacia.ab_multidro
*******************************************************************************/

class ab_multidro
{
	/**
	* @var int
	*/
	private $nroreg;

	/**
	* @var int
	* Class Unique ID
	*/
	private $coddroga;

	public function __construct($coddroga='')
	{
		$this->setcoddroga($coddroga);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_multidro WHERE `coddroga`='{$this->getcoddroga()}'";

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
		$query = "SELECT * FROM ab_multidro ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_multidro SET 
						`nroreg` = '" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "' 
						WHERE `coddroga`='{$this->getcoddroga()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_multidro 
						WHERE `coddroga`='{$this->getcoddroga()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_multidro (`nroreg`,`coddroga`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_multidro (`nroreg`,`coddroga`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "') ON DUPLICATE KEY UPDATE 
						`nroreg` = '" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setnroreg($nroreg='')
	{
		$this->nroreg = $nroreg;
		return true;
	}

	public function getnroreg()
	{
		return $this->nroreg;
	}

	public function setcoddroga($coddroga='')
	{
		$this->coddroga = $coddroga;
		return true;
	}

	public function getcoddroga()
	{
		return $this->coddroga;
	}

} // END class ab_multidro

/******************************************************************************
* Class for farmacia.ab_nuevadro
*******************************************************************************/

class ab_nuevadro
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $coddroga;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($coddroga='')
	{
		$this->setcoddroga($coddroga);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_nuevadro WHERE `coddroga`='{$this->getcoddroga()}'";

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
		$query = "SELECT * FROM ab_nuevadro ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_nuevadro SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `coddroga`='{$this->getcoddroga()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_nuevadro 
						WHERE `coddroga`='{$this->getcoddroga()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_nuevadro (`coddroga`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_nuevadro (`coddroga`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcoddroga($coddroga='')
	{
		$this->coddroga = $coddroga;
		return true;
	}

	public function getcoddroga()
	{
		return $this->coddroga;
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

} // END class ab_nuevadro

/******************************************************************************
* Class for farmacia.ab_regnueva
*******************************************************************************/

class ab_regnueva
{
	/**
	* @var int
	*/
	private $nroreg;

	/**
	* @var int
	* Class Unique ID
	*/
	private $coddroga;

	/**
	* @var string
	*/
	private $potencia;

	/**
	* @var int
	*/
	private $codunipotencia;

	public function __construct($coddroga='')
	{
		$this->setcoddroga($coddroga);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_regnueva WHERE `coddroga`='{$this->getcoddroga()}'";

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
		$query = "SELECT * FROM ab_regnueva ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_regnueva SET 
						`nroreg` = '" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "',
						`potencia` = '" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "',
						`codunipotencia` = '" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "' 
						WHERE `coddroga`='{$this->getcoddroga()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_regnueva 
						WHERE `coddroga`='{$this->getcoddroga()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_regnueva (`nroreg`,`coddroga`,`potencia`,`codunipotencia`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "','" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "','" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_regnueva (`nroreg`,`coddroga`,`potencia`,`codunipotencia`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "','" . mysqli_real_escape_string($dblink,$this->getcoddroga()) . "','" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "','" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "') ON DUPLICATE KEY UPDATE 
						`nroreg` = '" . mysqli_real_escape_string($dblink,$this->getnroreg()) . "',
						`potencia` = '" . mysqli_real_escape_string($dblink,$this->getpotencia()) . "',
						`codunipotencia` = '" . mysqli_real_escape_string($dblink,$this->getcodunipotencia()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setnroreg($nroreg='')
	{
		$this->nroreg = $nroreg;
		return true;
	}

	public function getnroreg()
	{
		return $this->nroreg;
	}

	public function setcoddroga($coddroga='')
	{
		$this->coddroga = $coddroga;
		return true;
	}

	public function getcoddroga()
	{
		return $this->coddroga;
	}

	public function setpotencia($potencia='')
	{
		$this->potencia = $potencia;
		return true;
	}

	public function getpotencia()
	{
		return $this->potencia;
	}

	public function setcodunipotencia($codunipotencia='')
	{
		$this->codunipotencia = $codunipotencia;
		return true;
	}

	public function getcodunipotencia()
	{
		return $this->codunipotencia;
	}

} // END class ab_regnueva

/******************************************************************************
* Class for farmacia.ab_tamanos
*******************************************************************************/

class ab_tamanos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_tamanos WHERE `codigo`='{$this->getcodigo()}'";

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
		$query = "SELECT * FROM ab_tamanos ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_tamanos SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_tamanos 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_tamanos (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_tamanos (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
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

} // END class ab_tamanos

/******************************************************************************
* Class for farmacia.ab_tipounid
*******************************************************************************/

class ab_tipounid
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_tipounid WHERE `codigo`='{$this->getcodigo()}'";

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
		$query = "SELECT * FROM ab_tipounid ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_tipounid SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_tipounid 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_tipounid (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_tipounid (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
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

} // END class ab_tipounid

/******************************************************************************
* Class for farmacia.ab_upotenci
*******************************************************************************/

class ab_upotenci
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_upotenci WHERE `codigo`='{$this->getcodigo()}'";

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
		$query = "SELECT * FROM ab_upotenci ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_upotenci SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_upotenci 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_upotenci (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_upotenci (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
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

} // END class ab_upotenci

/******************************************************************************
* Class for farmacia.ab_vias
*******************************************************************************/

class ab_vias
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_vias WHERE `codigo`='{$this->getcodigo()}'";

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
		$query = "SELECT * FROM ab_vias ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_vias SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_vias 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_vias (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_vias (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
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

} // END class ab_vias

/******************************************************************************
* Class for farmacia.caja
*******************************************************************************/

class caja
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_usuarios;

	/**
	* @var int
	*/
	private $fechaApertura;

	/**
	* @var int
	*/
	private $fechaCierre;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM caja WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}
        
        public function CargaCajaAbierta()
	{
		Global $dblink;
		$query = "SELECT * FROM caja WHERE `id_usuarios`='{$this->getid_usuarios()}' and fechaCierre is NULL order by id limit 1";

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
		$query = "SELECT @row := @row + 1 as row, c.*, (select CONCAT(u.nombre, ' ', u.apellido) from usuarios u where c.id_usuarios=u.id) as nombre FROM caja c , (SELECT @row:=0) R WHERE c.id_usuarios='{$this->getid_usuarios()}' order by c.id desc";
		$result = mysqli_query($dblink,$query);
                return $result;
	}
        
        public function ResumenPagos($idCaja=0)
	{
		Global $dblink;
                if(!$idCaja>0){$idCaja=$this->getid();}
		$query = "  select ti.nombre, sum(pa.importe) as importe from pagos pa
                            inner join tipopago ti on pa.id_tipoPago=ti.id
                            where pa.id_venta in (select id from venta where id_caja = '{$idCaja}')
                            group by ti.nombre
                            order by ti.nombre";
		$result = mysqli_query($dblink,$query);
        	return $result;
	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE caja SET 
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`fechaApertura` = '" . mysqli_real_escape_string($dblink,$this->getfechaApertura()) . "',
						`fechaCierre` = '" . mysqli_real_escape_string($dblink,$this->getfechaCierre()) . "' 
						 WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}
        
        public function CerrarCaja()
	{
		Global $dblink;
		$query = "UPDATE caja SET `fechaCierre` = now()
			  WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM caja 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO caja (`id_usuarios`,`fechaApertura`,`fechaCierre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',now(),null);";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO caja (`id`,`id_usuarios`,`fechaApertura`,`fechaCierre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getfechaApertura()) . "','" . mysqli_real_escape_string($dblink,$this->getfechaCierre()) . "') ON DUPLICATE KEY UPDATE 
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`fechaApertura` = '" . mysqli_real_escape_string($dblink,$this->getfechaApertura()) . "',
						`fechaCierre` = '" . mysqli_real_escape_string($dblink,$this->getfechaCierre()) . "';";
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

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
	}

	public function setfechaApertura($fechaApertura='')
	{
		$this->fechaApertura = $fechaApertura;
		return true;
	}

	public function getfechaApertura()
	{
		return $this->fechaApertura;
	}

	public function setfechaCierre($fechaCierre='')
	{
		$this->fechaCierre = $fechaCierre;
		return true;
	}

	public function getfechaCierre()
	{
		return $this->fechaCierre;
	}

} // END class caja

/******************************************************************************
* Class for farmacia.cierrectacte
*******************************************************************************/

class cierrectacte
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $estado;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var int
	*/
	private $id_usuarios;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM cierrectacte WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM cierrectacte ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE cierrectacte SET 
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM cierrectacte 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO cierrectacte (`id`,`estado`,`fecha`,`id_usuarios`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO cierrectacte (`id`,`estado`,`fecha`,`id_usuarios`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "') ON DUPLICATE KEY UPDATE 
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "';";
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

	public function setestado($estado='')
	{
		$this->estado = $estado;
		return true;
	}

	public function getestado()
	{
		return $this->estado;
	}

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
	}

} // END class cierrectacte

/******************************************************************************
* Class for farmacia.cierrectacte_venta
*******************************************************************************/

class cierrectacte_venta
{
	/**
	* @var int
	*/
	private $id_cierreCtaCte;

	/**
	* @var int
	*/
	private $id_venta;

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO cierrectacte_venta (`id_cierreCtaCte`,`id_venta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_cierreCtaCte()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO cierrectacte_venta (`id_cierreCtaCte`,`id_venta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_cierreCtaCte()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "') ON DUPLICATE KEY UPDATE ;";
		mysqli_query($dblink,$query);

	}

	public function setid_cierreCtaCte($id_cierreCtaCte='')
	{
		$this->id_cierreCtaCte = $id_cierreCtaCte;
		return true;
	}

	public function getid_cierreCtaCte()
	{
		return $this->id_cierreCtaCte;
	}

	public function setid_venta($id_venta='')
	{
		$this->id_venta = $id_venta;
		return true;
	}

	public function getid_venta()
	{
		return $this->id_venta;
	}

} // END class cierrectacte_venta

/******************************************************************************
* Class for farmacia.enviosproveedores
*******************************************************************************/

class enviosproveedores
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var string
	*/
	private $estado;

	/**
	* @var int
	*/
	private $id_usuarios;

	/**
	* @var int
	*/
	private $id_proveedores;

	/**
	* @var int
	*/
	private $id_farmacias;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM enviosproveedores WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM enviosproveedores ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE enviosproveedores SET 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`id_proveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "',
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM enviosproveedores 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO enviosproveedores (`id`,`fecha`,`estado`,`id_usuarios`,`id_proveedores`,`id_farmacias`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO enviosproveedores (`id`,`fecha`,`estado`,`id_usuarios`,`id_proveedores`,`id_farmacias`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "') ON DUPLICATE KEY UPDATE 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`id_proveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "',
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "';";
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

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setestado($estado='')
	{
		$this->estado = $estado;
		return true;
	}

	public function getestado()
	{
		return $this->estado;
	}

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
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

	public function setid_farmacias($id_farmacias='')
	{
		$this->id_farmacias = $id_farmacias;
		return true;
	}

	public function getid_farmacias()
	{
		return $this->id_farmacias;
	}

} // END class enviosproveedores

/******************************************************************************
* Class for farmacia.farmacias
*******************************************************************************/

class farmacias
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $telefono;

	/**
	* @var string
	*/
	private $email;

	/**
	* @var string
	*/
	private $domicilio;
        
        private $tipoIva;
        
        private $cuit;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM farmacias WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM farmacias ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE farmacias SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`email` = '" . mysqli_real_escape_string($dblink,$this->getemail()) . "',
						`domicilio` = '" . mysqli_real_escape_string($dblink,$this->getdomicilio()) . "', 
                                                `tipoIva` = '" . mysqli_real_escape_string($dblink,$this->gettipoIva()) . "',
						`cuit` = '" . mysqli_real_escape_string($dblink,$this->getcuit()) . "'
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM farmacias 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO farmacias (`id`,`nombre`,`telefono`,`email`,`domicilio`,`tipoIva`,`cuit`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getemail()) . "','" . mysqli_real_escape_string($dblink,$this->getdomicilio()) . "','" . mysqli_real_escape_string($dblink,$this->gettipoIva()) . "','" . mysqli_real_escape_string($dblink,$this->getcuit()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO farmacias (`id`,`nombre`,`telefono`,`email`,`domicilio`,`tipoIva`,`cuit`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getemail()) . "','" . mysqli_real_escape_string($dblink,$this->getdomicilio()) . "','" . mysqli_real_escape_string($dblink,$this->gettipoIva()) . "','" . mysqli_real_escape_string($dblink,$this->getcuit()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`email` = '" . mysqli_real_escape_string($dblink,$this->getemail()) . "',
                                                `domicilio` = '" . mysqli_real_escape_string($dblink,$this->getdomicilio()) . "',
                                                `tipoIva` = '" . mysqli_real_escape_string($dblink,$this->gettipoIva()) . "',
						`cuit` = '" . mysqli_real_escape_string($dblink,$this->getcuit()) . "';";
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

	public function settelefono($telefono='')
	{
		$this->telefono = $telefono;
		return true;
	}

	public function gettelefono()
	{
		return $this->telefono;
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

	public function setdomicilio($domicilio='')
	{
		$this->domicilio = $domicilio;
		return true;
	}

	public function getdomicilio()
	{
		return $this->domicilio;
	}
        
        public function settipoIva($tipoIva='')
	{
		$this->tipoIva = $tipoIva;
		return true;
	}

	public function gettipoIva()
	{
		return $this->tipoIva;
	}
        
        public function setcuit($cuit='')
	{
		$this->cuit = $cuit;
		return true;
	}

	public function getcuit()
	{
		return $this->cuit;
	}

} // END class farmacias

/******************************************************************************
* Class for farmacia.impresoras
*******************************************************************************/

class impresoras
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $server;

	/**
	* @var string
	*/
	private $nombrecompartida;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM impresoras WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM impresoras ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE impresoras SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`server` = '" . mysqli_real_escape_string($dblink,$this->getserver()) . "',
						`nombrecompartida` = '" . mysqli_real_escape_string($dblink,$this->getnombrecompartida()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM impresoras 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO impresoras (`id`,`nombre`,`server`,`nombrecompartida`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getserver()) . "','" . mysqli_real_escape_string($dblink,$this->getnombrecompartida()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO impresoras (`id`,`nombre`,`server`,`nombrecompartida`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getserver()) . "','" . mysqli_real_escape_string($dblink,$this->getnombrecompartida()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`server` = '" . mysqli_real_escape_string($dblink,$this->getserver()) . "',
						`nombrecompartida` = '" . mysqli_real_escape_string($dblink,$this->getnombrecompartida()) . "';";
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

	public function setserver($server='')
	{
		$this->server = $server;
		return true;
	}

	public function getserver()
	{
		return $this->server;
	}

	public function setnombrecompartida($nombrecompartida='')
	{
		$this->nombrecompartida = $nombrecompartida;
		return true;
	}

	public function getnombrecompartida()
	{
		return $this->nombrecompartida;
	}

} // END class impresoras

/******************************************************************************
* Class for farmacia.links
*******************************************************************************/

class links
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $url;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var int
	*/
	private $id_menu;

	/**
	* @var int
	*/
	private $orden;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM links WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT l.*,ifnull(m.nombre,'-') as menu FROM links l left join menu m on l.id_menu=m.id where '" . mysqli_real_escape_string($dblink,$this->getid_menu()) . "'='' or l.id_menu = '" . mysqli_real_escape_string($dblink,$this->getid_menu()) . "'";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllxUsuario($usuario)
	{
		Global $dblink;
		$query = "SELECT l.*,ifnull(m.nombre,'-') as menu FROM links l inner join roles_links rl on rl.id_links=l.id inner join usuarios_roles ur on ur.id_roles=rl.id_roles and ur.id_usuarios=".$usuario." left join menu m on l.id_menu=m.id where '" . mysqli_real_escape_string($dblink,$this->getid_menu()) . "'='' or l.id_menu = '" . mysqli_real_escape_string($dblink,$this->getid_menu()) . "' order by ifnull(l.orden,0)";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        
        
        

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE links SET 
						`url` = '" . mysqli_real_escape_string($dblink,$this->geturl()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`id_menu` = '" . mysqli_real_escape_string($dblink,$this->getid_menu()) . "',
						`orden` = '" . mysqli_real_escape_string($dblink,$this->getorden()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM links 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO links (`id`,`url`,`nombre`,`id_menu`,`orden`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->geturl()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getid_menu()) . "','" . mysqli_real_escape_string($dblink,$this->getorden()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
                if(mysqli_real_escape_string($dblink,$this->getid_menu())==''){$idm = "NULL"; }
                else{$idm = "'" . mysqli_real_escape_string($dblink,$this->getid_menu()) . "'";}
                
		$query ="INSERT INTO links (`id`,`url`,`nombre`,`id_menu`,`orden`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->geturl()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "'," . $idm . ",'" . mysqli_real_escape_string($dblink,$this->getorden()) . "') ON DUPLICATE KEY UPDATE 
						`url` = '" . mysqli_real_escape_string($dblink,$this->geturl()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
                                                `id_menu` = " . $idm . ",
                                                `orden` = '" . mysqli_real_escape_string($dblink,$this->getorden()) . "';";
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

	public function seturl($url='')
	{
		$this->url = $url;
		return true;
	}

	public function geturl()
	{
		return $this->url;
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

	public function setid_menu($id_menu='')
	{       
            	$this->id_menu = $id_menu;
		return true;
	}

	public function getid_menu()
	{
		return $this->id_menu;
	}

	public function setorden($orden='')
	{
		$this->orden = $orden;
		return true;
	}

	public function getorden()
	{
		return $this->orden;
	}

} // END class links




/******************************************************************************
* Class for farmacia.medicamentosfraccionados
*******************************************************************************/

class medicamentosfraccionados
{
	/**
	* @var string
	* Class Unique ID
	*/
	private $troquel;

	/**
	* @var int
	*/
	private $unidades;

	/**
	* @var string
	*/
	private $cntMostrar;

	public function __construct($troquel='')
	{
		$this->settroquel($troquel);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM medicamentosfraccionados WHERE `troquel`='{$this->gettroquel()}'";

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
		$query = "SELECT * FROM medicamentosfraccionados WHERE `troquel`='{$this->gettroquel()}'"; 
                $result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE medicamentosfraccionados SET 
						`unidades` = '" . mysqli_real_escape_string($dblink,$this->getunidades()) . "',
						`cntMostrar` = '" . mysqli_real_escape_string($dblink,$this->getcntMostrar()) . "' 
						WHERE `troquel`='{$this->gettroquel()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM medicamentosfraccionados 
						WHERE `troquel`='{$this->gettroquel()}' and `unidades`='{$this->getunidades()}' and `cntMostrar`='{$this->getcntMostrar()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO medicamentosfraccionados (`troquel`,`unidades`,`cntMostrar`) VALUES ('" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "','" . mysqli_real_escape_string($dblink,$this->getcntMostrar()) . "');";
                
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO medicamentosfraccionados (`troquel`,`unidades`,`cntMostrar`) VALUES ('" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "','" . mysqli_real_escape_string($dblink,$this->getcntMostrar()) . "') ON DUPLICATE KEY UPDATE 
						`unidades` = '" . mysqli_real_escape_string($dblink,$this->getunidades()) . "',
						`cntMostrar` = '" . mysqli_real_escape_string($dblink,$this->getcntMostrar()) . "';";
		mysqli_query($dblink,$query);

	}

	public function settroquel($troquel='')
	{
		$this->troquel = $troquel;
		return true;
	}

	public function gettroquel()
	{
		return $this->troquel;
	}

	public function setunidades($unidades='')
	{
		$this->unidades = $unidades;
		return true;
	}

	public function getunidades()
	{
		return $this->unidades;
	}

	public function setcntMostrar($cntMostrar='')
	{
		$this->cntMostrar = $cntMostrar;
		return true;
	}

	public function getcntMostrar()
	{
		return $this->cntMostrar;
	}

} // END class medicamentosfraccionados


/******************************************************************************
* Class for farmacia.menu
*******************************************************************************/

class menu
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var int
	*/
	private $orden;

	/**
	* @var string
	*/
	private $icono;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM menu WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM menu  order by orden, nombre";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        public function SelectAllUsuario($usuario)
	{
		Global $dblink;
		$query = "SELECT * FROM menu 
                          where id in (
                            select distinct l.id_menu from links l
                            inner join roles_links rl on rl.id_links=l.id
                            inner join usuarios_roles ur on ur.id_roles=rl.id_roles and ur.id_usuarios=".$usuario.")"
                        . "order by orden, nombre";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE menu SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`orden` = '" . mysqli_real_escape_string($dblink,$this->getorden()) . "',
						`icono` = '" . mysqli_real_escape_string($dblink,$this->geticono()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM menu 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO menu (`id`,`nombre`,`orden`,`icono`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getorden()) . "','" . mysqli_real_escape_string($dblink,$this->geticono()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO menu (`id`,`nombre`,`orden`,`icono`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getorden()) . "','" . mysqli_real_escape_string($dblink,$this->geticono()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`orden` = '" . mysqli_real_escape_string($dblink,$this->getorden()) . "',
						`icono` = '" . mysqli_real_escape_string($dblink,$this->geticono()) . "';";
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

	public function setorden($orden='')
	{
		$this->orden = $orden;
		return true;
	}

	public function getorden()
	{
		return $this->orden;
	}

	public function seticono($icono='')
	{
		$this->icono = $icono;
		return true;
	}

	public function geticono()
	{
		return $this->icono;
	}

} // END class menu



/******************************************************************************
* Class for farmacia.movimientosmanuales
*******************************************************************************/

class movimientosmanuales
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_caja;

	/**
	* @var 
	*/
	private $importe;

	/**
	* @var string
	*/
	private $observaciones;

	/**
	* @var string
	*/
	private $tipo;

	/**
	* @var int
	*/
	private $id_tipoPago;

	/**
	* @var int
	*/
	private $fecha;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM movimientosmanuales WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM movimientosmanuales ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE movimientosmanuales SET 
						`id_caja` = '" . mysqli_real_escape_string($dblink,$this->getid_caja()) . "',
						`importe` = '" . mysqli_real_escape_string($dblink,$this->getimporte()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "',
						`tipo` = '" . mysqli_real_escape_string($dblink,$this->gettipo()) . "',
						`id_tipoPago` = '" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM movimientosmanuales 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO movimientosmanuales (`id`,`id_caja`,`importe`,`observaciones`,`tipo`,`id_tipoPago`,`fecha`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_caja()) . "','" . mysqli_real_escape_string($dblink,$this->getimporte()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$this->gettipo()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO movimientosmanuales (`id`,`id_caja`,`importe`,`observaciones`,`tipo`,`id_tipoPago`,`fecha`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_caja()) . "','" . mysqli_real_escape_string($dblink,$this->getimporte()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$this->gettipo()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "') ON DUPLICATE KEY UPDATE 
						`id_caja` = '" . mysqli_real_escape_string($dblink,$this->getid_caja()) . "',
						`importe` = '" . mysqli_real_escape_string($dblink,$this->getimporte()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "',
						`tipo` = '" . mysqli_real_escape_string($dblink,$this->gettipo()) . "',
						`id_tipoPago` = '" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "';";
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

	public function setid_caja($id_caja='')
	{
		$this->id_caja = $id_caja;
		return true;
	}

	public function getid_caja()
	{
		return $this->id_caja;
	}

	public function setimporte($importe='')
	{
		$this->importe = $importe;
		return true;
	}

	public function getimporte()
	{
		return $this->importe;
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

	public function settipo($tipo='')
	{
		$this->tipo = $tipo;
		return true;
	}

	public function gettipo()
	{
		return $this->tipo;
	}

	public function setid_tipoPago($id_tipoPago='')
	{
		$this->id_tipoPago = $id_tipoPago;
		return true;
	}

	public function getid_tipoPago()
	{
		return $this->id_tipoPago;
	}

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

} // END class movimientosmanuales

/******************************************************************************
* Class for farmacia.obrasocial
*******************************************************************************/

class obrasocial
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $idaxml;

	/**
	* @var string
	*/
	private $rtaxml;

	/**
	* @var int
	*/
	private $activa;

	/**
	* @var string
	*/
	private $observaciones;

	/**
	* @var string
	*/
	private $telefono;

	/**
	* @var int
	*/
	private $codFinanciador;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM obrasocial WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM obrasocial order by nombre ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE obrasocial SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`idaxml` = '" . mysqli_real_escape_string($dblink,$this->getidaxml()) . "',
						`rtaxml` = '" . mysqli_real_escape_string($dblink,$this->getrtaxml()) . "',
						`activa` = '" . mysqli_real_escape_string($dblink,$this->getactiva()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`codFinanciador` = '" . mysqli_real_escape_string($dblink,$this->getcodFinanciador()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM obrasocial 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO obrasocial (`id`,`nombre`,`idaxml`,`rtaxml`,`activa`,`observaciones`,`telefono`,`codFinanciador`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getidaxml()) . "','" . mysqli_real_escape_string($dblink,$this->getrtaxml()) . "','" . mysqli_real_escape_string($dblink,$this->getactiva()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getcodFinanciador()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO obrasocial (`id`,`nombre`,`idaxml`,`rtaxml`,`activa`,`observaciones`,`telefono`,`codFinanciador`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getidaxml()) . "','" . mysqli_real_escape_string($dblink,$this->getrtaxml()) . "','" . mysqli_real_escape_string($dblink,$this->getactiva()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getcodFinanciador()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`idaxml` = '" . addslashes($this->getidaxml()) . "',
						`rtaxml` = '" .  addslashes($this->getrtaxml()) . "',
						`activa` = '" . mysqli_real_escape_string($dblink,$this->getactiva()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`codFinanciador` = '" . mysqli_real_escape_string($dblink,$this->getcodFinanciador()) . "';";
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

	public function setidaxml($idaxml='')
	{
		$this->idaxml = $idaxml;
		return true;
	}

	public function getidaxml()
	{
		return $this->idaxml;
	}

	public function setrtaxml($rtaxml='')
	{
		$this->rtaxml = $rtaxml;
		return true;
	}

	public function getrtaxml()
	{
		return $this->rtaxml;
	}

	public function setactiva($activa='')
	{
		$this->activa = $activa;
		return true;
	}

	public function getactiva()
	{
		return $this->activa;
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

	public function settelefono($telefono='')
	{
		$this->telefono = $telefono;
		return true;
	}

	public function gettelefono()
	{
		return $this->telefono;
	}

	public function setcodFinanciador($codFinanciador='')
	{
		$this->codFinanciador = $codFinanciador;
		return true;
	}

	public function getcodFinanciador()
	{
		return $this->codFinanciador;
	}

} // END class obrasocial

/******************************************************************************
* Class for farmacia.pagos
*******************************************************************************/

class pagos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var 
	*/
	private $importe;

	/**
	* @var int
	*/
	private $id_venta;

	/**
	* @var int
	*/
	private $id_tipoPago;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM pagos WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT tp.nombre, p.* FROM pagos p inner join tipopago tp on tp.id=p.id_tipoPago where `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' order by id desc ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function registraCtaCte()
	{
		Global $dblink;
		$query = "SELECT count(1) as cnt FROM pagos p inner join tipopago tp on tp.id=p.id_tipoPago where `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' and tp.mascara = 'C' ";

		$result = mysqli_query($dblink,$query);
                $x = mysqli_fetch_assoc($result);
                return $x['cnt'];

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE pagos SET 
						`importe` = '" . mysqli_real_escape_string($dblink,$this->getimporte()) . "',
						`id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "',
						`id_tipoPago` = '" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM pagos 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO pagos (`id`,`importe`,`id_venta`,`id_tipoPago`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getimporte()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO pagos (`id`,`importe`,`id_venta`,`id_tipoPago`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getimporte()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "') ON DUPLICATE KEY UPDATE 
						`importe` = '" . mysqli_real_escape_string($dblink,$this->getimporte()) . "',
						`id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "',
						`id_tipoPago` = '" . mysqli_real_escape_string($dblink,$this->getid_tipoPago()) . "';";
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

	public function setimporte($importe='')
	{
		$this->importe = $importe;
		return true;
	}

	public function getimporte()
	{
		return $this->importe;
	}

	public function setid_venta($id_venta='')
	{
		$this->id_venta = $id_venta;
		return true;
	}

	public function getid_venta()
	{
		return $this->id_venta;
	}

	public function setid_tipoPago($id_tipoPago='')
	{
		$this->id_tipoPago = $id_tipoPago;
		return true;
	}

	public function getid_tipoPago()
	{
		return $this->id_tipoPago;
	}

} // END class pagos

/******************************************************************************
* Class for farmacia.pedidosmedicamentos
*******************************************************************************/

class pedidosmedicamentos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var string
	*/
	private $troquel;

	/**
	* @var int
	*/
	private $cantidad;

	/**
	* @var int
	*/
	private $id_proveedores;

	/**
	* @var int
	*/
	private $id_usuarios;

	/**
	* @var int
	*/
	private $id_enviosProveedores;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM pedidosmedicamentos WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM pedidosmedicamentos ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE pedidosmedicamentos SET 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`id_proveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`id_enviosProveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM pedidosmedicamentos 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO pedidosmedicamentos (`id`,`fecha`,`troquel`,`cantidad`,`id_proveedores`,`id_usuarios`,`id_enviosProveedores`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO pedidosmedicamentos (`id`,`fecha`,`troquel`,`cantidad`,`id_proveedores`,`id_usuarios`,`id_enviosProveedores`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "') ON DUPLICATE KEY UPDATE 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`id_proveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`id_enviosProveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "';";
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

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function settroquel($troquel='')
	{
		$this->troquel = $troquel;
		return true;
	}

	public function gettroquel()
	{
		return $this->troquel;
	}

	public function setcantidad($cantidad='')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
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

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
	}

	public function setid_enviosProveedores($id_enviosProveedores='')
	{
		$this->id_enviosProveedores = $id_enviosProveedores;
		return true;
	}

	public function getid_enviosProveedores()
	{
		return $this->id_enviosProveedores;
	}

} // END class pedidosmedicamentos

/******************************************************************************
* Class for farmacia.pedidosproductos
*******************************************************************************/

class pedidosproductos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_productos;

	/**
	* @var int
	*/
	private $cantidad;

	/**
	* @var int
	*/
	private $id_proveedores;

	/**
	* @var int
	*/
	private $id_usuarios;

	/**
	* @var int
	*/
	private $id_enviosProveedores;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM pedidosproductos WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM pedidosproductos ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE pedidosproductos SET 
						`id_productos` = '" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`id_proveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`id_enviosProveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM pedidosproductos 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO pedidosproductos (`id`,`id_productos`,`cantidad`,`id_proveedores`,`id_usuarios`,`id_enviosProveedores`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO pedidosproductos (`id`,`id_productos`,`cantidad`,`id_proveedores`,`id_usuarios`,`id_enviosProveedores`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "') ON DUPLICATE KEY UPDATE 
						`id_productos` = '" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`id_proveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`id_enviosProveedores` = '" . mysqli_real_escape_string($dblink,$this->getid_enviosProveedores()) . "';";
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

	public function setid_productos($id_productos='')
	{
		$this->id_productos = $id_productos;
		return true;
	}

	public function getid_productos()
	{
		return $this->id_productos;
	}

	public function setcantidad($cantidad='')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
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

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
	}

	public function setid_enviosProveedores($id_enviosProveedores='')
	{
		$this->id_enviosProveedores = $id_enviosProveedores;
		return true;
	}

	public function getid_enviosProveedores()
	{
		return $this->id_enviosProveedores;
	}

} // END class pedidosproductos

/******************************************************************************
* Class for farmacia.personas
*******************************************************************************/

class personas
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $tipodoc;

	/**
	* @var int
	*/
	private $doc;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $apellido;

	/**
	* @var string
	*/
	private $credencial;

	/**
	* @var int
	*/
	private $idOS;

	/**
	* @var int
	*/
	private $fechaNac;

	/**
	* @var string
	*/
	private $telefono;

	/**
	* @var string
	*/
	private $email;

	/**
	* @var string
	*/
	private $observaciones;

	/**
	* @var string
	*/
	private $estadoCtaCte;

	/**
	* @var 
	*/
	private $limite;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM personas WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT p.*,os.nombre as ObraSocial FROM personas p left join obrasocial os on os.id=p.idOS";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE personas SET 
						`tipodoc` = '" . mysqli_real_escape_string($dblink,$this->gettipodoc()) . "',
						`doc` = '" . mysqli_real_escape_string($dblink,$this->getdoc()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`apellido` = '" . mysqli_real_escape_string($dblink,$this->getapellido()) . "',
						`credencial` = '" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "',
						`idOS` = '" . mysqli_real_escape_string($dblink,$this->getidOS()) . "',
						`fechaNac` = '" . mysqli_real_escape_string($dblink,$this->getfechaNac()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`email` = '" . mysqli_real_escape_string($dblink,$this->getemail()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "',
						`estadoCtaCte` = '" . mysqli_real_escape_string($dblink,$this->getestadoCtaCte()) . "',
						`limite` = '" . mysqli_real_escape_string($dblink,$this->getlimite()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM personas 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO personas (`id`,`tipodoc`,`doc`,`nombre`,`apellido`,`credencial`,`idOS`,`fechaNac`,`telefono`,`email`,`observaciones`,`estadoCtaCte`,`limite`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->gettipodoc()) . "','" . mysqli_real_escape_string($dblink,$this->getdoc()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getapellido()) . "','" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "','" . mysqli_real_escape_string($dblink,$this->getidOS()) . "','" . mysqli_real_escape_string($dblink,$this->getfechaNac()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getemail()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$this->getestadoCtaCte()) . "','" . mysqli_real_escape_string($dblink,$this->getlimite()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO personas (`id`,`tipodoc`,`doc`,`nombre`,`apellido`,`credencial`,`idOS`,`fechaNac`,`telefono`,`email`,`observaciones`,`estadoCtaCte`,`limite`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->gettipodoc()) . "','" . mysqli_real_escape_string($dblink,$this->getdoc()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getapellido()) . "','" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "','" . mysqli_real_escape_string($dblink,$this->getidOS()) . "','" . mysqli_real_escape_string($dblink,$this->getfechaNac()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getemail()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink,$this->getestadoCtaCte()) . "','" . mysqli_real_escape_string($dblink,$this->getlimite()) . "') ON DUPLICATE KEY UPDATE 
						`tipodoc` = '" . mysqli_real_escape_string($dblink,$this->gettipodoc()) . "',
						`doc` = '" . mysqli_real_escape_string($dblink,$this->getdoc()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`apellido` = '" . mysqli_real_escape_string($dblink,$this->getapellido()) . "',
						`credencial` = '" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "',
						`idOS` = '" . mysqli_real_escape_string($dblink,$this->getidOS()) . "',
						`fechaNac` = '" . mysqli_real_escape_string($dblink,$this->getfechaNac()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`email` = '" . mysqli_real_escape_string($dblink,$this->getemail()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "',
						`estadoCtaCte` = '" . mysqli_real_escape_string($dblink,$this->getestadoCtaCte()) . "',
						`limite` = '" . mysqli_real_escape_string($dblink,$this->getlimite()) . "';";
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

	public function settipodoc($tipodoc='')
	{
		$this->tipodoc = $tipodoc;
		return true;
	}

	public function gettipodoc()
	{
		return $this->tipodoc;
	}

	public function setdoc($doc='')
	{
		$this->doc = $doc;
		return true;
	}

	public function getdoc()
	{
		return $this->doc;
	}

	public function setnombre($nombre='')
	{
		$this->nombre = primeraMayuscula($nombre);
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
	}

	public function setapellido($apellido='')
	{
		$this->apellido = primeraMayuscula($apellido);
		return true;
	}

	public function getapellido()
	{
		return $this->apellido;
	}

	public function setcredencial($credencial='')
	{
		$this->credencial = $credencial;
		return true;
	}

	public function getcredencial()
	{
		return $this->credencial;
	}

	public function setidOS($idOS='')
	{
		$this->idOS = $idOS;
		return true;
	}

	public function getidOS()
	{
		return $this->idOS;
	}

	public function setfechaNac($fechaNac='')
	{
		$this->fechaNac = $fechaNac;
		return true;
	}

	public function getfechaNac()
	{
		return $this->fechaNac;
	}

	public function settelefono($telefono='')
	{
		$this->telefono = $telefono;
		return true;
	}

	public function gettelefono()
	{
		return $this->telefono;
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

	public function setobservaciones($observaciones='')
	{
		$this->observaciones = $observaciones;
		return true;
	}

	public function getobservaciones()
	{
		return $this->observaciones;
	}

	public function setestadoCtaCte($estadoCtaCte='')
	{
		$this->estadoCtaCte = $estadoCtaCte;
		return true;
	}

	public function getestadoCtaCte()
	{
		return $this->estadoCtaCte;
	}

	public function setlimite($limite='')
	{
		$this->limite = $limite;
		return true;
	}

	public function getlimite()
	{
		return $this->limite;
	}

} // END class personas

/******************************************************************************
* Class for farmacia.productos
*******************************************************************************/

class productos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var 
	*/
	private $precio;

	/**
	* @var int
	*/
	private $habilitado;

	/**
	* @var int
	*/
	private $id_tipoproductos;

	/**
	* @var string
	*/
	private $codbarra;

	/**
	* @var string
	*/
	private $descripcion;

	/**
	* @var int
	*/
	private $editaprecio;

	/**
	* @var int
	*/
	private $unidades;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM productos WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}
        
        public function Cnt($search)
	{
		Global $dblink;
		$query = "SELECT count(1) as recordsFiltered, (SELECT count(1) FROM productos) as recordsTotal FROM productos p
                          where p.codigo='".$search."' or p.nombre like '%".$search."%' or p.codbarra like '".$search."' ";
                
		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function SelectAll($len,$start, $search, $order, $direccion)
	{
		Global $dblink;
		$query = "SELECT p.*,tp.nombre as tipo, sp.cnt, sp.cntUnidades, sp.id_farmacias, sp.id_ubicacion FROM productos p left join tipoproductos tp on tp.id=p.id_tipoproductos left join stockproductos sp on sp.id_productos=p.id"
                        . " where p.codigo='".$search."' or p.nombre like '%".$search."%' or p.codbarra like '".$search."' "
                        . " order by ".$order." ".$direccion." limit ".$start.", ".$len;

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllItemBuscar($search, $idFarmacia)
	{
		Global $dblink;
		$query = "SELECT p.*,tp.nombre as tipo, sp.cnt as stockCnt, sp.cntUnidades as stockCntUnidades, u.nombre as ubicacion FROM productos p left join tipoproductos tp on tp.id=p.id_tipoproductos left join stockproductos sp on sp.id_productos=p.id and sp.id_farmacias=".$idFarmacia." left join ubicacion u on sp.id_ubicacion=u.id"
                        . " where p.codigo='".$search."' or p.nombre like '%".$search."%' or p.codbarra like '".$search."' "
                        . " order by p.nombre";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE productos SET 
						`codigo` = '" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`precio` = '" . mysqli_real_escape_string($dblink,$this->getprecio()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "',
						`id_tipoproductos` = '" . mysqli_real_escape_string($dblink,$this->getid_tipoproductos()) . "',
						`codbarra` = '" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "',
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "',
						`editaprecio` = '" . mysqli_real_escape_string($dblink,$this->geteditaprecio()) . "',
						`unidades` = '" . mysqli_real_escape_string($dblink,$this->getunidades()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM productos 
						WHERE `id`='{$this->getid()}'";
                                                
		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO productos (`id`,`codigo`,`nombre`,`precio`,`habilitado`,`id_tipoproductos`,`codbarra`,`descripcion`,`editaprecio`,`unidades`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getprecio()) . "','" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoproductos()) . "','" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "','" . mysqli_real_escape_string($dblink,$this->geteditaprecio()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO productos (`id`,`codigo`,`nombre`,`precio`,`habilitado`,`id_tipoproductos`,`codbarra`,`descripcion`,`editaprecio`,`unidades`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getprecio()) . "','" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoproductos()) . "','" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "','" . mysqli_real_escape_string($dblink,$this->geteditaprecio()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "') ON DUPLICATE KEY UPDATE 
						`codigo` = '" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`precio` = '" . mysqli_real_escape_string($dblink,$this->getprecio()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "',
						`id_tipoproductos` = '" . mysqli_real_escape_string($dblink,$this->getid_tipoproductos()) . "',
						`codbarra` = '" . mysqli_real_escape_string($dblink,$this->getcodbarra()) . "',
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "',
						`editaprecio` = '" . mysqli_real_escape_string($dblink,$this->geteditaprecio()) . "',
						`unidades` = '" . mysqli_real_escape_string($dblink,$this->getunidades()) . "';";
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

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
	}

	public function setnombre($nombre='')
	{
		$this->nombre = primeraMayuscula($nombre);
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
	}

	public function setprecio($precio='')
	{
		$this->precio = $precio;
		return true;
	}

	public function getprecio()
	{
		return $this->precio;
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

	public function setid_tipoproductos($id_tipoproductos='')
	{
		$this->id_tipoproductos = $id_tipoproductos;
		return true;
	}

	public function getid_tipoproductos()
	{
		return $this->id_tipoproductos;
	}

	public function setcodbarra($codbarra='')
	{
		$this->codbarra = $codbarra;
		return true;
	}

	public function getcodbarra()
	{
		return $this->codbarra;
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

	public function seteditaprecio($editaprecio='')
	{
		$this->editaprecio = $editaprecio;
		return true;
	}

	public function geteditaprecio()
	{
		return $this->editaprecio;
	}

	public function setunidades($unidades='')
	{
		$this->unidades = $unidades;
		return true;
	}

	public function getunidades()
	{
		return $this->unidades;
	}

} // END class productos

/******************************************************************************
* Class for farmacia.productosfraccionados
*******************************************************************************/

class productosfraccionados
{
	/**
	* @var int
	*/
	private $id_productos;

	/**
	* @var int
	*/
	private $unidades;

	/**
	* @var string
	*/
	private $cntMostrar;
        
        public function SelectAll()
	{
		Global $dblink;
		$query = "SELECT * FROM productosfraccionados WHERE `id_productos`='{$this->getid_productos()}'";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO productosfraccionados (`id_productos`,`unidades`,`cntMostrar`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "','" . mysqli_real_escape_string($dblink,$this->getcntMostrar()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO productosfraccionados (`id_productos`,`unidades`,`cntMostrar`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getunidades()) . "','" . mysqli_real_escape_string($dblink,$this->getcntMostrar()) . "') ON DUPLICATE KEY UPDATE ;";
		mysqli_query($dblink,$query);

	}
        
        public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM productosfraccionados 
						WHERE `id_productos`='{$this->getid_productos()}' and `unidades`='{$this->getunidades()}' and `cntMostrar`='{$this->getcntMostrar()}'";

		mysqli_query($dblink,$query);

	}

	public function setid_productos($id_productos='')
	{
		$this->id_productos = $id_productos;
		return true;
	}

	public function getid_productos()
	{
		return $this->id_productos;
	}

	public function setunidades($unidades='')
	{
		$this->unidades = $unidades;
		return true;
	}

	public function getunidades()
	{
		return $this->unidades;
	}

	public function setcntMostrar($cntMostrar='')
	{
		$this->cntMostrar = $cntMostrar;
		return true;
	}

	public function getcntMostrar()
	{
		return $this->cntMostrar;
	}

} // END class productosfraccionados

/******************************************************************************
* Class for farmacia.proveedores
*******************************************************************************/

class proveedores
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $email;

	/**
	* @var string
	*/
	private $telefono;

	/**
	* @var string
	*/
	private $estado;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM proveedores WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM proveedores ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE proveedores SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`email` = '" . mysqli_real_escape_string($dblink,$this->getemail()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM proveedores 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO proveedores (`id`,`nombre`,`email`,`telefono`,`estado`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getemail()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO proveedores (`id`,`nombre`,`email`,`telefono`,`estado`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getemail()) . "','" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`email` = '" . mysqli_real_escape_string($dblink,$this->getemail()) . "',
						`telefono` = '" . mysqli_real_escape_string($dblink,$this->gettelefono()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "';";
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
		$this->nombre = primeraMayuscula($nombre);
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
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

	public function settelefono($telefono='')
	{
		$this->telefono = $telefono;
		return true;
	}

	public function gettelefono()
	{
		return $this->telefono;
	}

	public function setestado($estado='')
	{
		$this->estado = $estado;
		return true;
	}

	public function getestado()
	{
		return $this->estado;
	}

} // END class proveedores

/******************************************************************************
* Class for farmacia.provinciaprestador
*******************************************************************************/

class provinciaprestador
{
	/**
	* @var string
	* Class Unique ID
	*/
	private $cod;

	/**
	* @var string
	*/
	private $nombre;

	public function __construct($cod='')
	{
		$this->setcod($cod);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM provinciaprestador WHERE `cod`='{$this->getcod()}'";

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
		$query = "SELECT * FROM provinciaprestador order by nombre ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE provinciaprestador SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "' 
						WHERE `cod`='{$this->getcod()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM provinciaprestador 
						WHERE `cod`='{$this->getcod()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO provinciaprestador (`cod`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcod()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO provinciaprestador (`cod`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcod()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "';";
		mysqli_query($dblink,$query);

	}

	public function setcod($cod='')
	{
		$this->cod = $cod;
		return true;
	}

	public function getcod()
	{
		return $this->cod;
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

} // END class provinciaprestador

/******************************************************************************
* Class for farmacia.receta
*******************************************************************************/

class recetas
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $credencial;

	/**
	* @var int
	*/
	private $id_obrasocial;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var int
	*/
	private $fechaReceta;

	/**
	* @var string
	*/
	private $cod_provinciaPrestador;

	/**
	* @var int
	*/
	private $id_tipoMatriculaPrestador;

	/**
	* @var string
	*/
	private $matriculaPrestador;

	/**
	* @var int
	*/
	private $id_usuarios;

	/**
	* @var string
	*/
	private $estado;

	/**
	* @var int
	*/
	private $idRefValidacion;

	/**
	* @var int
	*/
	private $id_venta;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM recetas WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT *,ifnull((select nombre from obrasocial where id=recetas.id_obrasocial limit 1),'') as OS FROM recetas WHERE estado in ('A', 'N') and `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' ORDER BY id";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllBuscador($fd, $fh, $prod, $est, $vend, $obraSocial, $nroReceta)
	{
		Global $dblink;
		$query = "SELECT r.id, r.id_venta as idVenta, r.fecha, r.estado, r.credencial,ifnull((select nombre from obrasocial where id=r.id_obrasocial limit 1),'') as OS,r.idRefValidacion,concat(ifnull(u.nombre,''),' ',ifnull(u.apellido,'')) as nombreUsuario,
                            (select ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0.00)), decimal(10,2)),0.00)
                            from recetaitem ri
                            where r.id=ri.id_receta
                            ) as importe
                            FROM recetas r 
                            left join usuarios u on u.id=r.id_usuarios
                            WHERE EXISTS (select * from recetaitem ri2 where r.id=ri2.id_receta) and ";    
                if($nroReceta>0){
                    $query .= "r.id=$nroReceta";
                }else{
                $query .= "date(r.fecha) BETWEEN '$fd' and '$fh' 
                          and (r.estado = '$est' or '-1'='$est') 
                          and (r.id_usuarios = $vend or -1 = $vend) 
                          ";
                if($prod!=''){
                $query .= " and (r.id in (select ri.id_receta from recetaitem ri inner join ab_manualdat p on p.troquel=ri.troquel where r.id=ri.id_receta AND p.troquel='$prod' or p.nombre like '%$prod%'))";}
                if($obraSocial!='-1'){
                $query .= " and (r.id_obrasocial=$obraSocial ) ";}
                }
                $query .= " order by r.id";
                //echo $query;
                
		$result = mysqli_query($dblink,$query);
//echo $query;
	return $result;
        /*

SELECT * FROM venta v WHERE date(v.fecha) BETWEEN '2018-01-04' and '2018-04-04'
and (v.id in (select id_venta from ventaitem vi inner join productos p on p.id=vi.id_productos where p.codigo='' or p.nombre like '%re%'))
and (v.estado = 'F' or '-1'='F')


        */
	}
        
        public function CantidadXVenta()
	{
		Global $dblink;
		$query = "SELECT count(1) as cnt FROM recetas where `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' and estado in ('A')";

		$result = mysqli_query($dblink,$query);
                $row = mysqli_fetch_assoc($result);
	return $row['cnt'];

	}
        
        public function SelectAllxUsuarioVenta()
	{
		Global $dblink;
		$query = "SELECT * FROM recetas where (`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "' or -1 = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "') and `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' order by fecha desc ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        public function Totales() {
        Global $dblink;
        $query = "SELECT case when r.estado in ('N','A') and ri.estado in ('N','A') then ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0)), decimal(10,2)),0.00) else 0.00 end as afiliado, convert(sum(ifnull(ri.importeCobertura,0.00)),decimal(10,2)) as os, convert(sum(ifnull(ri.cantidad*ri.importeUnitario,0.00)),decimal(10,2)) as total FROM recetaitem ri inner join recetas r on r.id=ri.id_receta where ri.id_receta = '" . mysqli_real_escape_string($dblink,$this->getid()) . "'";
        //echo $query;
        $total = 0;
        
        $result = mysqli_query($dblink, $query);
        $row = mysqli_fetch_assoc($result);
                
        return $row;
    }
        
        
        public function CntItems()
	{
		Global $dblink;
		$query = "SELECT count(1) as cnt FROM recetaitem where id_receta = '" . mysqli_real_escape_string($dblink,$this->getid()) . "'";
		$result = mysqli_query($dblink,$query);
                $x = mysqli_fetch_assoc($result);
                return $x['cnt'];
	

	}
        
        public function SelectResumenXVenta()
	{   
		Global $dblink;
		$query = "select -1 as id, -1 as id_venta, -1 as id_productos, '' as tipoCantidad,1 as cantidad, ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0.00)), decimal(10,2)),0.00) as precioUnitario, '' as codigo, CONCAT('Receta N° ',convert(ri.id_receta,char(10)),' (',convert(count(1),char(10)),' Items)') as nombre from recetaitem ri 
                          inner join recetas r on r.id=ri.id_receta 
                          where r.estado in ('N','A') and ri.estado in ('N','A') and r.id_venta='" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' 
                          group by ri.id_receta 
                          order by r.id; ";
                $result = mysqli_query($dblink,$query);
                return $result;
	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE recetas SET 
						`credencial` = '" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "',
						`id_obrasocial` = " . mysqli_real_escape_string($dblink,(($this->getid_obrasocial()>0) ? $this->getid_obrasocial() : " null ")) . ",
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`fechaReceta` = '" . mysqli_real_escape_string($dblink,$this->getfechaReceta()) . "',
						`cod_provinciaPrestador` = " . ((strlen($this->getcod_provinciaPrestador())>0) ? "'".mysqli_real_escape_string($dblink,($this->getcod_provinciaPrestador()))."'" : "NULL ") . ",
						`id_tipoMatriculaPrestador` = " . mysqli_real_escape_string($dblink,(($this->getid_tipoMatriculaPrestador() > 0) ? $this->getid_tipoMatriculaPrestador() : " null ")) . ",
						`matriculaPrestador` = '" . mysqli_real_escape_string($dblink,$this->getmatriculaPrestador()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`idRefValidacion` = '" . mysqli_real_escape_string($dblink,$this->getidRefValidacion()) . "',
						`id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' 
						WHERE `id`='{$this->getid()}'";
                logTxt($query);

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM recetas 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO recetas (`id`,`credencial`,`id_obrasocial`,`fecha`,`fechaReceta`,`cod_provinciaPrestador`,`id_tipoMatriculaPrestador`,`matriculaPrestador`,`id_usuarios`,`estado`,`idRefValidacion`,`id_venta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "','" . mysqli_real_escape_string($dblink,$this->getid_obrasocial()) . "',now(),'" . mysqli_real_escape_string($dblink,$this->getfechaReceta()) . "','" . mysqli_real_escape_string($dblink,$this->getcod_provinciaPrestador()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoMatriculaPrestador()) . "','" . mysqli_real_escape_string($dblink,$this->getmatriculaPrestador()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getidRefValidacion()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "');";
		echo $query;
                mysqli_query($dblink,$query);

	}
        public function CreateInicial()
	{
		Global $dblink;
		$query ="INSERT INTO recetas (`fecha`,`id_usuarios`,`estado`,`id_venta`) VALUES (now(),'" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "');";
		//echo $query;
                mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO recetas (`id`,`credencial`,`id_obrasocial`,`fecha`,`fechaReceta`,`cod_provinciaPrestador`,`id_tipoMatriculaPrestador`,`matriculaPrestador`,`id_usuarios`,`estado`,`idRefValidacion`,`id_venta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "','" . mysqli_real_escape_string($dblink,$this->getid_obrasocial()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getfechaReceta()) . "','" . mysqli_real_escape_string($dblink,$this->getcod_provinciaPrestador()) . "','" . mysqli_real_escape_string($dblink,$this->getid_tipoMatriculaPrestador()) . "','" . mysqli_real_escape_string($dblink,$this->getmatriculaPrestador()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getidRefValidacion()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "') ON DUPLICATE KEY UPDATE 
						`credencial` = '" . mysqli_real_escape_string($dblink,$this->getcredencial()) . "',
						`id_obrasocial` = '" . mysqli_real_escape_string($dblink,$this->getid_obrasocial()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`fechaReceta` = '" . mysqli_real_escape_string($dblink,$this->getfechaReceta()) . "',
						`cod_provinciaPrestador` = '" . mysqli_real_escape_string($dblink,$this->getcod_provinciaPrestador()) . "',
						`id_tipoMatriculaPrestador` = '" . mysqli_real_escape_string($dblink,$this->getid_tipoMatriculaPrestador()) . "',
						`matriculaPrestador` = '" . mysqli_real_escape_string($dblink,$this->getmatriculaPrestador()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`idRefValidacion` = '" . mysqli_real_escape_string($dblink,$this->getidRefValidacion()) . "',
						`id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "';";
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

	public function setcredencial($credencial='')
	{
		$this->credencial = $credencial;
		return true;
	}

	public function getcredencial()
	{
		return $this->credencial;
	}

	public function setid_obrasocial($id_obrasocial='')
	{
		$this->id_obrasocial = $id_obrasocial;
		return true;
	}

	public function getid_obrasocial()
	{
		return $this->id_obrasocial;
	}

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
                return $this->fecha;
	}

	public function setfechaReceta($fechaReceta='')
	{
		$this->fechaReceta = $fechaReceta;
		return true;
	}

	public function getfechaReceta()
	{
		return $this->fechaReceta;
	}

	public function setcod_provinciaPrestador($cod_provinciaPrestador='')
	{
		$this->cod_provinciaPrestador = $cod_provinciaPrestador;
		return true;
	}

	public function getcod_provinciaPrestador()
	{
		return $this->cod_provinciaPrestador;
	}

	public function setid_tipoMatriculaPrestador($id_tipoMatriculaPrestador='')
	{
		$this->id_tipoMatriculaPrestador = $id_tipoMatriculaPrestador;
		return true;
	}

	public function getid_tipoMatriculaPrestador()
	{
		return $this->id_tipoMatriculaPrestador;
	}

	public function setmatriculaPrestador($matriculaPrestador='')
	{
		$this->matriculaPrestador = $matriculaPrestador;
		return true;
	}

	public function getmatriculaPrestador()
	{
		return $this->matriculaPrestador;
	}

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
	}

	public function setestado($estado='')
	{
		$this->estado = $estado;
		return true;
	}

	public function getestado()
	{
		return $this->estado;
	}

	public function setidRefValidacion($idRefValidacion='')
	{
		$this->idRefValidacion = $idRefValidacion;
		return true;
	}

	public function getidRefValidacion()
	{
		return $this->idRefValidacion;
	}

	public function setid_venta($id_venta='')
	{
		$this->id_venta = $id_venta;
		return true;
	}

	public function getid_venta()
	{
		return $this->id_venta;
	}

} // END class receta

/******************************************************************************
* Class for farmacia.recetaitem
*******************************************************************************/

class recetaitem
{
	/**
	* @var int
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_receta;

	/**
	* @var string
	*/
	private $troquel;

	/**
	* @var 
	*/
	private $importeUnitario;

	/**
	* @var int
	*/
	private $cantidad;

	/**
	* @var 
	*/
	private $importeUnitarioRta;

	/**
	* @var int
	*/
	private $cantidadRta;

	/**
	* @var int
	*/
	private $porcentajeCobertura;

	/**
	* @var 
	*/
	private $importeCobertura;

	/**
	* @var 
	*/
	private $importeACargoAfiliado;

	/**
	* @var string
	*/
	private $estado;

	/**
	* @var string
	*/
	private $mensaje;
        
        private $espack;
        
        
        public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM recetaitem WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}
        
        
        
        public function SelectAll($len=1000000,$start=0, $search='', $order='1', $direccion='asc')
	{
		Global $dblink;
		$query = "SELECT i.*, case when i.espack=1 then '' else 'Un.' end as tipoCantidad, md.troquel, md.nombre, md.presentacion, md.nroregistro, md.codbarra FROM recetaitem i inner join ab_manualdat md on md.troquel=i.troquel 
                          where (md.nombre like '%".$search."%') and `id_receta` = '" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "' 
                          order by ".$order." ".$direccion." limit ".$start.", ".$len;
                

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        
        
        public function Cnt($search)
	{
		Global $dblink;
		$query = "SELECT count(1) as recordsFiltered, count(1) as recordsTotal FROM recetaitem i inner join ab_manualdat md on md.troquel=i.troquel 
                          where (md.nombre like '%".$search."%') and `id_receta` = '" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "' "; 

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function Total($idReceta) {
        Global $dblink;
        $query = "SELECT case when r.estado in ('N','A') and ri.estado in ('N','A') then ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0)), decimal(10,2)),0.00) else 0.00 end as total FROM recetaitem ri
                    inner join recetas r on r.id=ri.id_receta      
                    where ri.id_receta = '" . $idReceta . "'";
        //echo $query;
        $total = 0;
        if ($idReceta > 0) {
            $result = mysqli_query($dblink, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM recetaitem 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO recetaitem (`id_receta`,`troquel`,`importeUnitario`,`espack`,`cantidad`,`estado`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteUnitario()) . "','" . mysqli_real_escape_string($dblink,$this->getespack()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "');";
                //echo $query;
		mysqli_query($dblink,$query);

	}
        
        public function SaveValidacion()
	{
		Global $dblink;
		$query = "UPDATE recetaitem SET 
						`cantidadRta` = '" . mysqli_real_escape_string($dblink,$this->getcantidadRta()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`importeACargoAfiliado` = '" . mysqli_real_escape_string($dblink,$this->getimporteACargoAfiliado()) . "',
						`importeCobertura` = '" . mysqli_real_escape_string($dblink,$this->getimporteCobertura()) . "',
						`importeUnitarioRta` = '" . mysqli_real_escape_string($dblink,$this->getimporteUnitarioRta()) . "',
						`mensaje` = '" . mysqli_real_escape_string($dblink,$this->getmensaje()) . "',
						`porcentajeCobertura` = '" . mysqli_real_escape_string($dblink,$this->getporcentajeCobertura()) . "'
						WHERE `id`='{$this->getid()}'";
                //logTxt($query);
		mysqli_query($dblink,$query);

	}
        public function ReactivarItems()
	{
		Global $dblink;
		$query = "UPDATE recetaitem SET 
						`cantidadRta` = null,
						`estado` = 'N',
						`importeACargoAfiliado` = null,
						`importeCobertura` = null,
						`importeUnitarioRta` = null,
						`mensaje` = null,
						`porcentajeCobertura` = null
						WHERE `id_receta`='{$this->getid_receta()}'";
                //logTxt($query);
		mysqli_query($dblink,$query);

	}
/*
        public function CreateInicial()
	{
		Global $dblink;
		$query ="INSERT INTO recetaitem (`id`,`id_receta`,`troquel`,`importeUnitario`,`cantidad`,`importeUnitarioRta`,`cantidadRta`,`porcentajeCobertura`,`importeCobertura`,`importeACargoAfiliado`,`estado`,`mensaje`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteUnitario()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteUnitarioRta()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidadRta()) . "','" . mysqli_real_escape_string($dblink,$this->getporcentajeCobertura()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteCobertura()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteACargoAfiliado()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getmensaje()) . "');";
		mysqli_query($dblink,$query);

	}
*/
	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO recetaitem (`id`,`id_receta`,`troquel`,`importeUnitario`,`espack`,`cantidad`,`importeUnitarioRta`,`cantidadRta`,`porcentajeCobertura`,`importeCobertura`,`importeACargoAfiliado`,`estado`,`mensaje`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteUnitario()) . "','" . mysqli_real_escape_string($dblink,$this->getespack()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteUnitarioRta()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidadRta()) . "','" . mysqli_real_escape_string($dblink,$this->getporcentajeCobertura()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteCobertura()) . "','" . mysqli_real_escape_string($dblink,$this->getimporteACargoAfiliado()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getmensaje()) . "') ON DUPLICATE KEY UPDATE ;";
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

	public function setid_receta($id_receta='')
	{
		$this->id_receta = $id_receta;
		return true;
	}

	public function getid_receta()
	{
		return $this->id_receta;
	}

	public function settroquel($troquel='')
	{
		$this->troquel = $troquel;
		return true;
	}

	public function gettroquel()
	{
		return $this->troquel;
	}

	public function setimporteUnitario($importeUnitario='')
	{
		$this->importeUnitario = $importeUnitario;
		return true;
	}

	public function getimporteUnitario()
	{
		return $this->importeUnitario;
	}

	public function setcantidad($cantidad='')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
	}
        
        public function setespack($espack='')
	{
		$this->espack = $espack;
		return true;
	}

	public function getespack()
	{
		return $this->espack;
	}

	public function setimporteUnitarioRta($importeUnitarioRta='')
	{
		$this->importeUnitarioRta = $importeUnitarioRta;
		return true;
	}

	public function getimporteUnitarioRta()
	{
		return $this->importeUnitarioRta;
	}

	public function setcantidadRta($cantidadRta='')
	{
		$this->cantidadRta = $cantidadRta;
		return true;
	}

	public function getcantidadRta()
	{
		return $this->cantidadRta;
	}

	public function setporcentajeCobertura($porcentajeCobertura='')
	{
		$this->porcentajeCobertura = $porcentajeCobertura;
		return true;
	}

	public function getporcentajeCobertura()
	{
		return $this->porcentajeCobertura;
	}

	public function setimporteCobertura($importeCobertura='')
	{
		$this->importeCobertura = $importeCobertura;
		return true;
	}

	public function getimporteCobertura()
	{
		return $this->importeCobertura;
	}

	public function setimporteACargoAfiliado($importeACargoAfiliado='')
	{
		$this->importeACargoAfiliado = $importeACargoAfiliado;
		return true;
	}

	public function getimporteACargoAfiliado()
	{
		return $this->importeACargoAfiliado;
	}

	public function setestado($estado='')
	{
		$this->estado = $estado;
		return true;
	}

	public function getestado()
	{
		return $this->estado;
	}

	public function setmensaje($mensaje='')
	{
		$this->mensaje = $mensaje;
		return true;
	}

	public function getmensaje()
	{
		return $this->mensaje;
	}

} // END class recetaitem

/******************************************************************************
* Class for farmacia.reserva
*******************************************************************************/

class reserva
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_farmacias;

	/**
	* @var string
	*/
	private $troquel;

	/**
	* @var int
	*/
	private $cantidad;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var string
	*/
	private $estado;

	/**
	* @var string
	*/
	private $observaciones;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM reserva WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM reserva ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE reserva SET 
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "',
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM reserva 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO reserva (`id`,`id_farmacias`,`troquel`,`cantidad`,`fecha`,`estado`,`observaciones`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO reserva (`id`,`id_farmacias`,`troquel`,`cantidad`,`fecha`,`estado`,`observaciones`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "') ON DUPLICATE KEY UPDATE 
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "',
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "';";
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

	public function setid_farmacias($id_farmacias='')
	{
		$this->id_farmacias = $id_farmacias;
		return true;
	}

	public function getid_farmacias()
	{
		return $this->id_farmacias;
	}

	public function settroquel($troquel='')
	{
		$this->troquel = $troquel;
		return true;
	}

	public function gettroquel()
	{
		return $this->troquel;
	}

	public function setcantidad($cantidad='')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
	}

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setestado($estado='')
	{
		$this->estado = $estado;
		return true;
	}

	public function getestado()
	{
		return $this->estado;
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

} // END class reserva

/******************************************************************************
* Class for farmacia.roles
*******************************************************************************/

class roles
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM roles WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM roles ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE roles SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM roles 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO roles (`id`,`nombre`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO roles (`id`,`nombre`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
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

	public function setdescripcion($descripcion='')
	{
		$this->descripcion = $descripcion;
		return true;
	}

	public function getdescripcion()
	{
		return $this->descripcion;
	}

} // END class roles

/******************************************************************************
* Class for farmacia.roles_links
*******************************************************************************/

class roles_links
{
	/**
	* @var int
	*/
	private $id_links;

	/**
	* @var int
	*/
	private $id_roles;

	/**
	* @var int
	*/
	private $edita;

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO roles_links (`id_links`,`id_roles`,`edita`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_links()) . "','" . mysqli_real_escape_string($dblink,$this->getid_roles()) . "','" . mysqli_real_escape_string($dblink,$this->getedita()) . "');";
		mysqli_query($dblink,$query);

	}
        
        public function Delete()
	{
		Global $dblink;
		$query ="delete from roles_links where `id_links` = '" . mysqli_real_escape_string($dblink,$this->getid_links()) . "' and `id_roles` = '" . mysqli_real_escape_string($dblink,$this->getid_roles()) . "';";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO roles_links (`id_links`,`id_roles`,`edita`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_links()) . "','" . mysqli_real_escape_string($dblink,$this->getid_roles()) . "','" . mysqli_real_escape_string($dblink,$this->getedita()) . "') ON DUPLICATE KEY UPDATE ;";
		mysqli_query($dblink,$query);

	}
        
        public function SelectAllRelacionados()
	{
		Global $dblink;
		$query = "SELECT l.*, rl.edita FROM links l inner join roles_links rl on rl.id_links=l.id and rl.id_roles= " . mysqli_real_escape_string($dblink,$this->getid_roles()) . " order by l.nombre";
                
		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllNoRelacionados()
	{
		Global $dblink;
		$query = "SELECT * FROM links where id not in (select distinct id_links from roles_links where id_roles= " . mysqli_real_escape_string($dblink,$this->getid_roles()) . ")  order by nombre";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function setid_links($id_links='')
	{
		$this->id_links = $id_links;
		return true;
	}

	public function getid_links()
	{
		return $this->id_links;
	}

	public function setid_roles($id_roles='')
	{
		$this->id_roles = $id_roles;
		return true;
	}

	public function getid_roles()
	{
		return $this->id_roles;
	}

	public function setedita($edita='')
	{
		$this->edita = $edita;
		return true;
	}

	public function getedita()
	{
		return $this->edita;
	}

} // END class roles_links

/******************************************************************************
* Class for farmacia.stocklog
*******************************************************************************/

class stocklog
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_productos;

	/**
	* @var string
	*/
	private $troquel;

	/**
	* @var int
	*/
	private $cnt;

	/**
	* @var int
	*/
	private $cntUnidades;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var int
	*/
	private $id_usuarios;

	/**
	* @var string
	*/
	private $observaciones;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM stocklog WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}

	public function SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion)
	{
		Global $dblink;
		$query = "SELECT stocklog.cnt, stocklog.cntUnidades, stocklog.fecha, stocklog.observaciones , concat(ifnull(usuarios.apellido,'') , ' ' , ifnull(usuarios.nombre,'')) as usuario FROM stocklog 
                          left join usuarios on usuarios.id=stocklog.id_usuarios
                          WHERE (`troquel`='{$this->gettroquel()}' or `id_productos`='{$this->getid_productos()}') 
                          and (ifnull(usuarios.apellido,'') + ' ' + ifnull(usuarios.nombre,'') like '%".$search."%' or observaciones like '%".$search."%')
                          order by ".$order." ".$direccion." limit ".$iDisplayStart.", ".$iDisplayLength;
                          //echo $query;
		$result = mysqli_query($dblink,$query);
                logTxt($query);
        	return $result;
        }
        
        public function Cnt($search)
	{
		Global $dblink;
		$query = "SELECT count(1) as recordsFiltered, (SELECT count(1) FROM stocklog where `troquel`='{$this->gettroquel()}' ) as recordsTotal FROM stocklog 
                          left join usuarios on usuarios.id=stocklog.id_usuarios
                          WHERE `troquel`='{$this->gettroquel()}' 
                          and (ifnull(usuarios.apellido,'') + ' ' + ifnull(usuarios.nombre,'') like '%".$search."%' or observaciones like '%".$search."%')";
                          //echo $query;
                          $result = mysqli_query($dblink,$query);
                
        	return $result;
        }

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE stocklog SET 
						`id_productos` = '" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "',
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`cnt` = '" . mysqli_real_escape_string($dblink,$this->getcnt()) . "',
						`cntUnidades` = '" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM stocklog 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
            Global $dblink;
            $query ="INSERT INTO stocklog (`id`,`id_productos`,`troquel`,`cnt`,`cntUnidades`,`fecha`,`id_usuarios`,`observaciones`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "'," . mysqli_real_escape_string($dblink,$this->getid_productos()) . ",'" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcnt()) . "','" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "',now(),'" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "');";
            mysqli_query($dblink,$query);
        }

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO stocklog (`id`,`id_productos`,`troquel`,`cnt`,`cntUnidades`,`fecha`,`id_usuarios`,`observaciones`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcnt()) . "','" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "') ON DUPLICATE KEY UPDATE 
						`cnt` = '" . mysqli_real_escape_string($dblink,$this->getcnt()) . "',
						`cntUnidades` = '" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "';";
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

	public function setid_productos($id_productos='')
	{
		$this->id_productos = $id_productos;
		return true;
	}

	public function getid_productos()
	{
		return $this->id_productos;
	}

	public function settroquel($troquel='')
	{
		$this->troquel = $troquel;
		return true;
	}

	public function gettroquel()
	{
		return $this->troquel;
	}

	public function setcnt($cnt='')
	{
		$this->cnt = $cnt;
		return true;
	}

	public function getcnt()
	{
		return $this->cnt;
	}

	public function setcntUnidades($cntUnidades='')
	{
		$this->cntUnidades = $cntUnidades;
		return true;
	}

	public function getcntUnidades()
	{
		return $this->cntUnidades;
	}

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
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

} // END class stocklog

/******************************************************************************
* Class for farmacia.stockmedicamentos
*******************************************************************************/

class stockmedicamentos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $troquel;

	/**
	* @var int
	*/
	private $cnt;

	/**
	* @var int
	*/
	private $cntUnidades;

	/**
	* @var int
	*/
	private $id_farmacias;

	/**
	* @var int
	*/
	private $id_ubicacion;
        
        private $fechaModif;
        private $alertaMin;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM stockmedicamentos WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM stockmedicamentos  WHERE `troquel`='{$this->gettroquel()}' and `id_farmacias`='{$this->getid_farmacias()}'";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE stockmedicamentos SET 
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`cnt` = '" . mysqli_real_escape_string($dblink,$this->getcnt()) . "',
						`cntUnidades` = '" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "',
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "',
						`id_ubicacion` = '" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM stockmedicamentos 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO stockmedicamentos (`id`,`troquel`,`cnt`,`cntUnidades`,`id_farmacias`,`id_ubicacion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcnt()) . "','" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "','" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO stockmedicamentos (`id`,`troquel`,`cnt`,`cntUnidades`,`id_farmacias`,`id_ubicacion`,`ultModif`,`alertaMin`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "','" . mysqli_real_escape_string($dblink,$this->getcnt()) . "','" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "','" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "',now(),'" . mysqli_real_escape_string($dblink,$this->getalertaMin()) . "') ON DUPLICATE KEY UPDATE 
						`troquel` = '" . mysqli_real_escape_string($dblink,$this->gettroquel()) . "',
						`cnt` = '" . mysqli_real_escape_string($dblink,$this->getcnt()) . "',
						`cntUnidades` = '" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "',
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "',
						`id_ubicacion` = '" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "',
                                                `ultModif` = now(),
                                                `alertaMin` = '" . mysqli_real_escape_string($dblink,$this->getalertaMin()) . "';";
		logTxt($query);
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

	public function settroquel($troquel='')
	{
		$this->troquel = $troquel;
		return true;
	}

	public function gettroquel()
	{
		return $this->troquel;
	}

	public function setcnt($cnt='')
	{
		$this->cnt = $cnt;
		return true;
	}

	public function getcnt()
	{
		return $this->cnt;
	}

	public function setcntUnidades($cntUnidades='')
	{
		$this->cntUnidades = $cntUnidades;
		return true;
	}

	public function getcntUnidades()
	{
		return $this->cntUnidades;
	}

	public function setid_farmacias($id_farmacias='')
	{
		$this->id_farmacias = $id_farmacias;
		return true;
	}

	public function getid_farmacias()
	{
		return $this->id_farmacias;
	}

	public function setid_ubicacion($id_ubicacion='')
	{
		$this->id_ubicacion = $id_ubicacion;
		return true;
	}
        
        public function getid_ubicacion()
	{
		return $this->id_ubicacion;
	}

	public function getfechaModif()
	{
		return $this->fechaModif;
	}
        
        public function setfechaModif($fechaModif='')
	{
		$this->fechaModif = $fechaModif;
		return true;
	}

	public function getalertaMin()
	{
		return $this->alertaMin;
	}
        
        public function setalertaMin($alertaMin='')
	{
		$this->alertaMin = $alertaMin;
		return true;
	}

	

} // END class stockmedicamentos

/******************************************************************************
* Class for farmacia.stockproductos
*******************************************************************************/

class stockproductos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_productos;

	/**
	* @var int
	*/
	private $cnt;

	/**
	* @var int
	*/
	private $cntUnidades;

	/**
	* @var int
	*/
	private $id_farmacias;

	/**
	* @var int
	*/
	private $id_ubicacion;
        
        private $alertaMin;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM stockproductos WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM stockproductos WHERE `id_productos`='{$this->getid_productos()}' and `id_farmacias`='{$this->getid_farmacias()}'";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE stockproductos SET 
						`id_productos` = '" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "',
						`cnt` = '" . mysqli_real_escape_string($dblink,$this->getcnt()) . "',
						`cntUnidades` = '" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "',
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "',
						`id_ubicacion` = '" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "',
                                                `alertaMin` = '" . mysqli_real_escape_string($dblink,$this->getalertaMin()) . "'
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM stockproductos 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO stockproductos (`id`,`id_productos`,`cnt`,`cntUnidades`,`id_farmacias`,`id_ubicacion`,`alertaMin`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getcnt()) . "','" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "','" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "','" . mysqli_real_escape_string($dblink,$this->getalertaMin()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO stockproductos (`id`,`id_productos`,`cnt`,`cntUnidades`,`id_farmacias`,`id_ubicacion`,`alertaMin`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getcnt()) . "','" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "','" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "','" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "','" . mysqli_real_escape_string($dblink,$this->getalertaMin()) . "') ON DUPLICATE KEY UPDATE 
						`id_productos` = '" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "',
						`cnt` = '" . mysqli_real_escape_string($dblink,$this->getcnt()) . "',
						`cntUnidades` = '" . mysqli_real_escape_string($dblink,$this->getcntUnidades()) . "',
						`id_farmacias` = '" . mysqli_real_escape_string($dblink,$this->getid_farmacias()) . "',
						`id_ubicacion` = '" . mysqli_real_escape_string($dblink,$this->getid_ubicacion()) . "',
                                                `alertaMin` = '" . mysqli_real_escape_string($dblink,$this->getalertaMin()) . "';";
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

	public function setid_productos($id_productos='')
	{
		$this->id_productos = $id_productos;
		return true;
	}

	public function getid_productos()
	{
		return $this->id_productos;
	}

	public function setcnt($cnt='')
	{
		$this->cnt = $cnt;
		return true;
	}

	public function getcnt()
	{
		return $this->cnt;
	}

	public function setcntUnidades($cntUnidades='')
	{
		$this->cntUnidades = $cntUnidades;
		return true;
	}

	public function getcntUnidades()
	{
		return $this->cntUnidades;
	}

	public function setid_farmacias($id_farmacias='')
	{
		$this->id_farmacias = $id_farmacias;
		return true;
	}

	public function getid_farmacias()
	{
		return $this->id_farmacias;
	}

	public function setid_ubicacion($id_ubicacion='')
	{
		$this->id_ubicacion = $id_ubicacion;
		return true;
	}

	public function getid_ubicacion()
	{
		return $this->id_ubicacion;
	}
        
        public function setalertaMin($alertaMin='')
	{
		$this->alertaMin = $alertaMin;
		return true;
	}

	public function getalertaMin()
	{
		return $this->alertaMin;
	}

} // END class stockproductos

/******************************************************************************
* Class for farmacia.tipomatriculaprestador
*******************************************************************************/

class tipomatriculaprestador
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM tipomatriculaprestador WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM tipomatriculaprestador ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE tipomatriculaprestador SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM tipomatriculaprestador 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO tipomatriculaprestador (`id`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO tipomatriculaprestador (`id`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "';";
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

} // END class tipomatriculaprestador

/******************************************************************************
* Class for farmacia.tipopago
*******************************************************************************/

class tipopago
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;
        private $mascara;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM tipopago WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM tipopago ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE tipopago SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
                                                `mascara` = '" . mysqli_real_escape_string($dblink,$this->getmascara()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM tipopago 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO tipopago (`id`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getmascara()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO tipopago (`id`,`nombre`,`mascara`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getmascara()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "', `mascara` = '" . mysqli_real_escape_string($dblink,$this->getmascara()) . "';";
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
        
        
        public function setmascara($mascara='')
	{
		$this->mascara = $mascara;
		return true;
	}

	public function getmascara()
	{
		return $this->mascara;
	}

} // END class tipopago

/******************************************************************************
* Class for farmacia.tipoproductos
*******************************************************************************/

class tipoproductos
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM tipoproductos WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM tipoproductos ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE tipoproductos SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM tipoproductos 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO tipoproductos (`id`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO tipoproductos (`id`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "';";
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

} // END class tipoproductos

/******************************************************************************
* Class for farmacia.ubicacion
*******************************************************************************/

class ubicacion
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ubicacion WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM ubicacion ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ubicacion SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ubicacion 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ubicacion (`id`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ubicacion (`id`,`nombre`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "';";
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

} // END class ubicacion

/******************************************************************************
* Class for farmacia.usuarios
*******************************************************************************/

class usuarios
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $user;

	/**
	* @var string
	*/
	private $pass;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $apellido;

	/**
	* @var int
	*/
	private $vendedor;

	/**
	* @var int
	*/
	private $habilitado;
/*
	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}
*/
        
        public function __construct($user='',$pass='')
        {
        $this->setuser($user);
        $this->setpass($pass);
        $this->Load2();
        }
  
	public function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM usuarios WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}
        
        private function Load2()
	{
		Global $dblink;
		$query = "SELECT * FROM usuarios WHERE `user`='{$this->getuser()}' and `pass`='".md5($this->getpass())."'";
                
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
		$query = "SELECT * FROM usuarios order by apellido, nombre ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function SelectAllAppAndroid()
	{
		Global $dblink;
		$query = "SELECT * FROM usuarios where user = '" . mysqli_real_escape_string($dblink,$this->getuser()) . "' and pass = '" . mysqli_real_escape_string($dblink,$this->getpass()) . "' order by apellido, nombre ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}



	

    public function VerificaLink($script)
	{
		Global $dblink;
		$query = "SELECT count(1) as cnt FROM usuarios u
inner join usuarios_roles ur on ur.id_usuarios=u.id
inner join roles_links rl on rl.id_roles=ur.id_roles
inner join links l on l.id=rl.id_links
where ltrim(rtrim(l.url)) = ltrim(rtrim('".$script."')) and u.id='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);
                $row = mysqli_fetch_assoc($result);
                return $row['cnt'];

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE usuarios SET 
						`user` = '" . mysqli_real_escape_string($dblink,$this->getuser()) . "',
						`pass` = '" . mysqli_real_escape_string($dblink,$this->getpass()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`apellido` = '" . mysqli_real_escape_string($dblink,$this->getapellido()) . "',
						`vendedor` = '" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}
        
        public function SavePass()
	{
		Global $dblink;
		$query = "UPDATE usuarios SET `pass` = '" . md5(mysqli_real_escape_string($dblink,$this->getpass())) . "'
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM usuarios 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO usuarios (`id`,`user`,`pass`,`nombre`,`apellido`,`vendedor`,`habilitado`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getuser()) . "','" . mysqli_real_escape_string($dblink,$this->getpass()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getapellido()) . "','" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "','" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO usuarios (`id`,`user`,`pass`,`nombre`,`apellido`,`vendedor`,`habilitado`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getuser()) . "','" . md5(mysqli_real_escape_string($dblink,$this->getpass())) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getapellido()) . "','" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "','" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "') ON DUPLICATE KEY UPDATE 
						`user` = '" . mysqli_real_escape_string($dblink,$this->getuser()) . "',
						
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`apellido` = '" . mysqli_real_escape_string($dblink,$this->getapellido()) . "',
						`vendedor` = '" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "';";
		mysqli_query($dblink,$query);

                //`pass` = '" . mysqli_real_escape_string($dblink,$this->getpass()) . "',
                
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

	public function setuser($user='')
	{
		$this->user = $user;
		return true;
	}

	public function getuser()
	{
		return $this->user;
	}

	public function setpass($pass='')
	{
		$this->pass = $pass;
		return true;
	}

	public function getpass()
	{
		return $this->pass;
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

	public function setapellido($apellido='')
	{
		$this->apellido = $apellido;
		return true;
	}

	public function getapellido()
	{
		return $this->apellido;
	}

	public function setvendedor($vendedor='')
	{
		$this->vendedor = $vendedor;
		return true;
	}

	public function getvendedor()
	{
		return $this->vendedor;
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

} // END class usuarios

/******************************************************************************
* Class for farmacia.usuarios_roles
*******************************************************************************/

class usuarios_roles
{
	/**
	* @var int
	*/
	private $id_roles;

	/**
	* @var int
	*/
	private $id_usuarios;

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO usuarios_roles (`id_roles`,`id_usuarios`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_roles()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "');";
		mysqli_query($dblink,$query);

	}
        public function Delete()
	{
		Global $dblink;
		$query ="delete from usuarios_roles where id_roles='" . mysqli_real_escape_string($dblink,$this->getid_roles()) . "' and id_usuarios = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "';";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO usuarios_roles (`id_roles`,`id_usuarios`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_roles()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "') ON DUPLICATE KEY UPDATE ;";
		mysqli_query($dblink,$query);

	}
        
        public function SelectAllRelacionados()
	{
		Global $dblink;
		$query = "SELECT * FROM roles where id in (select distinct id_roles from usuarios_roles where id_usuarios= " . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . ") ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllNoRelacionados()
	{
		Global $dblink;
		$query = "SELECT * FROM roles where id not in (select distinct id_roles from usuarios_roles where id_usuarios= " . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . ") ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function setid_roles($id_roles='')
	{
		$this->id_roles = $id_roles;
		return true;
	}

	public function getid_roles()
	{
		return $this->id_roles;
	}

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
	}

} // END class usuarios_roles

/******************************************************************************
* Class for farmacia.validacion
*******************************************************************************/

class validacion
{
	/**
	* @var int
	*/
	private $id_receta;

	/**
	* @var string
	*/
	private $ida_credencial;

	/**
	* @var int
	*/
	private $id_obrasocial;

	/**
	* @var string
	*/
	private $codAccion;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var string
	*/
	private $CodRtaGeneral;

	/**
	* @var string
	*/
	private $Descripcion;

	/**
	* @var string
	*/
	private $Mensaje;
        
        public function SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion)
	{
		Global $dblink;
		$query = "SELECT v.ida_credencial, o.nombre as Obra_Social, case when v.codAccion='290020' then 'Sol. AutorizaciÃ³n' else 'Sol. CancelaciÃ³n' end as Tipo, v.fecha, v.CodRtaGeneral, v.Descripcion, v.Mensaje FROM `validacion` V
                          inner join `obrasocial` o on v.id_obrasocial=o.id
                          where id_receta = '" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "' order by v.fecha ";
                logTxt($query);
		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function Cnt($search)
	{
		Global $dblink;
		$query = "SELECT count(1) as recordsFiltered, count(1) as recordsTotal FROM `validacion` V
                          inner join `obrasocial` o on v.id_obrasocial=o.id
                          where id_receta = '" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "' ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO validacion (`id_receta`,`ida_credencial`,`id_obrasocial`,`codAccion`,`fecha`,`CodRtaGeneral`,`Descripcion`,`Mensaje`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "','" . mysqli_real_escape_string($dblink,$this->getida_credencial()) . "','" . mysqli_real_escape_string($dblink,$this->getid_obrasocial()) . "','" . mysqli_real_escape_string($dblink,$this->getcodAccion()) . "',now(),'" . mysqli_real_escape_string($dblink,$this->getCodRtaGeneral()) . "','" . mysqli_real_escape_string($dblink,$this->getDescripcion()) . "','" . mysqli_real_escape_string($dblink,$this->getMensaje()) . "');";
                logTxt($query);
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO validacion (`id_receta`,`ida_credencial`,`id_obrasocial`,`codAccion`,`fecha`,`CodRtaGeneral`,`Descripcion`,`Mensaje`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "','" . mysqli_real_escape_string($dblink,$this->getida_credencial()) . "','" . mysqli_real_escape_string($dblink,$this->getid_obrasocial()) . "','" . mysqli_real_escape_string($dblink,$this->getcodAccion()) . "',now(),'" . mysqli_real_escape_string($dblink,$this->getCodRtaGeneral()) . "','" . mysqli_real_escape_string($dblink,$this->getDescripcion()) . "','" . mysqli_real_escape_string($dblink,$this->getMensaje()) . "') ON DUPLICATE KEY UPDATE ;";
		mysqli_query($dblink,$query);

	}
        
        public function tiempoValidacion()
	{       $fecha = '';
		Global $dblink;
		$query = "SELECT max(fecha) as fecha, now() as ahora FROM validacion 
                          where `id_receta` = '" . mysqli_real_escape_string($dblink,$this->getid_receta()) . "'"; 
                $result = mysqli_query($dblink,$query);
                while($row = mysqli_fetch_assoc($result) )
                {
                    $segundos = diferenciafechaensegundos($row['ahora'], $row['fecha']);
                }        
            return $segundos;

	}

	public function setid_receta($id_receta='')
	{
		$this->id_receta = $id_receta;
		return true;
	}

	public function getid_receta()
	{
		return $this->id_receta;
	}

	public function setida_credencial($ida_credencial='')
	{
		$this->ida_credencial = $ida_credencial;
		return true;
	}

	public function getida_credencial()
	{
		return $this->ida_credencial;
	}

	public function setid_obrasocial($id_obrasocial='')
	{
		$this->id_obrasocial = $id_obrasocial;
		return true;
	}

	public function getid_obrasocial()
	{
		return $this->id_obrasocial;
	}

	public function setcodAccion($codAccion='')
	{
		$this->codAccion = $codAccion;
		return true;
	}

	public function getcodAccion()
	{
		return $this->codAccion;
	}

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setCodRtaGeneral($CodRtaGeneral='')
	{
		$this->CodRtaGeneral = $CodRtaGeneral;
		return true;
	}

	public function getCodRtaGeneral()
	{
		return $this->CodRtaGeneral;
	}

	public function setDescripcion($Descripcion='')
	{
		$this->Descripcion = $Descripcion;
		return true;
	}

	public function getDescripcion()
	{
		return $this->Descripcion;
	}

	public function setMensaje($Mensaje='')
	{
		$this->Mensaje = $Mensaje;
		return true;
	}

	public function getMensaje()
	{
		return $this->Mensaje;
	}

} // END class validacion

/******************************************************************************
* Class for farmacia.venta
*******************************************************************************/

class venta
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var string
	*/
	private $estado;

	/**
	* @var int
	*/
	private $id_usuarios;

	/**
	* @var int
	*/
	private $id_caja;

	/**
	* @var int
	*/
	private $id_personas;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM venta WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM venta ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllBuscador($fd, $fh, $prod, $est, $vend, $tipoPago, $nroVenta)
	{
		Global $dblink;
		$query = "SELECT v.id, v.fecha,v.estado, concat(ifnull(u.nombre,''),' ',ifnull(u.apellido,'')) as nombreUsuario, 
                            convert((SELECT ifnull(convert(sum(vi.cantidad * vi.precioUnitario), decimal(10,2)),0.00)
                            +(select ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0.00)), decimal(10,2)),0.00)
                            from recetaitem ri
                            inner join recetas r on r.id=ri.id_receta
                            where r.estado in ('N','A') and ri.estado in ('N','A') and r.id_venta=v.id
                            ) as total FROM ventaitem vi
                            where vi.id_venta = v.id),decimal(10,2)) as importe
                          FROM venta v 
                          left join usuarios u on u.id=v.id_usuarios
                          WHERE (EXISTS (select * from recetas ri2 inner join recetaitem ri2a on ri2.id=ri2a.id_receta where ri2.estado in ('A','N') and ri2.id_venta=v.id) or EXISTS (select * from ventaitem ri3 where ri3.id_venta=v.id)) and ";
                if($nroVenta>0){
                    $query .= "v.id=$nroVenta";
                }else{
                $query .= "date(v.fecha) BETWEEN '$fd' and '$fh' 
                          and (v.estado = '$est' or '-1'='$est') 
                          and (v.id_usuarios = $vend or -1 = $vend) 
                          ";
                if($prod!=''){
                $query .= " and (v.id in (select id_venta from ventaitem vi inner join productos p on p.id=vi.id_productos where v.id=vi.id_venta AND p.codigo='$prod' or p.nombre like '%$prod%'))";}
                if($tipoPago!='-1'){
                $query .= " and (v.id in (select id_venta from pagos where v.id=pagos.id_venta and id_tipoPago=$tipoPago)) ";}
                }
                $query .= " order by v.id";
                //echo $query;
                
		$result = mysqli_query($dblink,$query);

	return $result;
        /*

SELECT * FROM venta v WHERE date(v.fecha) BETWEEN '2018-01-04' and '2018-04-04'
and (v.id in (select id_venta from ventaitem vi inner join productos p on p.id=vi.id_productos where p.codigo='' or p.nombre like '%re%'))
and (v.estado = 'F' or '-1'='F')


        */
	}
        
        public function SelectAllResumen()
	{
		Global $dblink;
                $query = "select v.id, v.fecha, v.estado, concat(ifnull(u.nombre,''),' ',ifnull(u.apellido,'')) as nombreUsuario, 
                     (SELECT ifnull(convert(sum(vi.cantidad * vi.precioUnitario), decimal(10,2)),0.00)
                      +(select ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0.00)), decimal(10,2)),0.00)
                            from recetaitem ri
                            inner join recetas r on r.id=ri.id_receta
                            where r.estado in ('N','A') and ri.estado in ('N','A') and r.id_venta=v.id

                        ) as total FROM ventaitem vi
                        where vi.id_venta = v.id) as importe from venta v 
                          left join usuarios u on u.id=v.id_usuarios
                          where v.estado='N'
                          order by v.id";
                          

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        public function SelectAllResumenID()
	{
		Global $dblink;
                $query = "select v.id, v.fecha, v.estado, CONCAT(IFNULL(u.nombre,''),' ',IFNULL(u.apellido,'')) AS vendedor, 
       ifnull(((SELECT ifnull(convert(sum(vi.cantidad * vi.precioUnitario), decimal(10,2)),0.00)
	   FROM ventaitem vi where vi.id_venta = v.id)
       +(select ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0.00)), decimal(10,2)),0.00)
        from recetaitem ri inner join recetas r on r.id=ri.id_receta
        where r.estado in ('N','A') and ri.estado in ('N','A') and r.id_venta=v.id)),0.00) as importe,
		v.id_personas,p.doc, CONCAT(IFNULL(p.nombre,''),' ',IFNULL(p.apellido,'')) as nombreCompleto,p.estadoCtaCte, case when p.estadoCtaCte='A' then 'Activa' when p.estadoCtaCte='S' then 'Suspendida' else 'Inactiva' end as ctaCte,
        ifnull((select sum(importe) from pagos where pagos.id_venta=v.id),0.00) as registrado
        from venta v 
        left join usuarios u on u.id=v.id_usuarios
        left join personas p on p.id=v.id_personas
        where v.id = '" . mysqli_real_escape_string($dblink,$this->getid()) . "'
                          order by v.id";
                          

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        public function SelectAllxUsuario()
	{
		Global $dblink;
		$query = "SELECT * FROM venta where estado='N' and `id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "' order by id desc";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        public function SelectAllxID()
	{
		Global $dblink;
		$query = "SELECT * FROM venta where `id` = '" . mysqli_real_escape_string($dblink,$this->getid()) . "' order by id desc";

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
	public function Save()
	{
		Global $dblink;
		$query = "UPDATE venta SET 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
                                                `id_caja` = " . mysqli_real_escape_string($dblink,(($this->getid_caja() > 0) ? $this->getid_caja() : " null ")) . ",
                                                `id_personas` = " . mysqli_real_escape_string($dblink,(($this->getid_personas()>0) ? $this->getid_personas() : " null ")) . "
						
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM venta 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO venta (`id`,`fecha`,`estado`,`id_usuarios`,`id_caja`,`id_personas`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_caja()) . "','" . mysqli_real_escape_string($dblink,$this->getid_personas()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO venta (`id`,`fecha`,`estado`,`id_usuarios`,`id_caja`,`id_personas`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getfecha()) . "','" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "','" . mysqli_real_escape_string($dblink,$this->getid_caja()) . "','" . mysqli_real_escape_string($dblink,$this->getid_personas()) . "') ON DUPLICATE KEY UPDATE 
						`fecha` = '" . mysqli_real_escape_string($dblink,$this->getfecha()) . "',
						`estado` = '" . mysqli_real_escape_string($dblink,$this->getestado()) . "',
						`id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',
						`id_caja` = '" . mysqli_real_escape_string($dblink,$this->getid_caja()) . "',
						`id_personas` = '" . mysqli_real_escape_string($dblink,$this->getid_personas()) . "';";
		mysqli_query($dblink,$query);

	}
        
        public function NuevaVenta()
	{
		Global $dblink;
		$query ="INSERT INTO venta (`fecha`,`estado`,`id_usuarios`,`id_caja`,`id_personas`) VALUES (now(),'" . mysqli_real_escape_string($dblink,$this->getestado()) . "','" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "',NULL,NULL);";
		mysqli_query($dblink,$query);

	}
        
        public function CntNuevasxUsuario()
	{
		Global $dblink;
		$query = "SELECT count(1) as cnt FROM venta where estado='N' and `id_usuarios` = '" . mysqli_real_escape_string($dblink,$this->getid_usuarios()) . "'";
		$result = mysqli_query($dblink,$query);
                $x = mysqli_fetch_assoc($result);
                return $x['cnt'];
	

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

	public function setfecha($fecha='')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
	}

	public function setestado($estado='')
	{
		$this->estado = $estado;
		return true;
	}

	public function getestado()
	{
		return $this->estado;
	}

	public function setid_usuarios($id_usuarios='')
	{
		$this->id_usuarios = $id_usuarios;
		return true;
	}

	public function getid_usuarios()
	{
		return $this->id_usuarios;
	}

	public function setid_caja($id_caja='')
	{
		$this->id_caja = $id_caja;
		return true;
	}

	public function getid_caja()
	{
		return $this->id_caja;
	}

	public function setid_personas($id_personas='')
	{
		$this->id_personas = $id_personas;
		return true;
	}

	public function getid_personas()
	{
		return $this->id_personas;
	}

} // END class venta

/******************************************************************************
* Class for farmacia.ventaitem
*******************************************************************************/

class ventaitem
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $id_venta;

	/**
	* @var int
	*/
	private $id_productos;

	/**
	* @var 
	*/
        private $espack;
	private $cantidad;

	/**
	* @var 
	*/
	private $precioUnitario;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ventaitem WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}

	public function SelectAll($len=1000000,$start=0, $search='', $order='1', $direccion='asc')
	{
		Global $dblink;
		$query = "SELECT i.*, case when i.espack then '' else 'Un.' end as tipoCantidad, p.codigo, p.nombre FROM ventaitem i inner join productos p on p.id=i.id_productos 
                          where (p.nombre like '%".$search."%') and `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' 
                          order by ".$order." ".$direccion." limit ".$start.", ".$len;

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function SelectAllConRecetas($len=1000000,$start=0, $search='', $order='1', $direccion='asc')
	{   
                
		Global $dblink;
		$queryserfvsdf = "select id, id_venta, id_productos, tipoCantidad, cantidad, precioUnitario, codigo, nombre from (
                          SELECT i.id, i.id_venta, i.id_productos, case when i.espack=1 then '' else 'Un.' end as tipoCantidad, i.cantidad, i.precioUnitario, p.codigo, p.nombre FROM ventaitem i inner join productos p on p.id=i.id_productos 
                          where (p.nombre like '%".$search."%') and `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' 
                          union 
                          select -1 as id, -1 as id_venta, -1 as id_productos, '' as tipoCantidad,1 as cantidad, ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0.00)), decimal(10,2)),0.00) as precioUnitario, '' as codigo, CONCAT('Receta N° ',convert(ri.id_receta,char(10)),' (',convert(count(1),char(10)),' Items)') as nombre from recetaitem ri 
                          inner join recetas r on r.id=ri.id_receta 
                          where r.estado in ('N','A') and ri.estado in ('N','A') and r.id_venta='" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "' 
                          group by ri.id_receta ) as xxx
                          order by ".$order." ".$direccion." limit ".$start.", ".$len."; ";
                logTxt($queryserfvsdf);
		$resultedrgfsd = mysqli_query($dblink,$queryserfvsdf);
                if (!$resultedrgfsd and 1==2) {
                    printf("Error: %s\n", mysqli_error($dblink));
                }
                          
	return $resultedrgfsd;

	}
        
        public function Cnt($search)
	{
		Global $dblink;
		$query = "SELECT count(1) as recordsFiltered, count(1) as recordsTotal FROM ventaitem i inner join productos p on p.id=i.id_productos 
                          where (p.nombre like '%".$search."%') and `id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "'"; 

		$result = mysqli_query($dblink,$query);

	return $result;

	}
        
        public function Total($idVenta)
	{
		Global $dblink;
		$query = "SELECT ifnull(convert(sum(vi.cantidad * vi.precioUnitario), decimal(10,2)),0.00)
                      +(select ifnull(convert(sum((ri.cantidad * ri.importeUnitario) - ifnull(ri.importeCobertura,0.00)), decimal(10,2)),0.00)
                            from recetaitem ri
                            inner join recetas r on r.id=ri.id_receta
                            where r.estado in ('N','A') and ri.estado in ('N','A') and r.id_venta=vi.id_venta

                        ) as total FROM ventaitem vi
                        where `id_venta` =  " . $idVenta; 
                //echo $query;
                $total=0;
		$result = mysqli_query($dblink,$query);
                while($row = mysqli_fetch_assoc($result) )
                {
                    $total=$row['total'];
                }        
            return $total;

	}
        

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ventaitem SET 
						`id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "',
						`id_productos` = '" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
                                                `espack` = '" . mysqli_real_escape_string($dblink,$this->getespack()) . "',
						`precioUnitario` = '" . mysqli_real_escape_string($dblink,$this->getprecioUnitario()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ventaitem 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ventaitem (`id`,`id_venta`,`id_productos`,`espack`,`cantidad`,`precioUnitario`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "','" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getespack()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getprecioUnitario()) . "');";
                //echo logc($query);
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ventaitem (`id`,`id_venta`,`id_productos`,`espack`,`cantidad`,`precioUnitario`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "','" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "','" . mysqli_real_escape_string($dblink,$this->getespack()) . "','" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "','" . mysqli_real_escape_string($dblink,$this->getprecioUnitario()) . "') ON DUPLICATE KEY UPDATE 
						`id_venta` = '" . mysqli_real_escape_string($dblink,$this->getid_venta()) . "',
						`id_productos` = '" . mysqli_real_escape_string($dblink,$this->getid_productos()) . "',
						`espack` = '" . mysqli_real_escape_string($dblink,$this->getespack()) . "',
						`cantidad` = '" . mysqli_real_escape_string($dblink,$this->getcantidad()) . "',
						`precioUnitario` = '" . mysqli_real_escape_string($dblink,$this->getprecioUnitario()) . "';";
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

	public function setid_venta($id_venta='')
	{
		$this->id_venta = $id_venta;
		return true;
	}

	public function getid_venta()
	{
		return $this->id_venta;
	}

	public function setid_productos($id_productos='')
	{
		$this->id_productos = $id_productos;
		return true;
	}

	public function getid_productos()
	{
		return $this->id_productos;
	}

        public function setespack($espack='')
	{
		$this->espack = $espack;
		return true;
	}

	public function getespack()
	{
		return $this->espack;
	}
        
	public function setcantidad($cantidad='')
	{
		$this->cantidad = $cantidad;
		return true;
	}

	public function getcantidad()
	{
		return $this->cantidad;
	}

	public function setprecioUnitario($precioUnitario='')
	{
		$this->precioUnitario = $precioUnitario;
		return true;
	}

	public function getprecioUnitario()
	{
		return $this->precioUnitario;
	}

} // END class ventaitem

/******************************************************************************
* Class for farmacia.testcon
*******************************************************************************/

class testcon
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $url;

	/**
	* @var int
	*/
	private $puerto;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM testcon WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM testcon ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE testcon SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`url` = '" . mysqli_real_escape_string($dblink,$this->geturl()) . "',
						`puerto` = '" . mysqli_real_escape_string($dblink,$this->getpuerto()) . "',
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM testcon 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO testcon (`id`,`nombre`,`url`,`puerto`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->geturl()) . "','" . mysqli_real_escape_string($dblink,$this->getpuerto()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO testcon (`id`,`nombre`,`url`,`puerto`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->geturl()) . "','" . mysqli_real_escape_string($dblink,$this->getpuerto()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`url` = '" . mysqli_real_escape_string($dblink,$this->geturl()) . "',
						`puerto` = '" . mysqli_real_escape_string($dblink,$this->getpuerto()) . "',
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
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

	public function seturl($url='')
	{
		$this->url = $url;
		return true;
	}

	public function geturl()
	{
		return $this->url;
	}

	public function setpuerto($puerto='')
	{
		$this->puerto = $puerto;
		return true;
	}

	public function getpuerto()
	{
		return $this->puerto;
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

} // END class testcon


//if(is_resource($dblink)) mysqli_close($dblink);