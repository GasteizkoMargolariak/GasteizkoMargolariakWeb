<?php
    $http_host = $_SERVER['HTTP_HOST'];
    include("../../functions.php");
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
        $id = mysqli_real_escape_string($con, $_GET['id']);
        mysqli_query($con, "UPDATE activity SET visible = 0 WHERE id = $id;");
    }
?>
