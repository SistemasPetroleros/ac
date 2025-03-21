function iniciarSolicitud() {
    //var datastring = $("#" + idForm).serialize();
    var dniBeneficiario = $("#dniBeneficiario").val();
    //console.log(datastring);
    /*$('.modal').modal('hide');*/

    if (dniBeneficiario.length > 5 && dniBeneficiario > 5000 && dniBeneficiario < 99999999) {

        NProgress.start();
        var duracion = 180000;
        type = 'POST'
        $.ajax({
            url: 'controller/materiales/iniciarSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'dniBeneficiario': dniBeneficiario, 'cnt': miliseg() },
            success: function (datos) {

                NProgress.set(0.9);
                $('#solicitud').html(datos);

                $('#dataTable1').DataTable({
                    "responsive": true,
                    "paging": true
                });

            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
                NProgress.done();
            })
    } else {
        notificar('Debe indicar un DNI correcto');
    }

}

function guardarSolicitud() {
    //var datastring = $("#" + idForm).serialize();
    var idPersona = $("#idPersona").val();
    var idSolicitud = $("#idSolicitud").val();
    var puntoDispensa = $("#puntosDispensa").val();
    var telefono = $("#telefono").val();
    var email = $("#email").val();
    var idCategoria = $("#idCategoria").val();
    var idUrgente = $("#idUrgente").val();
    var id_tipo_solicitud = $("#id_tipo_solicitud").val();
    var observaciones = $("#observaciones").val();
    var fecha_vigencia_cotiz = $("#fecha_vigencia_cotiz").val();
    var esSur = 0;


    if ($('#esSur').prop('checked')) {
        esSur = 1;
    }

    //console.log(datastring);
    /*$('.modal').modal('hide');*/

    if (puntoDispensa > 0 && idPersona > 0) {

        NProgress.start();
        var duracion = 180000;
        type = 'POST'
        $.ajax({
            url: 'controller/materiales/guardarSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'idPersona': idPersona, 'puntoDispensa': puntoDispensa, 'telefono': telefono, 'email': email, 'observaciones': observaciones, 'esSur': esSur, 'idCategoria': idCategoria, 'idUrgente': idUrgente, 'fecha_vigencia_cotiz': fecha_vigencia_cotiz, 'id_tipo_solicitud': id_tipo_solicitud, 'cnt': miliseg() },
            success: function (datos) {


                NProgress.set(0.9);
                $('#solicitud').html(datos);

                $('#dataTable1').DataTable({
                    "responsive": true,
                    "paging": true
                });
                notificar('Solicitud registrada correctamente');
            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
                NProgress.done();
            })
    } else {
        notificar('Debe indicar un Punto de dispensa');
    }

}

function traerItems() {
    var idSolicitud = $("#idSolicitud").val();

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/solicitudItems.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {
            $('#items').html(datos);

            //notificar('Solicitud registrada correctamente');
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}



//////agregar items
//////////////////////////////////////////////////////////////////////////


var semaf = 0;

function buscarProducto(e, inputSold) {


    var key = e.keyCode || e.which;
    var inputS = $("#idSolicitud").val();
    semaf += 1;
    var semafInterno = semaf;
    if (key != 9) {
        $('#suggestionsnuevoItem' + inputS).fadeOut(100);
        $('#newItemVenta' + inputS).fadeOut(100);
    }

    //Obtenemos el value del input
    //setTimeout(function () {

    var service = $('#nuevoItem' + inputS).val();

    //console.log(service);
    //console.log("Semaf:"+semaf);
    //console.log("Semaf Interno:"+semafInterno);

    if (semaf == semafInterno && key == 13) {
        if (service.length > 1) {
            //var dataString = '{"service":"'+service+'", "inputS"="'+inputS+'"}';

            //Le pasamos el valor del input al ajax
            $.ajax({
                type: "POST",
                url: "controller/materiales/venta_item_buscar.php",
                data: { "service": service, "inputS": inputS },
                success: function (data) {

                    //Escribimos las sugerencias que nos manda la consulta
                    $('#suggestionsnuevoItem' + inputS).html(data);
                    //Al hacer click en algua de las sugerencias
                    $('.suggest-element').on('click', function () {
                        //Obtenemos la id unica de la sugerencia pulsada

                        var nombre = $(this).attr('nombre');
                        var idProducto = $(this).attr('dataid');
                        //var troquel = $(this).attr('troquel');
                        var id = $(this).attr('id');
                        var descripcion = $(this).attr('descripcion');
                        //var monodroga = $(this).attr('monodroga');


                        //console.log(codigoProducto);
                        //Editamos el valor del input con data de la sugerencia pulsada
                        $('#nuevoItem' + inputS).val('');
                        //Hacemos desaparecer el resto de sugerencias
                        $('#suggestionsnuevoItem' + inputS).fadeOut(20);

                        newProd(inputS, idProducto, nombre, descripcion);

                    });
                }
            });
        } else {
            $('#suggestionsnuevoItem' + inputS).fadeOut(1000);
        }
    }
    //}, 300);
}

function iniciarCantidad(inputS) {
    var cantidad = $('#cantidad' + inputS).val();
    if (cantidad == '') { $('#cantidad' + inputS).val('1'); }

}

function newProd(inputS, id, nombre, descripcion) {


    var data = '<div class="col-lg-3 col-md-6">&nbsp;</div><div class="col-lg-7 col-md-7" onmouseover="javascript:iniciarCantidad(' + inputS + ');"> ' +
        '<div class="panel btn-dark"> ' +
        '<div class="panel-heading"> ' +
        '<div class="row"> ' +
        '<div class="col-xs-9"> ' +
        '<b><font size="+2">' + nombre + '</font></b><br> ' +
        '<p><font size="-1">' + decodeURIComponent(escape((descripcion))) + '</font></p>' +
        '</div> ' +
        '<div class="col-xs-3 text-right"> ' +
        '<div class="huge">' + '-' + '</div> ' +
        '<div class="text-right">' + '' + '</div> ' +
        '</div> ' +
        '</div> ' +
        '</div> ' +
        ' ' +
        '<div class="panel-footer"> ' +
        '<form class="form-inline" method="post" id="formNuevoItemAgregar' + inputS + '"> ' +

        '<div class="form-group"> ' +

        '<input type="hidden" name="idProducto" value="' + id + '"> ' +
        '<input type="hidden" name="idSolicitud" value="' + inputS + '"> ' +

        '<div class="input-group" style="width:25%"> ' +

        '<input class="form-control" style="max-width:100px" name="cantidad" id="cantidad' + inputS + '" type="number" step="1" value="" placeholder="Cantidad" ' + '' + '/> ' +
        '</div> ' +

        '<div class="input-group " style="width:70%"> ' +
        '<input class="form-control"  name="obsItem" id="obsItem' + inputS + '" type="text"  value="" placeholder="Observaciones al Proveedor" ' + '' + '/> ' +
        '</div> ' +



        '<button type="button" id="agregarItem' + inputS + '" class="btn btn-round btn-dark form-control"  onclick="venta_item_agregar(' + inputS + ')" ><i class="fa fa-arrow-circle-right"></i> Agregar</button> ' +
        '</div> ' +


        '</form> ' +
        '<div class="clearfix"></div> ' +
        '</div> ' +
        ' ' +
        '</div> ';

    $('#newItemVenta' + inputS).fadeIn(100).html(data);
    $('#agregarItem' + inputS).focus();
}

function venta_item_agregar(inputS) {

    $.ajax({
        type: "POST",
        url: "controller/materiales/venta_item_agregar.php",
        data: $('#formNuevoItemAgregar' + inputS).serialize(),
        success: function (response) {
            if (response == '1') {
                $('#itemsVenta' + inputS).DataTable().ajax.reload();
                $('#newItemVenta' + inputS).fadeOut(500).html();
                $('#nuevoItem' + inputS).focus();
                notificar('Producto agregado correctamente.');
            } else if (response == '-2') {
                notificar('Debe especificar cantidad');
            } else if (response == '-1') {
                notificar('Se eliminó un producto');
            } else if (response == '0') {
                notificar('Solo es posible editar los productos mientras la solicitud esta en estado Nuevo');
            }
        }
    })
        .done(function (data, textStatus, jqXHR) {
            if (console && console.log) { }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        });
    return false;
}

function selectcnt(inputS) {

    if ($('#cantidad' + inputS).val() == 'n') {
        var n1 = prompt("Ingrese Cantidad");
        if (n1 > 0 && n1 < 100000) {
            $('#cantidad' + inputS).append('<option value="' + n1 + '" selected="selected">' + n1 + '</option>');
        } else {
            if (n1 != null) {
                notificar('La Cantidad ingresada no es correcta!');
            }
            $('#cantidad' + inputS).prop("selectedIndex", 0);
        }
    }
}

function selecttipocnt(inputS, precio, unidades, idProducto) {
    if ($("#selectTipoCnt" + inputS).val() == '0' && unidades > 0) {
        $("#precio" + inputS).val((parseFloat(precio) / parseFloat(unidades)).toFixed(2));
        $("#precioOriginal" + inputS).val((parseFloat(precio) / parseFloat(unidades)).toFixed(2));
        $.ajax({
            type: "POST",
            url: "controller/materiales/fraccionadosSelect.php",
            data: { "idTipo": "p", "idFraccionado": idProducto },
            success: function (response) {
                $("#cantidad" + inputS).empty();
                $("#cantidad" + inputS).html(response);
            }
        })
            .done(function (data, textStatus, jqXHR) {
                if (console && console.log) { }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus);
                }
            });
    } else {
        $("#precio" + inputS).val(precio);
        $("#precioOriginal" + inputS).val(precio);
        $("#cantidad" + inputS).empty();
        var opciones = '<option value="1" selected>1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="n">Otro</option>';
        $("#cantidad" + inputS).html(opciones);
    }

}



function totales(idVenta) {
    setTimeout(function () {
        $.ajax({
            type: "POST",
            url: "controller/materiales/venta_item_agregar.php",
            data: { "total": idVenta },
            success: function (data) {
                //console.log(data);
                $('#totales' + idVenta).html('Total: $ ' + data);
            }
        })
            .done(function (data, textStatus, jqXHR) {
                if (console && console.log) {
                    //console.log( "La solicitud se ha completado correctamente." );
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus);
                }
            });
    }, 300);
}

