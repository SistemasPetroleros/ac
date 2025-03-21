<?php
include 'class.php';
include 'login.php';

set_time_limit(300);
ini_set('max_execution_time', 3600);

$rand = rand(100, 999);
$mensaje = '';
$obj = new ab_manualdat();
$array = $obj->SelectTodosCantidades();




function ablog($n, $c) {
    $x = new ab_log();
    $x->setarchivo($n);
    $x->setcantidad($c);
    $x->Create();
}

if (isset($_FILES["zip_file"])) {

 


//comprobamos si se ha recibido el nombre del ZIP
    if ($_FILES["zip_file"]["name"]) {
        //obtenemos datos de nuestro ZIP
        $nombre = $_FILES["zip_file"]["name"];
        $ruta = $_FILES["zip_file"]["tmp_name"];
        $tipo = $_FILES["zip_file"]["type"];

        // --> AQUÍ INCLUIR ALGUNA DE LAS TRES FUNCIONALIDADES <--
    }

    $dir = 'c:/unzip/';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $archive = zip_open($ruta);
    while ($entry = zip_read($archive)) {
        $size = zip_entry_filesize($entry);
        $name = zip_entry_name($entry);
        $unzipped = fopen($dir . $name, 'wb');
        while ($size > 0) {
            $chunkSize = ($size > 10240) ? 10240 : $size;
            $size -= $chunkSize;
            $chunk = zip_entry_read($entry, $chunkSize);
            if ($chunk !== false)
                fwrite($unzipped, $chunk);
        }

        fclose($unzipped);
    }

    
    $codif='';
    
    $cnt = 0;
    $query = "";
    $file = fopen($dir . "monodro.txt", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_monodro (`codigo`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        
             
         $valor = fgets($file);
         $valor = iconv("CP850","UTF-8//TRANSLIT", $valor);
         $valor = ($valor);
         $valor = sanear_string($valor);  
         
        $query .= "(" . intval(substr($valor, 0, 5)) . ",'" . mysqli_real_escape_string($dblink,trim(substr($valor, 5, 32))) . "')";
        if ($cnt % 1000 == 999){
           
            $query .= "; ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        //$query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        $query .= "; ";
        Query($query);
    }
    
    fclose($file);
    echo "<script> $('#monodro').html('" . $cnt . $codif. "'); </script>";
   
    ablog('monodro', $cnt);

    
    
    

    $cnt = 0;
    $query = "";
    $file = fopen($dir . "manual.dat", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_manualdat (`troquel`,`nombre`,`presentacion`,`ioma1`,`ioma2`,`ioma3`,`laboratorio`,`precio`,`fecha`,`prodcontrolado`,`importado`,`tipoventa`,`iva`,`coddescpami`,`codlab`,`nroregistro`,`baja`,`codbarra`,`unidades`,`tamano`,`heladera`,`sifar`,`gravamen`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);  
        
        $query .= "('" . substr($valor, 0, 7) . "','" . substr($valor, 7, 44) . "','" . substr($valor, 51, 24) . "','" . substr($valor, 75, 8) . "'"
                . ",'" . substr($valor, 83, 1) . "','" . substr($valor, 84, 1) . "','" . substr($valor, 85, 16) . "','" . substr($valor, 101, 9) . "'"
                . ",'" . substr($valor, 110, 8) . "','" . substr($valor, 118, 1) . "','" . substr($valor, 119, 1) . "','" . substr($valor, 120, 1) . "'"
                . ",'" . substr($valor, 121, 1) . "','" . substr($valor, 122, 1) . "','" . substr($valor, 123, 3) . "','" . substr($valor, 126, 5) . "'"
                . ",'" . substr($valor, 131, 1) . "','" . substr($valor, 132, 13) . "','" . substr($valor, 145, 4) . "','" . substr($valor, 149, 1) . "'"
                . ",'" . substr($valor, 150, 1) . "','" . substr($valor, 151, 1) . "','" . substr($valor, 153, 1) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE `troquel` = VALUES(troquel),
                                        `nombre` = VALUES(nombre),
					`presentacion` = VALUES(presentacion),
					`ioma1` = VALUES(ioma1),
					`ioma2` = VALUES(ioma2),
					`ioma3` = VALUES(ioma3),
					`laboratorio` = VALUES(laboratorio),
					`precio` = VALUES(precio),
					`fecha` = VALUES(fecha),
					`prodcontrolado` = VALUES(prodcontrolado),
					`importado` = VALUES(importado),
					`tipoventa` = VALUES(tipoventa),
					`iva` = VALUES(iva),
					`coddescpami` = VALUES(coddescpami),
					`codlab` = VALUES(codlab),
					`baja` = VALUES(baja),
					`codbarra` = VALUES(codbarra),
					`unidades` = VALUES(unidades),
					`tamano` = VALUES(tamano),
					`heladera` = VALUES(heladera),
					`sifar` = VALUES(sifar),
					`gravamen` = VALUES(gravamen);";
            Query($query);
            $query = '';
        }
        $cnt += 1;
        
        if(substr($valor, 0, 7)=='9923380'){archivo('AlfabetaCheck.txt', $valor);}
        
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE `troquel` = VALUES(troquel),
                                        `nombre` = VALUES(nombre),
					`presentacion` = VALUES(presentacion),
					`ioma1` = VALUES(ioma1),
					`ioma2` = VALUES(ioma2),
					`ioma3` = VALUES(ioma3),
					`laboratorio` = VALUES(laboratorio),
					`precio` = VALUES(precio),
					`fecha` = VALUES(fecha),
					`prodcontrolado` = VALUES(prodcontrolado),
					`importado` = VALUES(importado),
					`tipoventa` = VALUES(tipoventa),
					`iva` = VALUES(iva),
					`coddescpami` = VALUES(coddescpami),
					`codlab` = VALUES(codlab),
					`baja` = VALUES(baja),
					`codbarra` = VALUES(codbarra),
					`unidades` = VALUES(unidades),
					`tamano` = VALUES(tamano),
					`heladera` = VALUES(heladera),
					`sifar` = VALUES(sifar),
					`gravamen` = VALUES(gravamen);";
        Query($query);
    }
    
    fclose($file);
    echo "<script> $('#manualdat').html('" . $cnt . "');</script>";
    ablog('manualdat', $cnt);
    
    

    $cnt = 0;
    $query = "";
    $file = fopen($dir . "vias.txt", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_vias (`codigo`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 50) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        Query($query);
    }
    
    fclose($file);
    echo "<script> $('#vias').html('" . $cnt . "');</script>";
    ablog('vias', $cnt);

    
    
    

    $cnt = 0;
    $query = "I";
    $file = fopen($dir . "tamanos.txt", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_tamanos (`codigo`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 2) . "','" . substr($valor, 2, 32) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        Query($query);
    }
    
    fclose($file);
    echo "<script> $('#tamanos').html('" . $cnt . "');</script>";
    ablog('tamanos', $cnt);





    $cnt = 0;
    $query = "";
    $file = fopen($dir . "acciofar.txt", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_acciofar (`codigo`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 32) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
            Query($query);
            $query = '';
        }

        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        Query($query);
    }
    
    fclose($file);
    echo "<script> $('#acciofar').html('" . $cnt . "');</script>";
    ablog('acciofar', $cnt);


    
    

    $cnt = 0;
    $query = "";
    $file = fopen($dir . "formas.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_formas (`codigo`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 50) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#formas').html('" . $cnt . "');</script>";
    ablog('formas', $cnt);


    
    
    
    $cnt = 0;
    $query = "INSERT INTO ab_tipounid (`codigo`,`descripcion`) VALUES ";
    $file = fopen($dir . "tipounid.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_tipounid (`codigo`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 50) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#tipounid').html('" . $cnt . "');</script>";
    ablog('tipounid', $cnt);
    
    
    
    
    $cnt = 0;
    $query = "";
    $file = fopen($dir . "nuevadro.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_nuevadro (`coddroga`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 36) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#nuevadro').html('" . $cnt . "');</script>";
    ablog('nuevadro', $cnt);

    
    
    
    $cnt = 0;
    $query = "";
    $file = fopen($dir . "upotenci.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_upotenci (`codigo`,`descripcion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 50) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion); ";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#upotenci').html('" . $cnt . "');</script>";
    ablog('upotenci', $cnt);
    






    $cnt = 0;
    $query = "";
    $file = fopen($dir . "barextra.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_barextra (`nroreg`,`codigobarra`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 13) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE codigobarra = VALUES(codigobarra); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE codigobarra = VALUES(codigobarra); ";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#barextra').html('" . $cnt . "');</script>";
    ablog('barextra', $cnt);
    
    
    
    
    
    
    $cnt = 0;
    $query = "";
    $file = fopen($dir . "gtin1.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_gtin1 (`nroreg`,`gtin`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 14) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE gtin = VALUES(gtin); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE gtin = VALUES(gtin); ";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#gtin1').html('" . $cnt . "');</script>";
    ablog('gtin1', $cnt);
    
    
    
    
    $cnt = 0;
    $query='';
    $file = fopen($dir . "multidro.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_multidro (`nroreg`,`coddroga`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 5) . "')";
        
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE coddroga = VALUES(coddroga); ";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE coddroga = VALUES(coddroga); ";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#multidro').html('" . $cnt . "');</script>";
    ablog('multidro', $cnt);
    
    
    
    
    
    $cnt = 0;
    $query = "";
    $file = fopen($dir . "regnueva.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = "INSERT INTO ab_regnueva (`nroreg`,`coddroga`,`potencia`,`codunipotencia`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 5) . "','" . substr($valor, 10, 16) . "','" . substr($valor, 26, 5) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE coddroga = VALUES(coddroga),
                                        potencia = VALUES(potencia),
                                        codunipotencia = VALUES(codunipotencia);";
            Query($query);
            $query = '';
        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE coddroga = VALUES(coddroga),
                                        potencia = VALUES(potencia),
                                        codunipotencia = VALUES(codunipotencia);";
        Query($query);
    }
        
    fclose($file);
    echo "<script> $('#regnueva').html('" . $cnt . "');</script>";
    ablog('regnueva', $cnt);
    
    




    
    
    
    
    
    $cnt = 0;
    $query = "";
    $file = fopen($dir . "manextra.txt", "r") or exit("Unable to open file!");
    while (!feof($file)) {
        if ($cnt % 1000 == 0){
            $query = " INSERT INTO ab_manextra (`nroregistro`,`codtamano`,`codaccionfar`,`coddroga`,`codformafar`,`potencia`,`codunipotencia`,`codtipounidad`,`codviaadministracion`) VALUES ";
        } else {
            $query .= " , ";
        }
        $valor = fgets($file);
        $valor = ($valor);
        $valor = sanear_string($valor);
        
        $query .= "('" . substr($valor, 0, 5) . "','" . substr($valor, 5, 2) . "','" . substr($valor, 7, 5) . "'," . intval(substr($valor, 12, 5)) . ""
                . ",'" . substr($valor, 17, 5) . "','" . substr($valor, 22, 16) . "','" . substr($valor, 38, 5) . "','" . substr($valor, 43, 5) . "'"
                . ",'" . substr($valor, 48, 5) . "')";
        if ($cnt % 1000 == 999){
            $query .= " ON DUPLICATE KEY UPDATE `codtamano` = VALUES(codtamano),
					`codaccionfar` = VALUES(codaccionfar),
					`coddroga` = VALUES(coddroga),
					`codformafar` = VALUES(codformafar),
					`potencia` = VALUES(potencia),
					`codunipotencia` = VALUES(codunipotencia),
					`codtipounidad` = VALUES(codtipounidad),
					`codviaadministracion` = VALUES(codviaadministracion);";
            Query($query);
            //echo $query;//exit;
            $query = '';

        }
        $cnt += 1;
    }
    
    if (strlen($query) > 1) {
        $query .= " ON DUPLICATE KEY UPDATE `codtamano` = VALUES(codtamano),
					`codaccionfar` = VALUES(codaccionfar),
					`coddroga` = VALUES(coddroga),
					`codformafar` = VALUES(codformafar),
					`potencia` = VALUES(potencia),
					`codunipotencia` = VALUES(codunipotencia),
					`codtipounidad` = VALUES(codtipounidad),
					`codviaadministracion` = VALUES(codviaadministracion);";
        Query($query);
    }
    fclose($file);
    echo "<script> $('#manextra').html('" . $cnt . "');</script>";
    ablog('manextra', $cnt);
    
   // echo "<script> console.log('" . $query . "');</script>";
    
    
   
    





//borramos todos los archivos creados
    $handle = opendir($dir);
    $ficherosEliminados = 0;
    while ($file = readdir($handle)) {
        if (is_file($dir . $file)) {
            if (unlink($dir . $file)) {
                $ficherosEliminados++;
            }
        }
    }

    echo "<script> $('#info34').html('<div class=\"alert alert-success\">Alfabeta fue actualizado correctamente</div>');</script>";
}else{
?>

<script>
    function guardarArchivo(url, idForm) {
        //var datastring = $("#" + idForm).serialize();
        $('.hidetr').show(1000);
        $('.actualizando').show(1000);
        var comprobar = $('#zip_file').val().length; 
        if(comprobar > 0){
            var f = $('#' + idForm);
            var formData = new FormData(document.getElementById(idForm));
            for (var i = 0; i < (f.find('input[type=file]').length); i++) { 
                formData.append((f.find('input[type="file"]:eq('+i+')').attr("name")),((f.find('input[type="file"]:eq('+i+')')[0]).files[0]));
            }

            
        $('.modal').modal('hide');
        $('.modal-backdrop').fadeOut();
        NProgress.start();
        var divInsert = 'result';
        var duracion = 300000;
        $.ajax({
            url: url,
            type: "post",
            dataType: "html",
            timeout: duracion,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (datos) {
                NProgress.set(0.9);
                $('#' + divInsert).html(datos);
                $('#zip_file').val('');
                $('.actualizando').hide(1000);
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
    }
</script>



<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Alfabeta</h3><?= $mensaje ?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- subtitulo -->
                <span class="pull-right">
                    <button type="button" class="btn btn-dark btn-circle btn-lg " data-toggle="modal" data-target=".bs-example-modal-lg" ><i class="fa fa-plus"></i>
                    </button>
                </span>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table jambo_table" id="dataTable1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>manualdat</th>
                            <th>acciofar</th>
                            <th>barextra</th>
                            <th>formas</th>
                            <th>gtin1</th>
                            <th>manextra</th>
                            <th>monodro</th>
                            <th>multidro</th>
                            <th>nuevadro</th>
                            <th>regnueva</th>
                            <th>tamanos</th>
                            <th>tipounid</th>
                            <th>upotenci</th>
                            <th>vias</th>
                        </tr>
                    </thead>
                    <tbody>
<?php


while ($x = mysqli_fetch_assoc($array)) {
    echo '<tr class="odd gradeX"><td><b>Total</b></td><td>' . $x['manualdat'] . '</td><td>' . $x['acciofar'] . '</td><td>' . $x['barextra'] . '</td><td>' . $x['formas'] . '</td><td>' . $x['gtin1'] . '</td>'
    . '<td>' . $x['manextra'] . '</td><td>' . $x['monodro'] . '</td><td>' . $x['multidro'] . '</td><td>' . $x['nuevadro'] . '</td><td>' . $x['regnueva'] . '</td><td>' . $x['tamanos'] . '</td>'
    . '<td>' . $x['tipounid'] . '</td><td>' . $x['upotenci'] . '</td><td>' . $x['vias'] . '</td></tr>';
}


    ?>
                            <tr class="odd gradeX hidetr" style="display: none;">
                                <td><b>Actualizado</b></td>
                                <td><span id="manualdat"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="acciofar"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="barextra"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="formas"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="gtin1"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="manextra"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="monodro"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="multidro"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="nuevadro"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="regnueva"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="tamanos"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="tipounid"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="upotenci"><img src="img/loading.gif" height="20px"/></span></td>
                                <td><span id="vias"><img src="img/loading.gif" height="20px"/></span></td>
                            </tr>
                    </tbody>
                </table>

                <!-- /.table-responsive -->
                <div class="well" >
                    <p>Se debe subir el archivo comprimido que se descarga de <a href="http://www.alfabeta.net/descargas/index.jsp" target="_blank">Alfabeta.</a></p>
                    <p>LOG de actualizaciones <a href="#" onclick="menu('alfabetaLOG.php', 'Log Alfabeta', '237',1); return false;">AQUÍ</a>    
                    </p>
                    <div id="info34">

                        <div class="alert alert-info actualizando" style="display: none;">Actualizando... <img src="img/loading.gif" height="20px"/></div>


                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>




<!--MODAL-->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="Editar">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
                <h4 class="modal-title" id="exampleModalLabel">Editar</h4> </div> 

                <form enctype="multipart/form-data" role="form" action="javascript:;" method="post" id="formulario"> 
                <div class="modal-body"> 
                    <div class="form-group"> 
                        <label for="zip_file" class="control-label">Archivo Alfabeta</label> 
                        <input class="form-control" id="zip_file" name="zip_file" type="file" required=""> 
                        <p class="help-block">Se debe subir el archivo comprimido que se descarga de <a href="http://www.alfabeta.net/descargas/index.jsp" target="_blank">Alfabeta.</a></p>
                    </div> 
                </div> 
                <div class="modal-footer">

                    <button type="reset" class="btn btn-default" onclick="editar();">Limpiar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="editar();">Cerrar</button> 
                    <button type="button" class="btn btn-primary" onclick="guardarArchivo('alfabeta.php', 'formulario');" name="guardar">Guardar</button> 
                </div> 
            </form> 
        </div>
    </div>
</div>
</div>
<div id="result"></div>
<?php } ?>