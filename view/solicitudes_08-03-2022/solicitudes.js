function iniciarSolicitud() {
    //var datastring = $("#" + idForm).serialize();
    var dniBeneficiario = $("#dniBeneficiario").val();
    //console.log(datastring);
    /*$('.modal').modal('hide');*/

    if (dniBeneficiario.length > 5 && dniBeneficiario > 5000 && dniBeneficiario < 99999999) {

        NProgress.start();
        var duracion = 5000;
        type = 'POST'
        $.ajax({
                url: 'controller/solicitudes/iniciarSolicitud.php',
                type: type,
                dataType: 'text',
                async: true,
                timeout: duracion,
                data: { 'dniBeneficiario': dniBeneficiario, 'cnt': miliseg() },
                success: function(datos) {

                    NProgress.set(0.9);
                    $('#solicitud').html(datos);

                    $('#dataTable1').DataTable({
                        "responsive": true,
                        "paging": true
                    });

                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function(data) {
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
    var observaciones = $("#observaciones").val();
    var esB24 = 0;

    if( $('#b24').prop('checked') ) {
        esB24 = 1;
    }

    //console.log(datastring);
    /*$('.modal').modal('hide');*/

    if (puntoDispensa > 0 && idPersona > 0) {

        NProgress.start();
        var duracion = 5000;
        type = 'POST'
        $.ajax({
                url: 'controller/solicitudes/guardarSolicitud.php',
                type: type,
                dataType: 'text',
                async: true,
                timeout: duracion,
                data: { 'idSolicitud': idSolicitud, 'idPersona': idPersona, 'puntoDispensa': puntoDispensa, 'telefono': telefono, 'email': email, 'observaciones': observaciones,'esB24': esB24, 'cnt': miliseg() },
                success: function(datos) {

                    NProgress.set(0.9);
                    $('#solicitud').html(datos);

                    $('#dataTable1').DataTable({
                        "responsive": true,
                        "paging": true
                    });
                    notificar('Solicitud registrada correctamente');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function(data) {
                //    console.log('Done!!!!!! ');
                NProgress.done();
            })
    } else {
        notificar('Debe indicar un Punto de dispensa');
    }

}

function traerItems() {
    var idSolicitud = $("#idSolicitud").val();

    var duracion = 5000;
    type = 'POST'
    $.ajax({
            url: 'controller/solicitudes/solicitudItems.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
            success: function(datos) {
                $('#items').html(datos);

                //notificar('Solicitud registrada correctamente');
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
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
                url: "controller/solicitudes/venta_item_buscar.php",
                data: { "service": service, "inputS": inputS },
                success: function(data) {

                    //Escribimos las sugerencias que nos manda la consulta
                    $('#suggestionsnuevoItem' + inputS).html(data);
                    //Al hacer click en algua de las sugerencias
                    $('.suggest-element').on('click', function() {
                        //Obtenemos la id unica de la sugerencia pulsada

                        var nombre = $(this).attr('nombre');
                        var idProducto = $(this).attr('dataid');
                        var troquel = $(this).attr('troquel');
                        var id = $(this).attr('id');
                        var presentacion = $(this).attr('presentacion');
                        var monodroga = $(this).attr('monodroga');


                        //console.log(codigoProducto);
                        //Editamos el valor del input con data de la sugerencia pulsada
                        $('#nuevoItem' + inputS).val('');
                        //Hacemos desaparecer el resto de sugerencias
                        $('#suggestionsnuevoItem' + inputS).fadeOut(20);

                        newProd(inputS, idProducto, troquel, nombre, presentacion, monodroga);

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

function newProd(inputS, id, troquel, nombre, presentacion, monodroga) {
    /*
        if (stockCnt == '') { stockCnt = 0; }
        if (stockCntUnidades == '') { stockCntUnidades = 0; }

        var stock = stockCnt;
        if (stockCntUnidades > 0) { stock = stockCnt + " (" + stockCntUnidades + "Un.)"; }
        if (stockCntUnidades == 0 && stockCnt == 0) {
            stock = "Sin Stock";
            habilitado = 0;
        }



        var habilitado2 = '';
        if (habilitado == 0) {
            habilitado = ' disabled="disabled" ';
            habilitado2 = '<span class="fa-stack fa-lg pull-right"><i class="fa fa-ban fa-stack-2x text-danger huge"></i></span>';
        } else {
            habilitado2 = ubicacion;
        }
        if (editaprecio == 0) {
            editaprecio = ' disabled="disabled" ';
        }*/

    var data = '<div class="col-lg-3 col-md-6">&nbsp;</div><div class="col-lg-7 col-md-7" onmouseover="javascript:iniciarCantidad(' + inputS + ');"> ' +
        '<div class="panel btn-dark"> ' +
        '<div class="panel-heading"> ' +
        '<div class="row"> ' +
        '<div class="col-xs-9"> ' +
        '<b><font size="+2">' + nombre + '</font></b><br> ' +
        '' + troquel + '<br> ' +
        '<p><font size="-1">' + presentacion + '</font></p>' +
        '<p><font size="-1">' + monodroga + '</font></p>' +
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
            url: "controller/solicitudes/venta_item_agregar.php",
            data: $('#formNuevoItemAgregar' + inputS).serialize(),
            success: function(response) {
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
        .done(function(data, textStatus, jqXHR) {
            if (console && console.log) {}
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
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
                url: "controller/solicitudes/fraccionadosSelect.php",
                data: { "idTipo": "p", "idFraccionado": idProducto },
                success: function(response) {
                    $("#cantidad" + inputS).empty();
                    $("#cantidad" + inputS).html(response);
                }
            })
            .done(function(data, textStatus, jqXHR) {
                if (console && console.log) {}
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
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
    setTimeout(function() {
        $.ajax({
                type: "POST",
                url: "controller/solicitudes/venta_item_agregar.php",
                data: { "total": idVenta },
                success: function(data) {
                    //console.log(data);
                    $('#totales' + idVenta).html('Total: $ ' + data);
                }
            })
            .done(function(data, textStatus, jqXHR) {
                if (console && console.log) {
                    //console.log( "La solicitud se ha completado correctamente." );
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus);
                }
            });
    }, 300);
}

function eliminarItem(idSolicitud, idItem) {

    $.ajax({
            type: "POST",
            url: "controller/solicitudes/venta_item_agregar.php",
            data: { "idSolicitud": idSolicitud, "eliminar": idItem },
            success: function(data) {
                if (data == '-1') {
                    notificar('Se eliminó un producto');

                    $('#itemsVenta' + idSolicitud).DataTable().ajax.reload();
                    //totales(idSolicitud);
                }
            }
        })
        .done(function(data, textStatus, jqXHR) {
            if (console && console.log) {
                //console.log( "La solicitud se ha completado correctamente." );
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
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
            success: function(datos) {
                if (datos == 2) {
                    notificar('Impresora Sin Conexión.');
                }
                if (datos == 3) {
                    notificar('Debe Seleccionar una Impresora.');
                }
                console.log(datos);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fallo Impresion!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {

        })
}


$(document).ready(function() {
    try {
        $(".rand").on('keyup', function() {
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
$(document).ready(function() {

    $(document).keydown(function(event) {
        if ((event.ctrlKey || event.metaKey) && event.which == 112) { //ctrl + F1
            console.log('apreto ctrl + F1');
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function(event) {
        if ((event.ctrlKey || event.metaKey) && event.which == 86) { //ctrl + v
            console.log('apreto ctrl + v');
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function(event) {
        if (event.which == 112) { //F1 Nueva Venta
            $('#nuevaVenta').click();
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function(event) {
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

    $(document).keydown(function(event) {
        if (event.which == 118) { //F7 Ir a Recetas
            window.location.href = $('.btnRecetas' + tabSelected).attr('href');
            event.preventDefault();
            return false;
        };
    });

    $(document).keydown(function(event) {
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

    $(document).keydown(function(event) {
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

    var duracion = 5000;
    type = 'POST'
    $.ajax({
            url: 'controller/solicitudes/estadosSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
            success: function(datos) {
                $('#estado').html(datos);

                $('.collapse').collapse();
                setTimeout(function() {
                    $('.collapse').collapse("show");
                    $("body").css("overflow", "scroll");
                }, 100);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
            //    console.log('Done!!!!!! ');
        })
}


function proveedoresSolicitud() {
    var idSolicitud = $("#idSolicitud").val();

    var duracion = 5000;
    type = 'POST'
    $.ajax({
            url: 'controller/solicitudes/asignarProveedores.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
            success: function(datos) {
                $('#proveedores').html(datos);

            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
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
    var duracion = 5000;
    $.ajax({
            url: url,
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: datastring + '&' + accion + '&idSolicitud=' + idSolicitud,
            success: function(datos) {

                document.getElementById('proveedores').innerHTML = datos;
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })

}


function abrirModal(idCotizacion, accion, idSolicitud, index) {
	
	var importe= document.getElementById('importe'+index).value;
	var comentarios=document.getElementById('obs'+index).value;

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
    if((parseFloat(importe)<=0)  || (importe=="") )
	{
        notificar('El importe debe ser mayor a cero.');
		document.getElementById('Confirmar').removeAttribute('disabled');
	}
     else{	 
		if (rand == "") {
			notificar('Ingrese el número antes de confirmar.');
			document.getElementById('Confirmar').removeAttribute('disabled');
		} else {
			if (rand != rand2) {
				notificar('El número ingresado no coincide con el mostrado en el cuadro de texto.');
				document.getElementById('Confirmar').removeAttribute('disabled');
			} else {
	 			$.ajax({
						url: 'controller/solicitudes/cotizaciones.php',
						type: 'POST',
						dataType: 'text',
						async: true,
						timeout: 10000,
						data: { 'id': idCotizacion, 'accion': operacion, 'idSolicitud': idSolicitud, 'rand': rand, 'rand2': rand2,'importe': importe, 'observaciones': observaciones },
						success: function(datos) {

							if (datos != 0) {
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
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log('Fail!!!!!!');
						console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
					})
					.done(function(data) {
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
            url: 'controller/solicitudes/cotizaciones.php',
            type: 'POST',
            dataType: 'text',
            async: true,
            timeout: 10000,
            data: { 'id': id, 'importe': importe, 'observaciones': observaciones, 'accion': accion },
            success: function(datos) {

                notificar('Se guardó la información con Éxito!');

            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
            console.log('Done!!!!!!');
            console.log(data);
        })




}






function abrirModalEstadosSolicitud(idEstado, idSolicitud) {

    //$("#id_cotizacion").val(idCotizacion);
    //$("#id_solicitud").val(idSolicitud);


    $("#idEstadoNuevo33").val(idEstado);
    //armamos el mensaje del modal 
    if (idEstado == "2") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert  btn-dark" role="alert"> ' +
            '¿Confirma solicitar auditoria medica?' +
            '</div>';


    } else if (idEstado == "3") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma Rechazo Auditoria Medica?' +
            '</div>';
    } else if (idEstado == "4") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert btn-dark" role="alert"> ' +
            '¿Confirma Aprobación Auditoria Medica?' +
            '</div>' +
            '<div class="alert alert-danger" role="alert"> ' +
            'RECUERDE QUE ESTA ACCION ENVIARA UN EMAIL A LOS PROVEEDORES SELECCIONADOS SOLICITANDO COTIZACIÓN.' +
            '</div>';
    } else if (idEstado == "6") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert   btn-dark" role="alert"> ' +
            '¿Confirma Recepción de pedido?' +
            '</div>';
    } else if (idEstado == "7") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert   btn-dark" role="alert"> ' +
            '¿Confirma que fueron realizados los controles previstos?' +
            '</div>' +
            '<div class="alert alert-danger" role="alert"> ' +
            'RECUERDE QUE ESTA ACCION ENVIARA UN EMAIL AL AFILIADO INDICANDO LA DIPONIBILIDAD DEL PEDIDO.' +
            '</div>';
    } else if (idEstado == "8") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert   btn-dark" role="alert"> ' +
            '¿Confirma Dispensa?' +
            '</div>';
    } else if (idEstado == "9") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert alert-danger" role="alert"> ' +
            '¿Confirma Anulación de la Solicitud?' +
            '</div>';
    } else if (idEstado == "obs") {
        document.getElementById('mensajeEstados').innerHTML = '';
    } else if (idEstado == "1") {
        document.getElementById('mensajeEstados').innerHTML = '<div class="alert   btn-dark" role="alert"> ' +
            '¿Confirma cambiar el estado de la solicitud a NUEVO?' +
            '</div>' +
            '<div class="alert alert-danger" role="alert"> ' +
            'Recuerde que esta acción volvera a la solicitud a un estado editable.' +
            '</div>';
    } 

    foc("observacionEstado");

}

function onChangeStatusSolicitud() {
    var idSolicitud = $("#idSolicitud").val();
    var observacion = $("#observacionEstado").val();
    var nuevoEstado = $("#idEstadoNuevo33").val();
    document.getElementById('cambiarEstado').setAttribute('disabled', 'disabled');

    if ((nuevoEstado == 'obs' && observacion.length > 0) || nuevoEstado == '2' || nuevoEstado == '3' || nuevoEstado == '4' || nuevoEstado == '6' || nuevoEstado == '7' || nuevoEstado == '8' || nuevoEstado == '9' || nuevoEstado == '1') { //([2, 3, 4, 6, 7, 8, 9].includes(nuevoEstado))
        NProgress.start();
        var duracion = 20000;
        type = 'POST'
        $.ajax({
                url: 'controller/solicitudes/estadosSolicitud.php',
                type: type,
                dataType: 'text',
                async: true,
                timeout: duracion,
                data: { 'idSolicitud': idSolicitud, 'observacion': observacion, 'tipo': nuevoEstado, 'cnt': miliseg() },
                success: function(datos) {
                    //  alert(datos);
                    NProgress.set(0.9);
                    $('#estado').html(datos);
                    $('.collapse').collapse();


                    $('#modalComentariosSolicitud').modal('hide');
                    $('.modal-backdrop').fadeOut(10);
                    //notificar('Estado registrado correctamente');
                    traerItems();
                    proveedoresSolicitud();
                    setTimeout(function() {
                        $('.collapse').collapse("show");
                        $("body").css("overflow", "scroll");
                    }, 500);
                    document.getElementById('cambiarEstado').removeAttribute('disabled');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log('Fail!!!!!!');
                console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
            })
            .done(function(data) {
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
    var duracion = 5000;
    type = 'POST'
    $.ajax({
            url: 'controller/solicitudes/consultarSolicitudes.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: datastring,
            success: function(datos) {

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
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })


}

function mostrarSolicitud(idS) {

    NProgress.start();
    var duracion = 5000;

    cargandoImagen('#solicitud');
    cargandoImagen('#items');
    cargandoImagen('#proveedores');
    cargandoImagen('#adjuntos');
    cargandoImagen('#estado');


    type = 'POST'
    $.ajax({
            url: 'controller/solicitudes/mostrarSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idS, 'cnt': miliseg() },
            success: function(datos) {

                NProgress.set(0.9);
                $('#solicitud').html(datos);


            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function(data) {
            //    console.log('Done!!!!!! ');
            NProgress.done();
        })
}


function traerAdjuntos() {
    var idSolicitud = $("#idSolicitud").val();
    var datos = '<iframe src="controller/file_manager/index.php?idSolicitud=' + idSolicitud + '" style="position: relative;top: 0;left: 0;bottom: 0;right: 0;width: 100%;height: 100%; min-height:500px;frameborder="0" scrolling="no"">';
    $('#adjuntos').html(datos);

}

function validarCambioEstado(estadoActual)
{
    var idSolicitud = $("#idSolicitud").val();
	

	if(estadoActual==1)
	{
	
		NProgress.start();
		var duracion = 5000;
		type = 'POST'
		$.ajax({
				url: 'controller/solicitudes/verificarProductosProveedores.php',
				type: 'POST',
				dataType: 'text',
				async: true,
				timeout: duracion,
				data: {"idSolicitud":idSolicitud},
				success: function(datos) {
					  
					if(datos==1)
					{
						notificar("Debe ingresar al menos un proveedor antes de realizar esta acción.");
					}	
					else
					{
						if(datos==2)
						{
							notificar("Debe ingresar al menos un producto antes de realizar esta acción.");
						}	
						else
						{  
							 onChangeStatusSolicitud();
							
						}
						
					}
					
					 
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log('Fail!!!!!!');
				console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
			})
			.done(function(data) {
				//    console.log('Done!!!!!! ');
				NProgress.done();
			})
	}
else{
	
	onChangeStatusSolicitud();
}
	
	
}

function cancelarModalEstado()
{
	
     $('#modalComentariosSolicitud').modal('hide');
	
}

function cerrarModalEstado()
{
	
     $('#modalComentariosSolicitud').modal('hide');
	
}




function cerrarModalProveedor()
{
	
     $('#exampleModal').modal('hide');
	
}

