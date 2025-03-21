<?php

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
	private $id_estados;

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
        Global $dblink;
		$query ="INSERT INTO cotizacion_solic_prov (`id_proveedores`,`id_solicitudes`,`userAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink,$this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink,$this->getuserAlta()) . "');";
		//echo $query;
		mysqli_query($dblink,$query);

		
	}
	
	public function Save()
	{
		Global $dblink;
		$query = "UPDATE cotizacion_solic_prov SET 
						`importe` = " .$this->getimporte() . " ,
						`observaciones` = '" . mysqli_real_escape_string($dblink,$this->getobservaciones()) . "' ,
                        `userModif` = '" . $_SESSION['user'] . "',
						`fechaModif` = now() 
						WHERE `id`='{$this->getid()}'";

	//	echo $query;				 
		mysqli_query($dblink,$query);

		
	}

    
    public function SelectAllNoAsignados($idSolicitud)
	{
		Global $dblink;
		$query = "SELECT *
                    FROM proveedores p
                    WHERE p.id NOT IN (
                                        SELECT csp.id_proveedores
                                            FROM cotizacion_solic_prov csp 
                                            WHERE csp.id_solicitudes=".$idSolicitud."
                    ) AND habilitado=1 AND tipo='A'
					ORDER BY nombre";

		$result = mysqli_query($dblink,$query);

	

		
	return $result;

    }
    
    public function SelectAllAsignados($idSolicitud)
	{
		Global $dblink;
		$query = "SELECT *
                    FROM proveedores p
                    WHERE p.id IN (
                                        SELECT csp.id_proveedores
                                            FROM cotizacion_solic_prov csp 
                                            WHERE csp.id_solicitudes=".$idSolicitud."
                    ) 
					ORDER BY nombre";

		$result = mysqli_query($dblink,$query);


		
	return $result;

	}
	
	public function SelectAllCotizaciones($idSolicitud)
	{
		Global $dblink;
		$query = " SELECT c.*, p.id idProveedor, p.nombre nombreProv, p.email, e.id idEStado, e.nombre nombreEst
					FROM  cotizacion_solic_prov c
						INNER JOIN proveedores p ON p.id=c.id_proveedores
						INNER JOIN estados e ON e.id=c.id_estados
					WHERE c.id_solicitudes=".$idSolicitud;

      //  echo $query;
		$result = mysqli_query($dblink,$query);

		
	return $result;

    }
    public function SelectCotizacionAprobada($idSolicitud='')
	{
		Global $dblink;

		if($idSolicitud!="")
		{
			$where=" csp.id_solicitudes=".$idSolicitud." AND csp.id_estados=10"; 
		}
		else{
            $where = " csp.id_solicitudes='{$this->getid_solicitudes()}' AND csp.id='{$this->getid()}'";
		}


		$query = "  SELECT s.id idSolicitud, s.esB24, pd.nombre farmacia, pd.GLN, pd.domicilio, pd.telefonos,
							l.nombre localidad, prov.nombre provincia, pr.id idProveedor, pr.nombre proveedor,
							pr.email, p.dni, p.apellido, p.nombre, p.codigoB24, csp.importe, p.email emailAf
					FROM cotizacion_solic_prov csp
						INNER JOIN solicitudes s ON s.id=csp.id_solicitudes
						INNER JOIN proveedores pr ON pr.id=csp.id_proveedores
						INNER JOIN personas p ON p.id=s.id_personas
						INNER JOIN puntos_dispensa pd ON pd.id=s.id_puntos_dispensa
						INNER JOIN localidades l ON l.id=pd.id_localidades
						INNER JOIN provincias prov ON prov.id=l.id_provincias
					WHERE ".$where;

       // echo $query;
		$result = mysqli_query($dblink,$query);

		
	return $result;

    }
    public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM cotizacion_solic_prov
						WHERE `id_proveedores`='{$this->getid_proveedores()}' AND `id_solicitudes`='{$this->getid_solicitudes()}'";

       //echo $query;
		mysqli_query($dblink,$query);
		

	}

	public function AprobarAnularCotizaciones()
	{
		Global $dblink;

		if(mysqli_query($dblink, "BEGIN"))
		{
			$query = "UPDATE cotizacion_solic_prov
			SET 	 `id_estados`=10,
			         `importe`='{$this->getimporte()}',
					 `observaciones`='{$this->getobservaciones()}',
					`userModif` = '{$this->getuserModif()}',
				  `fechaModif` = now() 
			WHERE `id`={$this->getid()}";

			//echo $query;
			if(!mysqli_query($dblink,$query))
			{
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			}
			else
			{
				$query = "SELECT * 
				          FROM cotizacion_solic_prov
						  WHERE `id_solicitudes`={$this->getid_solicitudes()} AND id<>{$this->getid()}";

				// echo $query;
				$resultCot = mysqli_query($dblink,$query);
				
				$query1 = "  UPDATE cotizacion_solic_prov
							SET `id_estados`=12,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
							WHERE `id_solicitudes`={$this->getid_solicitudes()} AND id<>{$this->getid()}";
				
				if(!mysqli_query($dblink,$query1))
				{
					mysqli_query($dblink, "ROLLBACK");
					return 0;
				}
				else
				{	
					$query2="INSERT INTO solicitudes_estados (`id_estados`,
					                                          `id_solicitudes`,
															  `observaciones`,
															  `userAlta`)
							 VALUES(5,{$this->getid_solicitudes()},'Cambio de estado Automático: la solcitud cambia a estado EN PROCESO DE COMPRA.','{$this->getuserModif()}')";
				//echo $query2;		 
					if(!mysqli_query($dblink,$query2))
					{
						mysqli_query($dblink, "ROLLBACK");
						return 0;
					}
					else{
						
						while($row=mysqli_fetch_assoc($resultCot))
					    {
							$obs='Cambio autom&aacute;tico de estado de la Cotizaci&oacute;n #'.$row['id'].' de la Solicitud #'.$this->getid_solicitudes().' de PENDIENTE a ANULADA';
							$query3="INSERT INTO solicitudes_estados (`id_estados`,
					                                          `id_solicitudes`,
															  `observaciones`,
															  `userAlta`)
							 VALUES(5,{$this->getid_solicitudes()},'".$obs."','{$this->getuserModif()}')";
							//echo $query3;		 
								if(!mysqli_query($dblink,$query3))
								{
									mysqli_query($dblink, "ROLLBACK");
									return 0;
								}
							
						}		
						
						mysqli_query($dblink, "COMMIT");
						return 1;
					}
					
					
				}


			}

		}
		

	}


	public function AnularCotizaciones()
	{
		Global $dblink;

		if(mysqli_query($dblink, "BEGIN"))
		{
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=12,
								`importe`='{$this->getimporte()}',
								`observaciones`='{$this->getobservaciones()}',
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id`={$this->getid()}";

			//echo $query;
			if(!mysqli_query($dblink,$query))
			{
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			}
			else
			{
				$query1 = "  SELECT COUNT(*) cant
								FROM cotizacion_solic_prov c
								WHERE c.id_solicitudes={$this->getid_solicitudes()} AND c.id_estados IN (10,11)";
				$res=mysqli_query($dblink,$query1);
				if(!$res)
				{
					mysqli_query($dblink, "ROLLBACK");
					return 0;
				}
				else
				{	
					$row=mysqli_fetch_assoc($res);
					if($row['cant']==0)
					{
						
						$query2="INSERT INTO solicitudes_estados (`id_estados`,
					                                          `id_solicitudes`,
															  `observaciones`,
															  `userAlta`)
							 VALUES(9,{$this->getid_solicitudes()},'Cambio de estado Automático: se pasa a ANULADA la SOLICITUD ya que estan todas las cotizaciones anuladas.','{$this->getuserModif()}')";
					//	echo $query2;		 
						if(!mysqli_query($dblink,$query2))
						{
							mysqli_query($dblink, "ROLLBACK");
							return 0;
						}


					}
					
					mysqli_query($dblink, "COMMIT");
					return 1;
						
					
				}


			}

		}
		

	}

	public function RevertirCotizaciones()
	{
		Global $dblink;

		if(mysqli_query($dblink, "BEGIN"))
		{
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=11,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id`={$this->getid()}";

			//echo $query;
			if(!mysqli_query($dblink,$query))
			{
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			}
			else
			{
				mysqli_query($dblink, "COMMIT");
				return 1;

			}

		}
		

	}

	public function QuitarAprobado()
	{
		Global $dblink;

		if(mysqli_query($dblink, "BEGIN"))
		{
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=11,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id_solicitudes`={$this->getid_solicitudes()} and `id_estados`=10";

			//echo $query;
			if(!mysqli_query($dblink,$query))
			{
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			}
			else
			{
				mysqli_query($dblink, "COMMIT");
				return 1;

			}

		}
	}
	
	public function VolverPendiente()
	{
		Global $dblink;

		if(mysqli_query($dblink, "BEGIN"))
		{
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=11,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id_solicitudes`={$this->getid_solicitudes()}";

			//echo $query;
			if(!mysqli_query($dblink,$query))
			{
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			}
			else
			{
				mysqli_query($dblink, "COMMIT");
				return 1;

			}

		}
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

	public function setid_proveedores($id_proveedores='')
	{
		$this->id_proveedores = $id_proveedores;
		return true;
	}

	public function getid_proveedores()
	{
		return $this->id_proveedores;
	}

	public function setid_solicitudes($id_solicitudes='')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setid_estados($id_estados='')
	{
		$this->id_estados = $id_estados;
		return true;
	}

	public function getid_estados()
	{
		return $this->id_estados;
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

	public function setimporte($importe='')
	{
		$this->importe = $importe;
		return true;
	}

	public function getimporte()
	{
		return $this->importe;
	}

	public function setuserAlta($userAlta='')
	{
		$this->userAlta = $userAlta;
		return true;
	}

	public function getuserAlta()
	{
		return $this->userAlta;
	}

	public function setfechaAlta($fechaAlta='')
	{
		$this->fechaAlta = $fechaAlta;
		return true;
	}

	public function getfechaAlta()
	{
		return $this->fechaAlta;
	}

	public function setuserModif($userModif='')
	{
		$this->userModif = $userModif;
		return true;
	}

	public function getuserModif()
	{
		return $this->userModif;
	}

	public function setfechaModif($fechaModif='')
	{
		$this->fechaModif = $fechaModif;
		return true;
	}

	public function getfechaModif()
	{
		return $this->fechaModif;
	}

} // END class cotizacion_solic_prov


