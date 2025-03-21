<?php 


$QR = isset($_POST['qr']) ? trim($_POST['qr']) : '';
$result = array();
$QR = preg_replace('/[^a-zA-Z0-9]/', '', $QR);
$result['qr'] = $QR;




if (strlen($QR) > 1 and isset($_GET['data'])) {

  

    $result['POSGTIN'] = strpos($QR, '01');
    $result['POSFECVENC'] = strpos($QR, '17', 16);
    $result['POSSERIE'] = strpos($QR, '21', 16);
    $result['POSLOTE'] = strpos($QR, '10', 16);

    /*

    if (substr($QR, 16, 2) == '17') {
        if (substr($QR, 24, 2) == '21') {
            $result['POSSERIE'] = strpos($QR, '21', 24);
            $result['POSLOTE'] = strpos($QR, '10', 36);
        }else{
            $result['POSSERIE'] = strpos($QR, '21', 28);
            $result['POSLOTE'] = strpos($QR, '10', 24);
        }
    }


    if (substr($QR, 16, 2) == '21') {
        if (substr($QR, 28, 2) == '17') {
            $result['POSFECVENC'] = strpos($QR, '17', 28);
            $result['POSLOTE'] = strpos($QR, '10', 36);
        }else{
            $result['POSFECVENC'] = strpos($QR, '17', 36);
            $result['POSLOTE'] = strpos($QR, '10', 28);
        }
    }
    
    if (substr($QR, 16, 2) == '10') {
        
            $result['POSFECVENC'] = strpos($QR, '21', 28);
            $result['POSLOTE'] = strpos($QR, '17', 28);
        
    }
    */

    if ((substr($QR, 0, 2) == '01' or substr($QR, 0, 3) == '414') and (substr($QR, 16, 2) == '17' or substr($QR, 16, 2) == '21' or substr($QR, 16, 2) == '10')) {



        $result['GTIN'] = substr($QR, 2, 14);
        if (substr($QR, 0, 3) == '414') {
            $result['GTIN'] = substr($QR, 3, 13);
        }

        if (substr($QR, 16, 2) == '17') {
            $result['FECVENC'] = substr($QR, 18, 6);
            if (substr($QR, 24, 2) == '21') {
                $result['POSSERIE'] = strpos($QR, '21', 7);
                $result['POSLOTE'] = strpos($QR, '10', 28);
            } else {
                $result['POSSERIE'] = strpos($QR, '21', 28);
                $result['POSLOTE'] = strpos($QR, '10', 24);
            }

            if ($result['POSLOTE'] < $result['POSSERIE']) {
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, $result['POSSERIE'] - ($result['POSLOTE'] + 2));
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, strlen($QR) - ($result['POSSERIE'] + 2));
            } else {
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, strlen($QR) - ($result['POSLOTE'] + 2));
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, $result['POSLOTE'] - ($result['POSSERIE'] + 2));
            }
        }


        if (substr($QR, 16, 2) == '21') {

            $result['POSFECVENC'] = strpos($QR, '17', 22);
            $result['POSLOTE'] = strpos($QR, '10', 22);


            if ($result['POSFECVENC'] < $result['POSLOTE']) {
                $result['POSLOTE'] = strpos($QR, '10', $result['POSFECVENC'] + 8);

                $result['SERIE'] = substr($QR, 18, $result['POSFECVENC'] - 18);
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, strlen($QR) - ($result['POSLOTE'] + 2));
            } else {

                $result['SERIE'] = substr($QR, 18, $result['POSLOTE'] - 18);
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, $result['POSFECVENC'] - ($result['POSLOTE'] + 2));
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
            }
        }

        if (substr($QR, 16, 2) == '10') {
            $result['POSFECVENC'] = strpos($QR, '17', 21);
            $result['POSSERIE'] = strpos($QR, '21', 21);

            if ($result['POSFECVENC'] < $result['POSSERIE']) {
                $result['LOTE'] = substr($QR, 18, $result['POSFECVENC'] - 18);
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, strlen($QR) - ($result['POSSERIE'] + 2));
            } else {
                $result['LOTE'] = substr($QR, 18, $result['POSSERIE'] - 18);
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, $result['POSFECVENC'] - ($result['POSSERIE'] + 2));
            }
        }


        if (strlen($result['FECVENC']) == 6) {
            $result['FECVENC'] = substr($result['FECVENC'], 4, 2) . '/' . substr($result['FECVENC'], 2, 2) . '/20' . substr($result['FECVENC'], 0, 2);
        }



        /*

        
        if ($result['POSSERIE'] < $result['POSLOTE'] and $result['POSSERIE'] < $result['POSFECVENC']) {
            if ($result['POSFECVENC'] < $result['POSLOTE']) {
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, $result['POSFECVENC'] - ($result['POSSERIE'] + 2));
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, strlen($QR) - ($result['POSLOTE'] + 2));
            } else {
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, $result['POSLOTE'] - ($result['POSSERIE'] + 2));
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, $result['POSFECVENC'] - ($result['POSLOTE'] + 2));
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
            }
        }

        if ($result['POSLOTE'] < $result['POSSERIE'] and $result['POSLOTE'] < $result['POSFECVENC']) {
            if ($result['POSFECVENC'] < $result['POSSERIE']) {
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, $result['POSFECVENC'] - ($result['POSLOTE'] + 2));
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, strlen($QR) - ($result['POSSERIE'] + 2));
            } else {
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, $result['POSSERIE'] - ($result['POSLOTE'] + 2));
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, $result['POSFECVENC'] - ($result['POSSERIE'] + 2));
            }
        }

        if ($result['POSFECVENC'] < $result['POSSERIE'] and $result['POSFECVENC'] < $result['POSLOTE']) {
            if ($result['POSLOTE'] < $result['POSSERIE']) {
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, $result['POSSERIE'] - ($result['POSLOTE'] + 2));
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, strlen($QR) - ($result['POSSERIE'] + 2));
            } else {
                $result['FECVENC'] = substr($QR, $result['POSFECVENC'] + 2, 6);
                $result['LOTE'] = substr($QR, $result['POSLOTE'] + 2, strlen($QR) - ($result['POSLOTE'] + 2));
                $result['SERIE'] = substr($QR, $result['POSSERIE'] + 2, $result['POSLOTE'] - ($result['POSSERIE'] + 2));
            }
        }
        

*/
    }

}

    echo json_encode($result);
?>