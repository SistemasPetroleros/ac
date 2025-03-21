<?php
header( 'Content-type: text/html; charset=iso-8859-1' );
include_once '../config.php';
include_once '../../model/solicitudes.php';
include_once '../../model/personas.php';
include_once '../../model/puntos_dispensa.php';
include_once '../../model/productos.php';
include_once '../funciones.php';

$search = isset($_POST['service']) ? $_POST['service'] : '';
$inputS = isset($_POST['inputS']) ? $_POST['inputS'] : '';

$producto = new productos();
$array = $producto->SelectAllItemBuscar($search);
$cnt=0;
$sugerencia='';
while ($x = mysqli_fetch_assoc($array)) {
    //$sugerencia .= '<div class="suggest-element" data="'.$x['nombre'].'" data2="'.$x['id'].'" data3="'.$x['codigo'].'"  descripcion="'.$x['descripcion'].'" precio="'.$x['precio'].'" editaprecio="'.$x['editaprecio'].'" unidades="'.$x['unidades'].'" habilitado="'.$x['habilitado'].'" codbarra="'.$x['codbarra'].'" id="service'.$x['id'].'"><a tabindex="1">['.($x['codigo']).'] '.($x['nombre']).' $ '.($x['precio']).'</a></div>';
    
    $sugerencia .= '<a href="#" class="list-group-item suggest-element" nombre="'.strtoupper($x['nombre']).'" dataid="'.$x['id'].'" troquel="'.$x['troquel'].'"  presentacion="'.ucwords($x['presentacion']).'" monodroga="'.ucwords($x['monodroga']).'" id="service'.$x['id'].'" "><span class="badge"> '.ucwords($x['monodroga']).'</span><b>'.strtoupper($x['nombre']).'</b> '.ucwords($x['presentacion']).'</a>';
    
    $cnt += 1;
    $script = "<script>
                    newProd('".$inputS."', '".$x['id']."', '".$x['troquel']."', '".strtoupper($x['nombre'])."', '".ucwords($x['presentacion'])."', '".ucwords($x['monodroga'])."');
                    $('#nuevoItem".$inputS."').val('');
               </script>";
    }
    
    $sugerencia .= "<script>$('#suggestionsnuevoItem'+".$inputS.").fadeIn(100);</script>";

    
if ($cnt==0){
    $sugerencia .= '<span class="list-group-item">No se encontraron coincidencias.</span>';
    echo $sugerencia;
}elseif ($cnt==1){
    echo $script;
}else{
    echo $sugerencia;
    
}
