<?php

    /**
     * Creates a database connection using the data in the config files.
     * 
     * @param mode 'ro' for read-only, 'rw' for (limited) write privileges.
     * @return MySQL_connection or -1 on error or wrong mode.
     */
    function start_db($mode = 'ro'){
        global $auth;
        $db;
        //Connect to to database
        if ($mode == 'ro')
            $db = mysqli_connect($auth["host"], $auth["user"], $auth["pass"], $auth["name"]);
        else if ($mode == 'rw'){
            $db = mysqli_connect($auth["host"], $auth["user-rw"], $auth["pass-rw"], $auth["name"]);
        }
        else{
            return -1;
        }

        // Check connection
        if (mysqli_connect_errno()){
            error_log("Failed to connect to database: " . mysqli_connect_error());
            return -1;
        }

        //Set encoding options
        mysqli_set_charset($db, 'utf-8');
        header('Content-Type: text/html; charset=utf8');
        mysqli_query($db, 'SET NAMES utf8;');

        //Return the db connection
        return $db;
    }

?>

