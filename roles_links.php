<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';


if (isset($_GET['idRoles'])){

$obj = new roles($_GET['idRoles']);
    

?>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Links habilitados en Rol:<?= $obj->getnombre() ?> </h3>
        <p><?= $obj->getdescripcion() ?></p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Asignacion de links habilitados en cada Rol. 
                
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
<?php
$roles_links=new roles_links();
$roles_links->setid_roles($_GET['idRoles']);
if ($obj->getid() > 0){
if(isset($_GET['idLinkAgregar'])){
    $roles_links->setid_links($_GET['idLinkAgregar']);
    (isset($_GET['sololec'])) ? $roles_links->setedita(0) : $roles_links->setedita(1);
    $roles_links->Create();        
}
if(isset($_GET['idLinkQuitar'])){
    $roles_links->setid_links($_GET['idLinkQuitar']);
    $roles_links->Delete();        
}

?>
<div class="col-md-4">        
        <table width="100%" class="table jambo_table">
            <tr><th>Links Disponibles</th><th></th></tr>
<?php

$array = $roles_links->SelectAllNoRelacionados();

    while ($x = mysqli_fetch_assoc($array)) {
        echo '<tr><td>' . $x['nombre'] . '</td><td align="right">'
        . '<form class="form-inline" method="get" id="formulario' . $x['id'] . '">
            <div class="form-group">
                <input type="hidden" class="form-control" id="idRoles" name="idRoles" value="' . $_GET['idRoles'] . '">
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" id="idLinkAgregar" name="idLinkAgregar" value="' . $x['id'] . '">
            </div>
            <div class="checkbox">
                <label>
                    Solo Lectura <input type="checkbox" id="sololec" name="sololec"> 
                </label>
            </div>
            <button type="button" class="btn btn-dark btn-round" onclick="guardarForm(\'roles_links.php\', \'formulario' . $x['id'] . '\', \'\', \'GET\');"> > </button> 
            </form></td></tr>';
    }
    ?>
</table>
</div>
                
<div class="col-md-1"></div>        
        
<div class="col-md-4">        
        <table width="100%" class="table jambo_table">
            <tr><th>Links Autorizados</th><th></th></tr>
<?php    
    $array=$roles_links->SelectAllRelacionados();
    
    while ($x = mysqli_fetch_assoc($array)) 
    {
        echo '<tr><td><a href="#" class="btn btn-dark btn-round" onclick="menu(\'roles_links.php?idRoles='.$_GET['idRoles'].'&idLinkQuitar='.$x['id'].'\', \'Link x Rol\', \'236\',0); return false;"> < </a>&nbsp;&nbsp;';
        if ($x['edita'] == '1'){ echo '<i class="fa fa-pencil" aria-hidden="true"></i>';}else{ echo '<i class="fa fa-eye" aria-hidden="true"></i>';}
        echo '</td><td>'.$x['nombre'].'</td></tr>';
    }
   
    
    
 ?>
</table>
</div>        
<?php } ?>
                
                
<div class="col-md-12"><br><br><br><br><br></div>                

                <!-- /.table-responsive -->
                <div class="well col-md-12" >
                    <h4>Información sobre Links</h4>
                    <p>Para visualizar los roles asignados a cada usuario debe ingresar <a href='usuarios.php'>Aquí</a>.</p>

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

<?php } ?>