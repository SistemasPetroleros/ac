<form role="form" action="" method="post" id="formulario">

    <br />
    <legend></legend>


    <div class="form-row">

        <div class="form-group col-sm-6">
            <label for="nroSolicitud" class="control-label">Id. Solicitud</label>
            <input class="form-control" id="nroSolicitud" name="nroSolicitud" value="<?= $solicitud->getid()?>" readonly>
        </div>

        <div class=" form-group col-sm-6">
            <label for="idCotizacion" class="control-label">Id. Cotización</label>
            <input class="form-control" id="idCotizacion" name="idCotizacion" value="<?php echo $idCotizacion ?>" readonly>
        </div>



    </div>

    <div class="form-row">
        <div class="form-group col-sm-4">
            <label for="dni" class="control-label">DNI Afiliado</label>
            <input class="form-control" name="dni" id="dni" value="<?= $persona->getdni() ?>" required="" disabled>
        </div>
        <div class="form-group col-sm-4">
            <label for="apellido" class="control-label">Apellido Afiliado</label>
            <input class="form-control" name="apellido" id="apellido" value="<?= $persona->getapellido() ?>" required="" disabled>
        </div>

        <div class="form-group col-sm-4">
            <label for="nombre" class="control-label">Nombre Afiliado</label>
            <input type="hidden" id="idPersona" name="idPersona" value="<?= $persona->getid() ?>">
            <input type="hidden" id="idSolicitud" name="idSolicitud" value="<?= $solicitud->getid() ?>">
            <input type="hidden" id="idTipoSolicitud" name="idTipoSolicitud" value="<?= $solicitud->getid_tipo_solicitud() ?>">
            <input class="form-control" id="nombre" name="nombre" type="text" value="<?= $persona->getnombre() ?>" required="" disabled>
        </div>

    </div>



    <div class="form-row">
        <div class="form-group col-sm-4">
            <label for="ptoDispensa" class="control-label">Farmacia</label>
            <input class="form-control" name="ptoDispensa" id="ptoDispensa" value="<?= $puntosdispensa->getnombre() ?>" required="" disabled>
        </div>

        <div class="form-group col-sm-4">
            <label for="gln" class="control-label">GLN</label>
            <input class="form-control" name="gln" id="gln" value="<?= $puntosdispensa->getGLN() ?>" required="" disabled>
        </div>

        <div class="form-group col-sm-4">
            <label for="fvigencia" class="control-label">Fecha Vencimiento Cotización</label>
            <input class="form-control" name="fvigencia" id="fvigencia" value="<?= fecha4($solicitud->getfecha_vigencia_cotiz()) ?>" required="" disabled>
        </div>
    </div>


    <div class="form-row">
        <div class="form-group col-sm-12">
            <label for="proveedor" class="control-label">Proveedor</label>
            <input class="form-control" name="proveedor" id="proveedor" value="<?= $proveedor->getnombre() ?>" required="" disabled>
            <input class="form-control" type="hidden" name="idProveedor" id="idProveedor" value="<?= $cotizacion->getid_proveedores() ?>">
        </div>
    </div>


</form>