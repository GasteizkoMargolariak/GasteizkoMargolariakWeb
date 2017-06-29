<?php
    include('functions.php');
    $con = startdb();
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $pass = mysqli_real_escape_string($con, $_POST['pass']);
    if (login($con, $user, $pass))
        header("Location: /main.php");
    else
        header("Location: /index.php");
?>
