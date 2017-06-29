<?php

    // $_GET valid parameters
    define('GET_USER', 'user');
    define('GET_PASS', 'pass');
    define('GET_ACTION', 'action');
    define('GET_LAT', 'lat');
    define('GET_LON', 'lon');

    // Valid values
    define('ACTION_START', 'start');
    define('ACTION_REFRESH', 'refresh');
    define('ACTION_STOP', 'stop');
    $actions = [ACTION_START, ACTION_REFRESH, ACTION_STOP];

    // Error messages
    define('ERR_USER', '-USER:');
    define('ERR_ACTION', '-ACTION:');
    define('ERR_LOCATION', '-TITLE:');

    /****************************************************
    * This function is called from almost everywhere at *
    * the beggining of the page. It initializes the     *
    * session variables, connect to the db, enabling    *
    * the variable $con for futher use everywhere in    *
    * the php code, and populates the arrays $user      *
    * and $permission, with info about the user.        *
    *                                                   *
    * @return: (db connection): The connection handler. *
    ****************************************************/
    function startdb(){
        //Include the db configuration file. It's somehow like this
        /*
         <?php
          $host = 'XXXX';
          $db_name = 'XXXX';
          $username_ro = 'XXXX';
          $username_rw = 'XXXX';
          $pass_ro = 'XXXX';
          $pass_rw = 'XXXX';
         ?>
        */
        include('../../.htpasswd');

        //Connect to to database
        $con = mysqli_connect($host, $username_rw, $pass_rw, $db_name);

        //Set encoding options
        mysqli_set_charset($con, 'utf-8');
        header('Content-Type: text/html; charset=utf8');
        mysqli_query($con, 'SET NAMES utf8;');

        //Return the db connection
        return $con;
    }

    $con = startdb('rw');
    $error = "";

    //Get fields
    $user = mysqli_real_escape_string($con, $_GET[GET_USER]);
    $pass = mysqli_real_escape_string($con, $_GET[GET_PASS]);
    $lat = mysqli_real_escape_string($con, $_GET[GET_LAT]);
    $lon = mysqli_real_escape_string($con, $_GET[GET_LON]);
    $action = mysqli_real_escape_string($con, $_GET[GET_ACTION]);

    //Validate user
    $q = mysqli_query($con, "SELECT id FROM user WHERE lower(username) = lower('$user') AND password = '$pass';");
    if (mysqli_num_rows($q) == 0){
        error_log(":SECURITY: Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
        http_response_code(403); // Forbidden
        $error = $error . ERR_USER . mysqli_real_escape_string($con, $_GET[GET_USER]);
        error_log($error);
        exit(-1);
    }
    // Get id
    $r = mysqli_fetch_array($q);
    $uid = $r['id'];

    //Validate fields
    if (!in_array($action, $actions)){
        http_response_code(400); // Bad request
        $error = $error . ERR_ACTION . $action;
        error_log($error);
        exit(-2);
    }
    if (is_numeric($lat) == false || is_numeric($lon) == false){
        http_response_code(400); // Bad request
        $error = $error . ERR_LOCATION . '($lat, $lon)';
        error_log($error);
        exit(-3);
    }
    if (strlen($lat) == 0 xor strlen($lon) == 0){
        // Only one coordinate.
        http_response_code(400); // Bad request
        $error = $error . ERR_LOCATION . '($lat, $lon)';
        error_log($error);
        exit(-4);
    }
    if (strlen($lat) != 0 && ($lat < -90.0 || $lat > 90.0)){
        // Invalid latitude
        http_response_code(400); // Bad request
        $error = $error . ERR_LOCATION . '(Lat: $lat)';
        error_log($error);
        exit(-5);
    }
    if (strlen($lon) != 0 && ($lon < -180.0 || $lon > 180.0)){
        // Invalid longitude
        http_response_code(400); // Bad request
        $error = $error . ERR_LOCATION . '(Lon: $lat)';
        error_log($error);
        exit(-6);
    }

    // Discern action
    switch ($action){
        case ACTION_START:
            // Insert
            mysqli_query($con, "INSERT INTO location (lat, lon, action, user) VALUES ($lat, $lon, 'S', $uid);");
            break;
        case ACTION_REFRESH:
            // Look for start node.
            $q = mysqli_query($con, "SELECT id, start, action FROM location WHERE user = $uid AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
            if (mysqli_num_rows($q) == 0){
                // No recent reports. Start anew.
                mysqli_query($con, "INSERT INTO location (lat, lon, action, user) VALUES ($lat, $lon, 'S', $uid);");
            }
            else{
                $r = mysqli_fetch_array($q);
                if ($r['action'] == 'F'){
                    // Previous track was stoped. Start anew.
                    mysqli_query($con, "INSERT INTO location (lat, lon, action, user) VALUES ($lat, $lon, 'S', $uid);");
                }
                else{
                    // Continue track.
                    $s = $r['id'];
                    mysqli_query($con, "INSERT INTO location (lat, lon, action, user, start) VALUES ($lat, $lon, 'R', $uid, $s);");
                }
            }
            break;
        case ACTION_STOP:
            // Look for start node.
            $q = mysqli_query($con, "SELECT id, start FROM location WHERE user = $uid AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                if ($r['action'] != 'F'){
                    // Finish track.
                    $s = $r['start'];
                    if (strlen($lat) > 0 && strlen($lon) > 0){
                        mysqli_query($con, "INSERT INTO location (lat, lon, action, user, start) VALUES ($lat, $lon, 'F', $uid, $s);");
                    }
                    else{
                        mysqli_query($con, "INSERT INTO location (action, user, start) VALUES ('F', $uid, $s);");
                    }
                }
            }
            break;
    }
    http_response_code(204); // No content.
?>
