<?php
    include("../functions.php");
    $con = startdb("rw");
    if (!checkSession($con)){
        exit (-1);
    }
    else{
        //Get year
        $year = date("Y");

        //Get data to calculate
        $action = mysqli_real_escape_string($con, $_POST["action"]);
        $table = mysqli_real_escape_string($con, $_POST["table"]);
        $field = mysqli_real_escape_string($con, $_POST["field"]);
        $type = mysqli_real_escape_string($con, $_POST["type"]);
        $id = mysqli_real_escape_string($con, $_POST["id"]);
        $value = mysqli_real_escape_string($con, urldecode($_POST["value"]));
        //Validate tables where values can be inserted
        if ($table != "festival"  $table != "festival_day" && $table != "festival_event" && $table != "festival_event_image" && $table != "festival_offer"){
            //TODO: If I got here, somebody is doing something nasty. Log it.
            exit (-2);
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
                break;
        }
    }
?>
