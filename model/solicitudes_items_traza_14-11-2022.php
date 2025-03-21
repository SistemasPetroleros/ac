<?php

/******************************************************************************
 * Class for ospepri_altocosto.solicitudes_items_traza
 *******************************************************************************/

class SolicitudesItemTraza
{
	/**
	 * @var 
	 * Class Unique ID
	 */
	private $id;

	/**
	 * @var 
	 * Class Unique ID
	 */
	private $id_solicitud;

	/**
	 * @var 
	 */
	private $id_item;

	/**
	 * @var 
	 */
	private $id_producto;

	/**
	 * @var 
	 */
	private $nombre;

	/**
	 * @var 
	 */
	private $gtin;

	/**
	 * @var 
	 */
	private $nroSerie;

	/**
	 * @var 
	 */
	private $lote;

	/**
	 * @var 
	 */
	private $nroRemito;

	/**
	 * @var 
	 */
	private $fechaEvento;

	/**
	 * @var 
	 */
	private $fechaVenc;

	/**
	 * @var 
	 */
	private $id_recepcion;

	/**
	 * @var 
	 */
	private $id_dispensa;



	/**
	 * @var 
	 */
	private $esTrazable;

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



	public function __construct($id = '')
	{
		$this->setid($id);
		$this->Load();
	}




	private function Load()
	{
		global $dblink;
		$query = "SELECT * FROM solicitudes_items_traza WHERE `id`='{$this->getid()}'";

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
		$query = "SELECT * FROM solicitudes_items_traza ";

		$result = mysqli_query($dblink, $query);


		return $result;
	}


	public function SelectItemsTraza($parametros)
	{

		global $dblink;

		$where = " ";
		if ($parametros['idItem'] != NULL and $parametros['idItem'] != "") {
			$where .= " AND sit.id=" . $parametros['idItem'];
		}

		if ($parametros['idSolicitud'] != NULL and $parametros['idSolicitud'] != "") {
			$where .= " AND sit.id_solicitud=" . $parametros['idSolicitud'];
		}

		if ($parametros['nroSerie'] != NULL and $parametros['nroSerie'] != "") {
			$where .= " AND sit.nroSerie='" . $parametros['nroSerie'] . "'";
		}

		if ($parametros['remito'] != NULL and $parametros['remito'] != "") {
			$where .= " AND sit.nroRemito='" . $parametros['remito'] . "'";
		}

		if ($parametros['idEstado'] != NULL and $parametros['idEstado'] != "") {
			$where .= " AND site.id_estado IN(" . $parametros['idEstado'] . ")";
		}

		/*		$query = "SELECT DISTINCT sit.*, p.nombre nombreProd, p.presentacion, p.gtin gtinProd, 
						e.nombre estado, sit.gtin gtinItem, site.id idTrazaEstado, site.id_estado
				FROM solicitudes_items_traza sit 
					INNER JOIN solicitudes_items si ON (si.id=sit.id_item) 
					INNER JOIN solicitudes s ON (s.id=si.id_solicitudes AND sit.id_solicitud=s.id) 
					INNER JOIN productos p ON (p.id=si.id_producto) 
					INNER JOIN solicitudes_items_traza_estados site ON (site.id_solicitud=sit.id_solicitud AND site.id_item=sit.id_item AND site.id_item_traza=sit.id) 
					INNER JOIN estados e ON e.id=site.id_estado
 WHERE (1=1) AND site.fhasta IS NULL
       ".$where." ORDER BY sit.id ASC";*/


		$query = "SELECT DISTINCT sit.*, e.nombre estado, site.id idTrazaEstado, site.id_estado, date_format(sit.fechaVenc,'%d/%m/%Y') fechaV, date_format(sr.fechaRemito,'%d/%m/%Y') fechaR, sit.laboratorio, date_format(sit.fechaEvento,'%d/%m/%Y') fechaE,
	                           (SELECT se.id_estados from solicitudes_estados se WHERE se.id_solicitudes=s.id ORDER BY se.fechaAlta DESC LIMIT 1) idEstadoS, date_format(site.fDesde,'%d/%m/%Y %H:%i:%s') fechaD, ifnull(pr.nombre, sit.nombre) nombrePr, sr.fechaRemito
				FROM solicitudes_items_traza sit 
									INNER JOIN solicitudes s ON (s.id=sit.id_solicitud) 
									INNER JOIN solicitudes_items_traza_estados site ON (site.id_solicitud=sit.id_solicitud AND sit.id=site.id_item_traza) 
									INNER JOIN estados e ON e.id=site.id_estado
									left join productos pr on pr.id=sit.id_producto
									left join solicitudes_remito sr on (sr.nroRemito=sit.nroRemito and sr.id_solicitud=s.id)
					WHERE (1=1) AND site.fhasta IS NULL " . $where . "
					ORDER BY sit.id ASC";

		$result = mysqli_query($dblink, $query);

		//echo $query;
		return $result;
	}

