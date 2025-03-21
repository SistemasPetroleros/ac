<?php

include 'class.php';
include_once 'controller/login.php';
set_time_limit(10);
error_reporting(0);

function test($url, $puerto) {

    $r = @fsockopen($url, $puerto, $errno, $errstr, 1);
    //echo "<hr>$errstr ($errno)<hr>";


    if ($r and $errno == 0) {
        //echo 'ok<hr>';
        fclose($r);
        return true;
    } else {
        //echo 'error<hr>';
        if ($r)
            fclose($r);
        return false;
    }
}

function contador($idTestCon, $tipo) {



    if (!isset($_GET['inicia'])) {

        $cnt = $_COOKIE["contadorConex" . $idTestCon];

        if ($_COOKIE["tipoContadorConex" . $idTestCon] != $tipo) {
            setcookie("tipoContadorConex" . $idTestCon, $tipo, time() + 3600 * 8);
            setcookie("contadorConex" . $idTestCon, 0, time() + 3600 * 8);
            $cnt = 10;
        } else {
            if ($cnt < 100) {
                $cnt += 10;
                setcookie("contadorConex" . $idTestCon, $cnt, time() + 3600 * 8);
            }
        }

        return $cnt;
    } else {
        setcookie("contadorConex" . $idTestCon, 10, time() + 3600 * 8);
        setcookie("tipoContadorConex" . $idTestCon, 'T', time() + 3600 * 8);
        return 10;
    }
}

$test = new testcon();
$array = $test->SelectAll();

while ($x = mysqli_fetch_assoc($array)) {
    //echo '' . $x['id'] . '-' . $x['nombre']. '-' . $x['puerto'] . '-' . $x['url'] . '-' . $x['descripcion'] . '<br>';
    if (test($x['url'], $x['puerto'])) {


        $cnt = contador($x['id'], 'T');
        echo '
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>' . $x['nombre'] . '</strong>
                                        <span class="pull-right text-muted">' . (($cnt == 100) ? 'OK' : '') . '</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' . $cnt . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $cnt . '%">
                                            <span class="sr-only">' . $cnt . '</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>';
    } else {
        $cnt = contador($x['id'], 'F');
        echo '
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>' . $x['nombre'] . '</strong>
                                        <span class="pull-right text-muted">' . (($cnt == 100) ? 'ERROR' : '') . '</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="' . $cnt . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $cnt . '%">
                                            <span class="sr-only">' . $cnt . '</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>';
    }
}
?>
                        

