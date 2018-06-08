<?php
    // Gasteizko Margolariak API v1 //
        
    //List of available data formatting
    define('FOR_JSON', 'json');
    
    //Default info format
    define('DEF_FORMAT', FOR_JSON);
    
    //Errors
    define('ERR_FORMAT', '-FORMAT:');
    
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
    
    //Connect to the database
    $con = startdb('rw');
    
    //Get data from the url
    $format = strtolower(mysqli_real_escape_string($con, $_GET[GET_FORMAT]));
    
    //Validate input
    if (strlen($format) < 1){
        $format = DEF_FORMAT;
    }
    if ($format != FOR_JSON){
        //Bad request
        http_response_code(400);
        $error = $error . ERR_FORMAT . mysqli_real_escape_string($con, $_GET[GET_FORMAT]);
        error_log($error);
        exit(-1);
    }
    
    //Get location
    $q = mysqli_query($con, "SELECT lat, lon, dtime FROM location WHERE action <> 'F' AND lat IS NOT null AND lon IS NOT null AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
    if (mysqli_num_rows($q) > 0){
        $r = mysqli_fetch_array($q);
        switch ($format){
            case FOR_JSON:
                echo("[{\"lat\":\"$r[lat]\",\"lon\":\"$r[lon]\",\"dtime\":\"$r[dtime]\"}]");
                break;
            default:
                http_response_code(400);
                $error = $error . ERR_FORMAT . mysqli_real_escape_string($con, $_GET[GET_FORMAT]);
                error_log($error);
                exit(-2);
        }
    }
    else{
        //No content
        http_response_code(204);
        exit(0);
    }
?>