function eliminarItem(idSolicitud, idItem) {

    $.ajax({
        type: "POST",
        url: "controller/materiales/venta_item_agregar.php",
        data: { "idSolicitud": idSolicitud, "eliminar": idItem },
        success: function (data) {
            if (data == '-1') {
                notificar('Se eliminó un producto');

                $('#itemsVenta' + idSolicitud).DataTable().ajax.reload();
                //totales(idSolicitud);
            }
        }
    })
        .done(function (data, textStatus, jqXHR) {
            if (console && console.log) {
                //console.log( "La solicitud se ha completado correctamente." );
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        });
    //    $('#totales').html('Total: $ ' + tot);
}


function editar(id, email, estado, nombre, telefono) {
    $("#id").val(id);
    $("#email").val(email);
    $("#estado").val(estado).change();
    $("#nombre").val(nombre);
    $("#telefono").val(telefono);


    if (id > 0) {
        $("#eliminar").show();
    } else {
        $("#eliminar").hide();
    }

}

function imprimir(idVenta) {
    $.ajax({
        url: 'imprimir.php',
        type: 'GET',
        dataType: 'text',
        async: true,
        timeout: 5000,
        data: { 'tag': 'connection', 'idVenta': idVenta, 'tipo': 'cobro' }, //'nuevaVenta'},
        success: function (datos) {
            if (datos == 2) {
                notificar('Impresora Sin Conexión.');
            }
            if (datos == 3) {
                notificar('Debe Seleccionar una Impresora.');
            }
            console.log(datos);
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fallo Impresion!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {

        })
}


$(document).ready(function () {
    try {
        $(".rand").on('keyup', function () {
            var value = $(this).val();
            var idVenta = $(this).attr('idventa');

            var rand2 = $("#rand2" + idVenta).val();
            if (value == rand2) {
                $('#btnCancelar' + idVenta).removeAttr('disabled');
            } else {
                $('#btnCancelar' + idVenta).attr('disabled', 'disabled');
            }

        }).keyup();
    } catch (e) {
        console.log(e);
    }
});




var tabSelected = 0;
$(document).ready(function () {

    $(document).keydown(function (event) {
        if ((event.ctrlKey || event.metaKey) && event.which == 112) { //ctrl + F1
            console.log('apreto ctrl + F1');
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function (event) {
        if ((event.ctrlKey || event.metaKey) && event.which == 86) { //ctrl + v
            console.log('apreto ctrl + v');
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function (event) {
        if (event.which == 112) { //F1 Nueva Venta
            $('#nuevaVenta').click();
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function (event) {
        if (event.which == 113) { //F2 Agregar Producto
            var btn = $('.btnNuevoProducto' + tabSelected);
            if (btn.attr('disabled') == 'disabled') {
                notificar('Boton Deshabilitado');
            } else {
                btn.click();
            }
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function (event) {
        if (event.which == 118) { //F7 Ir a Recetas
            window.location.href = $('.btnRecetas' + tabSelected).attr('href');
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function (event) {
        if (event.which == 116) { //F5 Imprimir
            var btn = $('.btnImprimir' + tabSelected);
            if (btn.attr('disabled') == 'disabled') {
                notificar('Boton Deshabilitado');
            } else {
                btn.click();
            }
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function (event) {
        if (event.which == 115) { //F4 Cancelar
            var btn = $('.btnCancelar' + tabSelected);
            if (btn.attr('disabled') == 'disabled') {
                notificar('Boton Deshabilitado');
            } else {
                btn.click();
            }
            event.preventDefault();
            return false;
        };
    });



});


function estadosSolicitud() {
    var idSolicitud = $("#idSolicitud").val();

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/estadosSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {


            $('#estado').html(datos);

            $('.collapse').collapse();
            setTimeout(function () {
                $('.collapse').collapse("show");
                $("body").css("overflow", "scroll");
            }, 100);
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })
}


function proveedoresSolicitud() {
    var idSolicitud = $("#idSolicitud").val();

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/asignarProveedores.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {
            $('#proveedores').html(datos);

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })
}


function guardarGrilla(url, idForm = '', accion = 'guardar', type = 'POST') {
    var idSolicitud = $("#idSolicitud").val();
    var datastring = $("#" + idForm).serialize();
    console.log(datastring);
    /*$('.modal').modal('hide');*/
    $('.modal-backdrop').fadeOut(10);
    NProgress.start();
    var divInsert = 'system';
    var duracion = 180000;
    $.ajax({
        url: url,
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: datastring + '&' + accion + '&idSolicitud=' + idSolicitud,
        success: function (datos) {

            document.getElementById('proveedores').innerHTML = datos;
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })

}


function abrirModal(idCotizacion, accion, idSolicitud, index) {

    var importe = document.getElementById('importe' + index).value;
    var comentarios = document.getElementById('obs' + index).value;

    $("#id_cotizacion").val(idCotizacion);
    $("#id_solicitud").val(idSolicitud);
    $("#importeCot").val(importe);
    $("#observacionesCot").val(comentarios);



    //armamos el mensaje del modal 
    if (accion == "A") {

        document.getElementById('mensaje').innerHTML = '<div class="alert alert-warning" role="alert"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' +
            'Esta operación es irreversible, una vez confirmada se envía E-Mail a Proveedor Aprobado.' +
            '</div>';

        document.getElementById('mensaje2').innerHTML = '<div class="alert btn-dark" role="alert"> ' +
            '¿Confirma cambiar el estado de la cotización seleccionada a APROBADA?' +
            '</div>';

        operacion = "aprobar";

    } else {

        if (accion == "C") {
            document.getElementById('mensaje2').innerHTML = '<div class="alert btn-dark" role="alert"> ' +
                '¿Confirma cambiar el estado de la cotización seleccionada a ANULADA?' +
                '</div>';
            document.getElementById('mensaje').innerHTML = '<div class="alert alert-warning" role="alert"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' +
                'Si todas las otras cotizaciones de la solicitud están anuladas, al confirmar se anulará automáticamente la presente solicitud.' +
                '</div>';
            operacion = "anular";
        } else {
            document.getElementById('mensaje').innerHTML = '<div class="alert btn-dark" role="alert"> ' +
                '¿Confirma cambiar el estado de la cotización seleccionada a PENDIENTE nuevamente?' +
                '</div>';
            document.getElementById('mensaje2').innerHTML = '';
            operacion = "revertir";

        }
    }

    $("#operacion").val(operacion);

}


function onChangeStatus() {
    var idCotizacion = document.getElementById('id_cotizacion').value;
    var idSolicitud = document.getElementById('id_solicitud').value;
    var importe = document.getElementById('importeCot').value;
    var observaciones = document.getElementById('observacionesCot').value;
    var operacion = document.getElementById('operacion').value;
    var rand = document.getElementById('randc').value;
    var rand2 = document.getElementById('randc2').value;

    document.getElementById('Confirmar').setAttribute('disabled', 'disabled');

    //alert(importe);
    if ((parseFloat(importe) <= 0) || (importe == "")) {
        notificar('El importe debe ser mayor a cero.');
        document.getElementById('Confirmar').removeAttribute('disabled');
    }
    else {
        if (rand == "") {
            notificar('Ingrese el número antes de confirmar.');
            document.getElementById('Confirmar').removeAttribute('disabled');
        } else {
            if (rand != rand2) {
                notificar('El número ingresado no coincide con el mostrado en el cuadro de texto.');
                document.getElementById('Confirmar').removeAttribute('disabled');
            } else {
                $.ajax({
                    url: 'controller/materiales/cotizaciones.php',
                    type: 'POST',
                    dataType: 'text',
                    async: true,
                    timeout: 10000,
                    data: { 'id': idCotizacion, 'accion': operacion, 'idSolicitud': idSolicitud, 'rand': rand, 'rand2': rand2, 'importe': importe, 'observaciones': observaciones },
                    success: function (datos) {

                        if (datos.trim() != "0") {
                            $('#exampleModal').modal('hide');
                            document.getElementById('proveedores').innerHTML = datos;
                            notificar('Operación realizada con éxito');
                            traerItems();
                            estadosSolicitud();
                        } else {
                            notificar('Se ha producido un error al intentar realizar la operación. Intente nuevamente.');
                        }
                        document.getElementById('Confirmar').removeAttribute('disabled');

                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                        NProgress.done();
                    })
            }
        }
    }

}




function editarCotizacion(id, indice, accion) {

    var importe = document.getElementById('importe' + indice).value;
    var observaciones = document.getElementById('obs' + indice).value;

    $.ajax({
        url: 'controller/materiales/cotizaciones.php',
        type: 'POST',
        dataType: 'text',
        async: true,
        timeout: 10000,
        data: { 'id': id, 'importe': importe, 'observaciones': observaciones, 'accion': accion },
        success: function (datos) {

            notificar('Se guardó la información con Éxito!');

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            console.log('Done!!!!!!');
            console.log(data);
        })




}






function abrirModalEstadosSolicitud(idEstado, idSolicitud) {

    //$("#id_cotizacion").val(idCotizacion);
    //$("#id_solicitud").val(idSolicitud);

    $("#idEstadoNuevo33").val(idEstado);
    //armamos el mensaje del modal 
    if (idEstado == "32") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert  btn-dark" role="alert"> ' +
            '¿Confirma solicitar auditoria médica?' +
            '</div>';


    } else if (idEstado == "33") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma Rechazo Auditoria Medica?' +
            '</div>';
    } else if (idEstado == "34") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert btn-dark" role="alert"> ' +
            '¿Confirma Aprobación Auditoria Medica?' +
            '</div>' +
            '<div class="alert alert-danger" role="alert"> ' +
            'RECUERDE QUE ESTA ACCION ENVIARA UN EMAIL A LOS PROVEEDORES SELECCIONADOS SOLICITANDO COTIZACIÓN.' +
            '</div>';
    } else if (idEstado == "9") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma Anulación de la Solicitud?' +
            '</div>';
    } else if (idEstado == "obs") {
        document.getElementById('mensajeEstados').innerHTML = '';
    } else if (idEstado == "31") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert   btn-dark" role="alert"> ' +
            '¿Confirma cambiar el estado de la solicitud a NUEVO?' +
            '</div>' +
            '<div class="alert alert-danger" role="alert"> ' +
            'Recuerde que esta acción volvera a la solicitud a un estado editable.' +
            '</div>';
    } else if (idEstado == "38") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert   btn-dark" role="alert"> ' +
            '¿Desea Observar la solicitud actual?' +
            '</div>' +
            '<div class="alert alert-warning" role="alert"> ' +
            'Ingrese una Observación de ser necesaria. La solicitud pasará a estado OBSERVADO POR AUDITORIA.' +
            '</div>';
    } else if (idEstado == "36") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma Anulación de la Solicitud?' +
            '</div>';
    }
    else if (idEstado == "37") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma cambiar el estado de la Solicitud a ADJUDICADO?' +
            '</div>';
    }  else if (idEstado == "43") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma cambiar el estado de la Solicitud a NOTIFICADO?' +
            '</div>';
    }
    else if (idEstado == "44") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma cambiar el estado de la Solicitud a FACTURADO?' +
            '</div>';
    }



    foc("observacionEstado");

}

function onChangeStatusSolicitud() {
    var idSolicitud = $("#idSolicitud").val();
    var observacion = $("#observacionEstado").val();
    var nuevoEstado = $("#idEstadoNuevo33").val();
    document.getElementById('cambiarEstado').setAttribute('disabled', 'disabled');


    if ((nuevoEstado == 'obs' && observacion.length > 0) || nuevoEstado == '31' || nuevoEstado == '32' || nuevoEstado == '33' || nuevoEstado == '34' || nuevoEstado == '35' || nuevoEstado == '36' || nuevoEstado == '37' || nuevoEstado == '38' || nuevoEstado == '43' || nuevoEstado == '44') {
        NProgress.start();
        var duracion = 20000;
        type = 'POST'
        $.ajax({
            url: 'controller/materiales/estadosSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'observacion': observacion, 'tipo': nuevoEstado, 'cnt': miliseg() },
            success: function (datos) {
                //   alert(datos);
                NProgress.set(0.9);
                $('#estado').html(datos);
                $('.collapse').collapse();


                $('#modalComentariosSolicitud').modal('hide');
                $('.modal-backdrop').fadeOut(10);
                //notificar('Estado registrado correctamente');
                traerItems();
                proveedoresSolicitud();
                setTimeout(function () {
                    $('.collapse').collapse("show");
                    $("body").css("overflow", "scroll");
                }, 500);
                //document.getElementById('cambiarEstado').removeAttribute('disabled');
                /*  if (nuevoEstado == 6 || nuevoEstado == 7 || nuevoEstado == 8) {
                      traerTrazaProductos();
                  }*/
            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
                NProgress.done();
            })

    } else {
        document.getElementById('cambiarEstado').removeAttribute('disabled');
        notificar('No fue posible realizar la operacion:' + nuevoEstado);
    }
}




function buscarSolicitudes() {
    var datastring = $("#formulario").serialize();



    NProgress.start();
    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/consultarSolicitudes.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: datastring,
        success: function (datos) {

            NProgress.set(0.9);
            $('#grillaSolicitudes').html(datos);

            $('#dataTableSolicitudes').DataTable({
                "responsive": true,
                "paging": true,
                "order": [
                    [0, "desc"]
                ]
            });

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })


}

function mostrarSolicitud(idS) {

    NProgress.start();
    var duracion = 180000;

    cargandoImagen('#solicitud');
    cargandoImagen('#items');
    cargandoImagen('#proveedores');
    cargandoImagen('#adjuntos');
    cargandoImagen('#estado');
    cargandoImagen('#recepcionTraza');
    // cargandoImagen('#dispensacionTraza');


    type = 'POST'
    $.ajax({
        url: 'controller/materiales/mostrarSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idS, 'cnt': miliseg() },
        success: function (datos) {

            NProgress.set(0.9);
            $('#solicitud').html(datos);

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })
}


function traerAdjuntos() {
    var idSolicitud = $("#idSolicitud").val();
    var datos = '<iframe src="controller/file_manager/index.php?idSolicitud=' + idSolicitud + '&adjuntosMateriales=1&tipo=M" style="position: relative;top: 0;left: 0;bottom: 0;right: 0;width: 100%;height: 100%; min-height:500px;frameborder="0" scrolling="yes"">';
    $('#adjuntos').html(datos);

}

function validarCambioEstado(estadoActual) {
    var idSolicitud = $("#idSolicitud").val();


    if (estadoActual == 1) {

        NProgress.start();
        var duracion = 180000;
        type = 'POST'
        $.ajax({
            url: 'controller/materiales/verificarProductosProveedores.php',
            type: 'POST',
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { "idSolicitud": idSolicitud },
            success: function (datos) {

                if (datos == 1) {
                    notificar("Debe ingresar al menos un proveedor antes de realizar esta acción.");
                }
                else {
                    if (datos == 2) {
                        notificar("Debe ingresar al menos un producto antes de realizar esta acción.");
                    }
                    else {
                        onChangeStatusSolicitud();

                    }

                }


            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
                NProgress.done();
            })
    }
    else {

        onChangeStatusSolicitud();
    }


}

function cancelarModalEstado() {

    $('#modalComentariosSolicitud').modal('hide');

}

function cerrarModalEstado() {

    $('#modalComentariosSolicitud').modal('hide');

}




function cerrarModalProveedor() {

    $('#exampleModal').modal('hide');

}


function abrirModalSolicitudRecep(idEstado) {

    NProgress.start();
    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/recepcionProdSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { "idEstado": idEstado },
        success: function (datos) {

            NProgress.set(0.9);
            $('#modalVerSolicitud').modal('hide');
            $('#divModalRecepcionProd').html(datos);
            $('#modalRecepcionProd').modal({ backdrop: 'static', keyboard: false, show: true });


        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })


}


function cerrarModal() {
    $('#modalVerSolicitud').modal('hide').data('bs.modal', null);

}


function traerTrazaProductos() {

    var idSolicitud = $("#idSolicitud").val();

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/trazabilidadSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {

            //  buscarInformados();

            if (datos.trim() != "") {


                $('#recepcionTraza').html(datos);
                $("#recepcionTab").removeClass('disabledTab');
                $("#remitosTab").removeClass('disabledTab');
                /* $('#recepcionTraza').removeClass('active in');*/


                if (document.getElementById("trecepcion") != null) {
                    $('#trecepcion').DataTable({
                        "responsive": false,
                        "paging": true
                    });
                }
                else {
                    if (document.getElementById("tablaDispensados") != null) {
                        $('#tablaDispensados').DataTable({
                            "responsive": false,
                            "paging": true
                        });

                    }

                }





            }
            else {
                $("#recepcionTab").addClass('disabledTab');
                $("#remitosTab").addClass('disabledTab');
                $('#recepcionTraza').html('');
                $('#remitosTraza').html('');
            }



        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}

/*function buscar_e_informar(idSolicitud) {
    //Primero valido campos


    if (confirm('Los productos marcados como trazables se informarán al AMNAT. Desea continuar?')) {

        $("#informarBuscar").attr('disabled', 'disabled');
        var items = new Array();
        var j = -1;
        var frm = document.getElementById("formRecep");
        var chequeado = true;
        var validado = true;

        for (i = 0; i < frm.elements.length; i++) {
            if (frm.elements[i].id.substring(0, 10) == 'esTrazable') {
                j = j + 1;
                items[j] = new Array(11);

                if (frm.elements[i].checked) {
                    items[j][0] = 1;
                    chequeado = true;
                }
                else {
                    items[j][0] = 0;
                    chequeado = false;
                }

            }

            if (frm.elements[i].id.substring(0, 4) == 'gtin') {
                if ((frm.elements[i].value).trim() == "" && chequeado) {
                    notificar("Debe ingresar GTIN a cada Producto Trazable.");
                    validado = false;
                    break;
                }
                else {
                    items[j][1] = frm.elements[i].value;
                }

            }

            if (frm.elements[i].id.substring(0, 8) == 'nroSerie') {
                if ((frm.elements[i].value).trim() == "" && chequeado) {
                    notificar("Debe ingresar Nro. Serie a cada Producto Trazable.");
                    validado = false;
                    break;
                }
                else {
                    items[j][2] = frm.elements[i].value;
                }
            }

            if (frm.elements[i].id.substring(0, 4) == 'lote') {
                if ((frm.elements[i].value).trim() == "" && chequeado) {
                    notificar("Debe ingresar Nro.de Lote a cada Producto Trazable.");
                    validado = false;
                    break;
                }
                else {
                    items[j][3] = frm.elements[i].value;
                }
            }

            if (frm.elements[i].id.substring(0, 5) == 'fVenc') {
                if ((frm.elements[i].value).trim() == "" && chequeado) {
                    notificar("Debe ingresar Fecha de Vencimiento a cada Producto Trazable.");
                    validado = false;
                    break;
                }
                else {
                    items[j][4] = frm.elements[i].value;
                }
            }

            if (frm.elements[i].id.substring(0, 6) == 'idItem') {
                items[j][5] = frm.elements[i].value;
            }

            if (frm.elements[i].id.substring(0, 10) == 'idProducto') {
                items[j][6] = frm.elements[i].value;
            }

            if (frm.elements[i].id.substring(0, 7) == 'idTabla') {
                items[j][7] = frm.elements[i].value;
            }

            if (frm.elements[i].id.substring(0, 13) == 'idTrazaEstado') {
                items[j][8] = frm.elements[i].value;
            }

            if (frm.elements[i].id.substring(0, 7) == 'fremito') {
                items[j][9] = frm.elements[i].value;
            }

            if (frm.elements[i].id.substring(0, 6) == 'remito') {
                items[j][10] = frm.elements[i].value;
            }






        }

        if (validado) {

            var duracion = 180000;
            type = 'POST'
            $.ajax({
                url: 'controller/materiales/buscarRecepcionarProductos.php',
                type: type,
                dataType: 'text',
                async: true,
                timeout: duracion,
                data: { 'idSolicitud': idSolicitud, "items": items.toString(), 'cnt': miliseg() },
                success: function (datos) {


                    if (datos.trim() == "") {
                        notificar('Operación realizada con éxito. Verifique estados.');
                        //CREAR FUNCIONES PARA QUE ACTUALICE AMBAS TABLAS (A RECEPCIONAR Y RECEPCIONADOS)
                        traerRecepcionProductos();
                    }
                    else {
                        notificar(datos);
                    }

                    $("#informarBuscar").removeAttr('disabled');

                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('Fail!!!!!!');
                    console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                    $("#informarBuscar").removeAttr('disabled');
                })
                .done(function (data) {
                    //    console.log('Done!!!!!! ');
                    $("#informarBuscar").removeAttr('disabled');
                })


        }
        else {
            $("#informarBuscar").removeAttr('disabled');
        }
    }

}*/


function abrirEstados(idSolicitud, idItem, idItemTraza) {
    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/abrirModalEstadosTraza.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, "idItem": idItem, "idItemTraza": idItemTraza },
        success: function (datos) {

            $('#divModalRecepcionProd').html(datos);
            $('#modalErrores').modal({ backdrop: 'static', keyboard: true, show: true });

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })
}

function cerrarModalEstados() {
    $('#modalErrores').modal('hide');
}


function habilitarDispensa(idSolicitud) {

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/abrirModalhabilitarDispensa.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {

            $('#divModalHabDisp').html(datos);
            $('#modalHabDispensa').modal({ backdrop: 'static', keyboard: true, show: true });

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })


}


function verificarRecepcion(idEstado) {

    var idSolicitud = $("#idSolicitud").val();
    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/verificarRecepcion.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {
            if (datos.trim() == "1")
                cambiarEstado(idEstado);
            else
                notificar('Recepcione todos los productos antes de continuar.');

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}


/*function cambiarEstado(idEstado){

    var idSolicitud = $("#idSolicitud").val();

    if(idEstado==7){
         var obs= 'La solicitud Nro. '+idSolicitud+' cambia a estado PRODUCTOS EN PROCESO ENTREGA.';
    }
    else {
        var obs= 'La solicitud Nro. '+idSolicitud+' cambia a estado PRODUCTOS DISPENSADOS.';
    } 

    var duracion = 180000;
    type = 'POST'
    $.ajax({
            url: 'controller/materiales/cambiarEstado.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'tipo': 'obs', 'observacion': obs, 'idEstado': idEstado},
            success: function(datos) {
                estadosSolicitud();
                if(idEstado==7)
                   { 
                       traerDispensaProductos();
                     
                   }
                else 
                    traerDispensaProductosIni();
                traerRecepcionProductos();
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
            //    console.log('Done!!!!!! ');
        })

}*/


function cambiarEstado(idEstado) {

    var idSolicitud = $("#idSolicitud").val();

    $("#cambiarEstadoBtn").attr('disabled', 'disabled');

    switch (idEstado) {
        case 7:
            var obs = 'La solicitud Nro. ' + idSolicitud + ' cambia a estado RECEPCIONADO - EN ESPERA DE ENTREGA.';
            break;
        case 8:
            var obs = 'La solicitud Nro. ' + idSolicitud + ' cambia a estado PRODUCTOS DISPENSADOS.';
            break;
        case 6:
            var obs = 'La solicitud Nro. ' + idSolicitud + ' cambia a estado PENDIENTE REVISION EN FARMACIA.';
    }


    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/estadosSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'tipo': idEstado, 'observacion': obs },
        success: function (datos) {
            $('#estado').html(datos);

            $('.collapse').collapse();
            setTimeout(function () {
                $('.collapse').collapse("show");
                $("body").css("overflow", "scroll");
            }, 100);

            if (idEstado = 6 || idEstado == 7 || idEstado == 8) traerTrazaProductos();

            // notificar('Cambio de Estado Exitoso.');
            $('#modalHabDispensa').modal('hide');
            $('#modalCerrarDispensa').modal('hide');



            $("#cambiarEstadoBtn").removeAttr('disabled');
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            $("#cambiarEstadoBtn").removeAttr('disabled');
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}


/*function traerDispensaProductos() {


    var idSolicitud = $("#idSolicitud").val();



    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/dispensaSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {

            if (datos.trim() != "") {
                $('#recepcionTraza').removeClass('active in');
                $('#dispensacionTraza').html(datos);
                $("#dispensaTab").removeClass('disabledTab');

                $('#tablaDispensados').DataTable({
                    "responsive": false,
                    "paging": true
                });


                setTimeout(function () {
                    $("#dispensaTab").tab('show');
                    $("#dispensacionTraza").addClass('active in');
                }, 500);

                notificar('Se habilitó la dispensa correctamente.');
                $('#modalHabDispensa').modal('hide');


            }
            else {
                $("#dispensaTab").addClass('disabledTab');
                $('#dispensacionTraza').html('');
                $("#recepcionTab").tab('show');
                // notificar('Hubo un error al cambiar estado. Intente nuevamente.');

            }



        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })



}


function traerDispensaProductosIni() {


    var idSolicitud = $("#idSolicitud").val();



    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/dispensaSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {

            if (datos.trim() != "") {
                $('#dispensacionTraza').html(datos);
                $("#dispensaTab").removeClass('disabledTab');

                $('#tablaDispensados').DataTable({
                    "responsive": false,
                    "paging": true
                });

                $('#modalCerrarDispensa').modal('hide');
                $('#modalHabDispensa').modal('hide');


            }
            else {
                $("#dispensaTab").addClass('disabledTab');
                $('#dispensacionTraza').html('');

            }



        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })



}*/


/*function informarDispensas(idSolicitud) {

    if (confirm('Los productos marcados como trazables se informarán al AMNAT como DISPENSADOS. Desea continuar?')) {

        $("#informarDispensa").attr('disabled', 'disabled');
        var items = new Array();
        var j = -1;
        var frm = document.getElementById("formDispensa");


        for (i = 0; i < frm.elements.length; i++) {
            if (frm.elements[i].id.substring(0, 9) == 'dispensar') {
                j = j + 1;
                items[j] = new Array(5);

                if (frm.elements[i].checked) {
                    items[j][0] = 1;

                }
                else {
                    items[j][0] = 0;

                }

            }

            if (frm.elements[i].id.substring(0, 6) == 'idItem') {

                items[j][1] = frm.elements[i].value;

            }

            if (frm.elements[i].id.substring(0, 7) == 'idTabla') {
                items[j][2] = frm.elements[i].value;

            }

            if (frm.elements[i].id.substring(0, 10) == 'esTrazable') {
                items[j][3] = frm.elements[i].value;

            }

            if (frm.elements[i].id.substring(0, 13) == 'idTrazaEstado') {
                items[j][4] = frm.elements[i].value;
            }





        }


        var duracion = 180000;
        type = 'POST'
        $.ajax({
            url: 'controller/materiales/dispensarProductos.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, "items": items.toString(), 'cnt': miliseg() },
            success: function (datos) {


                if (datos.trim() == "") {
                    notificar('Operación realizada con éxito. Verifique estados.');
                    //CREAR FUNCIONES PARA QUE ACTUALICE AMBAS TABLAS (A RECEPCIONAR Y RECEPCIONADOS)
                    traerDispensaProductosIni();
                    traerRecepcionProductos();
                }
                else {
                    notificar(datos);
                }

                $("#informarDispensa").removeAttr('disabled');

            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                $("#informarDispensa").removeAttr('disabled');
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
                $("#informarDispensa").removeAttr('disabled');
            })


    }


}*/


function cerrarDispensa(idSolicitud) {

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/abrirModalCerrarDispensa.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {



            $('#divModalCerrarDisp').html(datos);
            $('#modalCerrarDispensa').modal({ backdrop: 'static', keyboard: true, show: true });

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })


}


function verificarDispensa(idEstado) {

    var idSolicitud = $("#idSolicitud").val();
    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/verificarDispensa.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {
            if (datos.trim() == "1")
                cambiarEstado(idEstado);
            else
                notificar('Dispense todos los productos antes de continuar.');

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}


function imprimirDispensa(idSolitud) {

    var form = "controller/materiales/reporteDispensa.php";
    var ventana = window.open(form + "?idSolicitud=" + idSolitud, "Listado Dispensa");

}


function buscarNoConfirmados() {
    var serie = $('#serieb').val();
    var remito = $('#remitob').val();
    var idSolicitud = $("#idSolicitud").val();


    if ((serie.trim()).length == 0 && (remito.trim()).length == 0) {
        notificar('Debe ingresar al menos un filtro de búsqueda para continuar.');
    }
    else {
        var duracion = 180000;
        type = 'POST'
        $.ajax({
            url: 'controller/materiales/buscarNoRecepcionados.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'serie': serie, 'remito': remito },
            success: function (data) {

                $('#tablaNR').html(data);
                buscarInformados();

            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
            })

    }

}


function buscarInformados() {
    var idSolicitud = $("#idSolicitud").val();


    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/buscarRecepcionados.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (data) {
            $('#tablaR').html(data);
            $('#trecepcion').DataTable({
                "responsive": false,
                "paging": true
            });
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })


}


function seleccionarTodos(tabla) {
    var long = document.getElementById('long').value;

    for (i = 0; i < long; i++) {
        document.getElementById('check' + document.getElementById('serial' + i).value).checked = true;
        document.getElementById('cheq' + document.getElementById('serial' + i).value).value = 1;

    }

    /*  
        var table = document.getElementById(tabla);
  
         for (var i = 0, row; row = table.rows[i]; i++) {
          alert(document.getElementById('serial' + i).value);
  
          console.log("i:",table.rows[i]);
         
          document.getElementById('check' + document.getElementById('serial' + i).value).checked = true;
      }*/
}

function deseleccionarTodos(tabla) {

    var long = document.getElementById('long').value;

    for (i = 0; i < long; i++) {
        document.getElementById('check' + document.getElementById('serial' + i).value).checked = false;
        document.getElementById('cheq' + document.getElementById('serial' + i).value).value = 0;

    }

    /*    var table = document.getElementById(tabla);
    
        for (var i = 0, row; row = table.rows[i]; i++) {
    
          
    
            document.getElementById('check' + document.getElementById('serial' + i).value).checked = false;
        }*/
}

function abrirModalQR() {

    var idSolicitud = $("#idSolicitud").val();

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/seleccionarConQR.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (data) {

            $('#divModalQR').html(data);
            $('#modalQR').modal({ show: true });
            document.getElementById("code").focus();
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        });


}

function buscarProductoQR() {

    var qr = $("#code").val();


    type = 'POST'
    $.ajax({
        url: 'controller/materiales/decifrarQR.php?data=1',
        type: type,
        data: { 'qr': qr },
        success: function (response) {

            var jsonData = JSON.parse(response);

            if (jsonData.qr.trim() != "") {


                $('#GTIN').html(jsonData.GTIN + '  [' + jsonData.POSGTIN + ']');
                $('#FECVENC').html(jsonData.FECVENC + '  [' + jsonData.POSFECVENC + ']');
                $('#LOTE').html(jsonData.LOTE + '  [' + jsonData.POSLOTE + ']');
                $('#SERIE').html(jsonData.SERIE + '  [' + jsonData.POSSERIE + ']');
                $('#code').val('');
                //  $('#lectura').html(jsonData.qr);
                document.getElementById("code").focus();

                if (jsonData.SERIE != "" && jsonData.SERIE != undefined) {
                    if (document.getElementById('check' + jsonData.SERIE) != null) {
                        document.getElementById('check' + jsonData.SERIE).checked = true;
                        document.getElementById('cheq' + jsonData.SERIE).value = 1;

                        notificar('Seleccionado en la lista con Exito!');
                        document.getElementById('divSuccess').removeAttribute('style');
                        document.getElementById('divError').setAttribute('style', 'display:none;');
                    }
                    else {
                        notificar('Producto no encontrado en la lista!');
                        document.getElementById('divSuccess').setAttribute('style', 'display:none;');
                        document.getElementById('divError').removeAttribute('style');
                        $('#GTIN').html('');
                        $('#FECVENC').html('');
                        $('#LOTE').html('');
                        $('#SERIE').html('');
                        $('#code').val('');
                    }
                }
                else {
                    notificar('Producto no encontrado en la lista!');
                    document.getElementById('divSuccess').setAttribute('style', 'display:none;');
                    document.getElementById('divError').removeAttribute('style');
                    $('#GTIN').html('');
                    $('#FECVENC').html('');
                    $('#LOTE').html('');
                    $('#SERIE').html('');
                    $('#code').val('');
                }


            }
            else {
                notificar('Producto no encontrado en la lista!');
                document.getElementById('divSuccess').setAttribute('style', 'display:none;');
                document.getElementById('divError').removeAttribute('style');

                $('#GTIN').html('');
                $('#FECVENC').html('');
                $('#LOTE').html('');
                $('#SERIE').html('');
                $('#code').val('');
            }


        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        });

}


function informarSeleccionadosNC() {

    bootbox.confirm({
        message: "<h4>¿Desea confirmar la recepción de los productos seleccionados?</h4>",
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {



                var idSolicitud = $("#idSolicitud").val();
                var parametros = $("#tresultado").serialize();

                parametros = parametros + '&idSolicitud=' + idSolicitud;


                var duracion = 180000;
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/verificarGtines.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: parametros,
                    success: function (data) {

                        if (data.trim() == 1)
                            informarRecepcion();
                        else {
                            bootbox.confirm({
                                message: "Existen productos en los Items de la solicitud con GTIN que no coinciden con los GTIN de los productos seleccionados. ¿Desea continuar de todas formas?",
                                buttons: {
                                    confirm: {
                                        label: 'Sí',
                                        className: 'btn-primary'
                                    },
                                    cancel: {
                                        label: 'No',
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if (result) informarRecepcion();
                                }
                            });
                        }
                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);

                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                    });


            }

        }
    });


}

function informarRecepcion() {

    var idSolicitud = $("#idSolicitud").val();
    var parametros = $("#tresultado").serialize();

    parametros = parametros + '&idSolicitud=' + idSolicitud;

    $("#modalVerSolicitud").loading({
        stoppable: false,
        message: "Ejecutando...",
    });



    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/informarNoConfirmados.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: parametros,
        success: function (data) {

            if (data.trim() == "") {
                bootbox.alert('Se informaron los productos con éxito. Verifique su estado en la lista de abajo.');
                buscarInformados();
                buscarNoConfirmados();
            }
            else
                bootbox.alert(data);


        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            $("#modalVerSolicitud").loading('stop');
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            $("#modalVerSolicitud").loading('stop');
        });

}


function handle(e) {

    if (e.keyCode === 13) {
        e.preventDefault(); // Ensure it is only this code that runs
        buscarProductoQR();
    }
}


function setInput(id) {

    if (document.getElementById('check' + id).checked)
        $('#cheq' + id).val('1');
    else
        $('#cheq' + id).val('0');

}

function anularRecepcionANMAT(idItemTraza, idTransaccion) {


    bootbox.confirm({
        message: "<h5>¿Confirma la anulación de la transacción seleccionada?</h5>",
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                var idSolicitud = $("#idSolicitud").val();
                $("#modalVerSolicitud").loading({
                    stoppable: false,
                    message: "Ejecutando...",
                });

                var duracion = 180000;
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/anularTransaccionRecepcion.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: { "idItemTraza": idItemTraza, "idTransaccion": idTransaccion, "idSolicitud": idSolicitud },
                    success: function (data) {

                        if (data.trim() == "") {
                            bootbox.alert('Se anuló con exito la transacción seleccionada.');
                            buscarInformados();
                            buscarNoConfirmados();
                        }
                        else
                            bootbox.alert(data);
                        $("#modalVerSolicitud").loading('stop');


                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                        $("#modalVerSolicitud").loading('stop');
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                        $("#modalVerSolicitud").loading('stop');
                    });

            }
        }
    })

}


