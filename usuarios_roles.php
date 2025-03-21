<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';




    $obj = new usuarios();
    $obj->setid($_GET['idUsuarioRoles']);
    $obj->Load();
    

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
                Asignacion de Roles a Usuarios. 
                
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
<?php
$usuarios_roles=new usuarios_roles();
$usuarios_roles->setid_usuarios($_GET['idUsuarioRoles']);
if ($usuarios_roles->getid_usuarios()>0){
if(isset($_GET['idRolAgregar'])){
    $usuarios_roles->setid_roles($_GET['idRolAgregar']);
    $usuarios_roles->Create();        
}
if(isset($_GET['idRolQuitar'])){
    $usuarios_roles->setid_roles($_GET['idRolQuitar']);
    $usuarios_roles->Delete();        
}

?>
<div class="col-md-4">        
        <table width="100%" class="table jambo_table">
            <tr><th>Roles Disponibles</th><th></th></tr>
<?php

$array=$usuarios_roles->SelectAllNoRelacionados();
    
    while ($x = mysqli_fetch_assoc($array)) 
    {
        echo '<tr><td>'.$x['nombre'].'</td><td align="right"><a href="#" class="btn btn-dark btn-round"  onclick="menu(\'usuarios_roles.php?idUsuarioRoles='.$_GET['idUsuarioRoles'].'&idRolAgregar='.$x['id'].'\', \'' . $x['nombre'] . '\', \'242\',0); return false;"> > </a></td></tr>';
    }
   
    
 ?>
</table>
</div>
        
<div class="col-md-1"></div>        
        
<div class="col-md-4">        
        <table width="100%" class="table jambo_table">
            <tr><th>Roles Autorizados</th><th></th></tr>
<?php    
    $array=$usuarios_roles->SelectAllRelacionados();
    
    while ($x = mysqli_fetch_assoc($array)) 
    {
        echo '<tr><td><a href="#" class="btn btn-dark btn-round"  onclick="menu(\'usuarios_roles.php?idUsuarioRoles='.$_GET['idUsuarioRoles'].'&idRolQuitar='.$x['id'].'\', \'' . $x['nombre'] . '\', \'242\',0); return false;"> < </a></td><td>'.$x['nombre'].'</td></tr>';
    }
   
    
 ?>
</table>
</div>        
<?php } ?>
                
                
<div class="col-md-12"><br><br><br><br><br></div>                

                <!-- /.table-responsive -->
                <div class="well col-md-12" >
                    <h4>Información sobre Roles</h4>
                    <!--<p>Para visualizar las paginas que agrupa cada <b>Rol</b> ingrese <a href='roles.php'>Aquí</a>.</p>-->

                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


    

<!--MODAL-->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="Editar">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
                <h4 class="modal-title" id="exampleModalLabel">Editar</h4> </div> 
            <form role="form" action="" method="post"> 
                <div class="modal-body"> 

                    <div class="form-group"> 
                        <label for="nombre" class="control-label">Nombre</label> 
                        <input type="hidden" id="idUs" name="idUs" value="">
                        <input class="form-control" id="nombre" name="nombre" type="text"> 
                        <!--<p class="help-block">Este Nombre sera mostrado como submenú.</p>-->
                    </div> 
                    <div class="form-group"> 
                        <label for="apellido" class="control-label">Apellido</label> 
                        <input class="form-control" name="apellido" id="apellido">
                    </div>
                    <div class="form-group"> 
                        <label for="user2" class="control-label">Usuario</label> 
                        <input class="form-control" name="user2" id="user2">
                    </div>
                    <div class="form-group"> 
                        <label for="pass2" class="control-label">Contraseña</label> 
                        <input class="form-control" name="pass2" id="pass2" type="password">
                        <p class="help-block">Dejar en blanco si no se desea modificar la contraseña.</p>
                    </div>
                    <div class="form-group">
                        <label>Es Vendedor</label>
                        <select class="form-control" name="vendedor" id="vendedor">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Habilitado</label>
                        <select class="form-control" name="habilitado" id="habilitado">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>
                </div> 
                <div class="modal-footer"> 
                    <div id="eliminar">
                        <input id="rand" name="rand" type="text" class="form-control pull-left" placeholder="<?= $rand ?>" style="width: 50px;">
                        <input id="rand2" name="rand2" type="hidden" value="<?= $rand ?>">
                    <button type="submit" class="btn btn-danger pull-left" name="eliminar">Eliminar</button>
                    </div>
                    <button type="reset" class="btn btn-default">Limpiar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> 
                    <button type="submit" class="btn btn-dark" name="guardar">Guardar</button> 
                </div> 
            </form> 
        </div>
    </div>
</div>
</div>

