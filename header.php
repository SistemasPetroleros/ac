<?php
$sidebar = '
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>Alto Costo v.1.0</h3>
                <ul class="nav side-menu">';
if ((isset($_POST['user']) and isset($_POST['pass'])) or ! isset($_SESSION["sidebar-menu"])) {
    $obj = new menu();
    $array = $obj->SelectAllUsuario($u->getid());
    $sidebarPart = '';
    while ($x = mysqli_fetch_assoc($array)) {
        $sidebarPart .= '<li><a><i class="fa ' . $x['icono'] . '"></i> ' . $x['nombre'] . ' <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">';
        $obj2 = new links();
        $obj2->setid_menu($x['id']);
        $array2 = $obj2->SelectAllxUsuario($u->getid());
        while ($x2 = mysqli_fetch_assoc($array2)) {
            $sidebarPart .= '<li>
                            <a href="#" onclick="menu(\'' . $x2['url'] . '\', \'' . $x2['nombre'] . '\', \'' . $x2['id'] . '\',1); return false;">' . $x2['nombre'] . '</a>    
                        </li>';
            //<a href="' . $x2['url'] . '">' . $x2['nombre'] . '</a>
        }
        $sidebarPart .= '</ul></li>';
    }

    $sidebar .= $sidebarPart;
    $_SESSION["sidebar-menu"] = $sidebarPart;
} else {
    $sidebar .= isset($_SESSION["sidebar-menu"]) ? $_SESSION["sidebar-menu"] : '';
}
$sidebar .= '</ul></div></div>';
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <style type='text/css'>
            @-ms-viewport { width: device-width; }
            @-o-viewport { width: device-width; }
            @viewport { width: device-width; }
        </style>
        <meta name="description" content="">
        <meta name="author" content="">


        <title>OSPEPRI</title>

        <script src="lib/jquery/jquery-2.2.4.min.js"></script>

        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <!-- Bootstrap Core CSS -->
        
        <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS
        <link href="lib/metisMenu/metisMenu.min.css" rel="stylesheet">
        -->
        <!-- Custom CSS
        <link href="lib/css/sb-admin-2.css" rel="stylesheet">
        -->
        <!-- Custom Fonts -->
        <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="lib/datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
        <!-- NProgress -->
        <link href="lib/nprogress/nprogress.css" rel="stylesheet">
        <!-- jQuery custom content scroller -->
        <link href="lib/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
        <!-- Custom Theme Style -->
        <link href="css/custom.css" rel="stylesheet">

        <script src="js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap-select.css" />

        <!--<script src="js/jquery.hotkeys.min.js" type="text/javascript"></script>-->
        <style>
            .loader {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: rgb(249,249,249,0.9);
               /* background: url('img/pageLoader.gif') 50% 50% no-repeat rgb(249,249,249,0.5);*/
                opacity:0.5;
            }
            .btn-circle.btn-xl {
                width: 70px;
                height: 70px;
                padding: 10px 16px;
                border-radius: 35px;
                font-size: 24px;
                line-height: 1.33;
            }

            .btn-circle {
                width: 30px;
                height: 30px;
                padding: 6px 0px;
                border-radius: 15px;
                text-align: center;
                font-size: 12px;
                line-height: 1.42857;
                margin: 0px;
            }

        </style>
        <script>
            console.log('|---->');
            function toggleFullScreen() {
                var doc = window.document;
                var docEl = doc.documentElement;

                var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
                var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

                if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
                    requestFullScreen.call(docEl);
                } else {
                    cancelFullScreen.call(doc);
                }
            }


            $(document).ready(function () {
                console.log('<----|');

            });
        </script>

    </head>


    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
           
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="index.php?r=274" class="site_title"><img src="img/02.png" style="height: 55px; width:175px;"/><span></span></a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <!--<img src="images/img.jpg" alt="..." class="img-circle profile_img">-->
                            </div>
                            <div class="profile_info">
<!--                                <span>Hola,</span>
                                <h2>Daniel Rojas</h2>-->
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- /menu profile quick info -->

                        <br />

                        <?= $sidebar ?>
                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small">
                        <a href="" data-toggle="tooltip" data-placement="top" return false;">
                                <span>&nbsp;</span>
                            </a>
                            <a href="" data-toggle="tooltip" data-placement="top" return false;">
                                <span>&nbsp;</span>
                            </a>

                            <!--<a data-toggle="tooltip" data-placement="top" title="Otras configuraciones">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </a>-->
                            <a href="javascript:;" id="hrefFullScreen" data-toggle="tooltip" data-placement="top" title="Pantalla Completa"  onclick="toggleFullScreen();
                                    return false;">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>
                            <!--<a data-toggle="tooltip" data-placement="top" title="Lock">
                                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                            </a>-->
                            <a href="?cerrarsesion" data-toggle="tooltip" data-placement="top" title="Cerrar Sesion">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                        </div>
                        <!-- /menu footer buttons -->
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>

                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="img/user.png" alt="">
                                        <?= $_SESSION["nombreusuario"] ?>
                                        <span class=" fa fa-angle-down"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li><a href="#" onclick="menu('cambiarpass.php', 'Cambiar contraseña', '244',1); return false;"><i class="fa fa-gear fa-fw"></i> Cambiar Contraseña</a></li>
                                        <!--                                        <li>
                                                                                    <a href="javascript:;">
                                                                                        <span class="badge bg-red pull-right">50%</span>
                                                                                        <span>Settings</span>
                                                                                    </a>
                                                                                </li>-->
                                        <!--<li><a href="javascript:;">Help</a></li>-->
                                        <li><a href="?cerrarsesion"><i class="fa fa-sign-out fa-fw"></i> Salir</a></li>
                                    </ul>
                                </li>
<!--
                                <li role="presentation" class="dropdown">

                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-envelope-o"></i>
                                        <span class="badge bg-green">0</span>
                                    </a>
                                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                        <li>
                                            <a>
                                                <span class="image">
                                                    
                                                </span>
                                                <span>
                                                    <span>En Construccion...</span>
                                                    <span class="time">  </span>
                                                </span>
                                                <span class="message">

                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="text-center">
                                                <a>
                                                    <strong>Mostrar todas las alertas</strong>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li role="presentation" class="dropdown">

                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false"  onclick="testConexiones(1)">
                                        <i class="fa fa-tasks fa-fw"></i>
                                    </a>
                                    <ul id="testCon" class="dropdown-menu list-unstyled msg_list" role="menu">
                                        <li>

                                            <div style="text-align: center;">

                                                <img height="30px" src="img/loading.gif"/>

                                            </div>

                                        </li>

                                    </ul>
                                </li>
                                <li role="presentation" class="dropdown">

                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false"  onclick="SelecccionarImpresora(0);
                                            return false;">
                                        <i class="fa fa-print fa-fw"></i>
                                    </a>

                                    <ul class="dropdown-menu list-unstyled msg_list" id="ImpresorasDisponibles"></ul>
                                </li>
                                <li role="presentation" class="dropdown">

                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false"  onclick="$('#modalBusquedaGenerica').modal('show');">
                                        <i class="fa fa-search fa-fw"></i>
                                    </a>

                                </li>
        -->
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalBusquedaGenerica" id="modalBusquedaGenerica">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            En Construcción ...
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal" id="modalVarios">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body" id="contenidoModalVarios"></div>
                            
                        </div>
                    </div>
                </div>
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main" id="system" style="min-width: 1024px; min-height: 768px">


<?php
