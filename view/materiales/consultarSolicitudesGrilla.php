<table width="100%" class="table jambo_table" id="dataTableSolicitudes">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Dni</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Punto Dispensa</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php

        while ($x = mysqli_fetch_assoc($arraySolicitudes)) {
            $habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';


            echo '<tr class="odd gradeX">';
            if ($x['urgente'] == 1)
                echo '<td>' . $x['id'] . '</td>';
            else
                echo '<td>' . $x['id'] . '</td>';

            echo '<td>' . fecha($x['fecha']) . '</td>';
            echo '<td>' . $x['dni'] . '</td><td>' . $x['nombre'] . '</td>';
            echo '<td>' . $x['categoria'] . '</td> ';
            echo '<td>' . $x['puntoDispensa'] . '</td> ';
            if (($x['id_estados'] == 32 or $x['id_estados'] == 33 or $x['id_estados'] == 34) and $x['urgente']==1)
                echo '<td>' . $x['estado'] . ' <span class="label label-danger"> ¡Urgente!</span></td>';
            else

                echo '<td>' . $x['estado'] . '</td>';


            echo '<td><button type="button" data-toggle="modal" data-target="#modalVerSolicitud" class="btn btn-dark btn-round" onclick="mostrarSolicitud(\'' . $x['id'] . '\');"><i class="fa fa-eye" aria-hidden="true"></i></button> </td>';
            echo '</tr>';
            /*
    <td>' . number_format($x['id'], 2, ',', '') . '</td>
    $items->setid_venta($x['id']);
    $array2 = $items->SelectAllConRecetas();
    while ($x2 = mysqli_fetch_assoc($array2)) {
        //echo $coma;
        //$habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
        $precio = number_format($x2['precioUnitario'], 2, ',', '');
        $subtotal = number_format($x2['cantidad'] * $x2['precioUnitario'], 2, ',', '');

        //$fecha = substr($x['fecha'],6,2).'/'.substr($x['fecha'],4,2).'/'.substr($x['fecha'],0,4);
        echo $x2['cantidad'] . ' ' . $x2['nombre'] . ' U. $' . $precio . ' Subt. $' . $subtotal . '<br>';
    }
    */
            //echo '</td><td><a class="btn btn-dark btn-round" onclick="modalVarios(\'venta.php?idVenta=' . $x['id'] . '&buscar=1\'); return false;" href="#">Venta</a>&nbsp;<a class="btn btn-dark btn-round" onclick="modalVarios(\'receta.php?idVenta=' . $x['id'] . '&buscar=1\'); return false;" href="#">Recetas</a></td></tr>';
        }
        ?>


    </tbody>
</table>