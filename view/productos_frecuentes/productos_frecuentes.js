function editar(id='',  nombre='', presentacion='', troquel='', gtin='',  cod_droga='', usrAlta='',fechaAlta='', usrModif='', fechaModif='', activo='')
    {
        
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#presentacion").val(presentacion);
        $("#troquel").val(troquel);
		$("#gtin").val(gtin);
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
              
        if (cod_droga!=""){
            //$("#cod_droga").val(cod_droga);
            $("#cod_droga option[value='"+cod_droga+"']").attr("selected",true);
         }
 
       
        

        if (id > 0)
        {
            $("#eliminar").show();
        } else {
            $("#eliminar").hide();
    }

}

  /*  function armarComboLocalidades(id_provincia, id_localidad='') se podra usar armar combo monodro?
    {
        $.ajax({
            url: 'controller/proveedores/getLocalidades.php',
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


    }*/
