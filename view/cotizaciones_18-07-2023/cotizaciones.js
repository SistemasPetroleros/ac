function buscarCotizaciones() {
    var datastring = $("#formulariocot").serialize();

    console.log(datastring);



    NProgress.start();
    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/consultarCotizaciones.php',
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

function handle(e) {

    if (e.keyCode === 13) {
        e.preventDefault(); // Ensure it is only this code that runs
        iniciarSolicitud();
    }
}


function mostrarCotizacion(idS, idC, tipoS) {

    NProgress.start();
    var duracion = 5000;

    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/mostrarCotizacion.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idS, 'idCotizacion': idC, 'tipoS': tipoS, 'cnt': miliseg() },
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


function cerrarModal() {
    $('#modalVerSolicitud').modal('hide').data('bs.modal', null);
    document.getElementById("formulario").reset();


}


function cerrarModalCotizacion() {
    $('#modalCargarCotizacion').modal('hide').data('bs.modal', null);

}





function traerEstadosCotizacion() {
    var idSolicitud = $("#idSolicitud").val();
    var idCotizacion = $("#idCotizacion").val();

    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/estadosSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'idCotizacion': idCotizacion },
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


function traerItems() {
    var idSolicitud = $("#idSolicitud").val();
    var idCotizacion = $("#idCotizacion").val();
    var idProveedor = $("#idProveedor").val();
    var idTipoSolicitud = $("#idTipoSolicitud").val();





    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/solicitudItems.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'idCotizacion': idCotizacion, 'idProveedor': idProveedor, 'idTipoSolicitud': idTipoSolicitud },
        success: function (datos) {
            $('#items').html(datos);
            $('#tablaitems').DataTable();
            $('#idProductoc1').selectpicker();

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


function actualizarVisualizacion(idC) {

    //NProgress.start();
    var duracion = 5000;

    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/actualizarVisualizacion.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idCotizacion': idC, 'cnt': miliseg() },
        success: function (datos) {


            // NProgress.set(0.9);
            //$('#solicitud').html(datos);

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            //  NProgress.done();
        })


}


function calcularTotal(i) {

    var unitario = $('#precioUnit' + i).val();
    var cantidad = $('#cantCot_' + i).val();
    if (cantidad != "" && unitario != "") {
        var total = parseFloat(cantidad) * parseFloat(unitario);
        $('#total' + i).val(total);
    }
    else {
        $('#total' + i).val(0);
    }

}


function verificaCantidad(i) {
    var cantidadSol = $('#cant_' + i).val();
    var cantidadCot = $('#cantCot_' + i).val();
    if (cantidadSol < cantidadCot) {
        $('#cantCot_' + i).val(cantidadSol);
        notificar('La cantidad cotizada no puede ser mayor a la solicitada.');
    }
    else {
        if (cantidadCot < 0) {
            $('#cantCot_' + i).val(cantidadSol);
            notificar('La cantidad cotizada no puede ser menor a 0.');
        }
        else {
            if (cantidadCot === "") {
                $('#total' + i).val(0);
            }
        }
    }
}


function verificaImporte(i) {
    var precioUnit = $('#precioUnit' + i).val();

    if (precioUnit < 0) {
        $('#precioUnit' + i).val(0);
        $('#total' + i).val(0);
        notificar('El importe unitario no puede ser menor a cero.');
    }
    else {
        if (precioUnit === "") {
            $('#total' + i).val(0);
        }
    }

}




function validaGuarda(tipo) {

    var frm = document.getElementById('formItems');
    //chequeo los required
    if (frm.checkValidity()) {

        elementos = document.querySelectorAll('.validar');
        elementos.forEach((elemento) => {
            $('#div_' + elemento.id).attr('style', 'display:none;');
        })

        guardarCotizacion(tipo);

    }
    else {
        //si falta algun campo obligatorio, que indique cual
        frm.reportValidity()
        $('#formItems').addClass('was-validated');

        elementos = document.querySelectorAll('.validar');

        elementos.forEach((elemento) => {
            if (elemento.value === "") {
                $('#div_' + elemento.id).removeAttr('style');
            }
            else {
                $('#div_' + elemento.id).attr('style', 'display:none;');
            }
        });
    }

}

function guardarCotizacion(tipo) {

    var datastring = $("#formItems").serialize();

    var idSolicitud = $('#idSolicitud').val();
    var idCotizacion = $('#idCotizacion').val();
    var idProveedor = $('#idProveedor').val();


    type = 'POST'
    var duracion = 5000;

    $.ajax({
        url: 'controller/cotizaciones/guardarCotizacion.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: datastring + '&idSolicitud=' + idSolicitud + '&idCotizacion=' + idCotizacion + '&idProveedor=' + idProveedor + '&tipo=' + tipo,
        success: function (data) {
            if (data.substring(0, 6) === "error1") {
                notificar('Fila ' + data.charAt(7) + ': la cantidad cotizada no puede ser mayor a la solicitada.');
            }

            if (data.substring(0, 6) === "error2") {
                notificar('Fila ' + data.charAt(7) + ': la cantidad cotizada no puede ser menor a cero.');
            }

            if (data.substring(0, 6) === "error3") {
                notificar('Fila ' + data.charAt(7) + ': el importe unitario no puede ser menor a cero.');
            }

            if (data.trim() === "error4") {
                notificar('No existen items para cotizar.');
            }

            if (data.trim() === "error5") {
                notificar('Se ha producido un problema, por favor intente nuevamente en un instante.');
            }

            if (!isNaN(data)) {
                if (tipo == 'B') {
                    notificar('Se ha guardado la cotización correctamente.');

                }
                else {
                    traerItems();
                    notificar('Se ha enviado la cotización correctamente.');
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
            //  NProgress.done();
        })



}


function cargarCotizacion() {

    //NProgress.start();
    var duracion = 5000;

    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/cargarCotizacion.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: {},
        success: function (datos) {

            $('#divCargarCotizacion').html(datos);


        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            //  NProgress.done();
        })

}


function iniciarSolicitud() {
    //var datastring = $("#" + idForm).serialize();
    var dniBeneficiario = $("#dniCotizado").val();
    //console.log(datastring);
    /*$('.modal').modal('hide');*/

    if (dniBeneficiario.length > 5 && dniBeneficiario > 5000 && dniBeneficiario < 99999999) {

        NProgress.start();
        var duracion = 5000;
        type = 'POST'
        $.ajax({
            url: 'controller/cotizaciones/iniciarSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'dniBeneficiario': dniBeneficiario, 'cnt': miliseg() },
            success: function (datos) {

                NProgress.set(0.9);
                $('#cargarCotizacion').html(datos);

                /*   $('#dataTable1').DataTable({
                       "responsive": true,
                       "paging": true
                   });*/

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
    var idPersona = $("#idPersonac").val();
    var idSolicitud = $("#idSolicitudc").val();
    var puntoDispensa = $("#puntosDispensa").val();
    var telefono = $("#telefono").val();
    var email = $("#email").val();
    var observaciones = $("#observaciones").val();
    var idProveedor = $("#idProveedorc").val();


    //console.log(datastring);
    /*$('.modal').modal('hide');*/

    if (puntoDispensa > 0 && idPersona > 0) {
          $('#guardar').attr('disabled', 'disabled');
        NProgress.start();
        var duracion = 5000;
        type = 'POST'
        $.ajax({
            url: 'controller/cotizaciones/guardarSolicitud.php',
            type: type,
            dataType: 'text',
            async: true,
            timeout: duracion,
            data: { 'idSolicitud': idSolicitud, 'idPersona': idPersona, 'puntoDispensa': puntoDispensa, 'telefono': telefono, 'email': email, 'observaciones': observaciones, 'esB24': 0, 'esSur': 0, 'fecha_vigencia_cotiz': '', 'idProveedor': idProveedor, 'cnt': miliseg() },
            success: function (datos) {
                $('#guardar').removeAttr('disabled');
                NProgress.set(0.9);
                $('#cargarCotizacion').html(datos);

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
				$('#guardar').removeAttr('disabled');
            })
            .done(function (data) {
                //    console.log('Done!!!!!! ');
                NProgress.done();
				$('#guardar').removeAttr('disabled');
            })
    } else {
        notificar('Debe indicar un Punto de dispensa');
    }

}


function traerItemsMateriales(control) {
    if (control == 1) {
        var idSolicitud = $("#idSolicitudc").val();
    }
    else {
        var idSolicitud = $("#idSolicitud").val();
    }

    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/solicitudItemsOrtopedia.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'cnt': miliseg() },
        success: function (datos) {

            if (control == 1) {
                $('#itemsc').html(datos);
                $('#idProductoc').selectpicker();
            }
            else {
                $('#items').html(datos);
                $('#idProductoc1').selectpicker();
            }


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


function estadosSolicitud(control) {

    if (control == 1) {
        var idSolicitud = $("#idSolicitudc").val();
        var idCotizacion = $("#idCotizacionc").val();
    }
    else {
        var idSolicitud = $("#idSolicitud").val();
        var idCotizacion = $("#idCotizacion").val();

    }

    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/estadosSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'idCotizacion': idCotizacion },
        success: function (datos) {

            if (control == 1) {
                $('#estadoc').html(datos);
            }
            else {
                $('#estado').html(datos);

            }




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

function traerAdjuntos(control, idSolicitud) {

    var datos = '<iframe src="controller/file_manager/index.php?idSolicitud=' + idSolicitud + '&adjuntosMateriales=1&tipo=M&externo=1" style="position: relative;top: 0;left: 0;bottom: 0;right: 0;width: 100%;height: 100%; min-height:500px;frameborder="0" scrolling="yes"">';


    if (control == 1) {
        $('#adjuntos').html(datos);
    }
    else {
        $('#adjuntosc').html(datos);
    }

}



function agregarItem(control) {

    if (control == 1) {
        var idSolicitud = $("#idSolicitudc").val();
        var idProveedor = $("#idProveedorc").val();
        var idProducto = $("#idProductoc").val();
        var cantCotizada = $("#cantCot").val();
        var importeUnit = $("#importeUnit").val();
        var totalCot = $("#totalCot").val();

    }
    else {
        var idSolicitud = $("#idSolicitud").val();
        var idProveedor = $("#idProveedor").val();
        var idProducto = $("#idProductoc1").val();
        var cantCotizada = $("#cantCot1").val();
        var importeUnit = $("#importeUnit1").val();
        var totalCot = $("#totalCot1").val();


    }




    if (idProducto === "") {
        notificar('Seleccione un Producto.');
    }
    else {

        if (isNaN(cantCotizada))
            notificar('Ingrese una cantidad válida.');
        else
            if (cantCotizada <= 0)
                notificar('La cantidad debe ser mayor a cero.');
            else

                if (isNaN(importeUnit))
                    notificar('Ingrese un importe válido.');
                else
                    if (importeUnit <= 0)
                        notificar('El importe debe ser mayor a cero.');
                    else {

                        var duracion = 5000;
                        type = 'POST'
                        $.ajax({
                            url: 'controller/cotizaciones/agregarItemsOrtopedia.php',
                            type: type,
                            dataType: 'text',
                            async: true,
                            timeout: duracion,
                            data: { 'idSolicitud': idSolicitud, 'idProveedor': idProveedor, 'idProducto': idProducto, 'cantCotizada': cantCotizada, 'importeUnit': importeUnit, 'totalCot': totalCot, 'control': control },
                            success: function (datos) {

                                if (Number(datos) == -1) notificar('Error al insertar items cotización. Intente nuevamente.');
                                else
                                    if (Number(datos) == -2) notificar('Error al insertar items solicitud. Intente nuevamente.');
                                    else {

                                        if (control == 1) {
                                            $('#tbodyitemscot').html(datos);
                                            $('#cantCot').val('1');
                                            $('#importeUnit').val('0.00');
                                            $('#totalCot').val('0.00');

                                        }
                                        else {
                                            $('#tbodyitemscot1').html(datos);
                                            $('#cantCot1').val('1');
                                            $('#importeUnit1').val('0.00');
                                            $('#totalCot1').val('0.00');

                                        }

                                        recargarComboProductos(idSolicitud, control);
                                        notificar('Agregado con Éxito.');
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
    }



    foc("observacionEstado");

}

function cancelarModalEstado() {

    $('#modalComentariosSolicitud').modal('hide');

}

function cerrarModalEstado() {

    $('#modalComentariosSolicitud').modal('hide');

}


function onChangeStatusSolicitud() {
    var idSolicitud = $("#idSolicitud").val();
    var observacion = $("#observacionEstado").val();
    var nuevoEstado = $("#idEstadoNuevo33").val();
    document.getElementById('cambiarEstado').setAttribute('disabled', 'disabled');


    if ((nuevoEstado == 'obs' && observacion.length > 0) || nuevoEstado == '31' || nuevoEstado == '32' || nuevoEstado == '33' || nuevoEstado == '34' || nuevoEstado == '35' || nuevoEstado == '36' || nuevoEstado == '37' || nuevoEstado == '38') {
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
                $('#estadoc').html(datos);
                $('.collapse').collapse();


                $('#modalComentariosSolicitud').modal('hide');
                $('.modal-backdrop').fadeOut(10);
                //notificar('Estado registrado correctamente');
                // traerItems();
                // proveedoresSolicitud();
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


function calcularTotalOrtopedia(cnt) {

    if (cnt == '') {
        var cantidad = $("#cantCot").val();
        var unitario = $("#importeUnit").val();

    }
    else {
        var cantidad = $("#cantCot1").val();
        var unitario = $("#importeUnit1").val();
    }

    if (cantidad == "") cantidad = 0;
    if (unitario == "") unitario = 0;

    var total = parseFloat(cantidad) * parseFloat(unitario);

    if (cnt == '') {
        $('#totalCot').val(total.toFixed(2));
    } else {
        $('#totalCot1').val(total.toFixed(2));
    }

}

function traerValorSugerido(id, index) {
    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/traerValorSugerido.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'id': id },
        success: function (respuesta) {

            if (Number(respuesta) != 0) {
                $('#divSugerido').removeAttr('style');
            }
            else
                $('#divSugerido').attr('style', 'display:none;');

            $('#importeUnit'+index).val(respuesta);
            setTimeout(function () {
                calcularTotalOrtopedia(index);
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


function solicitarAutorizacion(control) {

    // validar si existe al menos un producto cargado

    if (control == 1) { var idSolicitud = $("#idSolicitudc").val(); }
    else { var idSolicitud = $("#idSolicitud").val(); }
    var dni = $("#dni").val();


    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/verificarCantProductosSolicitud.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (respuesta) {

            //Si existen items cargados, valido.
            if (respuesta == 1) {
                bootbox.confirm({
                    message: '¿Confirma realizar la operación?',
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


                            var duracion = 5000;
                            type = 'GET'
                            $.ajax({
                                url: 'controller/cotizaciones/validarAutorizacionOrtop.php',
                                type: type,
                                dataType: 'jsonp',
                                jsonpCallback: 'datosvalidacion',
                                async: false,
                                timeout: duracion,
                                data: { 'idSolicitud': idSolicitud },
                                success: function (datos) {
                                    console.log("Salida Validada 1: " + datos)

                                    var valida = datos;
                                    valida = atob(valida);
                                    valida = JSON.parse(valida);

                                    if (valida.autoriza === "S") {
                                        autorizacionValidada(valida.autoriza, valida.comentarios, control);
                                        //notificar('EXITO: La solicitud ha sido APROBADA.');
                                    }
                                    else {
                                        autorizacionValidada(valida.autoriza, valida.comentarios, control);
                                        notificar('ATENCION: La solicitud ha sido enviada a AUDITORIA OSPEPRI para su revisión.');
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
            else {
                //si no existen items cargados, error. 
                bootbox.alert('ATENCIÓN: Debe cargar al menos un producto para continuar.');

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


function autorizacionValidada(autoriza, comentarios, control) {


    if (control == 1) {
        var idSolicitud = $("#idSolicitudc").val();
        var idCotizacion = $("#idCotizacionc").val();
    }
    else {
        var idSolicitud = $("#idSolicitud").val();
        var idCotizacion = $("#idCotizacion").val();
    }


    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/solicitarAutorizacionOrtop.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'idCotizacion': idCotizacion, 'autoriza': autoriza, 'comentarios': comentarios },
        success: function (datos) {

            if (Number(datos) == 1) {
                notificar('La solicitud ha sido AUTORIZADA automáticamente.');
                //  notificar('Se envió la solicitud correctamente,  quedando PENDIENTE DE AUDITORIA.');
                traerItemsMateriales(control);
                estadosSolicitud(control);
            }

            if (Number(datos) == 2) {
                //  notificar('La solicitud ha sido AUTORIZADA automáticamente.');
                //  notificar('Se envió la solicitud correctamente,  quedando PENDIENTE DE AUDITORIA.');
                traerItemsMateriales(control);
                estadosSolicitud(control);
            }

            if (Number(datos) == 0) {
                notificar('Error al enviar, por favor intente nuevamente en un instante.');
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


function eliminarItem(idItemS, idItemCotizacion, control) {

    if (control == 1)
        var idSolicitud = $("#idSolicitudc").val();
    else
        idSolicitud = $("#idSolicitud").val();

    bootbox.confirm({
        message: '¿Confirma la eliminación del ítem seleccionado?',
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

                var duracion = 5000;
                type = 'POST'
                $.ajax({
                    url: 'controller/cotizaciones/eliminarItemCotizacion.php',
                    type: type,
                    dataType: 'text',
                    async: true,
                    timeout: duracion,
                    data: { 'idItemS': idItemS, 'idSolicitud': idSolicitud, 'idItemCotizacion': idItemCotizacion },
                    success: function (datos) {
                        if (Number(datos) == 0)
                            notificar('Ha ocurrido un problema, por favor intente nuevamente.');
                        else {
                            notificar('Se ha eliminado item con éxito.');
                            if (control == 1) {
                                $('#cantCot').val('1');
                                $('#importeUnit').val('0.00');
                                $('#totalCot').val('0.00');

                            }
                            else {
                                $('#cantCot1').val('1');
                                $('#importeUnit1').val('0.00');
                                $('#totalCot1').val('0.00');
                            }

                            recargarComboProductos(idSolicitud, control);
                            recargarListaProductos(idSolicitud, control);
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

function recargarComboProductos(idSolicitud, control) {

    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/recargarComboProductos.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud },
        success: function (datos) {
            if (Number(datos) == 0)
                notificar('Ha ocurrido un problema, por favor intente nuevamente.');
            else {
                if (control == 1) {
                    $('#idProductoc').html(datos);
                    $('#idProductoc').selectpicker('refresh');
                }
                else {
                    $('#idProductoc1').html(datos);
                    $('#idProductoc1').selectpicker('refresh');
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


function recargarListaProductos(idSolicitud, control) {

    var duracion = 5000;
    type = 'POST'
    $.ajax({
        url: 'controller/cotizaciones/recargarListaProductos.php',
        type: type,
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'idSolicitud': idSolicitud, 'control': control },
        success: function (datos) {
            if (control == 1)
                $('#tbodyitemscot').html(datos);
            else
                $('#tbodyitemscot1').html(datos);
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

function imprimirReporte(idSolitud, idCotizacion, idProveedor, idTipoSolicitud) {

    var form = "controller/cotizaciones/reporteCotizacion.php";
    var ventana = window.open(form + "?idSolicitud=" + idSolitud+'&idCotizacion='+idCotizacion+'&idProveedor='+idProveedor+'&idTipoSolicitud='+idTipoSolicitud, "Reporte Solicitud");

}