function informarDispensas(idSolicitud) {

    bootbox.confirm({
        message: "<h4>¿Confirma la Dispensación de los productos seleccionados?</h4>",
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {

                var parametros = $("#formDispensa").serialize();
                parametros = parametros + '&idSolicitud=' + idSolicitud;

                $("#modalVerSolicitud").loading({
                    stoppable: false,
                    message: "Ejecutando...",
                });


                var duracion = 180000;
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/informarDispensa.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: parametros,
                    success: function (data) {

                        $("#modalVerSolicitud").loading('stop');


                        if (data.trim() == "") {
                            bootbox.alert('Ejecución finalizada. Verifique estado de los productos.');
                            buscarProductosDispensa(idSolicitud);
                        }
                        else
                            bootbox.alert(data);

                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {

                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);

                        $("#modalVerSolicitud").loading('stop');
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                        $("#modalVerSolicitud").loading('stop');
                    });


            }

        }
    });


}


function buscarProductosDispensa(idSolicitud) {



    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/buscarProductosDispensa.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { "idSolicitud": idSolicitud },
        success: function (data) {


            $('#recepcionTraza').html(data);
            $('#tablaDispensados').DataTable({
                "responsive": false,
                "paging": true
            });

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        });

}

function anularDispensaANMAT(idItemTraza, idTransaccion) {


    bootbox.confirm({
        message: "<h5>¿Confirma la anulación de la transacción seleccionada?</h5>",
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                var idSolicitud = $("#idSolicitud").val();
                $("#modalVerSolicitud").loading({
                    stoppable: false,
                    message: "Ejecutando...",
                });

                var duracion = 180000;
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/anularTransaccionDispensa.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: { "idItemTraza": idItemTraza, "idTransaccion": idTransaccion, "idSolicitud": idSolicitud, },
                    success: function (data) {

                        if (data.trim() == "") {
                            bootbox.alert('Ejecución finalizaa con éxito.');
                            buscarProductosDispensa(idSolicitud);
                        }
                        else
                            bootbox.alert(decodeURIComponent(data));

                        $("#modalVerSolicitud").loading('stop');

                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                        $("#modalVerSolicitud").loading('stop');
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                        $("#modalVerSolicitud").loading('stop');
                    });

            }
        }
    })

}

