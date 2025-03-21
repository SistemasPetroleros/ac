<?php
error_reporting(0);
include_once '../config.php';
include_once '../../model/materiales_solicitudes_estados.php';
include_once '../../model/materiales_cotizaciones_estados.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizacion_solic_prov.php';
include_once '../../model/materiales_cotizacion_items.php';
include_once '../../model/usuario_autoriza_max.php';
include_once '../../model/materiales_solicitudes.php';

$obs = $_POST['obs'];
$idSolicitud = $_POST['idSolicitud'];

$maximos = new usuario_autoriza_max();
$solicitud = new materiales_solicitudes($idSolicitud);
$citems = new materiales_cotizacion_item();
$solestado = new materiales_solicitudes_estados('');
$cotestado = new materiales_cotizaciones_estados('');

//obtener maximo permitido para autorizar usuario login
$maximos->setidUsuario($_SESSION['idUsuario']);
$maximos->setidTipoSolicitud($solicitud->getid_tipo_solicitud());
$resVM = $maximos->getValorMaxUsuario();

$valorMax = 0;
if ($rowvm = mysqli_fetch_assoc($resVM)) {
    $valorMax = $rowvm['valorMax'];
}



//sumar items y ver si no supera el maximo del usuario
//si cumple, cambiar de estados sino mensaje de error



$resultado = $citems->getItemsPedido($idSolicitud);
if ($resultado) {

    if (mysqli_num_rows($resultado) > 0) {

        while ($row = mysqli_fetch_assoc($resultado)) {

            //Cambiar estado a cotizacion (cuando se vaya a cotizar con varios proveedores, ver esto, creo que no funcionaria, xq se deberia rechazar los que no tienen ningun item aprobado)
            $cotestado->setid_cotizacion($row['idCotizacion']);
            $cotestado->setobservaciones($obs);
            $cotestado->setid_estados(10); //Aprobado   
            $r = $cotestado->Create();

            if (!$r) {
                echo "-1";
            } else {

                $cotizacion = new materiales_cotizacion_solic_prov($row['idCotizacion']);
                $cotizacion->setuserModif($_SESSION['user']);
                $cotizacion->setid_estados(10); //Aprobado
                $cotizacion->cambiarEstado();

                //cambiar estado a solicitud 
                $solestado->setid_solicitudes($idSolicitud);
                $solestado->setobservaciones($obs);

                $est = new materiales_solicitudes_estados();
                $re = $est->getRolUserCarga();
                $rowes = mysqli_fetch_assoc($re);

                if ($rowes['id'] == '8') {   //si la carga es desde un user de ortopedia, pasa a ADJUDICADO
                    $solestado->setid_estados(37); //Adjudicando
                } else {
                    //si la carga es desde un user de ospepri pasa a PENDIENTE DE COMPRA
                    $solestado->setid_estados(35); //Pendiente de Compra 
                }

                $solestado->Create();

                echo "1";
            }
        } //while 
    } //if
    else {
        echo "-2";
    }
} else {
    echo "-1";
}
