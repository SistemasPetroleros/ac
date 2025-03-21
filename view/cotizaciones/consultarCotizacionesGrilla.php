<table width="100%" class="table jambo_table" id="dataTableSolicitudes">
    <thead>
        <tr>
            <th># Solicitud</th>
            <th class="hidden">Fecha Hidden</th>
            <th>Fecha</th>
            <th>Dni</th>
            <th>Afiliado</th>
            <th>Punto Dispensa</th>
            <th>Proveedor</th>
            <th>Vencimiento</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php

        while ($x = mysqli_fetch_assoc($arraySolicitudes)) {




            if($x['id_tipo_solicitud']==1) $tipo='AC';
            else $tipo='M';
 
            $afiliado=$x['nombre'];
            if($x['esB24']==="1"){
                   $afiliado=$x['codigoB24'];
            }
           
            

            if($x['excluida']==0 OR $x['id_tipo_solicitud']==3) {

            echo '<tr class="odd gradeX"><td> '.$tipo. $x['id'] . '</td><td class="hidden"> '.$x['fecha'] . '</td><td>' . fecha($x['fecha']) . '</td><td>' . $x['dni'] . '</td><td>' . $afiliado. '</td><td>' . $x['puntoDispensa'] . ' (' . $x['gln'] . ')' . '</td><td>' . $x['nomProv'] . '</td><td>' . fecha4($x['fecha_vigencia_cotiz']) . '</td><td>' . $x['estadoGral'] . '</td><td>';
            echo '<button type="button" data-toggle="modal" data-target="#modalVerSolicitud" class="btn btn-dark btn-round" onclick="mostrarCotizacion(\'' . $x['id'] . '\',\'' . $x['idCotizacion'] . '\',\''.$x['id_tipo_solicitud'].'\');"><i class="fa fa-eye" aria-hidden="true"></i></button>';
            echo '</td></tr>';
            }
        }
        ?>


    </tbody>
</table>