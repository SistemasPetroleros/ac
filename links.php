<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';



if (isset($_POST['eliminar'])) {
    $obj = new links($_POST['idLink']);
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

    $obj = new links($_POST['idLink']);
    $obj->seturl($_POST['link']);
    $obj->setnombre($_POST['nombre']);
    $obj->setid_menu($_POST['idMenu']);
    $obj->setorden($_POST['orden']);
    $x = $obj->CreateOrUpdate();
    $mensaje = '<div class="alert alert-success">
                                El registro se guardo correctamente.
                            </div>';
}
?>
<script>
    function editar(idLink, link, nombre, menu, orden)
    {
        $("#nombre").val(nombre);
        $("#link").val(link);
        $("#idLink").val(idLink);
        $("#idMenu").val(menu).change();
        $("#orden").val(orden);

        if (idLink > 0) {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
        }
    }


</script>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Links</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Link disponibles en el sistema.
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
                            <th>Menú</th>
                            <th>Orden</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $obj = new links();
                        $array = $obj->SelectAll();
                        $linksRegistrados = array();

                        while ($x = mysqli_fetch_assoc($array)) {
                            array_push($linksRegistrados, $x['url']);
                            echo '<tr class="odd gradeX"><td>' . $x['id'] . '</td><td>' . $x['nombre'] . '</td><td><a href="' . $x['url'] . '" target="_blank">' . $x['url'] . '</a></td><td>' . $x['menu'] . '</td><td>' . $x['orden'] . '</td><td style="width:100px;"><button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-dark btn-round" onclick="editar(\'' . $x['id'] . '\',\'' . $x['url'] . '\',\'' . $x['nombre'] . '\',\'' . $x['id_menu'] . '\',\'' . $x['orden'] . '\');"> Editar</button></td></tr>';
                        }
                        ?>


                    </tbody>
                </table>

                <!-- /.table-responsive -->
                <div class="well">
                    <h4>Informacion sobre Links</h4>
                    <p>Los links que aparecen en esta sección luego deberan ser relacionados con los <a href="roles.php">roles</a> que correspondan.</p>

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
            <form role="form" action="links.php" method="post" id="formulario"> 
                <div class="modal-body"> 

                    <div class="form-group"> 
                        <label for="nombre" class="control-label">Nombre</label> 
                        <input type="hidden" id="idLink" name="idLink" value="">
                        <input class="form-control" id="nombre" name="nombre" type="text"> 
                        <p class="help-block">Este Nombre sera mostrado como submenú.</p>
                    </div> 
                    <div class="form-group"> 
                        <label for="link" class="control-label">Link</label> 
                        <span class="pull-right"><button type="button" class="btn btn-dark btn-round" data-toggle="modal" data-target=".modalLinksFaltantes"><i class="fa fa-plus"></i> Faltantes
                            </button></span>
                        <input class="form-control" name="link" id="link" >
                    </div>
                    <div class="form-group">
                        <label>Menú al que pertenece</label>
                        <select class="form-control" name="idMenu" id="idMenu">
                            <option value="">Sin Especificar</option>
                            <?php
                            $obj = new menu();
                            $array = $obj->SelectAll();
                            while ($x = mysqli_fetch_assoc($array)) {
                                echo '<option value=' . $x['id'] . '>' . $x['nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group"> 
                        <label for="orden" class="control-label">Orden</label> 
                        <input class="form-control" id="orden" name="orden" type="number"> 
                    </div>
                </div> 
                <div class="modal-footer"> 
                    <div id="eliminar">
                        <input id="rand" name="rand" type="text" class="form-control pull-left" placeholder="<?= $rand ?>" style="width: 50px;">
                        <input id="rand2" name="rand2" type="hidden" value="<?= $rand ?>">
                        <button type="button" class="btn btn-danger pull-left btn-round" name="eliminar" onclick="guardarForm('links.php', 'formulario', 'eliminar');">Eliminar</button>
                    </div>
                    <button type="reset" class="btn btn-default btn-round">Limpiar</button>
                    <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cerrar</button> 
                    <button type="button" class="btn btn-dark btn-round" name="guardar" onclick="guardarForm('links.php', 'formulario');">Guardar</button> 
                </div> 
            </form> 
        </div>
    </div>
</div>

<div class="modal fade modalLinksFaltantes" tabindex="-1" role="dialog" aria-labelledby="Faltantes">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
                <h4 class="modal-title" id="exampleModalLabel">Links Faltantes</h4> </div> 

            <div class="modal-body"> 


                <div class="form-group">
                    <label>Links no agregados al sistema</label>
                    <select class="form-control" name="linksFaltantes" id="linksFaltantes">
                        <option value="">Seleccione...</option>
                        <?php
                        $path = './';
                        $directorio = dir($path);
                        $findme = '.php';
                        while ($archivo = $directorio->read()) {

                            $pos = strpos($archivo, $findme);

                            if (!$pos === false) {
                                if (!in_array($archivo, $linksRegistrados)) {
                                    echo '<option value=' . $archivo . '>' . $archivo . '</option>';
                                }
                            }
                        }
                        $directorio->close();



                        $obj = new menu();
                        $array = $obj->SelectAll();
                        while ($x = mysqli_fetch_assoc($array)) {
                            
                        }
                        ?>
                    </select>
                </div>
            </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cerrar</button> 
            </div> 

        </div>
    </div>
</div>

</div>

<script>
    $(document).ready(function () {
        $(document).on("change", "select[id^='linksFaltantes']", function () {
            $("#nombre").val($(this).val());
            $("#link").val($(this).val());
        })
    });
</script>
