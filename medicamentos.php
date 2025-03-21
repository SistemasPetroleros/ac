<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';


/*
if (isset($_POST['guardar'])) {

    $obj = new productos($_POST['id']);
    $obj->setcodbarra($_POST['codbarra']);
    $obj->setcodigo($_POST['codigo']);
    $obj->setdescripcion($_POST['descripcion']);
    $obj->seteditaprecio($_POST['editaprecio']);
    $obj->sethabilitado($_POST['habilitado']);
    $obj->setid_tipoproductos($_POST['id_tipoproductos']);
    $obj->setnombre($_POST['nombre']);
    $obj->setprecio($_POST['precio']);
    $obj->setunidades($_POST['unidades']);
    
    $x = $obj->CreateOrUpdate();
    $mensaje = '<div class="alert alert-success">
                                El registro se guardo correctamente.
                            </div><br>';
}
 * 
 * 
 */
?>
<script>
    function editar(troquel='', codalfabeta='', codBarra, nombre, presentacion, unidades, codDroga)
    {   
        $("#cod_droga").val(codDroga);
        $("#troquel").val(troquel);
        $("#troquel2").val(troquel);
        $("#codalfabeta").val(codalfabeta);
        $("#codbarra").val(codBarra);
        $("#nombre").val(nombre);
        $("#presentacion").val(presentacion);
        $("#unidades").val(unidades);
        $("#unidadesFraccion").val('0');
        $("#nombreFraccion").val('');
        
        console.log(codDroga);
        
    }
    
    function guardarFraccion()
    {
        var troquel = $("#troquel").val();
        var unidades = $("#unidades").val();
        var unidadesFraccion = $("#unidadesFraccion").val();
        var nombreFraccion = $("#nombreFraccion").val();
        
        if (parseInt(unidades) > parseInt(unidadesFraccion))
        {   
            $.ajax({
                        type: "POST",
                        url: "medicamentos_grilla.php?fraccionados="+troquel,
                        dataType: 'text',
                        async: true,
                        timeout: 5000,
                        data: {"troquel": troquel, "unidades": unidades, "unidadesFraccion": unidadesFraccion, "nombreFraccion": nombreFraccion},
                        success: function (data) {
                            $('#medicamentosFracciones tbody').html(data);
                            $("#unidadesFraccion").val('0');
                            $("#nombreFraccion").val('');
                            
                        }
                    });
            
        } else {
            alert('No es posible indicar una fraccion mayor a las unidades en el envase original.');
        }
    }
        function eliminarFraccion(t, u, n)
    {
        var url='medicamentos_grilla.php?eliminar='+t+'&unidades='+u+'&nombre='+n
        getAjaxText(url, 'medicamentosFracciones tbody', 5000);
    }
        
    


</script>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Medicamentos</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- subtitulo 
                <span class="pull-right">
                    <button type="button" class="btn btn-primary btn-circle btn-lg " data-toggle="modal" data-target=".bs-example-modal-lg" onclick="editar();"><i class="fa fa-plus"></i>
                    </button>
                </span>-->
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table jambo_table" id="medicamentos">
                    <thead>
                        <tr>
                            <th>Troquel</th>
                            <th>Nombre</th>
                            <th>Presentacion</th>
                            <th>Precio</th>
                            <th>Fecha</th>
                            <th>Cod Barras</th>
                            <th>Droga</th>
                            <th>Cod Alfabeta</th>
                            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /*
                        $obj = new ab_manualdat();
                        $array = $obj->SelectAll2();
                        while ($x = mysqli_fetch_assoc($array)) {
                            $habi = ($x['troquel'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
                            $precio = $x['precio']/100;
                            $fecha = substr($x['fecha'],6,2).'/'.substr($x['fecha'],4,2).'/'.substr($x['fecha'],0,4);
                            echo '<tr class="odd gradeX"><td>' . $x['troquel'] . '</td><td>' . $x['nombre'] . '</td><td>' . $x['presentacion'] . '</td><td>' . $precio . '</td><td>' . $fecha . '</td><td>' . $x['codbarra'] . '</td><td>' . $x['droga'] . '</td></tr>';
                        }
                         */
                         //<td style="width:100px;"><button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-primary" onclick="editar(\'' . $x['troquel'] . '\',\'' . $x['nombre'] . '\',\'' . $x['presentacion'] . '\',\'' . $x['precio'] . '\',\'' . $x['fecha'] . '\',\'' . $x['codbarra'] . '\',\'' . $x['droga'] . '\');"> Editar</button></td>
                        ?>


                    </tbody>
                </table>

                <!-- /.table-responsive -->
                <div class="well">
                    <h4>Información sobre Productos</h4>
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
            <form role="form" action="" method="post"> 
                <div class="modal-body"> 
                    <div class="form-group"> 
                        <label for="troquel" class="control-label">Troquel</label> 
                        <input class="form-control" id="troquel" name="troquel" type="hidden"> 
                        <input class="form-control" id="troquel2" name="troquel2" type="text" disabled> 
                    </div>
                    <div class="form-group"> 
                        <label for="codalfabeta" class="control-label">Código Alfabeta</label> 
                        <input class="form-control" id="codalfabeta" name="codalfabeta" type="text" disabled> 
                    </div>
                    
                    <div class="form-group"> 
                        <label for="nombre" class="control-label">Nombre</label> 
                        <input class="form-control" id="nombre" name="nombre" type="text" disabled> 
                    </div> 
                    <div class="form-group"> 
                        <label for="presentacion" class="control-label">Presentación</label> 
                        
                        <input class="form-control" name="presentacion" id="presentacion" disabled>
                    </div>
                    <div class="form-group"> 
                        <label for="codbarra" class="control-label">Codigo Barras</label> 
                        <input class="form-control" name="codbarra" id="codbarra" disabled>
                    </div>
                    <div class="form-group"> 
                        <label for="unidades" class="control-label">Unidades</label> 
                        <input class="form-control" name="unidades" id="unidades" disabled>
                    </div>
                   
                    

                    
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-round" data-dismiss="modal" onclick="editar();">Cerrar</button> 
                    <button type="button" class="btn btn-dark btn-round" data-dismiss="modal" name="guardar" onclick="CrearProductoActivo();">Registrar como Activo</button> 
                    
                </div> 
                <input  id="cod_droga" name="cod_droga" type="hidden" value=""> 
            </form> 
        </div>
    </div>
</div>
</div>




<script>
    $(document).ready(function() {
        $('#medicamentos').DataTable({
           "processing": true,
           "responsive": true,
           "serverSide": true,
           "ajax": {
               "type": "POST",
               "url": 'medicamentos_grilla.php'
           },
           "paging": true,
           "paginate": true,
           "cache": false
                });
});
    </script>
