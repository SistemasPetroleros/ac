function editar(id = '', nombre = '', cuit = '', domicilio = '', telefonos = '', email = '', id_localidad = '', id_provincia = '', fechaAlta = '', userAlta = '', fechaModif = '', userModif = '', habilitado = '', op = '', ids = '', enviarEmail='', iibb="") {



    $("#id").val(id);
    $("#nombre").val(nombre);
    $("#cuit").val(cuit);
    $("#domicilio").val(domicilio);
    $("#userAlta").html(userAlta);
    $("#fechaAlta").html(fechaAlta);
    $("#userModif").html(userModif);
    $("#fechaModif").html(fechaModif);
    $("#telefonos").val(telefonos);
    $("#email").val(email);
    $("#iibb").val(iibb);

    /*if(tipo!=""){
        $("#tipo").val(tipo);

    }*/



    if (id_provincia != "") {
        $("#id_provincia").val(id_provincia);
        armarComboLocalidades(id_provincia, id_localidad);
    }
    if (habilitado == 1) {
        document.getElementById("habilitado").checked = true;
    }
    else {
        document.getElementById("habilitado").checked = false;
    }


    if (enviarEmail == 1) {
        document.getElementById("enviarEmail").checked = true;
    }
    else {
        document.getElementById("enviarEmail").checked = false;
    }

    armarCombosTipo(op, ids);





    if (id > 0) {
        $("#eliminar").show();
    } else {
        $("#eliminar").hide();
    }



}

function armarComboLocalidades(id_provincia, id_localidad = '') {
    $.ajax({
        url: 'controller/proveedores/getLocalidades.php',
        type: 'POST',
        dataType: 'text',
        async: true,
        timeout: 10000,
        data: { 'id_provincia': id_provincia },
        success: function (datos) {

            var combo = document.getElementById('id_localidades');
            var array = datos.split('@');

            combo.options.length = 0;
            var option = document.createElement("option");
            option.text = "Seleccione Opci\u00F3n";
            option.value = "-1";
            combo.appendChild(option);

            for (i = 0; i < array.length - 1; i = i + 2) {
                option = document.createElement("option");
                option.text = array[i + 1];
                option.value = array[i];
                combo.appendChild(option);
            }

            if (id_localidad != "") {
                $("#id_localidades").val(id_localidad);
            }

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

function armarCombosTipo(op, ids) {

    $.ajax({
        url: 'controller/proveedores/getTipoSolicitud.php',
        type: 'POST',
        dataType: 'text',
        async: true,
        timeout: 10000,
        data: {},
        success: function (datos) {

            $('#tipoProv').html(datos);
            $('#tipoProv').selectpicker();
            if (op == "U") {
                let tipos = new Array();
                $.each(ids.split(","), function (i, e) {
                    $("#tipoProv option[value='" + e + "']").attr("selected", 'selected');
                    tipos.push(e); 
                });
                $('#tipoProv').selectpicker('refresh');
                $('#hidden_prov').val(tipos.toString());
            }

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


$('#tipoProv').change(function () {
    $('#hidden_prov').val($('#tipoProv').val());
});


//limpiar modal al cerrar
$(".modal").on("hidden.bs.modal", function() {
    document.getElementById('formulario').reset();
    $("#tipoProv").val('default');
    $('.selectpicker').selectpicker('refresh');
    });

