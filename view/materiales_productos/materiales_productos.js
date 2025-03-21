function editar(id='',  nombre='', descripcion='',  usrAlta='',fechaAlta='', usrModif='', fechaModif='', activo='')
    {
        
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#descripcion").val(descripcion);
        $("#usrAlta").html(usrAlta);
        $("#fechaAlta").html(fechaAlta);
        $("#usrModif").html(usrModif);
        $("#fechaModif").html(fechaModif);
        $("#activo").val(activo);
       
        if (activo==1)
        { 
             document.getElementById("activo").checked = true;
        }
        else
        {
            document.getElementById("activo").checked = false;
        }
              



        if (id > 0)
        {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
    }

}
