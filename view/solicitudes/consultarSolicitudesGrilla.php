
                <table width="100%" class="table jambo_table" id="dataTableSolicitudes">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Dni</th>
                            <th>Nombre</th>
                            <th>Punto Dispensa</th>
                            <th>Estado</th>
                            <th>Es B24?</th>
                            <th>Es Sur?</th>
                            <th>Es Oncológico?</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
<?php

while ($x = mysqli_fetch_assoc($arraySolicitudes)) {
    $habi = ($x['id'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
    $sur=($x['esSur']==1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
    $onco=($x['esOncologico']==1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
    $esB24=($x['esB24']==1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
    

    $afiliado=$x['nombre'];
    if($x['esB24']==="1"){
           $afiliado=$x['codigoB24'];
    }
   

    
    echo '<tr class="odd gradeX"><td>' . $x['id'] . '</td><td>' . fecha($x['fecha']) . '</td><td>' . $x['dni'] . '</td><td>' . $afiliado . '</td><td>' . $x['puntoDispensa'] . '</td><td>' . $x['estado'] . '</td><td>'.$esB24.'</td><td>'.$onco.'</td><td>'.$sur.'</td><td>';
    echo '<button type="button" data-toggle="modal" data-target="#modalVerSolicitud" class="btn btn-dark btn-round" onclick="mostrarSolicitud(\'' . $x['id'] . '\');"><i class="fa fa-eye" aria-hidden="true"></i></button>';
    echo '</td></tr>';
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
