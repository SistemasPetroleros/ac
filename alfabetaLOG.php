<?php
include 'class.php';
include 'login.php';


$rand = rand(100, 999);
$mensaje = '';


?>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">LOG Alfabeta</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                
                
               
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table jambo_table" id="dataTable1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Archivo</th>
                            <th>Cantidad Actualizada</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$obj = new ab_log();
$array = $obj->SelectAll();

while ($x = mysqli_fetch_assoc($array)) {
    echo '<tr class="odd gradeX"><td>' . $x['id'] . '</td><td>' . $x['fecha'] . '</td><td>' . $x['archivo'] . '</td><td>' . $x['cantidad'] . '</td></tr>';
}
?>


                    </tbody>
                </table>

                <!-- /.table-responsive -->
                <div class="well">
                    <h4>Log de actualizacion de Alfabeta</h4>
                    <p>El reporte actual solo muestra datos correspondientes a los ultimos 90 dias.</p>

                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>




