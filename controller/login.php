<?php
/*
  $x=new usuarios();
  $x->setapellido('Administrador');
  $x->setnombre('Sistemas');
  $x->setuser('administrador');
  $x->setpass('20238');
  $x->Create();
 */
/*
  $x=new usuarios();
  $x->setid(1);
  $x->Load();
  $x->setpass('20238');
  $x->Save();
 */

function urlactual() {
    $s = $_SERVER;
    $use_forwarded_host = false;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');

    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;

    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;

    return $protocol . '://' . $host;
}

if (isset($_GET['cerrarsesion'])) {
    $_SESSION["user"] = 'a';
    $_SESSION["pass"] = 'a';
    $_SESSION["idUsuario"] = '0';
    $_SESSION["nombreusuario"] = '';
    $_SESSION["esadmin"] = 'n';
    setcookie("idUsuario", '0', time() - 3600);
    setcookie("user", 'a', time() - 3600);
    setcookie("pass", 'a', time() - 3600);
    session_destroy();
}

$user = isset($_POST['user']) ? $_POST['user'] : FALSE;
if (isset($_GET['error'])) {
    echo 'POST USER:' . $user . '<br>';
}
if (!$user)
    $user = isset($_SESSION["user"]) ? $_SESSION["user"] : FALSE;
if (isset($_GET['error'])) {
    echo 'SESSION USER:' . $user . '<br>';
}
if (!$user)
    $user = isset($_COOKIE["user"]) ? $_COOKIE["user"] : FALSE;
if (isset($_GET['error'])) {
    echo 'COOKIE USER:' . $user . '<br>';
}

$pass = isset($_POST['pass']) ? $_POST['pass'] : FALSE;
if (!$pass)
    $pass = isset($_SESSION["pass"]) ? $_SESSION["pass"] : FALSE;
if (!$pass)
    $pass = isset($_COOKIE["pass"]) ? $_COOKIE["pass"] : FALSE;

$u = new usuarios($user, $pass);


if ($u->getid() > 0) {
    $_SESSION["nombreusuario"] = $u->getapellido() . ' ' . $u->getnombre();
    $_SESSION["user"] = $user;
    $_SESSION["pass"] = $pass;
    $_SESSION["idUsuario"] = $u->getid();
    setcookie("idUsuario", $u->getid(), time() + 3600 * 8);
    //$_SESSION["esadmin"]=$u->getadmin();
    setcookie("user", $user, time() + 3600 * 8);
    setcookie("pass", $pass, time() + 3600 * 8);

    
    
    if (!$u->VerificaLink(ScriptActual()) > 0 or ! $u->getuser() == 'administrador') {
        echo 'Script no configurado';
        exit();
    }
} else {
    $_SESSION["user"] = 'a';
    $_SESSION["pass"] = 'a';
    $_SESSION["idUsuario"] = '0';
    setcookie("idUsuario", '0', time() - 3600);
    setcookie("user", 'a', time() - 3600);
    setcookie("pass", 'a', time() - 3600);
    setcookie("sidebar-menu", 'a', time() - 3600);
    $_SESSION["sidebar-menu"] = 'a';
    /* echo '
      <form class="form-signin" method="post" action="index.php">
      <h2 class="form-signin-heading">Por favor inicie sesión</h2>
      <label for="user" class="sr-only">Usuario</label>
      <input type="text" id="user" name="user" class="form-control" placeholder="Usuario" required autofocus>
      <label for="pass" class="sr-only">Password</label>
      <input type="password" id="pass" name="pass" class="form-control" placeholder="Contraseña" required>
      <div class="checkbox">
      <label>
      <input type="checkbox" value="Recuerdame"> Mantener sesión activa
      </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
      </form>
      </div> <!-- /container -->
      <!-- Bootstrap core JavaScript
      ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->
      <script src="js/jquery-1.12.4.min.js"></script>
      <script>window.jQuery || document.write(\'<script src="js/jquery-1.12.4.min.js"><\/script>\')</script>
      <script src="js/bootstrap.min.js"></script>
      </body>
      </html>

      '; */
    ?>
    <!DOCTYPE html>
    <html lang="en">

        <head>

            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="">
            <meta name="author" content="">

            <title>MEOPP</title>
            <link rel="shortcut icon" type="image/png" href="img/logomeoppverde.png"/>
            <!-- Bootstrap Core CSS -->
            <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

            <!-- MetisMenu CSS -->
            <link href="lib/metisMenu/metisMenu.min.css" rel="stylesheet">

            <!-- Custom CSS -->
            <link href="lib/css/sb-admin-2.css" rel="stylesheet">

            <!-- Custom Fonts -->
            <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->

        </head>

        <body>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="login-panel panel panel-default">
                            <div class="panel-heading">
                                <img src="img/meopp2.png" style="height:50px; ">

                            </div>
                            <div class="panel-body">
                                <form role="form"  method="post" action="index.php">
                                    <fieldset>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Usuario" name="user" type="text" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Contraseña" name="pass" type="password" value="">
                                        </div>
                                        <!--
                                        <div class="checkbox">
                                            <label>
                                                <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                            </label>
                                        </div>
                                        -->

                                        <!-- Change this to a button or input when using this as a form -->
                                        <button type="submit" class="btn btn-lg btn-success btn-block">Ingresar</button>
    <?php if (isset($_POST['user'])) { ?>
                                            <br><br>
                                            <div class="alert alert-danger">

                                                <strong>Atención!</strong> Usuario o contraseña incorrecta.
                                            </div>


    <?php } ?>
                                        <br>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- jQuery -->
            <script src="lib/jquery/jquery-3.3.1.min.js"></script>

            <!-- Bootstrap Core JavaScript -->
            <script src="lib/bootstrap/js/bootstrap.min.js"></script>

            <!-- Metis Menu Plugin JavaScript -->
            <script src="lib/metisMenu/metisMenu.min.js"></script>

            <!-- Custom Theme JavaScript -->
            <script src="lib/js/sb-admin-2.js"></script>

        </body>

    </html>


    <?php
    exit;
}