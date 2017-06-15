<?php
    session_start();
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $proto = getProtocol();
    $con = startdb('rw');
    $server = "$proto$http_host";

    // Get parameters

    $t = mysqli_real_escape_string($con, urldecode($_GET['t'])); // table
    $f = mysqli_real_escape_string($con, urldecode($_GET['f'])); // field (column)
    $i = mysqli_real_escape_string($con, urldecode($_GET['i'])); // id
    $c = mysqli_real_escape_string($con, urldecode($_GET['c'])); // content (translation)
    $l = mysqli_real_escape_string($con, urldecode($_GET['l'])); // language
    $n = mysqli_real_escape_string($con, urldecode($_GET['n'])); // translator name

    if (strlen($t) == 0 || strlen($f) == 0 || 
      is_numeric($i) == false || strlen($c) == 0 || 
      ($l != 'en' && $l != 'eu') || strlen($n) == 0){
         http_response_code(400); // Bad request
    }
    else{

        /*$t = urldecode($_GET['t']); // table
        $f = urldecode($_GET['f']); // field (column)
        $i = urldecode($_GET['i']); // id
        $c = urldecode($_GET['c']); // content (translation)
        $l = urldecode($_GET['l']); // language
        $n = urldecode($_GET['n']); // translator name*/

        // TODO: Validate parameters
        $q_string = "INSERT INTO translation (username, tab, field, eid, lang, text) VALUES ('$n', '$t', '$f', '$i', '$l', '$c');";
        mysqli_query($con, $q_string);
    }
?>
