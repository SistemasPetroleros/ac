


<br>
<button type="button" id="btnNuevoProducto" class="btn btn-round btn-dark btnNuevoProducto" data-toggle="modal"
    data-target=".bs-example-modal-lg-newItem<?=$solicitud->getid()?>" onclick='foc("nuevoItem<?=$solicitud->getid()?>" );'><i class="fa fa-plus"></i>
    Productos</button>&nbsp;&nbsp;
    <!--
    <a href="#"
    onclick="menu('receta.php?idVenta=&idUsuarioFinalizar=&buscar=', 'Recetas', '245',0); return false;"
    class="btn btn-round btn-dark btnRecetas" type="button"><i class="fa fa-plus"></i>
    Recetas</a>
-->
&nbsp;&nbsp;
<!--
<button type="button" class="btn btn-round btn-danger btnCancelar" data-toggle="modal"
    data-target=".bs-example-modal-lg-cancelar<?=$solicitud->getid()?>" onclick="foc('rand<?=$solicitud->getid()?>');"><i class="fa fa-ban"></i>
    Cancelar</button>
-->

<br><br>
<table width="100%" class="table jambo_table" id="itemsVenta<?=$solicitud->getid()?>">
    <thead>
        <tr>
            <th>Cantidad</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Observaciones</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
                        


/*

                        while ($x = mysqli_fetch_assoc($arrayItems)) {
                           
                            echo '<tr class="odd gradeX"><td>' . $x['cantidad'] . '</td><td>' . ($x['nombre']) . '</td><td>' . ($x['presentacion']) . '</td><td>' . $x['observaciones'] . '</td><td>' . $x['monodroga'] . '</td><td style="width:100px;">';
                            
                            echo '<button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-dark btn-round" onclick="editar(\'' . $x['id'] . '\',\'' . ($x['apellido']) . '\',\'' . ($x['nombre']) . '\',\'' . $x['dni'] . '\',\'' . $x['estadoSIA'] . '\',\'' . $x['email'] . '\',\'' . $x['telefono'] . '\',\'' . $x['nroInternoSIA'] . '\',\'' . fecha4($x['fechaAlta']) . '\',\'' . $x['userAlta'] . '\',\'' . fecha4($x['fechaModif']) . '\',\'' . $x['userModif'] . '\');"> Eliminar</button>';
                            
                            echo '</td></tr>';
                        }
                        */
                        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style="text-align:right" rowspan="1">
                
            </th>
        </tr>
    </tfoot>
</table>
<script>
                                    
                                    $(document).ready(function() {


                                    
                                        $('#itemsVenta<?=$solicitud->getid()?>').DataTable({
                                            "processing": true,
                                            "responsive": true,
                                            "serverSide": true,
                                        "ajax": {
                                            "type": "POST",
                                             "url": 'controller/materiales/venta_item_grilla.php?idSolicitud=<?=$solicitud->getid()?>'
                                        },
                                        "paging": true,
                                        "autoWidth": false,
                                        "columnDefs": [
                                            { "width": "50px", "targets": 0 }
                                        ]
                                        });
                                        
                                      
                                    });

</script>
