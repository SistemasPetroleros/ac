<?php

error_reporting(0);
header('Content-Type: application/json');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Max-Age: 1000');

$salida = '';
include_once("baseDatos.php");
//require("conectarse.php");
//========================================================
$dni = trim($_GET['dni']);
$email = trim($_GET['email']);
$apellidos = ucwords(trim($_GET['apellidos']));
$nombres = ucwords(trim($_GET['nombres']));
$telefono = trim($_GET['telefono']);
$codAut = trim($_GET['codAut']);
//$pass = $_GET['pass'];
//$passEnc = md5($pass);
//========================================================

$salida = '';

require("bd_oracle.php");
$bd= new  BaseDatosOracle();

$SqlLucho="SELECT DISTINCT AF.BATITNROIN NRO_INT,AF.BAGFMNROIN, AF.BAGFMAPELL APELLIDO, AF.BAGFMNOMBR NOMBRES, AF.BAGFMNROAF NRODOC,
    PAR.BAPARDESCR PARENTESCO,  AF.OGORCODIGO COD_ENT,ENT.OGORDESCRI ENTIDAD,  To_Char(AF.BAGFMFEINI,'dd/mm/yyyy') FALTA, To_Char(AF.BAGFMFECBA,'dd/mm/yyyy') FBAJA,
    DOM.BEDOMCALLE CALLE, DOM.BEDOMNUMER NRO, DOM.BEDOMPISO PISO, DOM.BEDOMDPTO DPTO, DOM.BEDOMTELEF TELEFONOS,LOC.LOCADESCRI LOCALIDAD,
    PROV.SSPRDESCRI PROVINCIA,  TO_CHAR(BP.BAPERFENAC,'YYYY-MM-DD')  FECHA_NAC, BP.SEXOCODIGO SEXO, 
     BP.BAPERMAIL1 EMAIL1, BP.BAPERMAIL2 EMAIL2, PL.PLCADESBRE PLAN ,
     BE.BEEMPRAZSO EMPRESA, CONCAT(TRIM(DOM.BEDOMCALLE), CONCAT( ' ',CONCAT( DOM.BEDOMNUMER,CONCAT( '--Piso: ', CONCAT(DOM.BEDOMPISO,CONCAT('--Dpto: ', DOM.BEDOMDPTO)))))) DOMICILIO
FROM BAGFMIE AF
       left JOIN BATIEMP BAT ON ( AF.BATITNROIN=BAT.BATITNROIN and BATIEMPPRI='S' and BAT.BATIEFEBAJ='01-01-0001'  and  BAT.BATIEFEINI= ( SELECT MAX(BAT1.BATIEFEINI) from BATIEMP  BAT1
 WHERE BAT1.BATITNROIN=AF.BATITNROIN and  BAT1.BATIEFEBAJ='01-01-0001' and  BATIEMPPRI='S' ) )
     INNER JOIN BAPERSO BP ON BP.BAPERNROIN=AF.BAGFMNROIN
   left JOIN BEEMPRE BE ON BE.BEEMPNUMEM=BAT.BATIENUMEM
     INNER JOIN BAPAREN PAR ON PAR.BAPARCODIG=AF.BAPARCODIG
     INNER JOIN OGORGAN ENT ON ENT.OGORCODIGO=AF.OGORCODIGO
     INNER JOIN BEDOMI DOM ON( DOM.BEDOMNROIN=AF.BATITNROIN   AND DOM. BEDOMALFEH= (SELECT  MAX(BEDOMALFEH)  FROM BEDOMI DOM1 WHERE DOM.BEDOMNROIN=DOM1.BEDOMNROIN))
     INNER JOIN LOCALIDA LOC ON LOC.LOCACODIGO=DOM.LOCACODIGO
    INNER JOIN SSPROVIN PROV ON PROV.SSPRPROVAF=LOC.PCIACODIGO
    INNER JOIN PLCABECE PL ON PL.PLCANUMPLA=AF.BAGFMNUPLA
