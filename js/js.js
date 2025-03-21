function notificar(msj) {
    $.notiny({ text: msj, width: 'auto', position: 'right-top', image: 'img/alerta.png' });
}

function redirectPost(url, data) {
    var form = document.createElement('form');
    form.method = 'post';
    form.action = url;
    for (var name in data) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
}
// redirectPost('http://www.example.com', { text: 'text\n\ntext' });


function miliseg() {
    var x = new Date();
    return x.getTime();
}
console.log(miliseg());

function getAjaxText(url, divInsert, duracion = 5000) {
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'tag': 'connection', 'cnt': miliseg() },
        success: function (datos) {
            //$('#'+divInsert).fadeOut(duracion).html(datos).fadeIn(duracion); 
            $('#' + divInsert).html(datos);
            console.log("TestNro: " + cntTestConexiones.toString());
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            console.log('Done!!!!!! ');
        })

}




(function ($) {
    $.get = function (key) {
        key = key.replace(/[\[]/, '\\[');
        key = key.replace(/[\]]/, '\\]');
        var pattern = "[\\?&]" + key + "=([^&#]*)";
        var regex = new RegExp(pattern);
        var url = unescape(window.location.href);
        var results = regex.exec(url);
        if (results === null) {
            return null;
        } else {
            return results[1];
        }
    }
})(jQuery);




