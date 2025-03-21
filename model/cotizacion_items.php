<?php

class cotizacion_item
{

    /**
     * @var 
     */
    private $id;

    /**
     * @var 
     */
    private $id_item;

    /**
     * @var 
     */
    private $id_proveedores;

    /**
     * @var 
     */
    private $importe_unitario;

    /**
     * @var 
     */
    private $cantidad;

    /**
     * @var 
     */
    private $cantidadAprob;

    /**
     * @var 
     */
    private $marca;

    /**
     * @var 
     */
    private $id_estados;

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

        $query = "SELECT * FROM cotizacion_items WHERE `id`='{$this->getid()}'";

        $result = mysqli_query($dblink, $query);

        while ($row = mysqli_fetch_assoc($result))
            foreach ($row as $key => $value) {
                $column_name = str_replace('-', '_', $key);
                $this->{"set$column_name"}($value);
            }
    }


    public function create()
    {
        global $dblink;
        $query = "INSERT INTO cotizacion_items (	`id_item`,
                                                `id_proveedores`,
                                                `importe_unitario`,
                                                `cantidad`,
                                                `marca`,
                                                `id_estados`,
                                                `userAlta` ) VALUES (" . mysqli_real_escape_string($dblink, $this->getid_item()) . ","
            . mysqli_real_escape_string($dblink, $this->getid_proveedores()) . ","
            . mysqli_real_escape_string($dblink, $this->getimporte_unitario()) . ","
            . mysqli_real_escape_string($dblink, $this->getcantidad()) . ",'"
            . mysqli_real_escape_string($dblink, $this->getmarca()) . "',"
            . mysqli_real_escape_string($dblink, $this->getid_estados()) . ",'"
            . $_SESSION["user"] . "');";

        //  echo $query;

        $res = mysqli_query($dblink, $query);
        if ($res) {
            $id = mysqli_insert_id($dblink);
            return $id;
        } else
            return 0;
    }

    public function save()
    {
        global $dblink;

        $set = "";

        if ($this->getimporte_unitario() != "") {
            $set .= " `importe_unitario`=" . mysqli_real_escape_string($dblink, $this->getimporte_unitario()) . ", ";
        }

        if ($this->getcantidad() != "") {
            $set .= " `cantidad`=" . mysqli_real_escape_string($dblink, $this->getcantidad()) . ", ";
        }


        if ($this->getmarca() != "") {
            $set .=  "`marca`='" . mysqli_real_escape_string($dblink, $this->getmarca()) . "', ";
        }

        if ($this->getid_estados() != "") {
            $set .= " `id_estados`=" . mysqli_real_escape_string($dblink, $this->getid_estados()) . ", ";
        }


        if ($this->getcantidadAprob() != "") {
            $set .= " `cantidadAprob`=" . mysqli_real_escape_string($dblink, $this->getcantidadAprob()) . ", ";
        } else {
            $set .= " `cantidadAprob`=NULL, ";
        }


        $query = "UPDATE cotizacion_items 
                  SET " . $set . "
                        `userModif` ='" . $_SESSION['user'] . "',
                        `fechaModif`= now()
                  WHERE id=" . mysqli_real_escape_string($dblink, $this->getid());

        $res = mysqli_query($dblink, $query);

        return $res;
    }

    public function  getItemsCotizacion($idSolicitud, $idProveedor)
    {
        global $dblink;


        $where = " si.id_solicitudes=" . mysqli_real_escape_string($dblink, $idSolicitud);
        if ($idProveedor != 0 and $idProveedor != "") {

            $where .= " and ci.id_proveedores=" . mysqli_real_escape_string($dblink, $idProveedor);
        }





        $sql = "SELECT si.id idItem, si.id_solicitudes, si.id_producto, p.nombre,  si.cantidad cantSolicitada, ci.id idItemCot, ci.marca,
                ci.id_proveedores, ci.importe_unitario, ci.cantidad cantCotizada, ifnull(ci.cantidadAprob,0) cantidadAprob , ci.marca, ci.id_estados, pv.nombre proveedor,
                (ci.cantidad*ci.importe_unitario) total, mc.id idCotizacion,  (ifnull(ci.cantidadAprob,0)*ci.importe_unitario) totalAprob, e.nombre estado, e1.nombre estadoItem
        FROM solicitudes_items si
            INNER JOIN productos p ON si.id_producto=p.id
            INNER JOIN cotizacion_items ci ON ci.id_item=si.id
            INNER JOIN proveedores pv ON pv.id=ci.id_proveedores
            INNER JOIN cotizacion_solic_prov mc ON (mc.id_solicitudes=si.id_solicitudes AND mc.id_proveedores=pv.id)
            INNER JOIN estados e ON e.id=mc.id_estados
            INNER JOIN estados e1 on e1.id=ci.id_estados
        WHERE " . $where . "
        ORDER BY si.id";



        $result = mysqli_query($dblink, $sql);
        //  echo $sql;
        return $result;
    }

    public function existenItemsCotizaciones($idSolicitud, $idProveedor)
    {
        global $dblink;

        $sql = "  SELECT COUNT(*) cant
                FROM cotizacion_items ci
                    INNER JOIN solicitudes_items si ON ci.id_item=si.id
                WHERE si.id_solicitudes=" . $idSolicitud." and id_proveedores=".$idProveedor;
        $result = mysqli_query($dblink, $sql);

        $row = mysqli_fetch_assoc($result);

        if ($row['cant'] > 0) return true;
        else return false;
    }

    public function insertarItemsCotizacionesPorQuery($idSolicitud, $idProveedor)
    {
        global $dblink;

        $sql = " INSERT INTO cotizacion_items (  `id_item`,
                                                            `id_proveedores`,
                                                            `importe_unitario`,
                                                            `cantidad` ,
                                                            `cantidadAprob` ,
                                                            `marca`,
                                                            `id_estados`,
                                                            `userAlta` ,
                                                            `fechaAlta` ,
                                                            `userModif` ,
                                                            `fechaModif`)
                    SELECT mi.id id_item, mc.id_proveedores, '0' importe_unitario,  mi.cantidad, NULL cantidadAprob, '' marca, '39' id_estados, '" . $_SESSION["user"] . "' userAlta, 
                           NOW() fechaAlta, NULL userModif, NULL fechaModif
                    FROM  cotizacion_solic_prov mc
                          INNER JOIN solicitudes_items mi ON mc.id_solicitudes=mi.id_solicitudes
                    WHERE mc.id_solicitudes=" . mysqli_real_escape_string($dblink, $idSolicitud) . " and mc.id_proveedores=".mysqli_real_escape_string($dblink, $idProveedor)."
                    GROUP BY  mc.id_proveedores, mc.id , mi.id;";

        $result = mysqli_query($dblink, $sql);
        //echo $sql;
        return $result;
    }





    public function existeItem()
    {
        global $dblink;

        $sql = " SELECT *
                FROM cotizacion_items ci
                WHERE ci.id_item=" . mysqli_real_escape_string($dblink, $this->getid_item()) . " AND ci.id_proveedores=" . mysqli_real_escape_string($dblink, $this->getid_proveedores());

        $result = mysqli_query($dblink, $sql);
        if (mysqli_num_rows($result) > 0)
            return true;
        else return false;
    }


    public function devolverEstadoItems($data)
    {
        global $dblink;

        $sql = " SELECT ci.id_estados 
                FROM cotizacion_items ci 
                INNER JOIN cotizacion_solic_prov csp ON csp.id_proveedores=ci.id_proveedores
                INNER JOIN solicitudes_items si  ON si.id=ci.id_item
                    WHERE ci.id_proveedores=" . mysqli_real_escape_string($dblink, $data['idProveedor']) . " AND csp.id_solicitudes=" . mysqli_real_escape_string($dblink, $data['idSolicitud']) . " AND csp.id_solicitudes=si.id_solicitudes
                    LIMIT 0,1";

        $result = mysqli_query($dblink, $sql);
        // echo $sql;
        return $result;
    }

    /* public function estadoCotizacion($idProveedor)
    {
        global $dblink;

        $sql = " SELECT DISTINCT  id_estados, COUNT(id_estados) cant 
            FROM cotizacion_items ci
            WHERE ci.id_proveedores=" . mysqli_real_escape_string($dblink, $idProveedor) . " 
            GROUP BY id_estados";

        $result = mysqli_query($dblink, $sql);
        //  echo $sql;
        return $result;
    }*/


    public function cantidadTotalAprobada($idItem)
    {
        global $dblink;

        $sql = " SELECT ifnull(SUM(cantidadAprob),0) suma, ifnull(si.cantidad,0) cantidad
                    FROM cotizacion_items  ci
                        INNER JOIN solicitudes_items si ON ci.id_item=si.id
                    WHERE  id_item=" . $idItem;

        $result = mysqli_query($dblink, $sql);
        //     echo $sql;
        return $result;
    }

    public function getItemsPedido($idSolicitud)
    {
        global $dblink;

        $sql = " SELECT si.id idItem, si.id_solicitudes, si.id_producto, p.nombre, p.presentacion, ifnull(m.descripcion,'') monodroga, si.cantidad cantSolicitada, ci.id idItemCot,
        ci.id_proveedores, ci.importe_unitario, ci.cantidad cantCotizada, ci.cantidadAprob, ci.marca, ci.id_estados, pv.nombre proveedor,
        (ci.cantidad*ci.importe_unitario) total, (ci.cantidadAprob*ci.importe_unitario) totalAprob, c.id idCotizacion
                FROM solicitudes_items si
                    INNER JOIN productos p ON si.id_producto=p.id
                    LEFT JOIN ab_monodro m ON m.codigo=p.cod_droga
                    INNER JOIN cotizacion_items ci ON ci.id_item=si.id
                    INNER JOIN proveedores pv ON pv.id=ci.id_proveedores
                    INNER JOIN cotizacion_solic_prov c ON (c.id_solicitudes=si.id_solicitudes AND c.id_proveedores=pv.id)
                WHERE si.id_solicitudes=" . mysqli_real_escape_string($dblink, $idSolicitud) . " AND ci.cantidadAprob IS NOT NULL 
                ORDER BY si.id";

        $result = mysqli_query($dblink, $sql);
     //   echo $sql;
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


    public function setid_item($id_item = '')
    {
        $this->id_item = $id_item;
        return true;
    }

    public function getid_item()
    {
        return $this->id_item;
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

    public function setimporte_unitario($importe_unitario = '')
    {
        $this->importe_unitario = $importe_unitario;
        return true;
    }

    public function getimporte_unitario()
    {
        return $this->importe_unitario;
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

    public function setcantidad($cantidad = '')
    {
        $this->cantidad = $cantidad;
        return true;
    }

    public function getcantidad()
    {
        return $this->cantidad;
    }

    public function setmarca($marca = '')
    {
        $this->marca = $marca;
        return true;
    }

    public function getmarca()
    {
        return $this->marca;
    }


    public function setcantidadAprob($cantidadAprob = '')
    {
        $this->cantidadAprob = $cantidadAprob;
        return true;
    }

    public function getcantidadAprob()
    {
        return $this->cantidadAprob;
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
}
