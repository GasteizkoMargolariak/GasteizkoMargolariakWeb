 <?php
    // Gasteizko Margolariak API v3 //

    //Database section identifiers
    define('SEC_ALL', 'all');
    define('SEC_BLOG', 'blog');
    define('SEC_ACTIVITIES', 'activities');
    define('SEC_GALLERY', 'gallery');
    define('SEC_LABLANCA', 'lablanca');

    define('TAB_ACTIVITY', 'activity');
    define('TAB_ACTIVITY_IMAGE', 'activity_image');
    define('TAB_ACTIVITY_ITINERARY', 'activity_itinerary');
    define('TAB_ALBUM', 'album');
    define('TAB_FESTIVAL', 'festival');
    define('TAB_FESTIVAL_DAY', 'festival_day');
    define('TAB_FESTIVAL_EVENT_CITY', 'festival_event_city');
    define('TAB_FESTIVAL_EVENT_GM', 'festival_event_gm');
    define('TAB_FESTIVAL_OFFER', 'festival_offer');
    define('TAB_PEOPLE', 'people');
    define('TAB_PHOTO', 'photo');
    define('TAB_PHOTO_ALBUM', 'photo_album');
    define('TAB_PLACE', 'place');
    define('TAB_POST', 'post');
    define('TAB_POST_IMAGE', 'post_image');
    define('TAB_ROUTE', 'route');
    define('TAB_ROUTE_POINT', 'route_point');
    define('TAB_SETTINGS', 'settings');
    define('TAB_SPONSOR', 'sponsor');

    //$_GET valid parameters
    define('GET_CLIENT', 'client');
    define('GET_USER', 'user');
    define('GET_FOREGROUND', 'foreground');

    //Error messages
    define('ERR_CLIENT', 'CLIENT');

    //List of all tables to sync, sorted by priority.
    $tab_list = array(TAB_SETTINGS,             TAB_PLACE,          TAB_ROUTE_POINT,
                      TAB_ROUTE,                TAB_PEOPLE,         TAB_FESTIVAL_EVENT_GM,
                      TAB_FESTIVAL,             TAB_FESTIVAL_DAY,   TAB_FESTIVAL_OFFER,
                      TAB_FESTIVAL_EVENT_CITY,  TAB_ACTIVITY,       TAB_ACTIVITY_IMAGE,
                      TAB_ACTIVITY_ITINERARY,   TAB_SPONSOR,        TAB_ALBUM,
                      TAB_PHOTO,                TAB_PHOTO_ALBUM,    TAB_POST,
                      TAB_POST_IMAGE);
                      
    $fast_tables = array(TAB_FESTIVAL_EVENT_GM,  TAB_FESTIVAL,             TAB_FESTIVAL_DAY,
                         TAB_FESTIVAL_OFFER,     TAB_FESTIVAL_EVENT_CITY,  TAB_ACTIVITY,
                         TAB_ACTIVITY_IMAGE,     TAB_ACTIVITY_ITINERARY,   TAB_PHOTO,
                         TAB_PHOTO_ALBUM,        TAB_POST,                 TAB_POST_IMAGE);
    
    $slow_tables = array(TAB_SETTINGS,  TAB_PLACE,  TAB_ROUTE_POINT,
                         TAB_ROUTE,     TAB_PEOPLE, TAB_SPONSOR,  
                         TAB_ALBUM);

    /*****************************************************
     * This function is called from almost everywhere at *
     * the beggining of the page. It initializes the     *
     * session variables and connects to the db.         *
     *                                                   *
     * @return: (MySQL server connection): The           *
     *           connection handler.                     *
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
    /*****************************************************
     * Selects the value of a parameter from the list of *
     * GET arguments. It also sanitizes it to prevent    *
     * SQL injections.                                   *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    get: (string array) Contains the GET           *
     *         parameters.                               *
     *    param: (string) Name of the parameter.         *
     * @return: (string): Value of the parameter or an   *
     *          empty string if it was not passed.       *
     *****************************************************/
    function extract_param($con, $get, $param){
        if(isset($_GET[$param])){
            return mysqli_real_escape_string($con, $_GET[$param]);
        }
        else{
            return "";
        }
    }

    /*****************************************************
     * Gets information about the API call and the       *
     * assocciated client. If some mandatory parameter   *
     * is not provided, a error log entry is registered  *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    get: (string array) Contains the GET           *
     *         parameters.                               *
     * @return: (string array): Array with the keys      *
     *           'client', 'user', 'foreground', 'ip',   *
     *           'os', 'browser', 'uagent' and 'error'.  *
     *           'error' will contain the key of a       *
     *           mandatory value if it has not been      *
     *           provided, or will be empty if there     *
     *           were no problem.                        *
     *****************************************************/
    function get_user_info($con, $get){
        $info = array();
        $error = "";
        $info["client"] = extract_param($con, $get, GET_CLIENT);
        if(strlen($info["client"]) == 0) {
            error_log("SYNC ERROR: Trying to sync with no client name.");
            $error = ERR_CLIENT;
        }
        $info["user"] = extract_param($con, $get, GET_USER);
        $info["foreground"] = (int) extract_param($con, $get, GET_FOREGROUND);
        if($info["foreground"] != 1){
            $info["foreground"] = 0;
        }
        $info["ip"] = get_user_ip();
        $browser_data = get_browser(null, true);
        $info["os"] = $browser_data['platform'];
        $info["browser"] = $browser_data['browser'];
        $info["uagent"] = $browser_data['browser_name_pattern'];
        $info["error"] = $error;
        return $info;
    }

    /*****************************************************
     * Reads the version of the tables reported by the   *
     * user as GET parameters.                           *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    get: (string array) Contains the GET           *
     *         parameters.                               *
     * @return: (int array): Array with the version of   *
     *           the tables in the user app, keyed with  *
     *           the table names.                        *
     *****************************************************/
    function get_user_versions($con, $get){
        global $tab_list;
        $versions = array();
        foreach($tab_list as $tab){
            $versions[$tab] = intval(extract_param($con, $get, $tab));
        }
        return $versions;
    }

    /*****************************************************
     * Reads the version of the tables reported by the   *
     * user as GET parameters.                           *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) RO mode enough. *
     *         parameters.                               *
     * @return: (int array): Array with the version of   *
     *           the tables in the server, keyed with    *
     *           the table names.                        *
     *****************************************************/
    function get_server_versions($con){

        global $fast_tables;

        $versions = array();
        $q = mysqli_query($con, "SELECT section, version FROM version;");

        while($r = mysqli_fetch_array($q)){
            if (in_array($r['section'], $fast_tables)){
                $versions[$r['section']] = "0";
            }
            else {
                $versions[$r['section']] = $r['version'];
            }
        }
        return $versions;
    }

    /*****************************************************
     * Select the tables that need to be synced.         *
     *                                                   *
     * @params:                                          *
     *    user: (int array) Versions of tables in the    *
     *          user app.                                *
     *    server: (int array) Versions of tables in the  *
     *            server.                                *
     * @return: (string array): Array with the names of  *
     *           the tables that need to be synced.      *
     *****************************************************/
    function select_tables($user, $server){
        global $tab_list;
        $tables = array();
        foreach($tab_list as $table){
            if (intval($user[$table]) < intval($server[$table]) || intval($server[$table]) == 0){
                array_push($tables, $table);
            }
        }
        return $tables;
    }

    /*****************************************************
     * Formats the contents of ther 'versions' table,    *
     * Only for the tables that will be synced.          *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    tables (String array): List of table.          *
     * @return: (Assoc Array): Data in the table.        *
     ****************************************************/
    function get_table_version($con, $tables){
    
        global $fast_tables;
        global $slow_tables;
        
        // Build query, showing only tables to sync
        $s = "SELECT section, version FROM version WHERE ";
        foreach($tables as $table){
            if (in_array($table, $fast_tables) == false){
                $s = $s . "section = '$table' OR ";
            }
        }
        $s = $s . "1 = 2 ";
        
        $s =  $s . "UNION SELECT section, 0 AS version FROM version WHERE ";
        foreach($tables as $table){
            if (in_array($table, $fast_tables)){
                $s = $s . "section = '$table' OR ";
            }
        }
        
        $s = $s . "1 = 2;";
         $q = mysqli_query($con, $s);

        //If no rows, return
        if (mysqli_num_rows($q) == 0){
            return "";
        }

        //Create result array
        $str = "";
        $str = $str. "\"version\":[";
        while($r = mysqli_fetch_assoc($q)) {
            $str = $str . json_encode($r) . ",";
        }
        $str = rtrim($str,',');
        $str = $str . "],";
        
        return $str;

    }

    /*****************************************************
     * Formats the contents of a table in the database.  *
     * Inaccessible or sensitive tables or fields are    *
     * not printed.                                      *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) RO mode enough. *
     *    table (string): The name of the table.         *
     * @return: (Assoc Array): Data in the table.        *
     ****************************************************/
    function get_table($con, $table){
        $year = date("Y");
        $table = strtolower($table);
        switch ($table){
            case TAB_ACTIVITY:
                $q = mysqli_query($con, "SELECT id, permalink, date, city, title_es, title_en, title_eu, text_es, text_eu, text_en, after_es, after_en, after_eu, price, inscription, max_people, album FROM activity WHERE visible = 1 AND year(date) = $year;");
                break;
            case TAB_ALBUM:
                $q = mysqli_query($con, "SELECT id, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, open FROM album;");
                break;
            case TAB_PHOTO:
                $q = mysqli_query($con, "SELECT photo.id AS id, file, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, uploaded, place, width, height, size, CONCAT(photo.username, user) AS username FROM photo, user WHERE user.id = photo.user AND approved = 1 AND year(uploaded) = $year;;");
                break;
            case TAB_POST:
                $q = mysqli_query($con, "SELECT post.id AS id, permalink, title_es, title_en, title_eu, text_es, text_en, text_eu, comments, username, dtime FROM post, user WHERE user.id = user AND visible = 1 AND year(dtime) = $year;");
                break;
            case TAB_SPONSOR:
                $q = mysqli_query($con, "SELECT id, name_es, name_en, name_eu, text_es, text_en, text_eu, image, address_es, address_en, address_eu, link, lat, lon FROM sponsor;");
                break;
            case TAB_SETTINGS:
                $q = mysqli_query($con, "SELECT name, value FROM settings;");
                break;
            case TAB_FESTIVAL_EVENT_GM:
                $q = mysqli_query($con, "SELECT * FROM festival_event_gm WHERE year(start) = $year AND interest >= 1;");
                break;
            case TAB_FESTIVAL:
                $q = mysqli_query($con, "SELECT * FROM festival WHERE year = $year;");
                break;
            case TAB_FESTIVAL_DAY:
                $q = mysqli_query($con, "SELECT * FROM festival_day WHERE year(date) = $year;");
                break;
            case TAB_FESTIVAL_OFFER:
                $q = mysqli_query($con, "SELECT * FROM festival_offer WHERE year = $year;");
                break;
            case TAB_FESTIVAL_EVENT_CITY:
                $q = mysqli_query($con, "SELECT * FROM festival_event_city WHERE year(start) = $year AND interest >= 1;");
                break;
            case TAB_ACTIVITY_IMAGE:
                $q = mysqli_query($con, "SELECT activity_image.id AS id, activity, image, idx FROM activity_image, activity WHERE activity = activity.id AND year(date) = $year;");
                break;
            case TAB_ACTIVITY_ITINERARY:
                $q = mysqli_query($con, "SELECT * FROM activity_itinerary WHERE year(start) = $year;");
                break;
            case TAB_PHOTO_ALBUM:
                $q = mysqli_query($con, "SELECT photo, album FROM photo_album, photo WHERE photo = photo.id AND year(uploaded) = $year;");
                break;
            case TAB_POST_IMAGE:
                $q = mysqli_query($con, "SELECT post_image.id AS id, post, image, idx FROM post_image, post WHERE post = post.id AND year(dtime) = $year;");
                
            //Other cases:
            default:
                $q = mysqli_query($con, "SELECT * FROM $table;");
        }

        //If no rows, return
        if (mysqli_num_rows($q) == 0){
            return "";
        }

        //Create result array
        $str = "";
        $str = $str. "\"$table\":[";
        while($r = mysqli_fetch_assoc($q)) {
            $str = $str . json_encode($r) . ",";
        }
        $str = rtrim($str,",");
        $str = $str . "]";
        return $str;
    }

    /*****************************************************
     * Prints out required tables.                       *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    tables: (String array) List of tables to sync. *
     * @return: (String): Client IP address.             *
     *****************************************************/
    function sync($con, $tables){
        $str = "";
        if(sizeof($tables) > 0){
            $str = "{" . get_table_version($con, $tables);
            foreach($tables as $table){
                $str = $str . get_table($con, $table) . ",";
            }
            $str = rtrim($str, ",");
            $str = $str . "}";
            $str = str_replace(",,", ",", $str);
            echo($str);
            return true;
        }
        return false;
    }

    /*****************************************************
     * Gets the IP address of the client.                *
     *                                                   *
     * @return: (String): Client IP address.             *
     *****************************************************/
    function get_user_ip(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }
        else{
            $ip = $remote;
        }
        return $ip;
    }

    /*****************************************************
     * Registers the request in the database.            *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) RO mode enough. *
     *    user: (String array): Array with, at least,    *
     *          the keys 'client', 'user', 'foreground', *
     *          'ip', 'os', 'browser', 'uagent', with    *
     *          info about the calling app.              *
     *    synced: (Int): 1 if a sync content was sent, 0 *
     *            otherwise.                             *
     *****************************************************/
    function log_sync($con, $user, $synced){
        mysqli_query($con, "INSERT INTO sync (client, user, fg, synced, ip, os, uagent) VALUES ('$user[client]', '$user[user]', $user[foreground], $synced, '$user[ip]', '$user[os]', '$user[uagent]');");
    }

    /*****************************************************
     * Registers a failed request in the database.       *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) RO mode enough. *
     *    user: (String array): Array with, at least,    *
     *          the keys 'client', 'user', 'foreground', *
     *          'ip', 'os', 'browser', 'uagent', and     *
     *          'error', with info about the calling     *
     *           app. The 'error' key will contain an    *
     *           error description.                      *
     *****************************************************/
    function log_error($con, $user){
        mysqli_query($con, "INSERT INTO sync (client, user, fg, error, ip, os, uagent) VALUES ('$user[client]', '$user[user]', $user[foreground], $user[error], '$user[ip]', '$user[os]', '$user[uagent]');");
    }



    // Connect to the database
    $con = startdb('rw');

    // Get info about the user
    $user = get_user_info($con, $_GET);
    if(strlen($user["error"]) > 0){
        log_error($con, $user);
        http_response_code(400);
        exit(-1);
    }

    // Get tables to sync
    $v_user = get_user_versions($con, $_GET);
    $v_server = get_server_versions($con);
    $tables = select_tables($v_user, $v_server);
    $synced = sync($con, $tables);

    //Log the sync in the database
    log_sync($con, $user, $synced);
?>