WHERE  AF.BAGFMNROAF = '".$dni."' AND 
AF.OGORCODIGO=1   and BAGFMFECBA='0001-01-01';";

$Sql = "SELECT DISTINCT AF.BAGFMNROIN NRO_INT, AF.BAGFMFGCON CONVIVE,AF.BAGFMNROIN, TRIM(AF.BAGFMAPELL) APELLIDO, TRIM(AF.BAGFMNOMBR) NOMBRES, TRIM(AF.BAGFMNROAF) NRODOC,
   trim(PAR.BAPARDESCR) PARENTESCO,  AF.OGORCODIGO COD_ENT,ENT.OGORDESCRI ENTIDAD,  AF.BAGFMFEINI FALTA, To_Char(AF.BAGFMFECBA,'dd/mm/yyyy') FBAJA,
   TRIM(DOM.BEDOMCALLE) CALLE, TRIM(DOM.BEDOMNUMER) NRO, TRIM(DOM.BEDOMPISO) PISO, TRIM(DOM.BEDOMDPTO) DPTO, TRIM(DOM.BEDOMCUERP) CUERPO, TRIM(DOM.BEDOMTELEF) TELEFONOS, TRIM(BP.BAPERCELUL) CELULAR, TRIM(BP.BAPERTELEM) TELEMERGENCIA,trim(LOC.LOCACODIGO) LOCACODIGO,LOC.LOCADESCRI LOCALIDAD, 
   trim(LOC.PCIACODIGO) PCIACODIGO,PROV.SSPRDESCRI PROVINCIA, TRIM(BP.BAPERMAIL1) EMAIL1, TRIM(BP.BAPERMAIL2) EMAIL2, PL.PLCADESBRE PLAN, BE.BEEMPRAZSO EMPRESA
FROM BAGFMIE AF 
   INNER JOIN BATIEMP BAT ON AF.BATITNROIN=BAT.BATITNROIN and BAT.OGORCODIGO=AF.OGORCODIGO
   INNER JOIN BAPERSO BP ON BP.BAPERNROIN=AF.BAGFMNROIN
   INNER JOIN BEEMPRE BE ON BE.BEEMPNUMEM=BAT.BATIENUMEM
   INNER JOIN BAPAREN PAR ON PAR.BAPARCODIG=AF.BAPARCODIG
   INNER JOIN OGORGAN ENT ON ENT.OGORCODIGO=AF.OGORCODIGO
   INNER JOIN BEDOMI DOM ON (DOM.BEDOMNROIN=AF.BATITNROIN AND AF.BAGFMFGCON='S') OR (DOM.BEDOMNROIN=AF.BAGFMNROIN AND AF.BAGFMFGCON='N') 
   INNER JOIN LOCALIDA LOC ON LOC.LOCACODIGO=DOM.LOCACODIGO
   INNER JOIN SSPROVIN PROV ON PROV.SSPRPROVAF=LOC.PCIACODIGO
  INNER JOIN PLCABECE PL ON PL.PLCANUMPLA=AF.BAGFMNUPLA
WHERE AF.BAGFMNROAF='" . $dni . "' AND AF.OGORCODIGO=1                  
      AND To_Char(BAT.BATIEFEBAJ,'yyyy-mm-dd')='0001-01-01'  AND BAT.BATIEFEINI=(SELECT MAX(BAT1.BATIEFEINI)
                                                                  FROM BATIEMP BAT1
                                                                  WHERE BAT1.BATITNROIN=AF.BATITNROIN and To_Char(BAT1.BATIEFEBAJ,'yyyy-mm-dd')='0001-01-01') ";

