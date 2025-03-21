<?php

/******************************************************************************
 * Class for ospepri_altocosto.solicitudes
 *******************************************************************************/

class solicitudes
{
	/**
	 * @var 
	 * Class Unique ID
	 */
	private $id;

	/**
	 * @var 
	 */
	private $id_personas;

	/**
	 * @var 
	 */
	private $fecha;

	/**
	 * @var 
	 */
	private $observaciones;
	private $esB24;
	private $esSur;
	private $id_tipo_solicitud;
	private $fecha_vigencia_cotiz;
	private $privada;
	private $esOncologico;

	/**
	 * @var 
	 */
	private $id_puntos_dispensa;

	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		global $dblink;


		$query = "SELECT * FROM solicitudes WHERE `id`='{$this->getid()}'";

		//echo $query;

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value) {
				$column_name = str_replace('-', '_', $key);
				$this->{"set$column_name"}($value);
			}
	}

	public function SelectAll()
	{
		global $dblink;
		$query = "SELECT * FROM solicitudes ";

		$result = mysqli_query($dblink, $query);

		return $result;
	}

	public function SelectAllFiltros($fd, $fh, $pr, $es, $idS, $be, $b24, $pd, $nr, $sur, $onco)
	{
		global $dblink;


		$query = "SELECT DISTINCT s.id,s.fecha, concat(ifnull(p.apellido,''),' ', ifnull(p.nombre,'')) as nombre, p.apellido, p.nombre nombres,  p.dni, 
		          pd.nombre as puntoDispensa, est.nombre as estado, s.esB24, p.codigoB24, s.esSur, s.esOncologico,
				  case when est.id <> 4 then est.nombre else
								concat(est.nombre,' ',
								(case when s.fecha_vigencia_cotiz is not null then (case when s.fecha_vigencia_cotiz > now() then '<small style=\"color:red;\">' else ' <small style=\"color:red;\">Vencido! ' end) else ' <small style=\"color:red;\"> ' end),
								CONVERT((SELECT count(1) FROM cotizacion_solic_prov where id_solicitudes=s.id and id_estados<>42), CHAR),
								' de ',
								CONVERT((SELECT count(1) FROM cotizacion_solic_prov where id_solicitudes=s.id), CHAR),
								' Cotizado(s) </small>'
								) end
								as estado,
				  case when est.id=4 and s.fecha_vigencia_cotiz is not null then (case when s.fecha_vigencia_cotiz > now() then '' else ' Vencido! ' end) else '' end as vigente,
					CONVERT(case when est.id=4 then (SELECT count(1) FROM cotizacion_solic_prov where id_solicitudes=s.id) else 0 end, CHAR) as provAsignados,
					CONVERT(case when est.id=4 then (SELECT count(1) FROM cotizacion_solic_prov where id_solicitudes=s.id and id_estados<>42) else 0 end, CHAR) as provCotizados
				FROM `solicitudes` s
		inner join personas p on s.id_personas=p.id
		inner join puntos_dispensa pd on pd.id=s.id_puntos_dispensa
		inner join (
				SELECT `id_estados`, `id_solicitudes`, `observaciones`, `userAlta`, `fechaAlta` 
				FROM `solicitudes_estados` s
				where s.fechaAlta in (
					SELECT max(s2.fechaAlta) from `solicitudes_estados` s2 where s.id_solicitudes=s2.id_solicitudes)
		) e on e.id_solicitudes=s.id
		inner join estados est on est.id=e.id_estados 
	left join solicitudes_remito sr on sr.id_solicitud=s.id
		
		where 
		";
		if ($idS == '') {

			if ($fd != "" && $fh != "") {
				$query .= "s.fecha between '" . fecha2($fd) . "' and '" . fecha2($fh) . "' ";
			} else {
				$query .= " 1=1 ";
			}


			if (strlen($be) > 0) {
				if (is_numeric($be)) {
					$query .= "and p.dni ='" . trim($be) . "' ";
				} else {
					$query .= "and concat(p.apellido , ' ' , p.nombre) like '%" . trim($be) . "%' ";
				}
			}
			if (strlen($pr) > 0) {

				$query .= "and s.id in (select distinct i.id_solicitudes from solicitudes_items i
				inner join productos prod on prod.id=i.id_producto
				where prod.nombre like '%" . $pr . "%' or prod.id= '" . $pr . "') ";
			}
			if ($es != '-1') {
				$query .= "and e.id_estados=" . $es . ' ';
			}

			if ($b24 != '') {
				$query .= "and p.codigoB24 like '%" . $b24 . "%'";
			}

			if ($pd != '-1') {
				$query .= "and pd.id=" . $pd . ' ';
			}

			if ($nr != '') {
				$query .= "and  sr.nroRemito='" . $nr . "'";
			}

			if ($sur != '-1') {
				$query .= "and  s.esSur='" . $sur . "'";
			}

			if ($onco != '-1') {
				$query .= "and  s.esOncologico='" . $onco . "'";
			}
		} else {
			$query .= 's.id=' . $idS . ' ';
		}

		$query .= ' and pd.id IN (SELECT up.id_ptoDispensa FROM usuarios_ptosdispensa up WHERE up.id_usuarios=' . $_SESSION['idUsuario'] . ')';
		$query .= ' order by s.id desc';
		//echo $query;
		$result = mysqli_query($dblink, $query);

		//echo $query;

		return $result;
	}

	public function SelectExcel($fd, $fh, $pr, $es, $idS, $be, $b24, $pd, $nr, $sur)
	{
		global $dblink;


		$where = "";

		if ($idS == '') {

			if ($fd != "" && $fh != "") {
				$where .= " and s.fecha between '" . fecha2($fd) . "' and '" . fecha2($fh) . "' ";
			}



			if (strlen($be) > 0) {
				if (is_numeric($be)) {
					$where .= " and p.dni ='" . trim($be) . "' ";
				} else {
					$where .= " and concat(p.apellido , ' ' , p.nombre) like '%" . trim($be) . "%' ";
				}
			}
			if (strlen($pr) > 0) {

				$where .= " and s.id in (select distinct i.id_solicitudes from solicitudes_items i
				inner join productos prod on prod.id=i.id_producto
				where prod.nombre like '%" . $pr . "%' or prod.id= '" . $pr . "') ";
			}

			if ($es != '-1') {
				$where .= " and e.id=" . $es . ' ';
			}

			if ($b24 != '') {
				$where .= " and p.codigoB24 like '%" . $b24 . "%'";
			}

			if ($pd != '-1') {
				$where .= " and pd.id=" . $pd . ' ';
			}

			if ($nr != '') {
				$where .= "and  sr.nroRemito='" . $nr . "'";
			}

			if ($sur != '-1') {
				$where .= "and  s.esSur='" . $sur . "'";
			}
		} else {
			$where .= ' and s.id=' . $idS . ' ';
		}



		$query1 = "SET SESSION group_concat_max_len = 1000000";

		$query = "
		SELECT  s.id idSolicitud, s.id_personas, date_format(s.fecha, '%d/%m/%Y') fechaS, s.observaciones, s.esSur, s.esB24, s.id_puntos_dispensa, pd.nombre ptoDispensa,
							p.apellido, p.nombre, p.dni, p.codigoB24, se.id_estados, e.nombre estadoSol,  traza.laboratorio,
							concat('<table>',
								GROUP_CONCAT(
								DISTINCT IFNULL(
							CONCAT('<tr><td>',si.cantidad,'</td><td>',pr.nombre,'</td><td>', pr.presentacion,'</td></tr>')
									, '<tr><td>Sin Productos Solicitados</td></tr>')      
							ORDER BY pr.nombre  SEPARATOR' '),'</table>') AS Producto,
							concat('<table>',
							GROUP_CONCAT(
							DISTINCT IFNULL(
							CONCAT('<tr><td> <b>Remito:</b> ', ifnull(sr.nroRemito,traza.nroRemito),'</td><td> <b>F. Remito:</b> ', ifnull(DATE_FORMAT(sr.fechaRemito,'%d/%m/%Y'),'-'),'</td><td> <b>Laboratorio</b>: ',ifnull(traza.laboratorio,'-'),'</td><td> <b>Nombre</b>: ',ifnull(traza.nombre,'-'),'</td><td> <b>GTIN</b>: ', ifnull(traza.gtin,'-'),'</td><td> <b># Serie</b>: ', ifnull(traza.nroSerie,'-'),'</td><td> <b>Lote:</b>', ifnull(traza.lote,'-'),'</td><td> <b>F. Venc.:</b>', ifnull(DATE_FORMAT(traza.fechaVenc,'%d/%m/%Y'),''),'</td><td> <b>Estado:</b> ', ifnull(estados.nombre,'-'),'</td></tr>')
									, '<tr><td>Sin Productos Trazados</td></tr>')    
							ORDER BY traza.nombre  SEPARATOR' ')
							,'</table>') AS Trazados, (SELECT ifnull(max(fechaAlta),'') fechaDispensa
							FROM solicitudes_estados se
							WHERE se.id_estados=8 and se.id_solicitudes=s.id)  fechaDispensa
						FROM solicitudes s
								INNER JOIN puntos_dispensa pd ON s.id_puntos_dispensa=pd.id
								INNER JOIN personas p ON p.id=s.id_personas
								LEFT JOIN solicitudes_estados se ON se.id_solicitudes=s.id
								LEFT JOIN estados e ON e.id=se.id_estados
								LEFT JOIN solicitudes_items si ON si.id_solicitudes=s.id
								LEFT JOIN productos pr ON si.id_producto=pr.id
								LEFT JOIN solicitudes_remito sr ON (sr.id_solicitud=s.id )
								LEFT JOIN `solicitudes_items_traza` traza ON (length(traza.nroRemito)>1 and traza.id_solicitud=s.id AND (sr.nroRemito=traza.nroRemito or sr.nroRemito is null))
								LEFT JOIN `solicitudes_items_traza_estados` traza_estado on  traza_estado.id_solicitud=traza.id_solicitud and traza_estado.id_item_traza=traza.id and traza_estado.fhasta is null
								LEFT JOIN estados on estados.id=traza_estado.id_estado
				WHERE se.fechaAlta in (SELECT max(s2.fechaAlta) from `solicitudes_estados` s2 where se.id_solicitudes=s2.id_solicitudes) and
				    pd.id IN (SELECT up.id_ptoDispensa FROM usuarios_ptosdispensa up WHERE up.id_usuarios=" . $_SESSION['idUsuario'] . ")  " . $where . "
				GROUP BY s.id, s.id_personas, date_format(s.fecha, '%d/%m/%Y'), s.observaciones, s.esSur, s.esB24, s.id_puntos_dispensa, pd.nombre,
					p.apellido, p.nombre, p.dni, se.id_estados, e.nombre
				ORDER BY s.id;";





	   // echo $query;
		mysqli_query($dblink, $query1);
		$result = mysqli_query($dblink, $query);

		return $result;
	}


	public function SelectReporteDispensa()
	{
		global $dblink;


		$query = "SELECT  s.id idSolicitud, DATE_FORMAT(s.fecha,'%d/%m/%Y') fechaS, s.observaciones, 
						s.id_puntos_dispensa, pd.nombre ptoDispensa, pd.GLN, s.esB24, p.apellido, p.nombre, p.dni,
						p.codigoB24, pr.nombre nombreP, pr.troquel, pr.presentacion, pr.gtin gtinTProd, sit.id_item, sit.gtin, sit.nroSerie,
						sit.lote, DATE_FORMAT(sit.fechaVenc,'%d/%m/%Y') fVenc, sit.nroRemito, DATE_FORMAT(sit.fechaRemito,'%d/%m/%Y') fechaR,
		                sit.nroRemito, DATE_FORMAT(sit.fechaRemito,'%d/%m/%Y') fechaR, sit.id_recepcion,
						sit.id_dispensa, sit.esTrazable, DATE_FORMAT(site.fdesde,'%d/%m/%Y %H:%i:%s') fechaTrans
				FROM solicitudes s
					INNER JOIN solicitudes_items si ON si.id_solicitudes=s.id
					INNER JOIN solicitudes_estados se ON se.id_solicitudes=s.id
					INNER JOIN productos pr ON pr.id=si.id_producto
					INNER JOIN estados e ON e.id=se.id_estados
					INNER JOIN personas p ON p.id=s.id_personas
					INNER JOIN puntos_dispensa pd ON pd.id=s.id_puntos_dispensa
					INNER JOIN solicitudes_items_traza sit ON sit.id_solicitud=s.id AND sit.id_item=si.id
					INNER JOIN solicitudes_items_traza_estados site ON site.id_item_traza=sit.id
					INNER JOIN estados e1 ON e1.id=site.id_estado
					
				WHERE se.fechaAlta IN (
												SELECT MAX(s2.fechaAlta) 
														FROM solicitudes_estados s2 
														WHERE s2.id_solicitudes=s.id 
					
											)
						AND site.fhasta IS NULL AND site.id_estado=19 AND se.id_estados=8 AND s.id='{$this->getid()}'";

		//echo $query;

		$result = mysqli_query($dblink, $query);

		return $result;
	}



	public function Save()
	{
		global $dblink;
		$query = "UPDATE solicitudes SET 
						`id_personas` = '" . mysqli_real_escape_string($dblink, $this->getid_personas()) . "',
						`fecha` = '" . mysqli_real_escape_string($dblink, $this->getfecha()) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "',
						`id_puntos_dispensa` = '" . mysqli_real_escape_string($dblink, $this->getid_puntos_dispensa()) . "', 
						`esB24` = '" . mysqli_real_escape_string($dblink, $this->getesB24()) . "',
						`esSur` = '" . mysqli_real_escape_string($dblink, $this->getesSur()) . "',
						`esOncologico` = '" . mysqli_real_escape_string($dblink, $this->getesOncologico()) . "',
						`esSur` = '" . mysqli_real_escape_string($dblink, $this->getesSur()) . "',
						`fecha_vigencia_cotiz` = '" . mysqli_real_escape_string($dblink, $this->getfecha_vigencia_cotiz()) . "'
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);
	}

	public function Create()
	{
		global $dblink;

		if($this->getfecha_vigencia_cotiz()!=""){
			$fechaVigencia="'".mysqli_real_escape_string($dblink, $this->getfecha_vigencia_cotiz())."'";
		}
		else
		  $fechaVigencia='NULL';

		  
		$query = "INSERT INTO solicitudes (`id_personas`,`fecha`,`observaciones`,`id_puntos_dispensa`,`esB24`, `esSur`, `esOncologico`,  `id_tipo_solicitud`, `fecha_vigencia_cotiz` ) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_personas()) . "',now(),'" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink, $this->getid_puntos_dispensa()) . "','" . mysqli_real_escape_string($dblink, $this->getesB24()) . "','" . mysqli_real_escape_string($dblink, $this->getesSur()) . "','".mysqli_real_escape_string($dblink, $this->getesOncologico())."',".mysqli_real_escape_string($dblink, $this->getid_tipo_solicitud()).",".$fechaVigencia.");";
		mysqli_query($dblink, $query);
		//echo $query;
		$id = mysqli_insert_id($dblink);
		$this->setid($id);
		$this->Load();
	}


	public function getROlUserCarga()
	{
		global $dblink;
		$query=" select id_roles from usuarios_roles
				where id_usuarios in (
				select id from usuarios where user in (
				select userAlta from solicitudes_estados
					where id_solicitudes='{$this->getid()}' and fechaAlta in (
					select min(fechaAlta)
						from solicitudes_estados
						where id_solicitudes='{$this->getid()}'
					)
					)
				)
				";

		$result = mysqli_query($dblink, $query);

		//echo $query;

		return $result;
	}


	public function eliminarRemito($idRemito, $idSolicitud)
	{
		global $dblink;
		$query = "DELETE FROM solicitudes_remito WHERE `id_solicitud`='{$idSolicitud}' and `id`='{$idRemito}'";

		$result = mysqli_query($dblink, $query);
		if ($result) return true;
		else return false;
	}



	public function addSolicitudRemito($data)
	{

		if ($data['fechaRemito'] != "" and $data['fechaRemito'] != null)
			$fechaRemito = "'" . $data['fechaRemito'] . "'";
		else
			$fechaRemito = "NULL";

		global $dblink;
		$query = "INSERT INTO solicitudes_remito (	`id_solicitud` ,
				`nroRemito` ,
				`fechaRemito`,
				`observaciones` ,
				`esTrazado` ,
				`userAlta` ) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid()) . "','" . $data['nroRemito'] . "'," . $fechaRemito . ",'" . $data['obs'] . "'," . $data['esTrazado'] . ",'" . $_COOKIE['user']  . "');";

		mysqli_query($dblink, $query);
		$id = mysqli_insert_id($dblink);
		return $id;
	}


	public function addSolictudRemitoDocs($idRemito)
	{
		global $dblink;


		$query = "SELECT *
				FROM solicitudes_docs d
				WHERE d.activo=1";

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result)) {
			$query1 = "INSERT INTO solicitudes_remito_docs (`idDoc`,
			`idRemito`,
			`idSolicitud`,
			`userAlta` ) VALUES (" . $row['id'] . "," . mysqli_real_escape_string($dblink, $idRemito)  . "," . mysqli_real_escape_string($dblink, $this->getid())  . ",'" . $_COOKIE['user']  . "');";


			$result1 = mysqli_query($dblink, $query1);
			if (!$result1) {
				return 0;
			}
		}

		return 1;
	}

	public function updateSolicitudRemito($data)
	{

		global $dblink;
		$query = "UPDATE solicitudes_remito SET 
						`nroRemito` = '" . mysqli_real_escape_string($dblink, $data['nroRemito']) . "',
						`fechaRemito` = '" . mysqli_real_escape_string($dblink, $data['fechaRemito']) . "',
						`observaciones` = '" . mysqli_real_escape_string($dblink, $data['obs']) . "',
						`fechaModif`=now(),
						`userModif`='" . $_COOKIE['user'] . "'
				   WHERE `id_solicitud`='{$this->getid()}' and `id`=" . $data['idRemito'];


		$result = mysqli_query($dblink, $query);

		return $result;
	}

	public function getestado()
	{
		global $dblink;
		$query = "SELECT e.nombre as Estado, e.id as idEstado, se.fechaAlta, se.userAlta, se.observaciones 
		FROM `estados` e
		inner join `solicitudes_estados` se on se.id_estados=e.id
		where `id_solicitudes`='{$this->getid()}' order by fechaAlta desc limit 1";

		$result = mysqli_query($dblink, $query);

		//echo $query;

		return $result;
	}

	public function  getRemitoSolicitudDocs($idRemito)
	{
		global $dblink;
		$query = "SELECT srd.*, sr.nombre
		FROM solicitudes_remito_docs srd
		   INNER JOIN solicitudes_docs sr ON srd.idDoc=sr.id
		where `idSolicitud`='{$this->getid()}' AND srd.idRemito=" . $idRemito;

		$result = mysqli_query($dblink, $query);

		return $result;
	}


	public function SelectAllRemitos()
	{
		global $dblink;
		$query = "SELECT sr.id, sr.id_solicitud, sr.nroRemito, DATE_FORMAT(sr.fechaRemito,'%d/%m/%Y') fremito, sr.userAlta, DATE_FORMAT(sr.fechaAlta,'%d/%m/%Y %H:%i:%s') falta, sr.observaciones
						    FROM  solicitudes_remito sr
							WHERE sr.id_solicitud='{$this->getid()}'
					";

		$result = mysqli_query($dblink, $query);

		return $result;
	}


	public function existeRemito($data)
	{
		global $dblink;
		$query = "SELECT COUNT(*) cant
			      FROM  solicitudes_remito sr
				  WHERE sr.id_solicitud='{$this->getid()}' and sr.nroRemito='" . $data['nroRemito'] . "'";


		$result = mysqli_query($dblink, $query);
		$row = mysqli_fetch_assoc($result);
		if ($row['cant'] == 1)
			return true;
		else
			return false;
	}

	public function existeRemitoDocsSinCheck()
	{
		global $dblink;
		$query = "SELECT COUNT(*) cant
		FROM solicitudes_remito_docs s
		WHERE s.chequeado=0 AND s.idSolicitud='{$this->getid()}'";


		$result = mysqli_query($dblink, $query);
		$row = mysqli_fetch_assoc($result);
		return  $row['cant'];
	}


	public function SelectAllRemitosTraza()
	{
		global $dblink;
	/*	$query = "	SELECT sit.id_solicitud, sit.nroRemito,  sit.userAlta, DATE_FORMAT(sit.fechaAlta,'%d/%m/%Y') falta
					FROM solicitudes_items_traza sit
					WHERE sit.id_solicitud='{$this->getid()}'";*/

		$query = "SELECT sit.id_solicitud, sit.nroRemito,  sit.userAlta, DATE_FORMAT(sit.fechaAlta,'%d/%m/%Y') falta
					FROM solicitudes_items_traza sit
						INNER JOIN solicitudes_items_traza_estados site on sit.id=site.id_item_traza
					WHERE sit.id_solicitud='{$this->getid()}' and site.id_estado in (17,19) and site.fhasta is null";

		$result = mysqli_query($dblink, $query);

		return $result;
	}

	public function getSolicitud()
	{
		global $dblink;
		$query = "SELECT * FROM solicitudes WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink, $query);

		return $result;
	}


	public function editSolicitudRemitosDocs($data)
	{

		global $dblink;
		$query = "UPDATE solicitudes_remito_docs SET 
						`chequeado` = " . mysqli_real_escape_string($dblink, $data['chequear']) . ",
						`observaciones` = '" . mysqli_real_escape_string($dblink, $data['obs']) . "',
						`fechaModif`=now(),
						`userModif`='" . $_COOKIE['user'] . "'
				   WHERE `idSolicitud`='{$this->getid()}' and `idRemito`=" . $data['idRemito'] . " and idDoc=" . $data['idDoc'];


		$result = mysqli_query($dblink, $query);

		if ($result)
			return 1;
		else
			return 0;
	}


	public function editEsPrivada()
	{

		global $dblink;
		$query = "UPDATE solicitudes 
		           SET 
						`privada` = " . mysqli_real_escape_string($dblink, $this->getprivada()) . "
				   WHERE `id`='{$this->getid()}' ";


		$result = mysqli_query($dblink, $query);

		//echo $query;

		if ($result)
			return 1;
		else
			return 0;
	}


	public function getRemitoSolicitud($id)
	{
		global $dblink;
		$query = "SELECT * FROM solicitudes_remito WHERE `id`='{$id}'";

		$result = mysqli_query($dblink, $query);

		return $result;
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

	public function setid_personas($id_personas = '')
	{
		$this->id_personas = $id_personas;
		return true;
	}

	public function getid_personas()
	{
		return $this->id_personas;
	}

	public function setfecha($fecha = '')
	{
		$this->fecha = $fecha;
		return true;
	}

	public function getfecha()
	{
		return $this->fecha;
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

	public function setid_puntos_dispensa($id_puntos_dispensa = '')
	{
		$this->id_puntos_dispensa = $id_puntos_dispensa;
		return true;
	}

	public function getid_puntos_dispensa()
	{
		return $this->id_puntos_dispensa;
	}

	public function setesB24($esB24 = '')
	{
		$this->esB24 = $esB24;
		return true;
	}

	public function getesB24()
	{
		return $this->esB24;
	}

	public function setesSur($esSur = '')
	{
		$this->esSur = $esSur;
		return true;
	}

	public function getesSur()
	{
		return $this->esSur;
	}


	public function setesOncologico($esOncologico = '')
	{
		$this->esOncologico = $esOncologico;
		return true;
	}

	public function getesOncologico()
	{
		return $this->esOncologico;
	}


	public function setid_tipo_solicitud($id_tipo_solicitud = '')
	{
		$this->id_tipo_solicitud = $id_tipo_solicitud;
		return true;
	}

	public function getid_tipo_solicitud()
	{
		return $this->id_tipo_solicitud;
	}


	public function setfecha_vigencia_cotiz($fecha_vigencia_cotiz = '')
	{
		$this->fecha_vigencia_cotiz = $fecha_vigencia_cotiz;
		return true;
	}

	public function getfecha_vigencia_cotiz()
	{
		return $this->fecha_vigencia_cotiz;
	}


	public function setprivada($privada = '')
	{
		$this->privada = $privada;
		return true;
	}

	public function getprivada()
	{
		return $this->privada;
	}


} // END class solicitudes