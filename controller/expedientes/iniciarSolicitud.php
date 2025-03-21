<?php
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/personas.php';
include_once '../funciones.php';





require("../bd_oracle.php");
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
WHERE  AF.BAGFMNROAF = '".$_POST['dniBeneficiario']."' AND 
AF.OGORCODIGO=1   and BAGFMFECBA='0001-01-01';";

$Sql = "SELECT DISTINCT AF.BAGFMNROIN NRO_INT, AF.BAGFMFGCON CONVIVE,AF.BAGFMNROIN, TRIM(AF.BAGFMAPELL) APELLIDO, TRIM(AF.BAGFMNOMBR) NOMBRES, TRIM(AF.BAGFMNROAF) NRODOC,
   trim(PAR.BAPARDESCR) PARENTESCO,  AF.OGORCODIGO COD_ENT,ENT.OGORDESCRI ENTIDAD,  AF.BAGFMFEINI FALTA, To_Char(AF.BAGFMFECBA,'dd/mm/yyyy') FBAJA,
   TRIM(DOM.BEDOMCALLE) CALLE, TRIM(DOM.BEDOMNUMER) NRO, TRIM(DOM.BEDOMPISO) PISO, TRIM(DOM.BEDOMDPTO) DPTO, TRIM(DOM.BEDOMCUERP) CUERPO, TRIM(DOM.BEDOMTELEF) TELEFONOS, TRIM(BP.BAPERCELUL) CELULAR, TRIM(BP.BAPERTELEM) TELEMERGENCIA,trim(LOC.LOCACODIGO) LOCACODIGO,LOC.LOCADESCRI LOCALIDAD, 
   trim(LOC.PCIACODIGO) PCIACODIGO,PROV.SSPRDESCRI PROVINCIA, TRIM(BP.BAPERMAIL1) EMAIL1, TRIM(BP.BAPERMAIL2) EMAIL2, PL.PLCADESBRE PLAN, BE.BEEMPRAZSO EMPRESA,BP.SEXOCODIGO SEXO,TO_CHAR(BP.BAPERFENAC,'DDMMYYYY')  FECHA_NAC_CHAR,
   (select count(1) as B24 from ENPROGR progr
where progr.ENPRNROMIE=AF.BAGFMNROIN
and To_Char(SYSDATE,'yyyy-mm-dd') BETWEEN To_Char(progr.ENPRFECVIG,'yyyy-mm-dd') AND To_Char(progr.ENPRFECBAJ,'yyyy-mm-dd')
and progr.GRAVCODGRA=63) ESB24
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
WHERE AF.BAGFMNROAF='" . $_POST['dniBeneficiario'] . "' AND AF.OGORCODIGO=1                  
      AND (To_Char(AF.BAGFMFECBA,'yyyy-mm-dd')='0001-01-01' OR To_Char(AF.BAGFMFECBA,'yyyy-mm-dd') IS NULL)";  /*AND BAT.BATIEFEINI=(SELECT MAX(BAT1.BATIEFEINI)
                                                                  FROM BATIEMP BAT1
                                                                  WHERE BAT1.BATITNROIN=AF.BATITNROIN and To_Char(BAT1.BATIEFEBAJ,'yyyy-mm-dd')='0001-01-01') ";*/

																  
//echo $Sql;
// la tabla es la ENPROGR y el codigo es el 63!
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
   $telefonoSia=TRIM($rowSia['TELEFONOS'].' ' .$rowSia['CELULAR']);
   $nroInternoSia=$rowSia['NRO_INT'];
    $emailEnSIA=TRIM($rowSia['EMAIL1'].' ' .$rowSia['EMAIL2']);
    $codB24 = TRIM($rowSia['SEXO']).strtoupper(substr($nombresSia, 0, 2)).strtoupper(substr($apellidosSia, 0, 2)).TRIM($rowSia['FECHA_NAC_CHAR']);
    $esB24=(($rowSia['ESB24']>0)?1:0);

   if($rowSia['FBAJA']=="01/01/0001")
   {
     $activo='A';
   }
   else
   {
      $activo='I';
   }
}
else
{ //No existe en SIA
    $existeAfiliadoSIA=0;
    $activo='I';
}


$persona = new personas();
$persona->setdni($_POST['dniBeneficiario']);
$persona->LoadxDni();
if($persona->getid()>0){
    $persona->setnroInternoSIA($nroInternoSia);
    $persona->setestadoSIA($activo);
    $persona->setesB24($esB24);
    $persona->setcodigoB24($codB24);
    $persona->setuserModif($_SESSION['user']);
    $persona->Save();
    //actualizo estado
}else{
    $persona->setdni($_POST['dniBeneficiario']);
    $persona->setapellido($apellidosSia);
    $persona->setnombre($nombresSia);
    $persona->settelefono($telefonoSia);
    $persona->setemail($emailEnSIA);
    $persona->setnroInternoSIA($nroInternoSia);
    $persona->setestadoSIA($activo);
    $persona->setesB24($esB24);
    $persona->setcodigoB24($codB24);
    $persona->setuserAlta($_SESSION['user']);
    if($activo=='A'){$persona->Create();}
    
    //inserto
}



$puntosdispensa = new puntos_dispensa();
$arrayPuntosDispensa = $puntosdispensa->SelectAll();

$solicitud = new solicitudes();

include_once '../../view/solicitudes/iniciarSolicitud.php';
?>
