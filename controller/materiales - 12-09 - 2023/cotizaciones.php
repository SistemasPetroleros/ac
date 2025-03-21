<?php
include_once '../config.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_solicitudes_estados.php';

include_once '../funciones.php';


$obj = new materiales_cotizacion_solic_prov();
$idSolicitud=$_POST['idSolicitud'];
//echo "entra";

if (isset($_POST['accion'])) {
    if($_POST['accion']=="agregar")
    {
       // echo "ENTRA";
        $obj->setid($_POST['id']);
        $obj->setobservaciones($_POST['observaciones']);
        $obj->setimporte($_POST['importe']);
        $obj->setuserModif($_SESSION['user']);
        $obj->Save();   
    }
    else
    {   $idSolicitudProv=$_POST['id'];
        $obj->setid($_POST['id']);
		$obj->setobservaciones($_POST['observaciones']);
        $obj->setimporte($_POST['importe']);
        $obj->setid_solicitudes($_POST['idSolicitud']);
        $obj->setuserModif($_SESSION['user']);
		
		//Registro cambio de estado 
		$objStatus = new materiales_solicitudes_estados();
		$lastStatus=$objStatus -> SelectStatusRecent($idSolicitud);
		if ($x = mysqli_fetch_assoc($lastStatus)) {
			$estadoActual=$x['id_estados'];
		}
		
		
		

        if($_POST['accion']=="aprobar")
        {   //Cambia el estado de la cotización a APROBADA, y ANULA todas las demas cotizaciones
            //Estas ya no se pueden modicar 
            //Se envía el mail de la cotización a la droguería (PROVEEDOR APROBADO) 
            if ($_POST['rand'] == $_POST['rand2']) {
               
                if($obj->AprobarAnularCotizaciones())
                {
                    //enviar EMAIL ANTES  ------------------------
                    //--------------------------------------------------------

                  //  include_once 'enviarMailProveedorAprobado.php';
                    include_once 'asignarProveedores.php';
					
					$observacion= $_SESSION['user'].' cambia el estado la cotizaci&oacute;n #'.$_POST['id'].' de la solicitud #'.$idSolicitud.' de PENDIENTE a APROBADO.';
					//registra cambio de estado
					$objStatus->setid_estados($estadoActual);
					$objStatus->setobservaciones($observacion);
					$objStatus->setid_solicitudes($idSolicitud);
					$objStatus -> Create();
                    
                    
                }
                else{
                    echo 0;
                }   
                
            }  
        }
        else{
           if($_POST['accion']=="anular")
           {
                if ($_POST['rand'] == $_POST['rand2']) {

                    //ANULA una cotización, pero si todas estan anuladas deberia ANULAR la solicitud también.
                    if($obj->AnularCotizaciones())
                    {

                        include_once 'asignarProveedores.php';
						$observacion='El usuario '. $_SESSION['user'].' cambia el estado de la Cotizaci&oacute;n #'.$_POST['id'].' de la Solicitud #'.$idSolicitud.' de PENDIENTE a ANULADA.';
						//registra cambio de estado
						$objStatus->setid_estados($estadoActual);
						$objStatus->setobservaciones($observacion);
						$objStatus->setid_solicitudes($idSolicitud);
						$objStatus -> Create();
                       
                    }
                    else{
                        echo 0;
                    } 
                }  
           }
           else
           {
                //Revertir
                //solo si esta ANULADA y no hay ninguna aprobada
                if ($_POST['rand'] == $_POST['rand2']) {

                    if($obj->RevertirCotizaciones())
                    {

                        include_once 'asignarProveedores.php';
						
						$observacion= 'EL usuario '.$_SESSION['user'].' cambia el estado de la Cotizaci&oacute;n #'.$_POST['id'].' de la Solicitud #'.$idSolicitud.' de ANULADA a PENDIENTE.';
						//registra cambio de estado
						$objStatus->setid_estados($estadoActual);
						$objStatus->setobservaciones($observacion);
						$objStatus->setid_solicitudes($idSolicitud);
						$objStatus -> Create();
                    }
                    else{
                        echo 0;
                    } 
                }  

           }     


        }

       
    }
        
}


