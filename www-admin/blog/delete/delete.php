<?php
    $http_host = $_SERVER['HTTP_HOST'];
    include("../../functions.php");
    $con = startdb('rw');
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
        $id = mysqli_real_escape_string($con, $_GET['p']);
        mysqli_query($con, "UPDATE post SET visible = 0 WHERE id = $id;");
        version();
    }
?>
