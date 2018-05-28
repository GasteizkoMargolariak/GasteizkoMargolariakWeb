<?php
    include("../functions.php");
    $con = startdb("rw");
    if (!checkSession($con)){
        error_log("Error updating $_GET[table].$_GET[column] : Session has expired.");
        http_response_code(403); // Forbidden
        exit (-1);
    }
    else{
        if (db_update($con, $_GET["table"], $_GET["column"], $_GET["type"], $_GET["value"], $_GET["id"]) != 0){
            error_log("Error updating $_GET[table].$_GET[column] ($_GET[type]) to '$_GET[value] for id $_GET[id]' : Bad request");
            http_response_code(400); // Bad request
        }
    }
?>