$Sql_MODIFICADO_PRUEBAS = "SELECT DISTINCT AF.BAGFMNROIN NRO_INT, AF.BAGFMFGCON CONVIVE,AF.BAGFMNROIN, TRIM(AF.BAGFMAPELL) APELLIDO, TRIM(AF.BAGFMNOMBR) NOMBRES, TRIM(AF.BAGFMNROAF) NRODOC,
   trim(PAR.BAPARDESCR) PARENTESCO,  AF.OGORCODIGO COD_ENT,ENT.OGORDESCRI ENTIDAD,  AF.BAGFMFEINI FALTA, To_Char(AF.BAGFMFECBA,'dd/mm/yyyy') FBAJA,
   TRIM(DOM.BEDOMCALLE) CALLE, TRIM(DOM.BEDOMNUMER) NRO, TRIM(DOM.BEDOMPISO) PISO, TRIM(DOM.BEDOMDPTO) DPTO, TRIM(DOM.BEDOMCUERP) CUERPO, TRIM(DOM.BEDOMTELEF) TELEFONOS, TRIM(BP.BAPERCELUL) CELULAR, TRIM(BP.BAPERTELEM) TELEMERGENCIA,trim(LOC.LOCACODIGO) LOCACODIGO,LOC.LOCADESCRI LOCALIDAD, 
   trim(LOC.PCIACODIGO) PCIACODIGO,PROV.SSPRDESCRI PROVINCIA, TRIM(BP.BAPERMAIL1) EMAIL1, TRIM(BP.BAPERMAIL2) EMAIL2, PL.PLCADESBRE PLAN, BE.BEEMPRAZSO EMPRESA
FROM BAGFMIE AF 
   LEFT JOIN BATIEMP BAT ON AF.BATITNROIN=BAT.BATITNROIN and BAT.OGORCODIGO=AF.OGORCODIGO
   LEFT JOIN BAPERSO BP ON BP.BAPERNROIN=AF.BAGFMNROIN
   LEFT JOIN BEEMPRE BE ON BE.BEEMPNUMEM=BAT.BATIENUMEM
   LEFT JOIN BAPAREN PAR ON PAR.BAPARCODIG=AF.BAPARCODIG
   LEFT JOIN OGORGAN ENT ON ENT.OGORCODIGO=AF.OGORCODIGO
   LEFT JOIN BEDOMI DOM ON (DOM.BEDOMNROIN=AF.BATITNROIN AND AF.BAGFMFGCON='S') OR (DOM.BEDOMNROIN=AF.BAGFMNROIN AND AF.BAGFMFGCON='N') 
   LEFT JOIN LOCALIDA LOC ON LOC.LOCACODIGO=DOM.LOCACODIGO
   LEFT JOIN SSPROVIN PROV ON PROV.SSPRPROVAF=LOC.PCIACODIGO
   LEFT JOIN PLCABECE PL ON PL.PLCANUMPLA=AF.BAGFMNUPLA
WHERE AF.BAGFMNROAF='" . $dni . "' AND AF.OGORCODIGO=1                  
AND To_Char(AF.BAGFMFECBA,'yyyy-mm-dd')='0001-01-01'; ";

																  
echo $Sql;
	$con=$bd->iniciarSesionBDOracle();
	if($con)
	{
	    $query =oci_parse($con, $Sql);
		$rowora=oci_execute($query);
		
		if (!$rowora)
		{ 		  
		   die('ERROR al consultar Afiliado SIA.');
		}				
	   
	}
	$bd->cerrarSesionBDOracle();
  
if( $rowSia=@oci_fetch_assoc($query)) //si existe afiliado en SIA
{
   $existeAfiliadoSIA=1;
  
   $apellidosSia=utf8_encode($rowSia['APELLIDO']);
   $nombresSia=utf8_encode($rowSia['NOMBRES']);
   $telefonoSia=$rowSia['TELEFONOS'];
   
   if($rowSia['FBAJA']=="01/01/0001")
   {
     $activo=1;
   }
   else
   {
      $activo=0;
   }
}
else
{ //No existe en SIA
    $existeAfiliadoSIA=0;
	$activo=0;
}



//echo $Sql;	
//si no llega a existir hay que buscarlo en SIA
$row = [];

$bd = new BaseDatosMySQL();
$link = $bd->iniciarSesionBD();


