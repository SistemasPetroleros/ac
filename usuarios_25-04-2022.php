<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';



if (isset($_POST['eliminar'])) {
    $obj = new usuarios();
    $obj->setid($_POST['idUs']);
    $obj->Load();
    if ($_POST['rand'] == $_POST['rand2']) {
        $obj->Delete();
        $mensaje = '<div class="alert alert-success">
                                El registro se borro correctamente.
                            </div>';
    } else {
        $mensaje = '<div class="alert alert-danger">
                                Debe reingresar el numero que aparece a la izquierda del boton Eliminar
                            </div>';
    }
}

if (isset($_POST['guardar'])) {
    
    $obj = new usuarios();
    $obj->setid($_POST['idUs']);
    $obj->Load();
    $obj->setapellido($_POST['apellido']);
    $obj->setnombre($_POST['nombre']);
    $obj->setuser($_POST['user2']);
    $obj->setpass($_POST['pass2']);
    $obj->sethabilitado($_POST['habilitado']);
    $obj->setvendedor($_POST['vendedor']);
    $x = $obj->CreateOrUpdate();
    $mensaje = '<div class="alert alert-success">
                                El registro se guardo correctamente.
                            </div><br>';
    if (strlen($_POST['pass2'])>0 and $_POST['idUs']>0)
        {
            $obj->SavePass();
            $mensaje = '<div class="alert alert-success">
                                La contraseña fue correctamente modificada.
                            </div>';
        }
    }

?>
<script>
    function editar(idUs, apellido, nombre, user2, pass2, vendedor=1, habilitado=1)
    {
        $("#nombre").val(nombre);
        $("#apellido").val(apellido);
        $("#idUs").val(idUs);
        $("#user2").val(user2);
        $("#pass2").val('');
        $("#vendedor").val(vendedor).change();
        $("#habilitado").val(habilitado).change();
        
        if (idUs>0){$("#eliminar").show();}else{$("#eliminar").hide();}
    }


</script>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Usuarios</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Usuarios en sistema de Alto Costo.
                <span class="pull-right">
                    <button type="button" class="btn btn-dark btn-circle btn-lg " data-toggle="modal" data-target=".bs-example-modal-lg" onclick="editar();"><i class="fa fa-plus"></i>
                    </button>
                </span>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table jambo_table" id="dataTable1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Usuario</th>
                            <th>Cajero</th>
                            <th>Habilit.</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$obj = new usuarios();
$array = $obj->SelectAll();

while ($x = mysqli_fetch_assoc($array)) {
    $vend = ($x['vendedor']==1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
    $habi = ($x['habilitado']==1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
    echo '<tr class="odd gradeX"><td>' . $x['id'] . '</td><td>' . $x['nombre'] . '</td><td>' . $x['apellido'] . '</td><td>' . $x['user'] . '</td><td>' . $vend . '</td><td>' . $habi . '</td><td style="width:300px;"><button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-dark btn-round" onclick="editar(\'' . $x['id'] . '\',\'' . $x['apellido'] . '\',\'' . $x['nombre'] . '\',\'' . $x['user'] . '\',\'' . $x['pass'] . '\',\'' . $x['vendedor'] . '\',\'' . $x['habilitado'] . '\');"> Editar</button>&nbsp;&nbsp;<a href="#" class="btn btn-dark btn-round" onclick="menu(\'usuarios_roles.php?idUsuarioRoles=' . $x['id'] . '\', \'\', \'242\',0); return false;">Roles</a>&nbsp;&nbsp;<a href="#" class="btn btn-dark btn-round" onclick="menu(\'usuarios_estados.php?idUsuarioRoles=' . $x['id'] . '\', \'\', \'242\',0); return false;">Estados</a>&nbsp;&nbsp;<a href="#" class="btn btn-dark btn-round" onclick="menu(\'usuarios_ptosdispensa.php?idUsuarioRoles=' . $x['id'] . '\', \'\', \'242\',0); return false;">Ptos. Dispensa</a></td></tr>';
}
?>


                    </tbody>
                </table>

                <!-- /.table-responsive -->
                <div class="well">
                    <h4>Información sobre Usuarios</h4>
                    <p>Los Usuarios en esta seccion podran luego desde su menu personal cambiar sus datos personales.</p>

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
            <form role="form" action="" method="post" id="formulario"> 
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
                        <label>Es Cajero</label>
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
                    <button type="button" class="btn btn-danger pull-left btn-round" name="eliminar" onclick="guardarForm('usuarios.php', 'formulario', 'eliminar');">Eliminar</button>
                    </div>
                    <button type="reset" class="btn btn-default btn-round">Limpiar</button>
                    <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cerrar</button> 
                    <button type="button" class="btn btn-dark btn-round" name="guardar" onclick="guardarForm('usuarios.php', 'formulario');">Guardar</button> 
                </div> 
            </form> 
        </div>
    </div>
</div>
</div>
