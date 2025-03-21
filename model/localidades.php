<?php

/******************************************************************************
* Class for ospepri_altocosto.localidades
*******************************************************************************/

class localidades
{
	/**
	* @var 
	* Class Unique ID
	*/
	private $id;

	/**
	* @var 
	*/
	private $nombre;

	/**
	* @var 
	*/
	private $id_provincias;

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

	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}

	private function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM localidades WHERE `id`='{$this->getid()}'";

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
		$query = "	SELECT l.*, p.id id_provincia, p.nombre provincia
					FROM localidades l
			 			 INNER JOIN provincias p ON l.id_provincias=p.id ";

		$result = mysqli_query($dblink,$query);

		
	return $result;

    }
    
    public function SelectXProv($idProvincia='')
	{
		Global $dblink;

		
        
        if($idProvincia!="")
        {
            $where=" where id_provincias=".$idProvincia;
        }
        else
        {
            $where=" where true ";
        }

        $query = "SELECT * FROM localidades ".$where;
        
     //  echo $query;

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE localidades SET 
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`id_provincias` = '" . mysqli_real_escape_string($dblink,$this->getid_provincias()) . "',
						`userModif` = '" . $_SESSION['user'] . "',
						`fechaModif` = now() 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

		
	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO localidades (`nombre`,`id_provincias`,`userAlta`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getid_provincias()) . "','".$_SESSION["user"]."');";
		mysqli_query($dblink,$query);

		
	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM localidades
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

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

	public function setnombre($nombre='')
	{
		$this->nombre = $nombre;
		return true;
	}

	public function getnombre()
	{
		return $this->nombre;
	}

	public function setid_provincias($id_provincias='')
	{
		$this->id_provincias = $id_provincias;
		return true;
	}

	public function getid_provincias()
	{
		return $this->id_provincias;
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

} // END class localidades
