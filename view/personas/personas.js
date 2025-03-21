function editar(id='', apellido='', nombre='', dni='', estadoSIA='', email='', telefono='', nroInternoSIA='', fechaAlta='', userAlta='',fechaModif='', userModif='', b24='')
    {
        $("#id").val(id);
        $("#apellido").val(apellido);
        $("#nombre").val(nombre);
        $("#dni").val(dni);
       
        $("#userAlta").html(userAlta);
        $("#fechaAlta").html(fechaAlta);
        $("#userModif").html(userModif);
        $("#fechaModif").html(fechaModif);
        $("#telefono").val(telefono);
        $("#email").val(email);
        $("#nroInternoSIA").val(nroInternoSIA);

        if(estadoSIA=="A"){
           $("#estadoSIA").val('Activo');
        }
        else{
            $("#estadoSIA").val('Inactivo');
        } 
        if (b24==1)
        { 
             document.getElementById("b24").checked = true;
        }
        else
        {
            document.getElementById("b24").checked = false;
        }
        

        if (id > 0)
        {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
    }

    }
