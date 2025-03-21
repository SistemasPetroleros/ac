<?php

class BaseDatosOracle
{
  //produccion
    var $INSTANCIA="172.26.0.27:1521/odbctekhne";
	//var $USUARIO='ospepri_prepro_v11';
	var $USUARIO='OSPEPRI_PRODUCCION';
	var $CLAVE='ospepripw';
	
 	var $CONEXION;
	
	
function iniciarSesionBDOracle()
{
      
	  $conexion = oci_connect($this->USUARIO, $this->CLAVE, $this->INSTANCIA); 
	  if ($conexion){
	  
	       $this->CONEXION = $conexion;
		   return $conexion;
	  }
	  else
	  {
	     return false;
	  }
	    
}

function cerrarSesionBDOracle()
{
  		return oci_close($this->CONEXION);
}


  
  
	
}

?>

