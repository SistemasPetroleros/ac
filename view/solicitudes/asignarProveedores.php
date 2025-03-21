<br />
<legend>Proveedores a Cotizar</legend>
<br />

<!-- <div class="alert alert-dark" role="alert">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="esPublico" onchange="hideDiv();">
        <label class="form-check-label" for="esPublico"><b>¿COTIZACIÓN PÚBLICA?</b></label> (Cotizan TODOS los proveedores habilitados.)
        <br />
        

    </div>
</div>  -->


<div id="asigProv">

<div class="col-md-4">
    <table width="100%" class="table jambo_table">
        <tr>
            <th style="background-color:#f6f4f4">Proveedores No Asignados</th>
            <th style="background-color:#f6f4f4"><button type="button" class="btn btn-dark" onclick="addAllProveedores('agregar');">Todos <i class="fa fa-forward" aria-hidden="true"></i></button></th>
        </tr>
        <?php



        $array = $obj->SelectAllNoAsignados($idSolicitud);

        while ($x = mysqli_fetch_assoc($array)) {
            echo '<tr><td>' . $x['nombre'] . '</td><td align="right">'
                . '<form class="form-inline" method="get" id="formulario' . $x['id'] . '">
            
            <div class="form-group">
                <input type="hidden" class="form-control" id="idProvAgregar" name="idProvAgregar" value="' . $x['id'] . '">
            </div>
            
            <button type="button" class="btn btn-dark btn-round" onclick="guardarGrilla(\'controller/solicitudes/asignarProveedores.php\', \'formulario' . $x['id'] . '\', \'agregar\', \'GET\');"> > </button> 
            </form></td></tr>';
        }
        ?>
    </table>
</div>


<div class="col-md-1"></div>

<div class="col-md-4">
    <table width="100%" class="table jambo_table">
        <tr>
            <th style="background-color:#f6f4f4">Proveedores Asignados</th>
            <th style="background-color:#f6f4f4"><button type="button" class="btn btn-dark" onclick="addAllProveedores('quitar');"> <i class="fa fa-backward" aria-hidden="true"></i>
 Todos </button></th>
        </tr>
        <?php
        $array = $obj->SelectAllAsignados($idSolicitud);

        while ($x = mysqli_fetch_assoc($array)) {
            echo '<tr><td align="right">'
                . '<form class="form-inline" method="get" id="formularioq' . $x['id'] . '">
            
            <div class="form-group">
                <input type="hidden" class="form-control" id="idProvQuitar" name="idProvQuitar" value="' . $x['id'] . '">
            </div>
            
            <button type="button" class="btn btn-dark btn-round" onclick="guardarGrilla(\'controller/solicitudes/asignarProveedores.php\', \'formularioq' . $x['id'] . '\', \'quitar\', \'GET\');"> < </button> 
            </form></td><td>' . $x['nombre'] . '</td></tr>';
        }


        ?>
    </table>
</div>

    </div> <!-- asigProv ->