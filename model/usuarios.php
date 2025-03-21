<?php
/******************************************************************************
* Class for farmacia.usuarios
*******************************************************************************/

class usuarios
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $user;

	/**
	* @var string
	*/
	private $pass;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $apellido;

	/**
	* @var int
	*/
	private $vendedor;

	/**
	* @var int
	*/
	private $habilitado;
/*
	public function __construct($id='')
	{
		$this->setid($id);
		$this->Load();
	}
*/
        
        public function __construct($user='',$pass='')
        {
        $this->setuser($user);
        $this->setpass($pass);
        $this->Load2();
        }
  
	public function Load()
	{
		Global $dblink;
		$query = "SELECT * FROM usuarios WHERE `id`='{$this->getid()}'";

		$result = mysqli_query($dblink,$query);

		while($row = mysqli_fetch_assoc($result) )
			foreach($row as $key => $value)
			{
				$column_name = str_replace('-','_',$key);
				$this->{"set$column_name"}($value);

			}
	}
        
        private function Load2()
	{
		Global $dblink;
		$query = "SELECT * FROM usuarios WHERE `user`='{$this->getuser()}' and `pass`='".md5($this->getpass())."'";
            
// echo $query;			
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
		$query = "SELECT * FROM usuarios order by apellido, nombre ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}

	public function SelectAllAppAndroid()
	{
		Global $dblink;
		$query = "SELECT * FROM usuarios where user = '" . mysqli_real_escape_string($dblink,$this->getuser()) . "' and pass = '" . mysqli_real_escape_string($dblink,$this->getpass()) . "' order by apellido, nombre ";

		$result = mysqli_query($dblink,$query);

	return $result;

	}



	

    public function VerificaLink($script)
	{
		Global $dblink;
		$query = "SELECT count(1) as cnt FROM usuarios u
inner join usuarios_roles ur on ur.id_usuarios=u.id
inner join roles_links rl on rl.id_roles=ur.id_roles
inner join links l on l.id=rl.id_links
where ltrim(rtrim(l.url)) = ltrim(rtrim('".$script."')) and u.id='{$this->getid()}'";

//echo $query;
		$result = mysqli_query($dblink,$query);
                $row = mysqli_fetch_assoc($result);
                return $row['cnt'];

	}

	public function Save()
	{
		Global $dblink;
		$query = "UPDATE usuarios SET 
						`user` = '" . mysqli_real_escape_string($dblink,$this->getuser()) . "',
						`pass` = '" . mysqli_real_escape_string($dblink,$this->getpass()) . "',
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`apellido` = '" . mysqli_real_escape_string($dblink,$this->getapellido()) . "',
						`vendedor` = '" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "' 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}
        
        public function SavePass()
	{
		Global $dblink;
		$query = "UPDATE usuarios SET `pass` = '" . md5(mysqli_real_escape_string($dblink,$this->getpass())) . "'
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM usuarios 
						WHERE `id`='{$this->getid()}'";

		mysqli_query($dblink,$query);

	}

	public function Create()
	{
		Global $dblink;
		$query ="INSERT INTO usuarios (`id`,`user`,`pass`,`nombre`,`apellido`,`vendedor`,`habilitado`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getuser()) . "','" . mysqli_real_escape_string($dblink,$this->getpass()) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getapellido()) . "','" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "','" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "');";
		mysqli_query($dblink,$query);

	}

	public function CreateOrUpdate()
	{
		Global $dblink;
		$query ="INSERT INTO usuarios (`id`,`user`,`pass`,`nombre`,`apellido`,`vendedor`,`habilitado`) VALUES ('" . mysqli_real_escape_string($dblink,$this->getid()) . "','" . mysqli_real_escape_string($dblink,$this->getuser()) . "','" . md5(mysqli_real_escape_string($dblink,$this->getpass())) . "','" . mysqli_real_escape_string($dblink,$this->getnombre()) . "','" . mysqli_real_escape_string($dblink,$this->getapellido()) . "','" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "','" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "') ON DUPLICATE KEY UPDATE 
						`user` = '" . mysqli_real_escape_string($dblink,$this->getuser()) . "',
						
						`nombre` = '" . mysqli_real_escape_string($dblink,$this->getnombre()) . "',
						`apellido` = '" . mysqli_real_escape_string($dblink,$this->getapellido()) . "',
						`vendedor` = '" . mysqli_real_escape_string($dblink,$this->getvendedor()) . "',
						`habilitado` = '" . mysqli_real_escape_string($dblink,$this->gethabilitado()) . "';";
		mysqli_query($dblink,$query);

                //`pass` = '" . mysqli_real_escape_string($dblink,$this->getpass()) . "',
                
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

	public function setuser($user='')
	{
		$this->user = $user;
		return true;
	}

	public function getuser()
	{
		return $this->user;
	}

	public function setpass($pass='')
	{
		$this->pass = $pass;
		return true;
	}

	public function getpass()
	{
		return $this->pass;
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

	public function setapellido($apellido='')
	{
		$this->apellido = $apellido;
		return true;
	}

	public function getapellido()
	{
		return $this->apellido;
	}

	public function setvendedor($vendedor='')
	{
		$this->vendedor = $vendedor;
		return true;
	}

	public function getvendedor()
	{
		return $this->vendedor;
	}

	public function sethabilitado($habilitado='')
	{
		$this->habilitado = $habilitado;
		return true;
	}

	public function gethabilitado()
	{
		return $this->habilitado;
	}

} // END class usuarios