function habilitarRecepcion(idSolicitud) {

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/abrirModalhabilitarRecepcion.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {

            $('#divModalHabDisp').html(datos);
            $('#modalHabDispensa').modal({ backdrop: 'static', keyboard: true, show: true });


        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}


function verificarVolverRecep(idEstado) {

    var idSolicitud = $("#idSolicitud").val();
    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/verificarVolverRecep.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {
            if (datos.trim() == "1")
                cambiarEstado(idEstado);
            else
                notificar('No se puede realizar la operación ya que existen productos dispensados.');

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}


function descargarExcel() {
    var parametros = $("#formulario").serialize();

    form = "controller/materiales/generarExcel.php";
    location.href = form + "?" + parametros;
}



function traerRemitos() {

    var idSolicitud = $("#idSolicitud").val();

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/solicitudRemitos.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {
            $('#remitosTraza').html(datos);
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}


function agregarRemitoModal(id, idSolicitud, op) {
    NProgress.start();
    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/agregarRemitoSolicitudModal.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { "idSolicitud": idSolicitud, "id": id, "op": op },
        success: function (datos) {

            NProgress.set(0.9);
            //$('#modalVerSolicitud').modal('hide');
            $('#divModalAR').html(datos);
            $('#modalAgregarRemito').modal({ backdrop: 'static', keyboard: false, show: true });


        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })

}


function guardarRemito(idSolicitud, op) {

    nroRemito = $('#nroRemito').val();
    fechaRemito = $('#fechaRemito').val();
    obs = $('#obsRemito').val();
    idRemito = $('#idRemito').val();
    if (nroRemito == "")
        notificar('Ingrese un Nro. de Remito.');
    else {

        NProgress.start();
        var duracion = 180000;
        type = 'POST'
        $.ajax({
            url: 'controller/materiales/guardarRemitoSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { "idSolicitud": idSolicitud, "nroRemito": nroRemito, "fechaRemito": fechaRemito, "obs": obs, "idRemito": idRemito, "op": op },
            success: function (datos) {

                NProgress.set(0.9);
                switch (datos.trim()) {
                    case "1":
                        notificar('Se ha cargado Remito con Exito.');
                        if (op == "I") $('#modalAgregarRemito').modal('hide');
                        traerRemitos();
                        break;
                    case "-1":
                        notificar('Ya EXISTE remito ingresado.');
                        break;
                    default:
                        notificar('Ha ocurrido un problema interno. Intente nuevamente en un instante.');
                        break;
                }



            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
                NProgress.done();
            })


    }



}


function guardarDocsRemito(idSolicitud, idRemito) {
    var datastring = $("#idForm").serialize();

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/guardarDocsRemitos.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: datastring + '&idSolicitud=' + idSolicitud + '&idRemito=' + idRemito,
        success: function (data) {


            if (data = 1) notificar('Se guardó la información correctamente.');
            else notificar('Ha ocurrido un problema. Alguna información no pudo ser guardada.');

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })
}


function changeInput(id) {

    if (document.getElementById('check2' + id).checked)
        $('#chequear' + id).val('1');
    else
        $('#chequear' + id).val('0');

}


function eliminarRemito(idRemito, idSolicitud, nroRemito) {


    bootbox.confirm({
        message: "¿Desea confirmar la eliminación del remito seleccionado?",
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {

                var duracion = 180000;
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/eliminarSolicitudRemito.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: 'idSolicitud=' + idSolicitud + '&idRemito=' + idRemito + '&nroRemito=' + nroRemito,
                    success: function (data) {
                        if (data == 1) {
                            notificar('Se eliminó la información correctamente.');
                            traerRemitos();
                        }
                        else
                            if (data == 0) notificar('Ha ocurrido un problema. Por favor, intente nuevamente.');
                            else
                                notificar('Imposible eliminar. El remito esta asociado a una traza.');

                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                    })


            }
        }
    });




}


