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

		$result = mysqli_query($dblink, $query);

		while ($row = mysqli_fetch_assoc($result))
			foreach ($row as $key => $value)
			{
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

	public function SelectAllFiltros($fd, $fh, $pr, $es, $idS, $be)
	{
		global $dblink;
		$query = "SELECT s.id,s.fecha, concat(ifnull(p.apellido,''),' ', ifnull(p.nombre,'')) as nombre, p.dni, pd.nombre as puntoDispensa, est.nombre as estado FROM `solicitudes` s
		inner join personas p on s.id_personas=p.id
		inner join puntos_dispensa pd on pd.id=s.id_puntos_dispensa
		inner join (
				SELECT `id_estados`, `id_solicitudes`, `observaciones`, `userAlta`, `fechaAlta` 
				FROM `solicitudes_estados` s
				where s.fechaAlta in (
					SELECT max(s2.fechaAlta) from `solicitudes_estados` s2 where s.id_solicitudes=s2.id_solicitudes)
		) e on e.id_solicitudes=s.id
		inner join estados est on est.id=e.id_estados 
		
		where 
		";
		if ($idS == '') {
			$query .= "s.fecha between '" . fecha2($fd) . "' and '" . fecha2($fh) . "' ";
			if (strlen($be) > 0) {
				if (is_numeric($be)) {
					$query .= "and p.dni ='" . trim($be) . "' ";
				}
				else {
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
		}
		else {
			$query .= 's.id=' . $idS . ' ';
		}
		$query.=' order by s.id desc';
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
						`esB24` = '" . mysqli_real_escape_string($dblink, $this->getid_puntos_dispensa()) . "'
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink, $query);

	}

	public function Create()
	{
		global $dblink;
		$query = "INSERT INTO solicitudes (`id_personas`,`fecha`,`observaciones`,`id_puntos_dispensa`,`esB24`) VALUES ('" . mysqli_real_escape_string($dblink, $this->getid_personas()) . "',now(),'" . mysqli_real_escape_string($dblink, $this->getobservaciones()) . "','" . mysqli_real_escape_string($dblink, $this->getid_puntos_dispensa()) . "','" . mysqli_real_escape_string($dblink, $this->getesB24()) . "');";
		mysqli_query($dblink, $query);
		$id = mysqli_insert_id($dblink);
		$this->setid($id);
		$this->Load();

	}

	public function getestado() {
		global $dblink;
		$query = "SELECT e.nombre as Estado, e.id as idEstado, se.fechaAlta, se.userAlta, se.observaciones 
		FROM `estados` e
		inner join `solicitudes_estados` se on se.id_estados=e.id
		where `id_solicitudes`='{$this->getid()}' order by fechaAlta desc limit 1";

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

	public function setesB24($esB24='')
	{
		$this->esB24 = $esB24;
		return true;
	}

	public function getesB24()
	{
		return $this->esB24;
	}

} // END class solicitudes