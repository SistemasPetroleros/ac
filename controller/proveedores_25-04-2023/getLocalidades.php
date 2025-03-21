<?php

include_once '../config.php';
include_once '../../model/localidades.php';



$objLoc= new localidades();

$res= $objLoc -> SelectXProv($_POST['id_provincia']);

$opt="";
while ($xl = mysqli_fetch_assoc($res)) {
        $opt.= $xl['id'].'@'.($xl['nombre']).'@';
}
                              
echo $opt;