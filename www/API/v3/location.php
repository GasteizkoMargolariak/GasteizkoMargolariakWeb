<?php
    // Gasteizko Margolariak API v3 //

    /*****************************************************
     * This function is called from almost everywhere at *
     * the beggining of the page. It initializes the     *
     * session variables and connects to the db.         *
     *                                                   *
     * @return: (MySQL server connection): The           *
     *           connection handler.                     *
     *****************************************************/
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

    /*****************************************************
     * Retrieves the last reported location in the db,   *
     * only if it was reported in the last 30 mins.      *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     * @return: (Double array): array with the 'lat',    *
     *          'lon' and 'dtime' keys. null if nothing  *
     *           to report.                              *
     *****************************************************/
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

    /*****************************************************
     * Prints info about the last location report, in    *
     * the format:                                       *
     * [{"lat":"<LAT>","lon":"<LON>","dtime":"<DTIME>"}] *
     * where <LAT> and <LON> are doubles and <dtime> is  *
     * the datetime of the report in format:             *
     * 'YYYY-MM-DD hh:mm:ss'.                            *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     * @return: (Double array): array with the 'lat' and *
     *          'lon' keys. null if nothing to report.   *
     *****************************************************/
    function print_location($location){
        echo("[{\"lat\":\"$location[lat]\",\"lon\":\"$location[lon]\",\"dtime\":\"$location[dtime]\"}]");
    }



    //Connect to the database
    $con = startdb('rw');

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
