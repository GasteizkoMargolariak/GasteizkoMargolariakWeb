<?php
    /**
     * Gasteizko Margolariak API v3 - Location
     *
     * Used to get location reports.
     * This file is to be called directly from a URL request.
     *
     * https://margolariak.com/API/v3/help/
     *
     * @since 1.0.0
     */


    /**
     * Initializes the MySQL database connection.
     * 
     * Called at the beggining of the script. It connects to the database using the
     * parameters in the .htpasswd file. It also sets database and page encodings.
     * 
     * @since 1.0.0
     * @return object Database connection.
     */
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


    /**
     * Retrieves the last location report.
     *
     * Retrieves the last reported location in the db, only if it was reported
     * in the last 30 mins.
     * 
     * @since 3.0.0
     * @param object $con Open database connection.
     * @return null if there was no location report in the last 30 minutes or
     *         array{
     *     @type double lat Last location report latitude component.
     *     @type double lon Last location report longitude component.
     *     @type datetime dtime Last location report timestamp.
     * }
     */
    function get_location($con){
        $q = mysqli_query($con, "SELECT lat, lon, dtime FROM location WHERE action <> 'F' AND lat IS NOT null AND lon IS NOT null AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
        if (mysqli_num_rows($q) > 0){
            $r = mysqli_fetch_array($q);
            $loc = array();
            $loc["lat"] = $r["lat"];
            $loc["lon"] = $r["lon"];
            $loc["dtime"] = $r["dtime"];
            return $loc;
        }
        return null;
    }


    /**
     * Prints the last location report.
     *
     * Prints info about the last location report, in the format:
     *   [{"lat":"<LAT>","lon":"<LON>","dtime":"<DTIME>"}]
     * where <LAT> and <LON> are doubles and <dtime> is the datetime of the
     * report in format: 'YYYY-MM-DD hh:mm:ss'
     * 
     * @since 3.0.0
     * @params array $location As generated by {@see get_location($con)}.
     */
    function print_location($location){
        echo("[{\"lat\":\"$location[lat]\",\"lon\":\"$location[lon]\",\"dtime\":\"$location[dtime]\"}]");
    }


    // SCRIPT START


    //Connect to the database
    $con = startdb('r');

    //Get location
    $location = get_location($con);
    if($location == null){
        //No content
        http_response_code(204);
        exit(0);
    }
    else{
        // Print the location
        print_location($location);
    }
?>