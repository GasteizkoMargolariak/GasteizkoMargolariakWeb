<?php
    $http_host = $_SERVER['HTTP_HOST'];
    $default_host = substr($http_host, 0, strpos($http_host, ':'));
    include("../functions.php");
    $con = startdb('rw');
    if (!checkSession($con)){
        exit (-1);
    }
    else{
        //Get year
        $year = date("Y");
        
        //Get data to calculate
        $action = mysqli_real_escape_string($con, $_POST['action']);
        $table = mysqli_real_escape_string($con, $_POST['table']);
        $field = mysqli_real_escape_string($con, $_POST['field']);
        $type = mysqli_real_escape_string($con, $_POST['type']);
        $id = mysqli_real_escape_string($con, $_POST['id']);
        $value = mysqli_real_escape_string($con, urldecode($_POST['value']));
        //Validate tables where values can be inserted
        if ($table != 'festival' and $table != 'festival_day' and $table != 'festival_event' and $table != 'festival_event_image' and $table != 'festival_offer'){
            //TODO: If I got here, somebody is doing something nasty. Log it.
            exit (-1);
        }
        
        switch ($action){
            case "delete":
                mysqli_query($con, "DELETE FROM $table WHERE id = $id;");
                version();
                break;
            case "update":
                switch($type){
                    case "text":
                        mysqli_query($con, "UPDATE $table SET $field = '$value' WHERE id = $id;");
                        version();
                        break;
                    case "number":
                        mysqli_query($con, "UPDATE $table SET $field = $value WHERE id = $id;");
                        version();
                        break;
                    //TODO: add a case for dates
                }
        }
    }
?>
