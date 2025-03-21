<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';




if (isset($_POST['guardar'])) {
    echo 'tratando de guardar';
    if (md5($_POST['ant']) == $u->getpass()) {
        if ($_POST['nue'] === $_POST['renue']) {
            echo 'guardando';
            $u->setpass($_POST['nue']);
            $u->SavePass();
            $mensaje = '<div class="alert alert-success">
                                La contraseña fue cambiada correctamente.
                            </div><br>';
            redirJS('index.php');
        } else {
            $mensaje = '<div class="alert alert-danger">
                                La nueva contraseña no coincide con la reingresada.
                            </div><br>';
        }
    } else {
        $mensaje = '<div class="alert alert-danger">
                                La contraseña anterior no coincide.
                            </div><br>';
    }
}
?>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Usuario</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- subtitulo --><?= $u->getnombre() ?> <?= $u->getapellido() ?> [<?= $u->getuser() ?>]

            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" action="" method="post" id="formulario"> 
                    <div class="modal-body"> 

                        <div class="form-group"> 
                            <label for="nombre" class="control-label">Contraseña Anterior</label> 
                            <input class="form-control" id="ant" name="ant" type="password" required=""> 
                        </div> 
                        <div class="form-group"> 
                            <label for="descripcion" class="control-label">Nueva Contraseña</label> 
                            <input class="form-control" name="nue" id="nue" type="password" required="">
                        </div>
                        <div class="form-group"> 
                            <label for="descripcion" class="control-label">Repetir Contraseña</label> 
                            <input class="form-control" name="renue" id="renue" type="password" required="">
                        </div>

                    </div> 
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-default btn-round" onclick="editar();">Limpiar</button>
                        <button type="button" onclick="guardarForm('cambiarpass.php', 'formulario');" class="btn btn-dark btn-round" name="guardar">Guardar</button> 
                    </div> 
                </form> 

                <!-- /.table-responsive -->
                <div class="well">
                    <h4>Información sobre Usuario</h4>
                    <p></p>

                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>




</div>

<?php

?>