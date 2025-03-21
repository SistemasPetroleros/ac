function editar(id='',  nombre='', habilitado='', GLN='', domicilio='',  telefonos='', email='', id_localidad='', id_provincia='', fechaAlta='', userAlta='',fechaModif='', userModif='')
    {
        
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#GLN").val(GLN);
        $("#domicilio").val(domicilio);
        $("#userAlta").html(userAlta);
        $("#fechaAlta").html(fechaAlta);
        $("#userModif").html(userModif);
        $("#fechaModif").html(fechaModif);
        $("#telefonos").val(telefonos);
        $("#email").val(email);

        
        if (habilitado==1)
        { 
             document.getElementById("habilitado").checked = true;
        }
        else
        {
            document.getElementById("habilitado").checked = false;
        }
        
        if (id_provincia!=""){
            $("#id_provincia").val(id_provincia);
            armarComboLocalidades(id_provincia, id_localidad);
        }
       

        

       
        

        if (id > 0)
        {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
    }

    }

    function armarComboLocalidades(id_provincia, id_localidad='')
    {
        $.ajax({
            url: 'controller/puntos_dispensa/getLocalidades.php',
            type: 'POST',
            dataType: 'text',
            async: true,
            timeout: 10000,
            data: {'id_provincia':id_provincia},
            success: function (datos) {
                   
                    var combo=document.getElementById('id_localidades');
                    var array= datos.split('@');

                    combo.options.length = 0;
					var option= document.createElement("option");
					option.text = "Seleccione Opci\u00F3n";
					option.value = "-1";
					combo.appendChild(option);	
                  
                    for(i=0; i<array.length-1; i=i+2)
                    { 
                        option= document.createElement("option");
						option.text = array[i+1];
						option.value = array[i];
						combo.appendChild(option);	
                    }

                    if (id_localidad!=""){
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
