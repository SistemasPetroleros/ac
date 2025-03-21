function editar(id='',  nombre='', fechaAlta='', userAlta='',fechaModif='', userModif='')
{
        
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#userAlta").html(userAlta);
        $("#fechaAlta").html(fechaAlta);
        $("#userModif").html(userModif);
        $("#fechaModif").html(fechaModif);

      


        if (id > 0)
        {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
        }
    

}

    