	public function Save()
	{

		global $dblink;

		$set = "";


		$id_item = mysqli_real_escape_string($dblink, $this->getid_item());
		if ($id_item != null &&  $id_item != "") {
			$set .= " id_item=" . $id_item . ",";
		}

		$id_producto = mysqli_real_escape_string($dblink, $this->getid_producto());
		if ($id_producto != null &&  $id_producto != "") {
			$set .= " id_producto=" . $id_producto . ",";
		}


		$id_dispensa = mysqli_real_escape_string($dblink, $this->getid_dispensa());
		if ($id_dispensa != null &&  $id_dispensa != "") {
			$set .= " id_dispensa=" . $id_dispensa . ",";
		} else {
			$set .= " id_dispensa=NULL,";
		}

		$id_recepcion = mysqli_real_escape_string($dblink, $this->getid_recepcion());
		if ($id_recepcion != null &&  $id_recepcion != "") {
			$set .= " id_recepcion=" . $id_recepcion . ",";
		} else {
			$set .= " id_recepcion=NULL,";
		}


		$esTrazable = mysqli_real_escape_string($dblink, $this->getesTrazable());
		if ($esTrazable != null &&  $esTrazable != "") {
			$set .= " esTrazable=" . $esTrazable . ",";
		}

		$gtin = mysqli_real_escape_string($dblink, $this->getgtin());
		if ($gtin != null &&  $gtin != "") {
			$set .= " gtin='" . $gtin . "',";
		}

		$nroSerie = mysqli_real_escape_string($dblink, $this->getnroSerie());
		if ($nroSerie != null &&  $nroSerie != "") {
			$set .= " nroSerie='" . $nroSerie . "',";
		}

		$lote = mysqli_real_escape_string($dblink, $this->getlote());
		if ($lote != null &&  $lote != "") {
			$set .= " lote='" . $lote . "',";
		}

		$fechaVenc = mysqli_real_escape_string($dblink, $this->getfechaVenc());
		if ($fechaVenc != null &&  $fechaVenc != "") {
			$set .= " fechaVenc='" . $fechaVenc . "',";
		}

		$nroRemito = mysqli_real_escape_string($dblink, $this->getnroRemito());
		if ($nroRemito != null &&  $nroRemito != "") {
			$set .= " nroRemito='" . $nroRemito . "',";
		}

		$fechaEvento = mysqli_real_escape_string($dblink, $this->getfechaEvento());
		if ($fechaEvento != null &&  $fechaEvento != "") {
			$set .= " fechaEvento='" . $fechaEvento . "',";
		}


		$laboratorio = mysqli_real_escape_string($dblink, $this->getlaboratorio());
		if ($laboratorio != null &&  $laboratorio != "") {
			$set .= " laboratorio='" . $laboratorio . "',";
		}


		$query = "UPDATE solicitudes_items_traza SET 
							" . $set . "
							`userModif` = '" . mysqli_real_escape_string($dblink, $this->getuserModif()) . "',
							`fechaModif` = now() 
							WHERE `id`='{$this->getid()}'";

		// echo $query;

		if (mysqli_query($dblink, $query))
			return true;
		else
			return false;
	}

