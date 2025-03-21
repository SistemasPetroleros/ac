<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';



if (isset($_POST['eliminar'])) {
    $obj = new testcon($_POST['id']);
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

    $obj = new testcon($_POST['id']);
    $obj->setdescripcion($_POST['descripcion']);
    $obj->seturl($_POST['url']);
    $obj->setpuerto($_POST['puerto']);
    $obj->setnombre($_POST['nombre']);
    $x = $obj->CreateOrUpdate();
    $mensaje = '<div class="alert alert-success">
                                El registro se guardo correctamente.
                            </div><br>';
}
?>
<script>
    function editar(id, nombre, descripcion, url, puerto)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#descripcion").val(descripcion);
        $("#url").val(url);
        $("#puerto").val(puerto);

        if (id > 0)
        {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
        }
        
    }


</script>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Test de Links</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- subtitulo -->
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
                            <th>Url</th>
                            <th>Puerto</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $obj = new testcon();
                        $array = $obj->SelectAll();

                        while ($x = mysqli_fetch_assoc($array)) {
                            $habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
                            echo '<tr class="odd gradeX"><td>' . $x['id'] . '</td><td>' . $x['nombre'] . '</td><td>' . $x['url'] . '</td><td>' . $x['puerto'] . '</td><td style="width:150px;"><button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-dark btn-round" onclick="editar(\'' . $x['id'] . '\',\'' . $x['nombre'] . '\',\'' . $x['descripcion'] . '\',\'' . $x['url'] . '\',\'' . $x['puerto'] . '\');"> Editar</button></td></tr>';
                        }
                        ?>


                    </tbody>
                </table>

                <!-- /.table-responsive -->
                <div class="well">
                    <h4>Información sobre Test de Links</h4>
                    <p>....</p>

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
                        <input type="hidden" id="id" name="id" value="">
                        <input class="form-control" id="nombre" name="nombre" type="text" required=""> 
                        <!--<p class="help-block">Este Nombre sera mostrado como submenÃº.</p>-->
                    </div>
                    <div class="form-group"> 
                        <label for="url" class="control-label">Url</label> 
                        <input class="form-control" name="url" id="url" type="text" required="">
                    </div>
                    <div class="form-group"> 
                        <label for="puerto" class="control-label">Puerto</label> 
                        <input class="form-control" name="puerto" id="puerto" type="number" required="">
                    </div>
                    <div class="form-group"> 
                        <label for="descripcion" class="control-label">Descripcion</label> 
                        <input class="form-control" name="descripcion" id="descripcion" type="text">
                    </div>
                    
                </div> 
                <div class="modal-footer">
                    <div id="eliminar">
                        <input id="rand" name="rand" type="text" class="form-control pull-left" placeholder="<?= $rand ?>" style="width: 50px;">
                        <input id="rand2" name="rand2" type="hidden" value="<?= $rand ?>">
                    <button type="button" class="btn btn-danger pull-left btn-round" name="eliminar" onclick="guardarForm('testCon.php', 'formulario', 'eliminar');">Eliminar</button>
                    </div>
                    <button type="reset" class="btn btn-default btn-round">Limpiar</button>
                    <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cerrar</button> 
                    <button type="button" class="btn btn-dark btn-round" name="guardar" onclick="guardarForm('testCon.php', 'formulario');">Guardar</button> 
                </div> 
            </form> 
        </div>
    </div>
</div>
</div>
