<?php

/******************************************************************************
* Class for ospepri_altocosto.solicitudes_items_traza_estados
*******************************************************************************/

class SolicitudesItemTrazaEstados
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
	private $id_item_traza;

    /**
	* @var 
	*/
	private $id_estado;


		/**
	* @var 
	*/
	private $fdesde;

		/**
	* @var 
	*/
	private $fhasta;


		/**
	* @var 
	*/
	private $observaciones;

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
		$query = "SELECT * FROM solicitudes_items_traza_estados WHERE `id`='{$this->getid()}'";

		//echo $query;
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
		$query = "SELECT * FROM solicitudes_items_traza_estados ";

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}

	/*public function SelectEstadosItem($parametros)
	{
		Global $dblink;
		$query = "	SELECT t.*, DATE_FORMAT(fdesde,'%d/%m/%Y %H:%i:%s') fechaDesde, e.nombre estado
					FROM solicitudes_items_traza_estados t
					INNER JOIN estados e ON e.id=t.id_estado
					WHERE t.id_solicitud=".$parametros['idSolicitud']."
					      AND t.id_item=".$parametros['idItem']."
						  AND t.id_item_traza=".$parametros['idItemTraza']."
					ORDER BY t.fdesde DESC ";

		echo $query;			

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}*/

	public function SelectEstadosItem($parametros)
	{

		Global $dblink;
		$query = "	SELECT t.*, DATE_FORMAT(fdesde,'%d/%m/%Y %H:%i:%s') fechaDesde, e.nombre estado
					FROM solicitudes_items_traza_estados t
					INNER JOIN estados e ON e.id=t.id_estado
					WHERE t.id_solicitud=".$parametros['idSolicitud']."
						  AND t.id_item_traza=".$parametros['idItemTraza']." and t.id_estado IN (".$parametros['idEstados'].")
					ORDER BY t.id DESC, t.fdesde DESC ";

		//echo $query;			

		$result = mysqli_query($dblink,$query);

		
	return $result;

	}


    public function SelectItemsTraza($parametros)
	{
		Global $dblink;

        $where=" WHERE (1=1) ";
        if($parametros['idItem']!=NULL AND $parametros['idItem']!=""){
           $where.=" AND sit.id_item=".$parametros['idItem'];
        }

        if($parametros['idSolicitud']!=NULL AND $parametros['idSolicitud']!=""){
            $where.=" AND sit.id_solicitud=".$parametros['idSolicitud'];
         }

         if($parametros['idEstado']!=NULL AND $parametros['idEstado']!=""){
            $where.=" AND sit.id_estado IN(".$parametros['idEstado'].")";
         }

		$query = "SELECT sit.*, p.nombre nombreProd, p.presentacion, p.gtin gtinProd, site.nombre estado, sit.gtin gtinItem
        FROM solicitudes_items_traza sit 
                INNER JOIN solicitudes_items si ON (si.id=sit.id_item) 
                INNER JOIN solicitudes s ON (s.id=si.id_solicitudes AND sit.id_solicitud=s.id) 
                INNER JOIN productos p ON (p.id=si.id_producto) 
                INNER JOIN solicitudes_items_traza_estados site ON (site.id=sit.id_estado)
       ".$where;

		$result = mysqli_query($dblink,$query);

	//	echo $query;
	return $result;

	}

	public function Save()
	{
		Global $dblink;


		$query = "UPDATE solicitudes_items_traza_estados SET 
							`fhasta`='".mysqli_real_escape_string($dblink,$this->getfhasta())."', 
							`userModif` = '" . mysqli_real_escape_string($dblink,$this->getuserModif()) . "',
							`fechaModif` = now() 
							WHERE `id`='{$this->getid()}'";

	   //echo $query;

		mysqli_query($dblink,$query);

		
	}

	public function Create()
	{
		Global $dblink;


		$fhasta= mysqli_real_escape_string($dblink,$this->getfhasta()); 
		if($fhasta!="" && $fhasta!=null){
           $fhasta="'".$fhasta."'";
		}
		else{
			$fhasta='NULL'; 
		}

		$id_item= mysqli_real_escape_string($dblink,$this->getid_item()); 
		if($id_item=="") $id_item='NULL';

		$query ="INSERT INTO solicitudes_items_traza_estados (	`id_solicitud` ,
                            `id_item` ,
							`id_item_traza` ,
                            `id_estado` ,
                            `fdesde`,
                            `fhasta` ,
							`observaciones` ,
                            `userAlta`,
                            `fechaAlta`) VALUES (" . mysqli_real_escape_string($dblink,$this->getid_solicitud()) .",".
                                                $id_item.",". 
												mysqli_real_escape_string($dblink,$this->getid_item_traza()).",". 
                                                mysqli_real_escape_string($dblink,$this->getid_estado()).",'".
                                                mysqli_real_escape_string($dblink,$this->getfdesde())."',".
                                                $fhasta.",'".
												mysqli_real_escape_string($dblink,$this->getobservaciones())."','".
                                                mysqli_real_escape_string($dblink,$this->getuserAlta())."',now())";
		
        
       mysqli_query($dblink,$query);

      // echo $query;

		
	}

	public function Delete()
	{
		Global $dblink;
		$query = "DELETE FROM solicitudes_items_traza
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

	public function setid_solicitud($idSolicitud='')
	{
		$this->id_solicitud = $idSolicitud;
		return true;
	}

	public function getid_solicitud()
	{
		return $this->id_solicitud;
	}


    public function setid_item($idItem='')
	{
		$this->id_item = $idItem;
		return true;
	}

	public function getid_item()
	{
		return $this->id_item;
	}

	public function setid_item_traza($idItemTraza='')
	{
		$this->id_item_traza = $idItemTraza;
		return true;
	}

	public function getid_item_traza()
	{
		return $this->id_item_traza;
	}






    public function setid_estado($idEstado='')
	{
		$this->id_estado = $idEstado;
		return true;
	}

	public function getid_estado()
	{
		return $this->id_estado;
	}

    
    public function setfdesde($fdesde='')
	{
		$this->fdesde = $fdesde;
		return true;
	}

	public function getfdesde()
	{
		return $this->fdesde;
	}


	    
    public function setfhasta($fhasta='')
	{
		$this->fhasta = $fhasta;
		return true;
	}

	public function getfhasta()
	{
		return $this->fhasta;
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

} // END class Trazabilidad