function verificarCheckLists(idSolicitud) {

    var duracion = 180000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/verificarCheckList.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {
            if (datos.trim() == "0")
                onChangeStatusSolicitud();
            else
                notificar('Existen documentos en remitos sin chequear.');

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })

}


function buscarCotizaciones() {
    var idSolicitud = $("#idSolicitud").val();
    var nroCotizacion = $("#nroCotizacion").val();
    var idsProveedores = $('#proveedorCot').val();
    var cotiza = $('#cotiza').val();

    if (idsProveedores == null) idsProveedores = '';

    var duracion = 1800000;
    type = 'POST'
    $.ajax({
        url: 'controller/materiales/buscarProveedoresCotizaciones.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'nroCotizacion': nroCotizacion, 'idsProveedores': idsProveedores.toString(), 'cotiza': cotiza },
        success: function (datos) {
            $('#divresultado').html(datos);
            $('#pedidoCot').DataTable();

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
        })
}

function abrirModal2(idItem, accion, idSolicitud, idItemCotizacion) {


    switch (accion) {
        case 'A':
            mensaje1 = '<br/><h4>Agregar Item #' + idItem + ' a Pedido</h4>';
            mensaje2 = '<label>Cantidad Aprobada (Obligatorio):</label> <br/> <input type="number" min="1" value="1" id="cantAprobada" name="cantidadAprobada" class="form-control" required onblur="verificacantAprob(this.value);" /> ' +
                '<label>Precio Unitario(Opcional)*:</label> <br/> <input type="number" min="0" value="0" id="importeAprob" name="importeAprob" class="form-control" step="0.01" />' +
                '<small>*Completar en caso que el producto no tenga Precio Unitario asignado.</small>' +
                ' <div id="msjeerror"></div><br/> <br/>' +
                '<div class="alert btn-dark" role="alert">  ' +
                '¿Confirma agregar el item seleccionado al pedido?' +
                '</div> ';
            break;

    }


    bootbox.confirm({
        title: mensaje1,
        message: mensaje2,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check" aria-hidden="true"></i> Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: '<i class="fa fa-times" aria-hidden="true"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                if ($('#cantAprobada').val() > 0) {
                    cantAprobada = $('#cantAprobada').val();
                    importe = $('#importeAprob').val();
                    if (importe == '') importe = 0;

                    var duracion = 180000;
                    type = 'POST'
                    $.ajax({
                        url: 'controller/materiales/agregarItemPedidoCotizacion.php',
                        type: type,
                        dataType: 'text',
                        async: true,
                        timeout: duracion,
                        data: { 'idSolicitud': idSolicitud, 'idItem': idItem, 'idItemCotizacion': idItemCotizacion, 'accion': accion, 'cantAprobada': cantAprobada, 'importe': importe },
                        success: function (datos) {

                            switch (datos.trim()) {
                                case "1":
                                    bootbox.alert('Producto agregado con éxito.');
                                    proveedoresSolicitud();
                                    break;
                                case "-1":
                                    bootbox.alert('ATENCION: La cantidad aprobada no puede ser vacía.');
                                    break;
                                case "-2":
                                    bootbox.alert('ATENCION: La cantidad aprobada no puede ser cero.');
                                    break;
                                case "-3":
                                    bootbox.alert('ERROR: disculpe, el producto no pudo ser agregado, intente nuevamente.');
                                    break;
                                case "-4":
                                    bootbox.alert('ATENCION: La cantidad aprobada no puede ser mayor a la cotizada.');
                                    break;
                                    break;
                                case "-5":
                                    bootbox.alert('ATENCION: La cantidad aprobada supera a la agregada al pedido.');
                                    break;
                                case "-6":
                                    bootbox.alert('ATENCION: El producto no puede ser agregado al pedido con importe igual a cero.');
                                    break;
                            }


                        }
                    })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.log('Fail!!!!!!');
                            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                        })
                        .done(function (data) {
                            //    console.log('Done!!!!!! ');
                        })
                } else {
                    bootbox.alert('<h4> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> La cantidad no puede ser cero.</h4>');
                }



            }
        }
    });



}


