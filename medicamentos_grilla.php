<?php
include 'class.php';
set_time_limit(120);
ini_set('max_execution_time', 3600);

$coma = '';
$rand = rand(100, 999);
$mensaje = '';
//$cnt=0;

$iDisplayLength = isset($_POST['length']) ? $_POST['length'] : 10;
$iDisplayStart = isset($_POST['start']) ? $_POST['start'] : 0;
$order = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$order += 1;
$direccion = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : '';
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

if (isset($_GET['fraccionados'])) {
    $obj = new medicamentosfraccionados();
    $obj->settroquel($_GET['fraccionados']);
    $t = isset($_POST['troquel']) ? $_POST['troquel'] : '';
    $u = isset($_POST['unidades']) ? $_POST['unidades'] : '';
    $uf = isset($_POST['unidadesFraccion']) ? $_POST['unidadesFraccion'] : '';
    $nf = isset($_POST['nombreFraccion']) ? $_POST['nombreFraccion'] : '';
    if ($_GET['fraccionados'] == $t and $u > $uf and strlen($nf) > 0) {
        $obj->setunidades($uf);
        $obj->setcntMostrar($nf);
        $obj->Create();
    }



    $array = $obj->SelectAll();
    while ($x = mysqli_fetch_assoc($array)) {
        echo '<tr"><td align="left">' . $x['unidades'] . '</td><td align="left">' . $x['cntMostrar'] . '</td><td><button type="button" class="btn btn-circle btn-danger" onclick="eliminarFraccion(\'' . $x['troquel'] . '\',\'' . $x['unidades'] . '\',\'' . $x['cntMostrar'] . '\');"> <i class="fa fa-eraser"></i></button></td></tr>';
    }
} else if (isset($_GET['eliminar'])) {
    $obj = new medicamentosfraccionados();
    $obj->settroquel($_GET['eliminar']);
    $uf = isset($_GET['unidades']) ? $_GET['unidades'] : '';
    $nf = isset($_GET['nombre']) ? $_GET['nombre'] : '';
    $obj->setunidades($uf);
    $obj->setcntMostrar($nf);
    $obj->Delete();
    $array = $obj->SelectAll();
    while ($x = mysqli_fetch_assoc($array)) {
        echo '<tr"><td align="left">' . $x['unidades'] . '</td><td align="left">' . $x['cntMostrar'] . '</td><td><button type="button" class="btn btn-circle btn-danger" onclick="eliminarFraccion(\'' . $x['troquel'] . '\',\'' . $x['unidades'] . '\',\'' . $x['cntMostrar'] . '\');"> <i class="fa fa-eraser"></i></button></td></tr>';
    }
} else {

    echo '{
    "draw": ' . $_POST['draw'] . ',
    "data": [';

    $obj = new ab_manualdat();
    $array = $obj->SelectAll($iDisplayLength, $iDisplayStart, $search, $order, $direccion);
    while ($x = mysqli_fetch_assoc($array)) {
        echo $coma;
        $habi = ($x['troquel'] == 1) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-times"></span>';
        //$stock = $x['cnt'].(($x['cntUnidades'] > 0) ? ' (+ '.$x['cntUnidades'].' Un.)' : '');
        $precio = number_format($x['precio'] / 100, 2, ',', '');
        $fecha = substr($x['fecha'], 6, 2) . '/' . substr($x['fecha'], 4, 2) . '/' . substr($x['fecha'], 0, 4);
        echo '["' . trim($x['troquel']) . '", "' . trim($x['nombre']) . '", "' . trim($x['presentacion']) . '", "' . $precio . '", "' . $fecha . '", "' . trim($x['codbarra']) . '", "' . trim($x['droga']) . '", "' . trim($x['nroregistro']) . '", "<button type=\"button\" data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" class=\"btn btn-dark btn-round\" onclick=\"editar(\'' . trim($x['troquel']) . '\',\'' . trim($x['nroregistro']) . '\',\'' . trim($x['codbarra']) . '\',\'' . trim($x['nombre']) . '\',\'' . trim($x['presentacion']) . '\',\'' . $x['unidades'] . '\',\'' . trim($x['coddroga']) . '\');\">+</button>"]';
        $coma = ',';
        //$cnt +=1;
    }
    $cnt = $obj->Cnt($search);
    $xx = mysqli_fetch_assoc($cnt);
    $rt = $xx['recordsTotal'];
    $rf = $xx['recordsFiltered'];

    /*
      $file = fopen("archivo.txt", "a+");
      fwrite($file, "POST:".json_encode($_POST) . PHP_EOL);
      fwrite($file, "GET:".json_encode($_GET) . PHP_EOL);
      fwrite($file, "POST Search:".$_POST['search']['value'] . PHP_EOL);
      fwrite($file, "Order:".$_POST['order'][0]['column'] . " - ". $_POST['order'][0]['dir'] . PHP_EOL);


      fwrite($file, PHP_EOL . "--------------------------" . PHP_EOL);
      fclose($file);
     */
    ?>
    ],
    "recordsTotal": <?= $rt ?>,
    "recordsFiltered": <?= $rf ?>
    }                        

    <?php
}
?>