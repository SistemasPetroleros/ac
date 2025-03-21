<?php
set_time_limit(300);
//date_default_timezone_set('America/Argentina/Rio_Gallegos');
error_reporting(0);
//error_reporting(-1); error_reporting(E_ALL); ini_set('error_reporting', E_ALL);
//if (isset($_GET['error'])){error_reporting(-1); error_reporting(E_ALL); ini_set('error_reporting', E_ALL);}else{error_reporting(0);}
session_start();

$hoy = date("d/m/Y");
$hoyMenosUnMes = date("d/m/Y",strtotime(date("d-m-Y")."- 1 month"));

define('DB_HOST','127.0.0.1');
define('DB_USER','root');
define('DB_PASS','pepe');
define('DB_BASE','ospepri_altocostotest');

try
		{
			$dblink = mysqli_connect(DB_HOST,DB_USER,DB_PASS);
			mysqli_select_db($dblink,DB_BASE);
		}
		catch(Exception $ex)
		{
			echo "Could not connect to " . DB_HOST . ":" . DB_BASE . "\n";
			echo "Error: " . $ex->message;
			exit;
        }
$dblink->set_charset("utf8");


//include_once '../model/usuarios.php';
//include_once 'login.php';






        