function quitar(idItem, idSolicitud, idItemCotizacion) {

    bootbox.confirm({
        message: '¿Esta seguro de eliminar del pedido el item seleccionado?',
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/quitarItemPedido.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    data: { 'idSolicitud': idSolicitud, 'idItem': idItem, 'idItemCotizacion': idItemCotizacion },
                    success: function (r) {

                        switch (r.trim()) {
                            case "1":
                                bootbox.alert('Producto eliminado con éxito.');
                                proveedoresSolicitud();
                                break;
                            case "-1":
                                bootbox.alert('Hubo un problema. Por favor, inetente nuevamente.');
                                break;

                        }



                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                    })
            }
        }
    });

}


function finalizarCotizacion(idSolicitud) {

    mensaje1 = '<br/><h4>Finalizar Cotización Solicitud #' + idSolicitud + '</h4>';
    mensaje2 = '<label>Observaciones:</label> <br/> <textarea id="obsFin" name="obsFin" class="form-control" required></textarea> ' +
        ' <div id="msjeerror"></div><br/> <br/>' +
        '<div class="alert btn-dark" role="alert">  ' +
        '¿Confirma finalizar la cotización?' +
        '</div> ';

    bootbox.confirm({
        title: mensaje1,
        message: mensaje2,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check" aria-hidden="true"></i> Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: '<i class="fa fa-times" aria-hidden="true"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {

                obs = $('#obsFin').val();
                var duracion = 1800000;
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/finalizarCotizacion.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: { 'idSolicitud': idSolicitud, 'obs': obs },
                    success: function (datos) {

                        if (Number(datos) == 1) {
                            proveedoresSolicitud();
                            estadosSolicitud();
                            notificar('Se finalizó con éxito.');
                        }
                        else {

                            if (Number(datos) == -2) {
                                notificar('Atención: el pedido no puede estar vacío.');
                            }
                            else{
                                notificar('Disculpe hubo un problema, por favor intente nuevamente.');
                            }

                           
                        }



                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                    })




            }
        }
    });

}



