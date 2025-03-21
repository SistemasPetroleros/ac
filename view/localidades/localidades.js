function editar(id='',  nombre='', id_provincia='', fechaAlta='', userAlta='',fechaModif='', userModif='')
{
        
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#userAlta").html(userAlta);
        $("#fechaAlta").html(fechaAlta);
        $("#userModif").html(userModif);
        $("#fechaModif").html(fechaModif);

        if (id_provincia!=""){
           $("#id_provincia").val(id_provincia);
        }


        if (id > 0)
        {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
        }
    

}


    