<?php
include 'class.php';
$r = isset($_POST['r']) ? $_POST['r'] : 0;
if ($r > 0){
    $obj = new links($r);
    if(strlen($obj->geturl())>3){
      echo '<script>menu(\'' . $obj->geturl() . '\', \'' . $obj->getnombre() . '\', \'' . $obj->getid() . '\',0);</script>';
    }
}
