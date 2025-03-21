<?php

class cotizacion_solic_prov
{

	/**
	 * @var 
	 */
	private $id;

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


	private $calificacion;
	private $aprobado;
	private $obs_calificacion;
	private $fecha_visualizacion;
	private $usuario_visualizacion;
	private $fecha_visualizacion_aprobacion;
	private $usuario_visualizacion_aprobacion;
	private $validez_propuesta;
	private $plazo_entrega_dias;
	private $condiciones_pago;
	private $incluye_flete;
	private $observaciones_cotizacion;



	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		global $dblink;

		$query = "SELECT * FROM cotizacion_solic_prov WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);
		//	echo $query;

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
	}

	public function Create()
	{
		global $dblink;
		$query = "INSERT INTO cotizacion_solic_prov (`id_proveedores`,`id_solicitudes`,`userAlta`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_proveedores()) . "','" . mysqli_real_escape_string($dblink, $this->getid_solicitudes()) . "','" . mysqli_real_escape_string($dblink, $this->getuserAlta()) . "');";
		mysqli_query($dblink, $query);
	}

	public function Save()
	{
		global $dblink;

		$set = "";

		if ($this->getvalidez_propuesta() != "" and  $this->getvalidez_propuesta() != null) {
			$set .= "`validez_propuesta` = '" . mysqli_real_escape_string($dblink, $this->getvalidez_propuesta()) . "', ";
		}

		if ($this->getplazo_entrega_dias() != "" and  $this->getplazo_entrega_dias() != null) {
			$set .= "`plazo_entrega_dias` = " . mysqli_real_escape_string($dblink, $this->getplazo_entrega_dias()) . ", ";
		}

		if ($this->getincluye_flete() != "" and  $this->getincluye_flete() != null) {
			$set .= "`incluye_flete` = " . mysqli_real_escape_string($dblink, $this->getincluye_flete()) . ", ";
		}

		if ($this->getobservaciones() != "" and  $this->getobservaciones() != null) {
			$set .= "`observaciones` = '" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "', ";
		}

		if ($this->getcondiciones_pago() != "" and  $this->getcondiciones_pago() != null) {
			$set .= "`condiciones_pago` = '" . mysqli_real_escape_string($dblink, $this->getcondiciones_pago()) . "', ";
		}

		if ($this->getcondiciones_pago() != "" and  $this->getcondiciones_pago() != null) {
			$set .= "`condiciones_pago` = '" . mysqli_real_escape_string($dblink, $this->getcondiciones_pago()) . "', ";
		}

		if ($this->getid_estados() != "" and  $this->getid_estados() != null) {
			$set .= "`id_estados` = " . mysqli_real_escape_string($dblink, $this->getid_estados()) . ", ";
		}



		$query = "UPDATE cotizacion_solic_prov SET 
                         " . $set . "
                        `userModif` = '" . $_SESSION['user'] . "',
						`fechaModif` = now() 
						WHERE `id`='{$this->getid()}'";

		//echo $query;				 
		$res = mysqli_query($dblink, $query);

		return $res;
	}



	public function SelectACotizacionesUserProveedor($data)
	{
		global $dblink;

		$where = " ";

	/*	if ($data['fechaDesde'] != "") {
			$where .= " and s.fecha>='" . mysqli_real_escape_string($dblink, $data['fechaDesde']) . "'";
		}

		if ($data['fechaHasta'] != "") {
			$where .= " and s.fecha<='" . mysqli_real_escape_string($dblink, $data['fechaHasta']) . "'";
		}*/
		
			if ($data['fechaDesde'] != "") {
			$where .= " and s.fecha>=STR_TO_DATE('".$data['fechaDesde']."','%Y-%m-%d')";
		}
		

		if ($data['fechaHasta'] != "") {
			$where .= " and s.fecha<=STR_TO_DATE('".$data['fechaHasta']."','%Y-%m-%d')";
		}


		if ($data['idCotizacion'] != "") {
			$where .= " and s.id=" . mysqli_real_escape_string($dblink, $data['idCotizacion']);
		}

		if ($data['afiliado'] != "") {
			if (is_numeric($data['afiliado'])) {
				$where .= " and p.dni='" . mysqli_real_escape_string($dblink, $data['afiliado']) . "'";
			} else {
				$where .= " and (p.nombre like '%" . mysqli_real_escape_string($dblink, $data['afiliado']) . "%' or p.apellido like '%" . mysqli_real_escape_string($dblink, $data['afiliado']) . "%')";
			}
		}

		if ($data['farmacia'] != "") {
			$where .= " and (pd.nombre like '%" . mysqli_real_escape_string($dblink, $data['farmacia']) . "%' or pd.gln like '%" . mysqli_real_escape_string($dblink, $data['farmacia']) . "%')";
		}



		$query = "SELECT DISTINCT s.id, s.fecha,  CONCAT(p.apellido,' ', p.nombre) nombre , p.dni, pd.nombre puntoDispensa, pd.gln, l.nombre localidad, prov.nombre provincia,
							up.id_proveedor, (SELECT prr.nombre FROM proveedores prr WHERE prr.id=up.id_proveedor) nomProv, c.id idCotizacion, c.id_estados, est.nombre estadoCot,
							s.fecha_vigencia_cotiz, ec.nombre estadoGral,  s.id_tipo_solicitud
					FROM solicitudes s
						INNER JOIN tipo_solicitud ts ON s.id_tipo_solicitud=ts.id     
						INNER JOIN (
									SELECT `id_estados`, `id_solicitudes`, `observaciones`, `userAlta`, `fechaAlta` 
									FROM `solicitudes_estados` s1
									WHERE s1.fechaAlta in (
										SELECT max(s2.fechaAlta) from `solicitudes_estados` s2 WHERE s1.id_solicitudes=s2.id_solicitudes)
							) e on e.id_solicitudes=s.id
						INNER JOIN personas p ON p.id=s.id_personas
						INNER JOIN puntos_dispensa pd ON pd.id=s.id_puntos_dispensa
						INNER JOIN localidades l ON l.id=pd.id_localidades
						INNER JOIN provincias prov ON prov.id=l.id_provincias
						INNER JOIN proveedores_tipo_solicitud pts ON pts.id_tipo_solicitud=s.id_tipo_solicitud
						INNER JOIN usuarios_proveedor up ON up.id_proveedor=pts.id_proveedores 
						INNER JOIN usuarios u ON up.id_usuarios=u.id
						INNER JOIN cotizacion_solic_prov c ON c.id_proveedores=up.id_proveedor AND c.id_solicitudes=s.id
						inner join estados ec on ec.id=c.id_estados
						INNER JOIN estados est ON est.id=c.id_estados
					WHERE  e.id_estados=4 AND s.fecha>= STR_TO_DATE('2023-05-01','%Y-%m-%d') AND s.fecha_vigencia_cotiz>=NOW() AND u.id=" . $_COOKIE['idUsuario'].$where . " 
					UNION
					SELECT DISTINCT s.id, s.fecha,  CONCAT(p.apellido,' ', p.nombre) nombre , p.dni, pd.nombre puntoDispensa, pd.gln, l.nombre localidad, prov.nombre provincia,
							up.id_proveedor, (SELECT prr.nombre FROM proveedores prr WHERE prr.id=up.id_proveedor) nomProv, c.id idCotizacion, c.id_estados, est.nombre estadoCot,
							s.fecha_vigencia_cotiz, ec.nombre estadoGral,  s.id_tipo_solicitud
					FROM materiales_solicitudes s
						INNER JOIN tipo_solicitud ts ON s.id_tipo_solicitud=ts.id     
						INNER JOIN (
									SELECT `id_estados`, `id_solicitudes`, `observaciones`, `userAlta`, `fechaAlta` 
									FROM `materiales_solicitudes_estados` s1
									WHERE s1.fechaAlta in (
										SELECT max(s2.fechaAlta) from `materiales_solicitudes_estados` s2 WHERE s1.id_solicitudes=s2.id_solicitudes)
							) e on e.id_solicitudes=s.id
						INNER JOIN personas p ON p.id=s.id_personas
						INNER JOIN puntos_dispensa pd ON pd.id=s.id_puntos_dispensa
						INNER JOIN localidades l ON l.id=pd.id_localidades
						INNER JOIN provincias prov ON prov.id=l.id_provincias
						INNER JOIN proveedores_tipo_solicitud pts ON pts.id_tipo_solicitud=s.id_tipo_solicitud
						INNER JOIN usuarios_proveedor up ON up.id_proveedor=pts.id_proveedores 
						INNER JOIN usuarios u ON up.id_usuarios=u.id
						INNER JOIN materiales_cotizacion_solic_prov c ON c.id_proveedores=up.id_proveedor AND c.id_solicitudes=s.id
						inner join estados ec on ec.id=c.id_estados
						INNER JOIN estados est ON est.id=c.id_estados 
						WHERE (1=1) AND s.fecha>= STR_TO_DATE('2023-05-01','%Y-%m-%d') AND u.id=" . $_COOKIE['idUsuario'] ." ". $where;

		$result = mysqli_query($dblink, $query);

		//echo $query;

		return $result;
	}



	public function SelectAllNoAsignados($idSolicitud)
	{
		global $dblink;
		/*	$query = "SELECT *
                    FROM proveedores p
                    WHERE p.id NOT IN (
                                        SELECT csp.id_proveedores
                                            FROM cotizacion_solic_prov csp 
                                            WHERE csp.id_solicitudes=".$idSolicitud."
                    ) AND habilitado=1 AND tipo='A'
					ORDER BY nombre";*/

		$query = "SELECT *
					FROM proveedores p
						INNER JOIN proveedores_tipo_solicitud pts ON p.id=pts.id_proveedores
					WHERE p.habilitado=1 
						AND pts.id_tipo_solicitud=1 
						AND pts.id_proveedores NOT IN (
								SELECT csp.id_proveedores
								FROM cotizacion_solic_prov csp
								WHERE csp.id_solicitudes=" . $idSolicitud . "
							)
				ORDER BY nombre";

		$result = mysqli_query($dblink, $query);

		// echo $query;


		return $result;
	}

	public function SelectAllAsignados($idSolicitud)
	{
		global $dblink;
		$query = "SELECT *
                    FROM proveedores p
                    WHERE p.id IN (
                                        SELECT csp.id_proveedores
                                            FROM cotizacion_solic_prov csp 
                                            WHERE csp.id_solicitudes=" . $idSolicitud . "
                    ) 
					ORDER BY nombre";

		$result = mysqli_query($dblink, $query);



		return $result;
	}

	public function SelectAllCotizaciones($idSolicitud)
	{
		global $dblink;
		$query = " SELECT c.*, p.id idProveedor, p.nombre nombreProv, p.email, e.id idEStado, e.nombre nombreEst
					FROM  cotizacion_solic_prov c
						INNER JOIN proveedores p ON p.id=c.id_proveedores
						INNER JOIN estados e ON e.id=c.id_estados
					WHERE c.id_solicitudes=" . $idSolicitud . " 
				    ORDER BY p.nombre";

		//  echo $query;
		$result = mysqli_query($dblink, $query);


		return $result;
	}


	public function getCalificacionPromedio($idProveedor)
	{
		global $dblink;
		$query = " SELECT ifnull(ROUND(AVG(c.calificacion)),0) promedio
				   FROM cotizacion_solic_prov c
				    WHERE c.id_proveedores=" . $idProveedor . " AND c.calificacion IS NOT NULL";

		//  echo $query;
		$result = mysqli_query($dblink, $query);


		return $result;
	}



	public function SelectAllCotizacionesFiltros($data)
	{
		global $dblink;

		$where = "";
		if ($data['idCotizacion'] != "") {
			$where .= " and c.id=" . $data['idCotizacion'];
		}

		if ($data['cotiza'] != "T") {
			if ($data['cotiza'] == "S") {
				$where .= " AND EXISTS (
						SELECT id_proveedores
							 FROM cotizacion_items ci
							 WHERE ci.id_proveedores=c.id_proveedores 
				  )";
			} else {

				$where .= " AND NOT EXISTS (
					SELECT id_proveedores
						 FROM cotizacion_items ci
						 WHERE ci.id_proveedores=c.id_proveedores 
			  )";
			}
		}

		if ($data['idsProveedores'] != "") {
			$where .= " and c.id_proveedores IN (" . $data['idsProveedores'] . ")";
		}

		$query = " SELECT c.*, p.id idProveedor, p.nombre nombreProv, p.email, e.id idEStado, e.nombre nombreEst
					FROM  cotizacion_solic_prov c
						INNER JOIN proveedores p ON p.id=c.id_proveedores
						INNER JOIN estados e ON e.id=c.id_estados
					WHERE c.id_solicitudes=" . $data['idSolicitud'] . " " . $where . "
				    ORDER BY p.nombre";

		//echo $query;
		$result = mysqli_query($dblink, $query);


		return $result;
	}

	public function SelectCotizacionAprobada($idSolicitud = '')
	{
		global $dblink;

		if ($idSolicitud != "") {
			$where = " csp.id_solicitudes=" . $idSolicitud . " AND csp.id_estados=10";
		} else {
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
					WHERE " . $where;

		// echo $query;
		$result = mysqli_query($dblink, $query);


		return $result;
	}
	public function Delete()
	{
		global $dblink;
		$query = "DELETE FROM cotizacion_solic_prov
						WHERE `id_proveedores`='{$this->getid_proveedores()}' AND `id_solicitudes`='{$this->getid_solicitudes()}'";

		//echo $query;
		mysqli_query($dblink, $query);
	}

	public function AprobarAnularCotizaciones()
	{
		global $dblink;

		if (mysqli_query($dblink, "BEGIN")) {
			$query = "UPDATE cotizacion_solic_prov
			SET 	 `id_estados`=10,
			         `importe`='{$this->getimporte()}',
					 `observaciones`='{$this->getobservaciones()}',
					`userModif` = '{$this->getuserModif()}',
				  `fechaModif` = now() 
			WHERE `id`={$this->getid()}";

			//echo $query;
			if (!mysqli_query($dblink, $query)) {
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			} else {
				$query = "SELECT * 
				          FROM cotizacion_solic_prov
						  WHERE `id_solicitudes`={$this->getid_solicitudes()} AND id<>{$this->getid()}";

				// echo $query;
				$resultCot = mysqli_query($dblink, $query);

				$query1 = "  UPDATE cotizacion_solic_prov
							SET `id_estados`=12,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
							WHERE `id_solicitudes`={$this->getid_solicitudes()} AND id<>{$this->getid()}";

				if (!mysqli_query($dblink, $query1)) {
					mysqli_query($dblink, "ROLLBACK");
					return 0;
				} else {
					$query2 = "INSERT INTO solicitudes_estados (`id_estados`,
					                                          `id_solicitudes`,
															  `observaciones`,
															  `userAlta`)
							 VALUES(5,{$this->getid_solicitudes()},'Cambio de estado Automático: la solcitud cambia a estado EN PROCESO DE COMPRA.','{$this->getuserModif()}')";
					//echo $query2;		 
					if (!mysqli_query($dblink, $query2)) {
						mysqli_query($dblink, "ROLLBACK");
						return 0;
					} else {

						while ($row = mysqli_fetch_assoc($resultCot)) {
							$obs = 'Cambio autom&aacute;tico de estado de la Cotizaci&oacute;n #' . $row['id'] . ' de la Solicitud #' . $this->getid_solicitudes() . ' de PENDIENTE a ANULADA';
							$query3 = "INSERT INTO solicitudes_estados (`id_estados`,
					                                          `id_solicitudes`,
															  `observaciones`,
															  `userAlta`)
							 VALUES(5,{$this->getid_solicitudes()},'" . $obs . "','{$this->getuserModif()}')";
							//echo $query3;		 
							if (!mysqli_query($dblink, $query3)) {
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
		global $dblink;

		if (mysqli_query($dblink, "BEGIN")) {
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=12,
								`importe`='{$this->getimporte()}',
								`observaciones`='{$this->getobservaciones()}',
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id`={$this->getid()}";

			//echo $query;
			if (!mysqli_query($dblink, $query)) {
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			} else {
				$query1 = "  SELECT COUNT(*) cant
								FROM cotizacion_solic_prov c
								WHERE c.id_solicitudes={$this->getid_solicitudes()} AND c.id_estados IN (10,11)";
				$res = mysqli_query($dblink, $query1);
				if (!$res) {
					mysqli_query($dblink, "ROLLBACK");
					return 0;
				} else {
					$row = mysqli_fetch_assoc($res);
					if ($row['cant'] == 0) {

						$query2 = "INSERT INTO solicitudes_estados (`id_estados`,
					                                          `id_solicitudes`,
															  `observaciones`,
															  `userAlta`)
							 VALUES(9,{$this->getid_solicitudes()},'Cambio de estado Automático: se pasa a ANULADA la SOLICITUD ya que estan todas las cotizaciones anuladas.','{$this->getuserModif()}')";
						//	echo $query2;		 
						if (!mysqli_query($dblink, $query2)) {
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
		global $dblink;

		if (mysqli_query($dblink, "BEGIN")) {
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=11,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id`={$this->getid()}";

			//echo $query;
			if (!mysqli_query($dblink, $query)) {
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			} else {
				mysqli_query($dblink, "COMMIT");
				return 1;
			}
		}
	}

	public function QuitarAprobado()
	{
		global $dblink;

		if (mysqli_query($dblink, "BEGIN")) {
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=11,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id_solicitudes`={$this->getid_solicitudes()} and `id_estados`=10";

			//echo $query;
			if (!mysqli_query($dblink, $query)) {
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			} else {
				mysqli_query($dblink, "COMMIT");
				return 1;
			}
		}
	}

	public function VolverPendiente()
	{
		global $dblink;

		if (mysqli_query($dblink, "BEGIN")) {
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`id_estados`=11,
								`userModif` = '{$this->getuserModif()}',
								`fechaModif` = now() 
						WHERE `id_solicitudes`={$this->getid_solicitudes()}";

			//echo $query;
			if (!mysqli_query($dblink, $query)) {
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			} else {
				mysqli_query($dblink, "COMMIT");
				return 1;
			}
		}
	}


	public function getCantCotizacionesProv($idSolicitud)
	{
		global $dblink;
		$query = "SELECT count(*) cant
					FROM cotizacion_solic_prov csp 
					WHERE csp.id_solicitudes=" . $idSolicitud;

		$result = mysqli_query($dblink, $query);
		$row = mysqli_fetch_assoc($result);
		$cant = $row['cant'];


		return $cant;
	}

	public function updateVisualizacion($data)
	{

		global $dblink;

		if (mysqli_query($dblink, "BEGIN")) {
			$query = "  UPDATE cotizacion_solic_prov
						SET 	`fecha_visualizacion`=now(),
								`usuario_visualizacion` = '{$_COOKIE['user']}',
								`userModif` = '{$_COOKIE['user']}',
								`fechaModif` = now() 
						WHERE `id`=" . $data['idCotizacion'] . " and fecha_visualizacion is null and usuario_visualizacion is null";

			//	echo $query;
			if (!mysqli_query($dblink, $query)) {
				mysqli_query($dblink, "ROLLBACK");
				return 0;
			} else {
				mysqli_query($dblink, "COMMIT");
				return 1;
			}
		}
	}

	public function setid($id = '')
	{
		$this->id = $id;
		return true;
	}

	public function getid()
	{
		return $this->id;
	}

	public function setid_proveedores($id_proveedores = '')
	{
		$this->id_proveedores = $id_proveedores;
		return true;
	}

	public function getid_proveedores()
	{
		return $this->id_proveedores;
	}

	public function setid_solicitudes($id_solicitudes = '')
	{
		$this->id_solicitudes = $id_solicitudes;
		return true;
	}

	public function getid_solicitudes()
	{
		return $this->id_solicitudes;
	}

	public function setid_estados($id_estados = '')
	{
		$this->id_estados = $id_estados;
		return true;
	}

	public function getid_estados()
	{
		return $this->id_estados;
	}

	public function setobservaciones($observaciones = '')
	{
		$this->observaciones = $observaciones;
		return true;
	}

	public function getobservaciones()
	{
		return $this->observaciones;
	}

	public function setimporte($importe = '')
	{
		$this->importe = $importe;
		return true;
	}

	public function getimporte()
	{
		return $this->importe;
	}



	public function setcalificacion($calificacion = '')
	{
		$this->calificacion = $calificacion;
		return true;
	}

	public function getcalificacion()
	{
		return $this->calificacion;
	}

	public function setaprobado($aprobado = '')
	{
		$this->aprobado = $aprobado;
		return true;
	}

	public function getaprobado()
	{
		return $this->aprobado;
	}

	public function setobs_calificacion($obs_calificacion = '')
	{
		$this->obs_calificacion = $obs_calificacion;
		return true;
	}

	public function getobs_calificacion()
	{
		return $this->obs_calificacion;
	}

	public function setfecha_visualizacion($fecha_visualizacion = '')
	{
		$this->fecha_visualizacion = $fecha_visualizacion;
		return true;
	}

	public function getfecha_visualizacion()
	{
		return $this->fecha_visualizacion;
	}


	public function setusuario_visualizacion($usuario_visualizacion = '')
	{
		$this->usuario_visualizacion = $usuario_visualizacion;
		return true;
	}

	public function getusuario_visualizacion()
	{
		return $this->usuario_visualizacion;
	}

	public function setfecha_visualizacion_aprobacion($fecha_visualizacion_aprobacion = '')
	{
		$this->fecha_visualizacion_aprobacion = $fecha_visualizacion_aprobacion;
		return true;
	}

	public function getfecha_visualizacion_aprobacion()
	{
		return $this->fecha_visualizacion_aprobacion;
	}

	public function setusuario_visualizacion_aprobacion($usuario_visualizacion_aprobacion = '')
	{
		$this->usuario_visualizacion_aprobacion = $usuario_visualizacion_aprobacion;
		return true;
	}

	public function getusuario_visualizacion_aprobacion()
	{
		return $this->usuario_visualizacion_aprobacion;
	}


	public function setvalidez_propuesta($validez_propuesta = '')
	{
		$this->validez_propuesta = $validez_propuesta;
		return true;
	}

	public function getvalidez_propuesta()
	{
		return $this->validez_propuesta;
	}

	public function setplazo_entrega_dias($plazo_entrega_dias = '')
	{
		$this->plazo_entrega_dias = $plazo_entrega_dias;
		return true;
	}

	public function getplazo_entrega_dias()
	{
		return $this->plazo_entrega_dias;
	}

	public function setcondiciones_pago($condiciones_pago = '')
	{
		$this->condiciones_pago = $condiciones_pago;
		return true;
	}

	public function getcondiciones_pago()
	{
		return $this->condiciones_pago;
	}


	public function setincluye_flete($incluye_flete = '')
	{
		$this->incluye_flete = $incluye_flete;
		return true;
	}

	public function getincluye_flete()
	{
		return $this->incluye_flete;
	}

	public function setobservaciones_cotizacion($observaciones_cotizacion = '')
	{
		$this->observaciones_cotizacion = $observaciones_cotizacion;
		return true;
	}

	public function getobservaciones_cotizacion()
	{
		return $this->observaciones_cotizacion;
	}





	public function setuserAlta($userAlta = '')
	{
		$this->userAlta = $userAlta;
		return true;
	}

	public function getuserAlta()
	{
		return $this->userAlta;
	}

	public function setfechaAlta($fechaAlta = '')
	{
		$this->fechaAlta = $fechaAlta;
		return true;
	}

	public function getfechaAlta()
	{
		return $this->fechaAlta;
	}

	public function setuserModif($userModif = '')
	{
		$this->userModif = $userModif;
		return true;
	}

	public function getuserModif()
	{
		return $this->userModif;
	}

	public function setfechaModif($fechaModif = '')
	{
		$this->fechaModif = $fechaModif;
		return true;
	}

	public function getfechaModif()
	{
		return $this->fechaModif;
	}
} // END class cotizacion_solic_prov
