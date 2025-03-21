function editar(id='',  nombre='', tipo='',  fechaAlta='', userAlta='',fechaModif='', userModif='')
{
        
        $("#id").val(id);
        $("#nombre").val(nombre);
		$("#tipo").val(tipo);
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

    