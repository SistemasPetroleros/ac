<?php 

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    error_reporting(0);
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
    $glnOrigen= $ptoDispensa -> getGLN();
    $user=$ptoDispensa -> getuserAnmat();
    $pass=$ptoDispensa -> getclaveAnmat();




    //Testing
    //$wsdl = "https://servicios.pami.org.ar/trazamed.WebService";
        
        
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

        

        $solicitudItemTraza= new SolicitudesItemTraza($items[$i+2]);
       
        if($items[$i]==1){
          //si se marcó para dispensar
     
                if($items[$i+3]==1){
                    //es trazable, trazo ante anmat y actualizo estado item
             
                    //1ro busco datos afiliado
                    $resS= $solicitud -> SelectAllFiltros('','','','',$idSolicitud,'');

                   
                  
                    if($row=mysqli_fetch_assoc($resS)){
                       

                        if($row['esB24']==0)
                        {  
                              $apellido= $row['apellido'];
                              $nombres= $row['nombres'];
                        }
                        else{
                            $apellido= $row['codigoB24'];
                            $nombres= "";

                        }

                        $nroDoc= $row['dni'];
                        $nroSerie= $solicitudItemTraza -> getnroSerie();
                        $fechaTrans= date('d/m/Y');
                        $gtin= $solicitudItemTraza -> getgtin();
                        $horaTrans=date('H:i');
                        $idEvento=111; //dispensa (111 de farmacia a paciente, prueba: )
                        $idOS= 127901;
                        $nroLote= $solicitudItemTraza -> getlote();
                        $nroRemito= $solicitudItemTraza -> getnroRemito();
                        $tipoDoc="";
                        $time = strtotime($solicitudItemTraza -> getfechaVenc());
                        $fechaVenc = date('d/m/Y',$time);

                           
                        $argumentos="<arg0>	
                                    <f_evento>".$fechaTrans."</f_evento>
                                    <h_evento>".$horaTrans."</h_evento>
                                    <gln_origen>".trim($glnOrigen)."</gln_origen>
                                    <gln_destino></gln_destino>
                                    <n_remito>".trim($nroRemito)."</n_remito>
                                    <n_factura></n_factura>
                                    <vencimiento>".$fechaVenc."</vencimiento>
                                    <gtin>".trim($gtin)."</gtin>
                                    <lote>".trim($nroLote)."</lote>
                                    <numero_serial>".trim($nroSerie)."</numero_serial>
                                    <id_evento>".$idEvento."</id_evento>
                                    <apellido>".utf8_decode($apellido)."</apellido>
                                    <nombres>".utf8_decode($nombres)."</nombres>
                                    <n_documento>".$nroDoc."</n_documento>
                                    <sexo></sexo>
                                    <tipo_documento>".$tipoDoc."</tipo_documento>
                                    <direccion></direccion>
                                    <localidad></localidad> 
                                    <numero></numero>
                                    <piso></piso>
                                    <dpto></dpto>
                                    <n_postal></n_postal>
                                    <telefono></telefono>
                                    <id_obra_social>".$idOS."</id_obra_social>
                                    <nro_asociado>".$nroDoc."</nro_asociado>
                                    <id_motivo_devolucion></id_motivo_devolucion>
                                    <otro_motivo_devolucion></otro_motivo_devolucion>
                                    <id_motivo_reposicion></id_motivo_reposicion>
                                    <id_programa></id_programa>
                                </arg0>
                                <arg1>".$user."</arg1>
                                <arg2>".$pass."</arg2>";
                              
                            

                            $oSoap->soap_defencoding = 'utf-8'; 
                            $oSoap->encode_utf8 = false;
                            $oSoap->decode_utf8 = false;	
                            $result = $oSoap->call('sendMedicamentos', $argumentos);




                            if ($oSoap->fault) {
                                $error= 'Fallo al enviar producto en los servicios de ANMAT.';
                              //  print_r($result);
                            } else {	// Chequea errores
                                $err = $oSoap->getError();
                                if ($err) {		// Muestra el error
                                    $error='<b>Error: </b>' . $err ;
                                } else {

                                   
                                    if($result['resultado']=="false")
                                    {
                                       //error al intentar dispensar el producto al ANMAT
                                       if (!array_key_exists('0', $result['errores'])) 
                                       {
                                           $listError="Error ".$result['errores']['_c_error'].": ".$result['errores']['_d_error'];
                                           
                                       }
                                       else
                                       {
                                           $errores=$result['errores'];
                                           for($i=0; $i<sizeof($errores);$i++)
                                           {
                                           
                                               $listError.="Error ".$errores[$i]['_c_error'].": ".$errores[$i]['_d_error']."<br><br>";
                                           }                    
                                           
                                       }

                                       //finalizo el estado actual
                                       $objTrazaEstado = new SolicitudesItemTrazaEstados($items[$i+4]);
                                       $objTrazaEstado -> setfhasta(date('Y-m-d H:i:s'));
                                       $objTrazaEstado -> setuserModif($_SESSION['user']);
                                       $objTrazaEstado -> Save();

                                       //creo nuevo objeto para el estado recepcionado
                                       $objTrazaEstado = new SolicitudesItemTrazaEstados('');
                                       $objTrazaEstado -> setid_solicitud($idSolicitud);
                                       $objTrazaEstado -> setid_item($items[$i+1]);
                                       $objTrazaEstado -> setid_item_traza($items[$i+2]);
                                       $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
                                       $objTrazaEstado -> setfhasta('');
                                       $objTrazaEstado -> setobservaciones($listError);
                                       $objTrazaEstado -> setid_estado(20);//dispensado con error
                                       $objTrazaEstado -> setuserAlta($_SESSION['user']);
                                       $objTrazaEstado -> Create();


                                    }
                                    else{
                                        //Producto dispensado correctamente

                                            //actualizo a item:
                                            $solicitudItemTraza -> setid_solicitud($idSolicitud);
                                            $solicitudItemTraza -> setid_item($items[$i+1]);
                                            $solicitudItemTraza -> setid_dispensa($result['codigoTransaccion']);
                                            $solicitudItemTraza -> setuserModif($_SESSION['user']);
                                            $solicitudItemTraza -> save();


                                          //finalizo el estado actual
                                            $objTrazaEstado = new SolicitudesItemTrazaEstados($items[$i+4]);
                                            $objTrazaEstado -> setfhasta(date('Y-m-d H:i:s'));
                                            $objTrazaEstado -> setuserModif($_SESSION['user']);
                                            $objTrazaEstado -> Save();

                                            //creo nuevo objeto para el estado recepcionado
                                            $objTrazaEstado = new SolicitudesItemTrazaEstados('');
                                            $objTrazaEstado -> setid_solicitud($idSolicitud);
                                            $objTrazaEstado -> setid_item($items[$i+1]);
                                            $objTrazaEstado -> setid_item_traza($items[$i+2]);
                                            $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
                                            $objTrazaEstado -> setfhasta('');
                                            $objTrazaEstado -> setobservaciones('Producto pasa a estado DISPENSADO. <br/> Nro. Transacción ANMAT:'.$result['codigoTransaccion']);
                                            $objTrazaEstado -> setid_estado(19);//dispensado
                                            $objTrazaEstado -> setuserAlta($_SESSION['user']);
                                            $objTrazaEstado -> Create();
                                    }
                                  
                                }
                            }


                    }//fin while
             
                }
                else{
                    //no trazable
                    //actualizo estado a Item a Dispensado

                     //finalizo el estado actual
                     $objTrazaEstado = new SolicitudesItemTrazaEstados($items[$i+4]);

                     $objTrazaEstado -> setfhasta(date('Y-m-d H:i:s'));
                     $objTrazaEstado -> setuserModif($_SESSION['user']);
                     $objTrazaEstado -> Save();

                     //creo nuevo objeto para el estado recepcionado
                     $objTrazaEstado = new SolicitudesItemTrazaEstados('');
                     $objTrazaEstado -> setid_solicitud($idSolicitud);
                     $objTrazaEstado -> setid_item($items[$i+1]);
                     $objTrazaEstado -> setid_item_traza($items[$i+2]);
                     $objTrazaEstado -> setfdesde(date('Y-m-d H:i:s'));
                     $objTrazaEstado -> setfhasta('');
                     $objTrazaEstado -> setobservaciones('Producto pasa a estado DISPENSADO.');
                     $objTrazaEstado -> setid_estado(19);//dispensado
                     $objTrazaEstado -> setuserAlta($_SESSION['user']);
                     $objTrazaEstado -> Create();
                   
                }


        }//fin si se marcó para dispensar
        
        $i=$i+5;
    }//fin while


    echo $error;