window.onpopstate = function (event) {
    var r = $.get("r");

    if (r > 0) {

        $('.nav.side-menu li').each(function () {
            $(this).removeClass('active');
        });
        $.ajax({
            url: 'historialNavegacion.php',
            type: 'POST',
            dataType: 'text',
            async: true,
            timeout: 5000,
            data: { 'tag': 'connection', 'r': r },
            success: function (datos) {
                //$("body").append(datos);
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
};

function menu(url, titulo, id, his = 1) {

    setCookie('ultimaPagina', id);
    $('li').each(function () {
        $(this).removeClass('active');
    });
    console.log(id);
    if (his == 1) {
        history.pushState('', titulo, '?r=' + id);
    }
    NProgress.start();
    var divInsert = 'system';
    var duracion = 5000;
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: { 'tag': 'connection', 'cnt': miliseg() },
        success: function (datos) {
            NProgress.set(0.9);
            $('#' + divInsert).html(datos);

            $('#dataTable1').DataTable({
                "responsive": true,
                "paging": true
            });
            $('.fecha').datepicker();
            $('form').keypress(function (e) {
                if (e == 13) {
                    return false;
                }
            });

            $('input').keypress(function (e) {
                if (e.which == 13) {
                    return false;
                }
            });

            $('#inputBuscarModal').keypress(function (e) {
                if (e.which == 13) {
                    buscarModal();
                }
            });
        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fail!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            //    console.log('Done!!!!!! ');
            NProgress.done()

        })

}

function modalVarios(url = '', idForm = '', accion = '') {
    $('#contenidoModalVarios').html('Cargando...');
    var datastring = $("#" + idForm).serialize();
    $('#modalVarios').modal('show');
    NProgress.start();
    var divInsert = 'contenidoModalVarios';
    var duracion = 5000;
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'text',
        async: true,
        timeout: duracion,
        data: datastring + '&' + accion,
        success: function (datos) {
            NProgress.set(0.9);
            $('#' + divInsert).html(datos);
            /*
            $('#dataTable1').DataTable({
                "responsive": true,
                "paging": true
            });*/
            $('.fecha').datepicker();
            $('form').keypress(function (e) {
                if (e == 13) {
                    return false;
                }
            });

            $('input').keypress(function (e) {
                if (e.which == 13) {
                    return false;
                }
            });
            $('#inputBuscarModal').keypress(function (e) {
                if (e.which == 13) {
                    buscarModal();
                }
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


function guardarForm(url, idForm = '', accion = 'guardar', type = 'POST') {
    var datastring = $("#" + idForm).serialize();
    console.log(datastring);
    /*$('.modal').modal('hide');*/
    
    $('.modal').modal('hide');
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
        data: datastring + '&' + accion,
        success: function (datos) {

            NProgress.set(0.9);
            $('#' + divInsert).html(datos);

            $('#dataTable1').DataTable({
                "responsive": true,
                "paging": true
            });
            $('.fecha').datepicker();
            $('form').keypress(function (e) {
                if (e == 13) {
                    return false;
                }
            });

            $('input').keypress(function (e) {
                if (e.which == 13) {
                    return false;
                }
            });

            $('#inputBuscarModal').keypress(function (e) {
                if (e.which == 13) {
                    buscarModal();
                }
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




var cntTestConexiones = 0;
var instanciaTestConexiones = 0;
var instanciaTestConexiones2 = 1;

function testConexiones(inicia) {
    cntTestConexiones += 1;
    var iniciatxt = '';

    if (inicia == 1) {
        instanciaTestConexiones += 1;
        iniciatxt = '?inicia=0';
    }

    getAjaxText('testCon_test.php' + iniciatxt, 'testCon', '');
    if (cntTestConexiones < 10 && instanciaTestConexiones == instanciaTestConexiones2) {
        setTimeout(testConexiones, 1000);
    } else {
        cntTestConexiones = 0;
        instanciaTestConexiones2 += 1;
    }

}
/*
 
 var auto_refresh = setInterval(informaPendientes, 60000);
 setTimeout(informaPendientes, 1000);
 */

function buscarModal() {
    var search = $('#inputBuscarModal').val();
    if (search.length >= 3) {
        $('#resultadomodal').html('<div style="text-align: center;"><img height="30px" src="img/loading.gif"/></div>');
        $.ajax({
            url: 'buscarxmodal.php',
            type: 'POST',
            dataType: 'text',
            async: true,
            timeout: 10000,
            data: { 'tag': 'connection', 'cnt': miliseg(), 'search': search },
            success: function (datos) {
                //$('#'+divInsert).fadeOut(duracion).html(datos).fadeIn(duracion); 
                $('#resultadomodal').html(datos);
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
        $('.bs-modal-lg-buscar').modal('show');
    }
}

function foc(id) {
    setTimeout(function () {
        $("#" + id).focus();
    }, 500);

}

function cargandoImagen(obj) {
    $(obj).html('<img style="width:100px; display:block; margin-left: auto; margin-right: auto;" src="img/pageLoader.gif" alt="Cargando">');
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setCookie(cname, value) {
    document.cookie = cname + '=' + value + ';';
}



function SelecccionarImpresora(impreSelec) {
    $.ajax({
        url: 'impresorasSeleccionar.php',
        type: 'POST',
        dataType: 'text',
        async: true,
        timeout: 5000,
        data: { 'tag': 'connection', 'impreSelec': impreSelec },
        success: function (datos) {
            $('#ImpresorasDisponibles').html(datos);

        }
    })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log('Fallo Seleccion de Impresora!!!!!!');
            console.log(textStatus + ' /// ' + errorThrown + ' /// ' + jqXHR);
        })
        .done(function (data) {
            console.log('Impresora Seleccionada!!!!!!');
        })
}

function CrearProductoActivo() {
    var troquel = $("#troquel").val();
    var nombre = $("#nombre").val();
    var presentacion = $("#presentacion").val();
    var cod_droga = $("#cod_droga").val();
    console.log(cod_droga);
    menu('controller/productos_frecuentes/productos_frecuentes.php', 'Productos Activos', '272', 1);
    var verifica = 0;
    while (verifica == 0) {
        if ($("#nuevoProductoBtn")) {
            verifica = 1;
            setTimeout(function () {
                $("#nuevoProductoBtn").click();
                $("#nombre").val(nombre);
                $("#presentacion").val(presentacion);
                $("#troquel").val(troquel);
                document.getElementById("activo").checked = true;

                setTimeout(function () {
                if (cod_droga != "") {
                    //$("#cod_droga").val(cod_droga);
                    //$("#cod_droga option[value='" + cod_droga + "']").attr("selected", true);
                    $("#cod_droga").val(cod_droga);
                    notificar('Revise los datos sugeridos antes de Guardar');

                }
            }, 500);
        }, 1000);
        }
    }
}

function CrearProductoActivoMonodroga() {
    var troquel = '';
    var nombre = $("#descripcion").val();
    var presentacion = 'Monodroga Generica';
    var cod_droga = $("#codigo").val();
    menu('controller/productos_frecuentes/productos_frecuentes.php', 'Productos Activos', '272', 1);
    var verifica = 0;
    while (verifica == 0) {
        if ($("#nuevoProductoBtn")) {
            verifica = 1;
            setTimeout(function () {
                $("#nuevoProductoBtn").click();
                $("#nombre").val(nombre);
                $("#presentacion").val(presentacion);
                $("#troquel").val(troquel);
                document.getElementById("activo").checked = true;

                setTimeout(function () {
                if (cod_droga != "") {
                    //$("#cod_droga").val(cod_droga);
                    //$("#cod_droga option[value='" + cod_droga + "']").attr("selected", true);
                    $("#cod_droga").val(cod_droga);
                    notificar('Revise los datos sugeridos antes de Guardar');

                }
            }, 500);
        }, 1000);
        }
    }
}



$(document).ready(function () {
    var r = $.get("r");
    if (!r > 0) {
        r = getCookie('ultimaPagina');
    }
    if (!r > 0) {
        r = 232;
    }
    if (r > 0) {

        $('.nav.side-menu li').each(function () {
            $(this).removeClass('active');
        });
        $.ajax({
            url: 'historialNavegacion.php',
            type: 'POST',
            dataType: 'text',
            async: true,
            timeout: 5000,
            data: { 'tag': 'connection', 'r': r },
            success: function (datos) {
                $("body").append(datos);
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


    $('#dataTable1').DataTable({
        "responsive": true,
        "paging": true
    });


    /*
     setTimeout(function () {
     $(".alert").hide('slow');
     }, 10000);
     */

    $('form').keypress(function (e) {
        if (e == 13) {
            return false;
        }
    });

    $('input').keypress(function (e) {
        if (e.which == 13) {
            return false;
        }
    });

    $('#inputBuscarModal').keypress(function (e) {
        if (e.which == 13) {
            buscarModal();
        }
    });


});


$(function () {
    $.fn.datepicker.dates['en'] = {
        days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
        daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        clear: "Borrar",
        format: "dd/mm/yyyy",
        titleFormat: "MM yyyy",
        /* Leverages same syntax as 'format' */
        weekStart: 0
    };
    $('.fecha').datepicker();
});