function rechazarCotizaciones(idSolicitud) {

    mensaje1 = '<br/><h4>Rechazar Cotización Solicitud #' + idSolicitud + '</h4>';
    mensaje2 = '<label>Motivo (Obligatorio):</label> <br/> <textarea id="obsFin" name="obsFin" class="form-control" required></textarea> ' +
        ' <div id="msjeerror"></div><br/> <br/>' +
        '<div class="alert btn-dark" role="alert">  ' +
        '¿Confirma finalizar la cotización?' +
        '</div> ';

    bootbox.confirm({
        title: mensaje1,
        message: mensaje2,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check" aria-hidden="true"></i> Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: '<i class="fa fa-times" aria-hidden="true"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {

                obs = $('#obsFin').val();
                var duracion = 1800000;
                type = 'POST'
                $.ajax({
                    url: 'controller/materiales/rechazarCotizacion.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: { 'idSolicitud': idSolicitud, 'obs': obs },
                    success: function (datos) {

                        if (Number(datos) == 1) {
                            proveedoresSolicitud();
                            estadosSolicitud();
                            notificar('Se realizó la operación con éxito.');
                        }
                        else {
                            notificar('Disculpe hubo un problema, por favor intente nuevamente.');
                        }



                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('Fail!!!!!!');
                        console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
                    })
                    .done(function (data) {
                        //    console.log('Done!!!!!! ');
                    })




            }
        }
    });

}









