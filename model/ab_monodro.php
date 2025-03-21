<?php

/******************************************************************************
* Class for ospepri_altocosto.ab_monodro
*******************************************************************************/

class ab_monodro
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $descripcion;

	public function __construct($codigo='')
	{
		$this->setcodigo($codigo);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_monodro WHERE `codigo`='{$this->getcodigo()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}

	public function SelectAll()
	{
		Global $dblink;
		$query = "SELECT * FROM ab_monodro order by descripcion";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE ab_monodro SET 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "' 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM ab_monodro 
						WHERE `codigo`='{$this->getcodigo()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "') ON DUPLICATE KEY UPDATE 
						`descripcion` = '" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "';";
		mysqli_query($dblink,$query);

	}
        
        
        
        
        public function CreateTemp()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro_temp (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
                logc($query);
		mysqli_query($dblink,$query);

	}
        public function BorrarTemporal()
	{
		Global $dblink;
		$query ="Delete from ab_monodro_temp;";
                logc($query);
		mysqli_query($dblink,$query);
	}
        public function Actualizar()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro "
                        . "SELECT * FROM ab_monodro_temp t "
                        . "ON DUPLICATE KEY UPDATE descripcion = t.descripcion; ";
                logc($query);
		mysqli_query($dblink,$query);
	}
        
        
        
        
        public function CreateRegTemporal()
	{
		Global $dblink;
		$query ="INSERT INTO ab_monodro (`codigo`,`descripcion`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getcodigo()) . "','" . mysqli_real_escape_string($dblink,$this->getdescripcion()) . "');";
		mysqli_query($dblink,$query);

	}

	public function setcodigo($codigo='')
	{
		$this->codigo = $codigo;
		return true;
	}

	public function getcodigo()
	{
		return $this->codigo;
	}

	public function setdescripcion($descripcion='')
	{
		$this->descripcion = $descripcion;
		return true;
	}

	public function getdescripcion()
	{
		return $this->descripcion;
	}

} // END class ab_monodro