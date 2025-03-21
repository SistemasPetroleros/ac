<?php
include 'class.php';
include 'login.php';
include 'model/usuario.php';
include 'model/usuario_tipo_solicitud.php';

$rand = rand(100, 999);
$mensaje = '';




    $obj = new usuarios();
    $obj->setid($_GET['idUsuarioRoles']);
    $obj->Load();
	
	
	error_reporting(-1);
    

?>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Usuario:<?= $obj->getnombre() ?> <?= $obj->getapellido() ?> [<?= $obj->getuser() ?>]</h3>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Asignacion de Tipos Solicitudes a Usuarios. 
                
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
<?php

$usuario_tipo_solicitud=new usuario_tipo_solicitud();
$usuario_tipo_solicitud->setidUsuario($_GET['idUsuarioRoles']);
if ($usuario_tipo_solicitud->getidUsuario()>0){
if(isset($_GET['idRolAgregar'])){
    $usuario_tipo_solicitud->setidTipoSolicitud($_GET['idRolAgregar']);
    $usuario_tipo_solicitud->Create();        
}
if(isset($_GET['idRolQuitar'])){
    $usuario_tipo_solicitud->setidTipoSolicitud($_GET['idRolQuitar']);
    $usuario_tipo_solicitud->Delete();        
}

?>
<div class="col-md-4">        
        <table width="100%" class="table jambo_table">
            <tr><th>Tipos de Solicitudes Disponibles</th><th></th></tr>
<?php

$array=$usuario_tipo_solicitud->SelectAllNoRelacionados();
    
    while ($x = mysqli_fetch_assoc($array)) 
    {
		
		
        echo '<tr><td>'.$x['nombre'].'</td><td align="right"><a href="#" class="btn btn-dark btn-round"  onclick="menu(\'usuarios_tipoSolicitud.php?idUsuarioRoles='.$_GET['idUsuarioRoles'].'&idRolAgregar='.$x['id'].'\', \'' . $x['nombre'] . '\', \'242\',0); return false;"> > </a></td></tr>';
    }
   
    
 ?>
</table>
</div>
        
<div class="col-md-1"></div>        
        
<div class="col-md-4">        
        <table width="100%" class="table jambo_table">
            <tr><th>Tipos Solicitudes Autorizados</th><th></th></tr>
<?php    
    $array=$usuario_tipo_solicitud->SelectAllRelacionados();
    
    while ($x = mysqli_fetch_assoc($array)) 
    {
		
		
        echo '<tr><td><a href="#" class="btn btn-dark btn-round"  onclick="menu(\'usuarios_tipoSolicitud.php?idUsuarioRoles='.$_GET['idUsuarioRoles'].'&idRolQuitar='.$x['id'].'\', \'' . $x['nombre'] . '\', \'242\',0); return false;"> < </a></td><td>'.$x['nombre'].'</td></tr>';
    }
   
    
 ?>
</table>
</div>        
<?php } ?>
                
                

    
