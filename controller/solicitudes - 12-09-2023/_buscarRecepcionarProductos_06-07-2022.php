<?php 
       
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        error_reporting(-1);
        session_start();
        include_once '../config.php';
        include_once '../../model/solicitudes.php';
        include_once '../../model/solicitudes_items_traza.php';
        include_once '../../model/puntos_dispensa.php';
        require_once('../../lib/nusoap/nusoap.php');
        include_once '../../model/solicitudes_items_traza_estado.php';


        $error="";

        $idSolicitud = isset($_POST['idSolicitud'])?$_POST['idSolicitud']:-1;
        $solicitud= new Solicitudes($idSolicitud);

   

        //Obtengo datos pto de dispensa
        $idPtoDispensa=$solicitud->getid_puntos_dispensa();
        $ptoDispensa= new puntos_dispensa($idPtoDispensa);
        $glnDestino= $ptoDispensa -> getGLN();
        $user=$ptoDispensa -> getuserAnmat();
        $pass=$ptoDispensa -> getclaveAnmat();



         //Testing
      //  $wsdl = "https://servicios.pami.org.ar/trazamed.WebService";
        
        
        //Produccion
        $wsdl = "https://trazabilidad.pami.org.ar:9050/trazamed.WebService";
   
        $XmlSeguridad =  "Seguridad.xml";
        
        $tipo = "SOAP";
              
        $oSoap = new nusoap_client($wsdl, false);
        $oSoap->setHeaders(file_get_contents($XmlSeguridad));

         if($oSoap->fault) {		
            echo "Error: ";
            var_dump($oSoap);			
        }


        $items=explode(",",$_POST['items']); //convierto string a array
		

        $i=0;
        while($i<count($items)){
           //Por cada items, verificar si esta trazable
           //si es trazable primero buscar si existe en el AMMAT

           $solicitudItemTraza= new SolicitudesItemTraza($items[$i+7]);

           if($items[$i]==1){
            //si es trazable  



              $fachaVenc=$items[$i+4];
              $time = strtotime($fachaVenc);
              $newFechaVenc = date('d/m/Y',$time);
              $gtin=$items[$i+1];
              $nroLote=$items[$i+3];
              $nroSerie=$items[$i+2];
 

              $param="	<arg0>".$user."</arg0>
                        <arg1>".$pass."</arg1>
                        <arg2></arg2>
                        <arg3></arg3>
                        <arg4></arg4>
                        <arg5>".$glnDestino."</arg5>
                        <arg6>".$gtin."</arg6> 
                        <arg7>1</arg7>
                        <arg8></arg8>
                        <arg9></arg9>
                        <arg10></arg10>
                        <arg11></arg11>
                        <arg12>".$newFechaVenc."</arg12>
                        <arg13>".$newFechaVenc."</arg13>
                        <arg14></arg14>
                        <arg15></arg15>
                        <arg16>-1</arg16>
                        <arg17>".$nroLote."</arg17>
                        <arg18>".$nroSerie."</arg18>
                        <arg19></arg19>
                        <arg20>10</arg20>";		

                 /// echo $param."<br/>";

                        //arg6 gtin, arg17 nroLote, arg20 cant x paginas, arg19 pagina actual, 
                        //arg 12 fecha venc desde , arg 13 fechaVenc hasta, arg7 idEvento

                        $oSoap->soap_defencoding = 'utf-8'; 
                        $oSoap->encode_utf8 = false;
                        $oSoap->decode_utf8 = false;	
                        $result = $oSoap->call('getTransaccionesNoConfirmadas', $param);
            
                        if ($oSoap->fault) {
                            $error= 'Fallo al buscar producto en los servicios de ANMAT.';
                          //  print_r($result);
                        } else {	// Chequea errores
                            $err = $oSoap->getError();
                            if ($err) {		// Muestra el error
                                $error='<b>Error: </b>' . $err ;
                            } else {		// Muestra el resultado
                              
                               
                              
                                if($result['cantPaginas']==0){

                                        //actualizo item
                                        $solicitudItemTraza -> setid_solicitud($idSolicitud);
                                        $solicitudItemTraza -> setid_item($items[$i+5]);
                                        $solicitudItemTraza -> setgtin($items[$i+1]);
                                        $solicitudItemTraza -> setnroSerie($items[$i+2]);
                                        $solicitudItemTraza -> setlote($items[$i+3]);
                                        $solicitudItemTraza -> setfechaVenc($items[$i+4]);
                                        $solicitudItemTraza -> setesTrazable(1);
                                        $solicitudItemTraza -> setid_recepcion('');
                                        $solicitudItemTraza -> setfechaRemito($items[$i+9]);
                                        $solicitudItemTraza -> setnroRemito($items[$i+10]);
                                        $solicitudItemTraza -> setuserModif($_SESSION['user']);
                                        $solicitudItemTraza -> Save();

                                        //Actualizo estado
                                         //finalizo el estado actual
                                        $objTrazaEstado = new SolicitudesItemTrazaEstados($items[$i+8]);
                                        $objTrazaEstado -> setfhasta(date('Y-m-d H:i:s'));
                                        $objTrazaEstado -> setuserModif($_SESSION['user']);
                                        $objTrazaEstado -> Save();

                                        //creo nuevo objeto para el estado recepcionado
                                        $objTrazaEstado = new SolicitudesItemTrazaEstados('');
                                        $objTrazaEstado -> setid_solicitud($idSolicitud);
                                        $objTrazaEstado -> setid_item($items[$i+5]);
                                        $objTrazaEstado -> setid_item_traza($items[$i+7]);
                                        $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
                                        $objTrazaEstado -> setfhasta('');
                                        $objTrazaEstado -> setid_estado(18);//con errores (R)
                                        $objTrazaEstado -> setobservaciones('Producto no encontrado para Recepcionar en AMNAT: verifique datos ingresados.');
                                        $objTrazaEstado -> setuserAlta($_SESSION['user']);
                                        $objTrazaEstado -> Create();

                                }
                                else{

                                         
                                  
                                   //Esta para confirmar. 
                                   $fechaActual = date('d/m/Y');
                                   $paramConf="	<arg0>".$user."</arg0>
                                   <arg1>".$pass."</arg1>
                                   <arg2>
                                        <f_operacion>".$fechaActual."</f_operacion>
                                        <p_ids_transac>".$result['list']['_id_transaccion']."</p_ids_transac>
                                   </arg2>";
                                   

                                   $resultConf = $oSoap->call('sendConfirmaTransacc', $paramConf);

                                   if ($oSoap->fault) {
                                        $error='Fallo al intentar informar recepción a ANMAT.';
                                       // print_r($resultConf);
                                    } else {	// Chequea errores
                                        $err = $oSoap->getError();
                                        if ($err) {		// Muestra el error
                                            $error='<b>Error: </b>' . $err ;
                                        } else {
                                           

                                            if($resultConf['resultado']){
                                                //actualizo a item:
                                                $solicitudItemTraza -> setid_solicitud($idSolicitud);
                                                $solicitudItemTraza -> setid_item($items[$i+5]);
                                                $solicitudItemTraza -> setgtin($items[$i+1]);
                                                $solicitudItemTraza -> setnroSerie($items[$i+2]);
                                                $solicitudItemTraza -> setlote($items[$i+3]);
                                                $solicitudItemTraza -> setfechaVenc($items[$i+4]);
                                                $solicitudItemTraza -> setesTrazable(1);
                                                $solicitudItemTraza -> setfechaRemito($items[$i+9]);
                                                $solicitudItemTraza -> setnroRemito($items[$i+10]);
                                                $solicitudItemTraza -> setid_recepcion($resultConf['id_transac_asociada']);
                                                $solicitudItemTraza -> setuserModif($_SESSION['user']);
                                                $solicitudItemTraza -> save();

                                                 //finalizo el estado actual
                                                $objTrazaEstado = new SolicitudesItemTrazaEstados($items[$i+8]);
                                                $objTrazaEstado -> setfhasta(date('Y-m-d H:i:s'));
                                                $objTrazaEstado -> setuserModif($_SESSION['user']);
                                                $objTrazaEstado -> Save();

                                                //creo nuevo objeto para el estado recepcionado
                                                $objTrazaEstado = new SolicitudesItemTrazaEstados('');
                                                $objTrazaEstado -> setid_solicitud($idSolicitud);
                                                $objTrazaEstado -> setid_item($items[$i+5]);
                                                $objTrazaEstado -> setid_item_traza($items[$i+7]);
                                                $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
                                                $objTrazaEstado -> setfhasta('');
                                                $objTrazaEstado -> setobservaciones('Producto pasa a estado RECEPCIONADO. <br/> Nro. Transacción ANMAT:'.$resultConf['id_transac_asociada']);
                                                $objTrazaEstado -> setid_estado(17);//Recepcionado
                                                $objTrazaEstado -> setuserAlta($_SESSION['user']);
                                                $objTrazaEstado -> Create();
                                               

                                            }
                                            else{
                                                //Error al recepcionar, guardar errores

                                                if (!array_key_exists('0', $resultConf['errores'])) 
                                                {
                                                    $listError="Error ".$resultConf['errores']['_c_error'].": ".$result['errores']['_d_error'];
                                                    
                                                }
                                                else
                                                {
                                                    $errores=$resultConf['errores'];
                                                    for($i=0; $i<sizeof($errores);$i++)
                                                    {
                                                    
                                                        $listError.="Error ".$errores[$i]['_c_error'].": ".$errores[$i]['_d_error']."<br><br>";
                                                    }                    
                                                    
                                                }

                                                //Actualizo tabla de items de traza
                                                $solicitudItemTraza -> setid_solicitud($idSolicitud);
                                                $solicitudItemTraza -> setid_item($items[$i+5]);
                                                $solicitudItemTraza -> setgtin($items[$i+1]);
                                                $solicitudItemTraza -> setnroSerie($items[$i+2]);
                                                $solicitudItemTraza -> setlote($items[$i+3]);
                                                $solicitudItemTraza -> setfechaVenc($items[$i+4]);
                                                $solicitudItemTraza -> setesTrazable(1);
                                                $solicitudItemTraza -> setfechaRemito($items[$i+9]);
                                                $solicitudItemTraza -> setnroRemito($items[$i+10]);
                                                $solicitudItemTraza -> setuserModif($_SESSION['user']);  
                                                $solicitudItemTraza -> save();

                                                //Actualizo estado
                                                //finalizo el estado actual
                                                $objTrazaEstado = new SolicitudesItemTrazaEstados($items[$i+8]);
                                                $objTrazaEstado -> setfhasta(date('Y-m-d H:i:s'));
                                                $objTrazaEstado -> setuserModif($_SESSION['user']);
                                                $objTrazaEstado -> Save();

                                                //creo nuevo objeto para el estado recepcionado
                                                $objTrazaEstado = new SolicitudesItemTrazaEstados('');
                                                $objTrazaEstado -> setid_solicitud($idSolicitud);
                                                $objTrazaEstado -> setid_item($items[$i+5]);
                                                $objTrazaEstado -> setid_item_traza($items[$i+7]);
                                                $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
                                                $objTrazaEstado -> setfhasta('');
                                                $objTrazaEstado -> setid_estado(18);//con errores (R)
                                                $objTrazaEstado -> setobservaciones($listError);
                                                $objTrazaEstado -> setuserAlta($_SESSION['user']);
                                                $objTrazaEstado -> Create();


                                            }

                                          
                                        }

                                    } 




                                }

                            }
                        }
            
           }
           else{
             //no es trazable
			 

              //Actualizo tabla de items de traza
              $solicitudItemTraza -> setid_solicitud($idSolicitud);
              $solicitudItemTraza -> setid_item($items[$i+5]);
              $solicitudItemTraza -> setgtin($items[$i+1]);
              $solicitudItemTraza -> setnroSerie($items[$i+2]);
              $solicitudItemTraza -> setlote($items[$i+3]);
              $solicitudItemTraza -> setfechaVenc($items[$i+4]);
              $solicitudItemTraza -> setesTrazable(0);
              $solicitudItemTraza -> setfechaRemito($items[$i+9]);
              $solicitudItemTraza -> setnroRemito($items[$i+10]);
              $solicitudItemTraza -> setuserModif($_SESSION['user']);
              $solicitudItemTraza -> save();


              //finalizo el estado actual
              $objTrazaEstado = new SolicitudesItemTrazaEstados($items[$i+8]);
              $objTrazaEstado -> setfhasta(date('Y-m-d H:i:s'));
              $objTrazaEstado -> setuserModif($_SESSION['user']);
              $objTrazaEstado -> Save();

              //creo nuevo objeto para el estado recepcionado
              $objTrazaEstado = new SolicitudesItemTrazaEstados('');
              $objTrazaEstado -> setid_solicitud($idSolicitud);
              $objTrazaEstado -> setid_item($items[$i+5]);
              $objTrazaEstado -> setid_item_traza($items[$i+7]);
              $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
              $objTrazaEstado -> setfhasta('');
              $objTrazaEstado -> setobservaciones('Producto pasa a estado RECEPCIONADO.');
              $objTrazaEstado -> setid_estado(17);//Recepcionado
              $objTrazaEstado -> setuserAlta($_SESSION['user']);
              $objTrazaEstado -> Create();
     
             
           }



            $i=$i+11;
        }//fin while


        echo $error;

        

?>
