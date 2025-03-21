<?php


function archivo($nombreArchivo, $linea) {
    $fecha = date("Y-m-d H:i:s");
    $file = fopen($nombreArchivo, "a+");
    fwrite($file, $fecha . ": " . $linea . PHP_EOL);
//fwrite($file, PHP_EOL . "--------------------------" . PHP_EOL);
    fclose($file);
}

function logTxt($linea) {
    $nombreArchivo='C:\xampp\htdocs\ac\log\log.txt';
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
    $string = str_replace('	', ' ', $string);
 
    
 
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


function esMiembro($elemento,$array) {
    $longitud=count($array);
    for($i=0; $i<$longitud; $i++)
    {
          if($array[$i]==$elemento)  {
              return TRUE;
          }
    }
 
    return FALSE;
 
 }

function encryptWithKey($data, $key)
{
    $cipher = 'AES-256-CBC'; // Puedes elegir un algoritmo de cifrado adecuado
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}


function decryptWithKey($data, $key)
{
    $cipher = 'AES-256-CBC'; // Debe coincidir con el algoritmo utilizado en la función de encriptación
    $data = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $ivlen);
    $data = substr($data, $ivlen);
    $decrypted = openssl_decrypt($data, $cipher, $key, 0, $iv);
    return $decrypted;
}



function numerosAletras($num, $fem = false, $dec = true)
{
    $matuni[2] = "dos";
    $matuni[3] = "tres";
    $matuni[4] = "cuatro";
    $matuni[5] = "cinco";
    $matuni[6] = "seis";
    $matuni[7] = "siete";
    $matuni[8] = "ocho";
    $matuni[9] = "nueve";
    $matuni[10] = "diez";
    $matuni[11] = "once";
    $matuni[12] = "doce";
    $matuni[13] = "trece";
    $matuni[14] = "catorce";
    $matuni[15] = "quince";
    $matuni[16] = "dieciseis";
    $matuni[17] = "diecisiete";
    $matuni[18] = "dieciocho";
    $matuni[19] = "diecinueve";
    $matuni[20] = "veinte";
    $matunisub[2] = "dos";
    $matunisub[3] = "tres";
    $matunisub[4] = "cuatro";
    $matunisub[5] = "quin";
    $matunisub[6] = "seis";
    $matunisub[7] = "sete";
    $matunisub[8] = "ocho";
    $matunisub[9] = "nove";

    $matdec[2] = "veint";
    $matdec[3] = "treinta";
    $matdec[4] = "cuarenta";
    $matdec[5] = "cincuenta";
    $matdec[6] = "sesenta";
    $matdec[7] = "setenta";
    $matdec[8] = "ochenta";
    $matdec[9] = "noventa";
    $matsub[3] = 'mill';
    $matsub[5] = 'bill';
    $matsub[7] = 'mill';
    $matsub[9] = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4] = 'millones';
    $matmil[6] = 'billones';
    $matmil[7] = 'de billones';
    $matmil[8] = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';

    //Zi hack
    $float = explode('.', $num);
    $num = $float[0];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    } else
        $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (!(strpos(".,'''", $n) === false)) {
            if ($punt) break;
            else {
                $punt = true;
                continue;
            }
        } elseif (!(strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') $zeros = false;
                $fra .= $n;
            } else

                $ent .= $n;
        } else

            break;
    }
    $ent = ' ' . $ent;
    if ($dec and $fra and !$zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= ' cero';
            elseif ($s == '1')
                $fin .= $fem ? ' una' : ' un';
            else
                $fin .= ' ' . $matuni[$s];
        }
    } else
        $fin = '';
    if ((int)$ent === 0) return 'Cero ' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while (($num = substr($ent, -3)) != ' ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent = 'as';
        } else {
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        } elseif ($n2 < 21)
            $t = ' ' . $matuni[(int)$n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) $t = 'i' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        } else {
            $n3 = $num[2];
            if ($n3 != 0) $t = ' y ' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        } elseif ($n == 5) {
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        } elseif ($n != 0) {
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        } elseif (!isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            } elseif ($num > 1) {
                $t .= ' mil';
            }
        } elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        } elseif ($num > 1) {
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') $mils++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);
    $end_num = ucfirst($tex) . ' pesos ' . $float[1] . '/100 M.N.';
    return $end_num;
}



function number_words($valor, $desc_moneda, $sep, $desc_decimal)
{
    $arr = explode(".", $valor);
    $entero = $arr[0];
    if (isset($arr[1])) {
        $decimos = strlen($arr[1]) == 1 ? $arr[1] . '0' : $arr[1];
    }

    $fmt = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
    if (is_array($arr)) {
        $num_word = ($arr[0] >= 1000000) ? "{$fmt->format($entero)} de $desc_moneda" : "{$fmt->format($entero)} $desc_moneda";
        if (isset($decimos) && $decimos > 0) {
            $num_word .= " $sep {$fmt->format($decimos)} $desc_decimal";
        }
    }
    return $num_word;
}

function utf8ize($data) {
    // Verifica si es un array
    if (is_array($data)) {
        return array_map('utf8ize', $data);
    }

        $data = utf8_encode($data);
        // Detectar la codificación de la cadena
        $encoding = mb_detect_encoding($data, mb_detect_order(), true);

        return mb_convert_encoding($data, 'UTF-8', $encoding);


    // Si no es un string ni un array, devuelve el dato sin cambios
    return $data;
}



function limpiarNumeroTelefono($numero) {
    // Eliminar cualquier caracter no numérico del número de teléfono
    $numero = preg_replace('/\D/', '', $numero);



    // Si el número comienza con 549, quitarlo
    if (substr($numero, 0, 3) === '549' && strlen($numero) > 10) {
        $numero = substr($numero, 3);
    }
    // Si el número comienza con 54, quitarlo
    if (substr($numero, 0, 2) === '54' && strlen($numero) > 10) {
        $numero = substr($numero, 2);  
    }
   

        // Si el número comienza con 0, quitarlo
    if (substr($numero, 0, 1) === '0') {
        $numero = substr($numero, 1);
    }
    // Si el número comienza con 15, reemplazarlo con 299
    if (substr($numero, 0, 2) === '15') {
        $numero = '299' . substr($numero, 2);
    }

    // Si "15" está en las posiciones 3, 4 o 5, quitarlo --- SOLO QUITA LA PRIMER OCURRENCIA
    if ((substr($numero, 2, 2) === '15' || substr($numero, 3, 2) === '15' || substr($numero, 4, 2) === '15') && strlen($numero) > 10) {
        $numero = preg_replace('/15/', '', $numero, 1); // Elimina solo la primera ocurrencia de '15'
    }

    //si el largo es de 7 caracteres asumo agregar 299
    if (strlen($numero) == 7) {
        $numero = '299'.$numero;
    }

    // Asegurar que el número tenga 10 dígitos
    if (strlen($numero) > 10) {
        $numero = substr($numero, 0,10);
    }
    if (strlen($numero) < 10) {
        $numero = '';
    }

    return $numero;
}