if ($link) {
    $Sql = "SELECT `id`,`nroDocumento`,`nombres`,`apellidos`,`email`,`emailValido`,`codigoValidacion`, ".$existeAfiliadoSIA." as existe, ".$activo." activo FROM `afiliados` WHERE `nroDocumento`= '" . $dni . "' limit 1";
    $query = mysqli_query($link, $Sql);
    $numrow = mysqli_num_rows($query);
    if (!$query) {
        die('ERROR al consultar datos de Beneficiarios.');
    } else {
        $row = @mysqli_fetch_assoc($query);
    }


    if ($numrow < 1 and strlen($apellidos) > 0 and strlen($nombres) > 0 and strlen($telefono) > 0) {


        $random = mt_rand(1000, 9999);
        $Sql = "INSERT INTO `afiliados`(`nroDocumento`, `sexo`, `nombres`, `apellidos`, `fechaNac`, `telefonos`, `email`, `codigoValidacion`, `idOSocial`, `observaciones`, `userAlta`, `fAlta`) 
VALUES ('" . $dni . "', '-', '" . $nombres . "', '" . $apellidos . "',now(), '" . $telefono . "', '" . $email . "', " . $random . ", 1, 'Creado desde Turnos Web', 'zunigal', now() )";
        $query = mysqli_query($link, $Sql);

        $Sql = "SELECT `id`,`nroDocumento`,`nombres`,`apellidos`,`email`,`emailValido`,`codigoValidacion`, ".$existeAfiliadoSIA." as existe, ".$activo." activo FROM `afiliados` WHERE `nroDocumento`= '" . $dni . "' limit 1";
        $query = mysqli_query($link, $Sql);
        $numrow = mysqli_num_rows($query);
        if (!$query) {
            die('ERROR al consultar datos de Beneficiarios.');
        } else {
            $row = @mysqli_fetch_assoc($query);
        }
    }


    if ($numrow < 1 and strlen($apellidos) == 0 and strlen($nombres) == 0 and strlen($telefono) == 0) {
        ///ACA TRAEMOS LOS DATOS PARA INSERTAR DE SIA
		if($existeAfiliadoSIA==1)
		{
			$random = mt_rand(1000, 9999);
			$Sql = "INSERT INTO `afiliados`(`nroDocumento`, `sexo`, `nombres`, `apellidos`, `fechaNac`, `telefonos`, `email`, `codigoValidacion`, `idOSocial`, `observaciones`, `userAlta`, `fAlta`) 
	VALUES ('" . $dni . "', '-', '" . $nombresSia . "', '" . $apellidosSia . "',now(), '" . $telefonoSia . "', '" . $email . "', " . $random . ", 1, 'Importado de SIA', 'zunigal', now() )";
			$query = mysqli_query($link, $Sql);
	
			$Sql = "SELECT `id`,`nroDocumento`,`nombres`,`apellidos`,`email`,`emailValido`,`codigoValidacion`, ".$existeAfiliadoSIA." as existe, ".$activo." activo FROM `afiliados` WHERE `nroDocumento`= '" . $dni . "' limit 1";
			$query = mysqli_query($link, $Sql);
			//echo $sql;
			$numrow = mysqli_num_rows($query);
			if (!$query) {
				die('ERROR al consultar datos de Beneficiarios.');
			} else {
				$row = @mysqli_fetch_assoc($query);
			}
		}
		else
		{
		   $row=[];
		}
		
    }


    if ($numrow == 1) {
        if ($row['email'] != $email) {
            $random = mt_rand(1000, 9999);
            $Sql = "UPDATE `afiliados` SET `email`='" . $email . "',`emailValido`=0,`codigoValidacion`= '" . $random . "' WHERE `nroDocumento`= '" . $dni . "' limit 1";
            $query = mysqli_query($link, $Sql);

            $Sql = "SELECT `id`,`nroDocumento`,`nombres`,`apellidos`,`email`,`emailValido`,`codigoValidacion`, ".$existeAfiliadoSIA." as existe, ".$activo." activo FROM `afiliados` WHERE `nroDocumento`= '" . $dni . "' limit 1";
            $query = mysqli_query($link, $Sql);
            $numrow = mysqli_num_rows($query);
            if (!$query) {
                die('ERROR al consultar datos de Beneficiarios.');
            } else {
                $row = @mysqli_fetch_assoc($query);
            }
        }

        if ($row['emailValido'] == 0 and $row['codigoValidacion'] == $codAut) {

            $Sql = "UPDATE `afiliados` SET `emailValido`=1 WHERE `nroDocumento`= '" . $dni . "' and `codigoValidacion`= " . $codAut . " and `email`='" . $email . "' limit 1";
            $query = mysqli_query($link, $Sql);

            $Sql = "SELECT `id`,`nroDocumento`,`nombres`,`apellidos`,`email`,`emailValido`,`codigoValidacion`, ".$existeAfiliadoSIA." as existe, ".$activo." activo FROM `afiliados` WHERE `nroDocumento`= '" . $dni . "' limit 1";
            $query = mysqli_query($link, $Sql);
            $numrow = mysqli_num_rows($query);
            if (!$query) {
                die('ERROR al consultar datos de Beneficiarios.');
            } else {
                $row = @mysqli_fetch_assoc($query);
            }
        }


        if ($row['emailValido'] == 0 and $codAut=='') {
            include_once('enviar_mail.php');

            $noHtml = 'Número de Autorización:' . $row['codigoValidacion'] . '';
            $body = "";
            $body .= '<tr><td><b>Número de Autorización:</b></td><td><h3>' . $row['codigoValidacion'] . '</h3></td></tr>';





            $mensaje = '<html>
				 <head>
				  <title>HTML email</title>
				 </head>
				  <body>
					   <p>Estimado/a ' . utf8_encode(trim($row['nombres'])) . ' ' . utf8_encode(trim($row['apellidos'])) . ', por favor ingrese el siguiente Número de Autorización para solicitar turnos</p>
					   <table border="0">
						   <thead>
						</thead>
						<tbody>
						' . $body . '
						</tbody>
						
						</table>
				<br/>
				
				<p> Recuerde ser puntual y respetar las normas de seguridad e higiene vigentes. </p>
				<p>Obra Social de Petroleros Privados (O.S.Pe.Pri.)</p>
				<br/>
				<hr>
				<p>Correo enviado autom&aacute;ticamente, no responder!</p>
				
					 </body>
				 </html>';




            //$destino = "zuniga.luciano@gmail.com";
            $remitente = "Turnos O.S.Pe.Pri.";
            $asunto = utf8_encode("Número de Autorización");
            $mensaje = utf8_encode($mensaje);

            $destino = utf8_decode($email);
            $asunto = utf8_decode($asunto);
            $mensaje = utf8_decode($mensaje);
            $remitente = utf8_decode($remitente);

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: Turnos O.S.Pe.Pri. <no-reply@ospepri.org.ar>' . "\r\n";

            enviarMail($destino, $asunto, $mensaje, $noHtml);
        }


        $row['codigoValidacion'] = '0000';
        $salida = json_encode($row);
    }




    $bd->cerrarSesionBD();
}





// verifico que exista 
// si n existe lo traigo de sia
//si no existe en sia lo creo, ver que datos pedir
//si exite verifico que emailValido este en true
//en caso de no estan en true y codigo de validacion este en 0 genero random 4 digitos en codigo Validacion
///envio respuesta, no enviar codigoValidacion
//echo $salida;

if (isset($_GET['direct'])) {
    echo $salida;
} else {
    $salida = base64_encode($salida);
    if (isset($_GET['callback'])) { // Si es una peticiï¿½n cross-domain  
        echo $_GET['callback'] . '(' . json_encode($salida) . ')';
    } else // Si es una normal, respondemos de forma normal  
        echo json_encode($salida);
}