	public function Create()
	{
		global $dblink;


		$fechaVenci = $this->getfechaVenc();
		if ($fechaVenci == NULL) {
			$fechaV = 'NULL';
		} else {
			$fechaV = "'" . $fechaVenci . "'";
		}


		$id_item = mysqli_real_escape_string($dblink, $this->getid_item());
		if ($id_item == "") {
			$id_item = "NULL";
		}

		$id_producto = mysqli_real_escape_string($dblink, $this->getid_producto());
		if ($id_producto == "") {
			$id_producto = 'NULL';
		}

		$idDispensa = mysqli_real_escape_string($dblink, $this->getid_dispensa());
		if ($idDispensa == '') {
			$idDispensa = 'NULL';
		}

		$idRecepcion = mysqli_real_escape_string($dblink, $this->getid_recepcion());
		if ($idRecepcion == "") {
			$idRecepcion = 'NULL';
		}

		$idRecepcion = mysqli_real_escape_string($dblink, $this->getid_recepcion());

		$query = "INSERT INTO solicitudes_items_traza (	`id_solicitud` ,
                            `id_item` ,
                            `id_producto` ,
							`nombre`,
                            `gtin`,
                            `nroSerie` ,
                            `lote`,
                            `fechaVenc`,
							`nroRemito`,
							`fechaEvento`,
							`laboratorio`,
                            `id_recepcion` ,
                            `id_dispensa` ,
                            `esTrazable`,
                            `userAlta`,
                            `fechaAlta`) VALUES (" . mysqli_real_escape_string($dblink, $this->getid_solicitud()) . "," .
			$id_item . "," .
			$id_producto . ",'" .
			mysqli_real_escape_string($dblink, $this->getnombre()) . "','" .
			mysqli_real_escape_string($dblink, $this->getgtin()) . "','" .
			mysqli_real_escape_string($dblink, $this->getnroSerie()) . "','" .
			mysqli_real_escape_string($dblink, $this->getlote()) . "'," .
			$fechaV . ",'" .
			mysqli_real_escape_string($dblink, $this->getnroRemito()) . "','" .
			mysqli_real_escape_string($dblink, $this->getfechaEvento()) . "','" .
			mysqli_real_escape_string($dblink, $this->getlaboratorio()) . "'," .
			$idRecepcion . "," .
			$idDispensa . "," .
			mysqli_real_escape_string($dblink, $this->getesTrazable()) . ",'" .
			mysqli_real_escape_string($dblink, $this->getuserAlta()) . "',now())";



		if (mysqli_query($dblink, $query)) {
			$id = mysqli_insert_id($dblink);
			return $id;
		} else
			return 0;
	}

	public function Delete()
	{
		global $dblink;
		$query = "DELETE FROM solicitudes_items_traza
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);
	}

	public function ExisteRemito($idSolicitud, $nroRemito)
	{
		global $dblink;
		$query = "SELECT COUNT(*) cant FROM  solicitudes_items_traza
						WHERE `id_solicitud`='{$idSolicitud}' and `nroRemito`='{$nroRemito}'";

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

	public function setid_solicitud($idSolicitud = '')
	{
		$this->id_solicitud = $idSolicitud;
		return true;
	}

	public function getid_solicitud()
	{
		return $this->id_solicitud;
	}


	public function setid_item($idItem = '')
	{
		$this->id_item = $idItem;
		return true;
	}

	public function getid_item()
	{
		return $this->id_item;
	}


	public function setid_producto($idProducto = '')
	{
		$this->id_producto = $idProducto;
		return true;
	}

	public function getid_producto()
	{
		return $this->id_producto;
	}


	public function setgtin($gtin = '')
	{
		$this->gtin = $gtin;
		return true;
	}

	public function getgtin()
	{
		return $this->gtin;
	}


	public function setnroSerie($nroSerie = '')
	{
		$this->nroSerie = $nroSerie;
		return true;
	}

	public function getnroSerie()
	{
		return $this->nroSerie;
	}

	public function setlote($lote = '')
	{
		$this->lote = $lote;
		return true;
	}

	public function getlote()
	{
		return $this->lote;
	}

	public function setlaboratorio($laboratorio = '')
	{
		$this->laboratorio = $laboratorio;
		return true;
	}

	public function getlaboratorio()
	{
		return $this->laboratorio;
	}

	



	public function setnroRemito($nroRemito = '')
	{
		$this->nroRemito = $nroRemito;
		return true;
	}

	public function getnroRemito()
	{
		return $this->nroRemito;
	}

	public function setfechaEvento($fechaEvento = '')
	{
		$this->fechaEvento = $fechaEvento;
		return true;
	}

	public function getfechaEvento()
	{
		return $this->fechaEvento;
	}

	public function setfechaVenc($fechaVenc = '')
	{
		$this->fechaVenc = $fechaVenc;
		return true;
	}

	public function getfechaVenc()
	{
		return $this->fechaVenc;
	}

	public function setid_recepcion($idRecepcion = '')
	{
		$this->id_recepcion = $idRecepcion;
		return true;
	}

	public function getid_recepcion()
	{
		return $this->id_recepcion;
	}

	public function setid_dispensa($idDispensa = '')
	{
		$this->id_dispensa = $idDispensa;
		return true;
	}

	public function getid_dispensa()
	{
		return $this->id_dispensa;
	}



	public function setesTrazable($esTrazable = '')
	{
		$this->esTrazable = $esTrazable;
		return true;
	}

	public function getesTrazable()
	{
		return $this->esTrazable;
	}


	public function setnombre($nombre = '')
	{
		$this->nombre = $nombre;
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
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
} // END class Trazabilidad