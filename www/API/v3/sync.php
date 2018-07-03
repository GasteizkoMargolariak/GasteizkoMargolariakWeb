 <?php
    /**
     * Gasteizko Margolariak API v3 - Sync
     *
     * Used to sync data with apps with persistent storage (i.e. no web apps).
     * This file is to be called directly from a URL request.
     *
     * @link https://margolariak.com/API/v3/help/
     *
     * @since 1.0.0
     */


    // Database section identifiers
    define('SEC_ALL', 'all');
    define('SEC_BLOG', 'blog');
    define('SEC_ACTIVITIES', 'activities');
    define('SEC_GALLERY', 'gallery');
    define('SEC_LABLANCA', 'lablanca');

    // Tables
    define('TAB_ACTIVITY', 'activity');
    define('TAB_ACTIVITY_COMMENT', 'activity_comment');
    define('TAB_ACTIVITY_IMAGE', 'activity_image');
    define('TAB_ACTIVITY_ITINERARY', 'activity_itinerary');
    define('TAB_ACTIVITY_TAG', 'activity_tag');
    define('TAB_ALBUM', 'album');
    define('TAB_FESTIVAL', 'festival');
    define('TAB_FESTIVAL_DAY', 'festival_day');
    define('TAB_FESTIVAL_EVENT_CITY', 'festival_event_city');
    define('TAB_FESTIVAL_EVENT_GM', 'festival_event_gm');
    define('TAB_FESTIVAL_OFFER', 'festival_offer');
    define('TAB_PEOPLE', 'people');
    define('TAB_PHOTO', 'photo');
    define('TAB_PHOTO_ALBUM', 'photo_album');
    define('TAB_PHOTO_COMMENT', 'photo_comment');
    define('TAB_PLACE', 'place');
    define('TAB_POST', 'post');
    define('TAB_POST_COMMENT', 'post_comment');
    define('TAB_POST_IMAGE', 'post_image');
    define('TAB_POST_TAG', 'post_tag');
    define('TAB_ROUTE', 'route');
    define('TAB_ROUTE_POINT', 'route_point');
    define('TAB_SETTINGS', 'settings');
    define('TAB_SPONSOR', 'sponsor');

    // $_GET key parameters
    define('GET_CLIENT', 'client');
    define('GET_USER', 'user');
    define('GET_FOREGROUND', 'foreground');

    // Error messages
    define('ERR_CLIENT', 'CLIENT');


    /**
     * List of all tables that can be synced, sorted by priority / dependencies.
     * 
     * @var string $tab_list
     */
    $tab_list = [TAB_SETTINGS,             TAB_PLACE,          TAB_ROUTE_POINT,
                 TAB_ROUTE,                TAB_PEOPLE,         TAB_FESTIVAL_EVENT_GM,
                 TAB_FESTIVAL,             TAB_FESTIVAL_DAY,   TAB_FESTIVAL_OFFER,
                 TAB_FESTIVAL_EVENT_CITY,  TAB_ACTIVITY,       TAB_ACTIVITY_IMAGE,
                 TAB_ACTIVITY_ITINERARY,   TAB_SPONSOR,        TAB_ALBUM,
                 TAB_PHOTO,                TAB_PHOTO_ALBUM,    TAB_POST,
                 TAB_POST_IMAGE,           TAB_PHOTO_COMMENT,  TAB_POST_COMMENT,
                 TAB_ACTIVITY_COMMENT,     TAB_ACTIVITY_TAG,   TAB_POST_TAG];


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
     * Extracts a request parameter.
     * 
     * Extracts the value of a parameter from the list of GET parameters,
     * sanitizing it to prevent SQL injections.
     * 
     * @since 3.0.0
     * @param object $con Open database connection.
     * @param string $param Key of the parameter to retrieve.
     * @return string Value of the parameter or an empty string if it was not found.
     */
    function extract_param($con, $param){
        if(isset($_GET[$param])){
            return mysqli_real_escape_string($con, $_GET[$param]);
        }
        else{
            return "";
        }
    }


    /**
     * Gets request info.
     * 
     * Gets information about the request by reading it's parameters.
     * 
     * @since 3.0.0
     * @param object $con Open database connection.
     * @param array $get Array with the request parameters.
     * @return array {
     *     @type string client Client identifier. Empty if not provided.
     *     @type string user User identifier. Empty if not provided.
     *     @type int foreground 1 if the sync is being made in the app
     *                          foreground, 0 otherwise.
     *     @type string ip Client IP.
     *     @type string os Client operating system identifier. Empty if not
     *                     found.
     *     @type string browser Client browser identifier. Empty if not found.
     *     @type string uagent Client user agent. Empty if not found.
     *     @type string error Will contain ERR_CLIENT if the client was not
     *                        specified, empty otherwise.
     * }
     */
    function get_user_info($con, $get){
        $info = array();
        $error = "";
        $info["client"] = extract_param($con, GET_CLIENT);
        if(strlen($info["client"]) == 0) {
            error_log("SYNC ERROR: Trying to sync with no client name.");
            $error = ERR_CLIENT;
        }
        $info["user"] = extract_param($con, GET_USER);
        $info["foreground"] = (int) extract_param($con, GET_FOREGROUND);
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


    /**
     * Reads the table version in the client app.
     * 
     * Reads the version of the tables reported by the user as GET parameters.
     * Those parameters must be the same as the table names listed in 
     * {@see $tab_list}.
     * 
     * @since 3.0.0
     * @global array $tab_list Array with the names of the tables to sync.
     * @param object $con Open database connection.
     * @param array $get Array with the request parameters.
     * @return array Integer array with the version of the tables reported in
     *               the request, keyed with the table names. If no table
     *               version was specified, the array will be empty.
     */
    function get_user_versions($con, $get){
        global $tab_list;
        $versions = array();
        foreach($tab_list as $tab){
            $versions[$tab] = intval(extract_param($con, $tab));
        }
        return $versions;
    }


    /**
     * Reads the table version in the server.
     * 
     * Reads from the database the version of the tables that sync with the
     * clients.
     * 
     * @since 3.0.0
     * @param object $con Open database connection.
     * @return array Integer array with the version of the tables in the
     *               database, keyed with the table names.
     */
    function get_server_versions($con){
        $versions = array();
        $q = mysqli_query($con, "SELECT section, version FROM version;");
        while($r = mysqli_fetch_array($q)){
            $versions[$r['section']] = $r['version'];
        }
        return $versions;
    }


    /**
     * Select the tables that need to be synced.
     * 
     * Determines the tables in {@see $tab_list} that are out of sync between
     * the server and the client.
     *
     * @since 3.0.0
     * @global array $tab_list Array with the names of the tables that sync
     *                         whit clients.
     * @param object $con Open database connection.
     * @param array $user Integer array with the version of the tables reported
     *                    in the request, keyed with the table names.
     * @param array $server Integer array with the version of the tables in the
     *                      database, keyed with the table names.
     * @return array String array with the name of the tables present in $user whose
     *               versions are lower than the ones in $server.
     */
    function select_tables($user, $server){
        global $tab_list;
        $tables = array();
        foreach($tab_list as $table){
            if ((int) $user[$table] < (int) $server[$table]){
                array_push($tables, $table);
            }
        }
        return $tables;
    }


    /**
     * JSON-izes the versions of the tables to sync.
     * 
     * Generates a JSON-formatted string with the versions of all the tables
     * to sync.
     * 
     * @since 3.0.0
     * @param object $con Open database connection.
     * @param array $tables String array with the names of the tables.
     * @return string JSON-formatted string with the version of the tables.
     *                Empty string if no valid table names were passes in $tables.
     */
    function get_table_version($con, $tables){
        // Build query, showing only tables to sync
        $s = "SELECT * FROM version WHERE ";
        foreach($tables as $table){
            $s = $s . "section = '$table' OR ";
        }
        $s = $s . "1 = 2;";
        $q = mysqli_query($con, $s);

        //If no rows, return
        if (mysqli_num_rows($q) == 0){
            return "";
        }

        //Create result JSON
        $str = "";
        $str = $str. "\"version\":[";
        while($r = mysqli_fetch_assoc($q)) {
            $str = $str . json_encode($r) . ",";
        }
        $str = rtrim($str,',');
        $str = $str . "],";
        return $str;

    }


    /**
     * JSON-izes the data in a table.
     * 
     * Generates a JSON-formatted string with the data in a table. Inaccessible
     * or sensitive tables or fields are not returned.
     * 
     * @since 1.0.0
     * @param object $con Open database connection.
     * @param string $table Table name.
     * @return string JSON-formatted string with the data in the table. Empty
     *                string if $table was not a valid table name.
     */
    function get_table($con, $table){
        $table = strtolower($table);
        switch ($table){
            case TAB_ACTIVITY:
                $q = mysqli_query($con, "SELECT id, permalink, date, city, title_es, title_en, title_eu, text_es, text_eu, text_en, after_es, after_en, after_eu, price, inscription, max_people, album FROM activity WHERE visible = 1;");
                break;
            case TAB_ACTIVITY_COMMENT:
                $q = mysqli_query($con, "SELECT id, activity, text, dtime, username, lang FROM activity_comment WHERE approved = 1;");
                break;
            case TAB_ALBUM:
                $q = mysqli_query($con, "SELECT id, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, open FROM album;");
                break;
            case TAB_PHOTO:
                $q = mysqli_query($con, "SELECT photo.id AS id, file, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, uploaded, place, width, height, size, CONCAT(photo.username, user) AS username FROM photo, user WHERE user.id = photo.user AND approved = 1;");
                break;
            case TAB_POST:
                $q = mysqli_query($con, "SELECT post.id AS id, permalink, title_es, title_en, title_eu, text_es, text_en, text_eu, comments, username, dtime FROM post, user WHERE user.id = user AND visible = 1;");
                break;
            case TAB_POST_COMMENT:
                $q = mysqli_query($con, "SELECT post_comment.id AS id, post, text, dtime, username, lang FROM post_comment WHERE approved = 1;");
                break;
            case TAB_PHOTO_COMMENT:
                $q = mysqli_query($con, "SELECT photo_comment.id AS id, post, text, dtime, username, lang FROM photo_comment WHERE approved = 1;");
                break;
            case TAB_SPONSOR:
                $q = mysqli_query($con, "SELECT id, name_es, name_en, name_eu, text_es, text_en, text_eu, image, address_es, address_en, address_eu, link, lat, lon FROM sponsor;");
                break;
            case TAB_SETTINGS:
                $q = mysqli_query($con, "SELECT name, value FROM settings;");
                break;

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


    /**
     * Gets the data on the requested tables.
     * 
     * Builds a JSON string with the data in all the requested tables.
     * Inaccessible or sensitive tables or fields are not returned.
     *
     * @since 1.0.0
     * @see get_table($con, $table)
     * @param object $con Open database connection.
     * @param array $tables String array with the names of the tables to sync.
     * @return string JSON-formatted string with the data in the requested
     *                tables. Empty string if no valid table names were
     *                provided in $tables.
     */
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
            if (strlen($str > 0)){
                log_sync($con, $user, $synced);
                echo($str);
                http_response_code(200);
            }
            else{
                http_response_code(204);
            }
            return true;
        }
        http_response_code(204);
        return false;
    }


    /**
     * Gets the user IP address.
     * 
     * @since 1.0.0
     * @return string User IP address.
     */
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


    /**
     * Logs a request to the database.
     * 
     * Creates an entry in the table 'sync' with the details of the request.
     *
     * @since 1.0.0
     * @param object $con Open database connection.
     * @param array $user {
     *     @type string client Client identifier. Empty if not provided.
     *     @type string user User identifier. Empty if not provided.
     *     @type int foreground 1 if the sync is being made in the app
     *                          foreground, 0 otherwise.
     *     @type string ip Client IP.
     *     @type string os Client operating system identifier. Empty if not 
     *                     found.
     *     @type string browser Client browser identifier. Empty if not found.
     *     @type string uagent Client user agent. Empty if not found.
     * }
     * @param int synced 1 if sync data was finally sent, 0 otherwise.
     */
    function log_sync($con, $user, $synced){
        mysqli_query($con, "INSERT INTO sync (client, user, fg, synced, ip, os, uagent) VALUES ('$user[client]', '$user[user]', $user[foreground], $synced, '$user[ip]', '$user[os]', '$user[uagent]');");
    }


    /**
     * Logs a failed request to the database.
     * 
     * Creates an entry in the table 'sync' with the details of the failed request.
     *
     * @since 1.0.0
     * @param object $con Open database connection.
     * @param array $user {
     *     @type string client Client identifier. Empty if not provided.
     *     @type string user User identifier. Empty if not provided.
     *     @type int foreground 1 if the sync is being made in the app
     *                          foreground, 0 otherwise.
     *     @type string ip Client IP.
     *     @type string os Client operating system identifier. Empty if not 
     *                     found.
     *     @type string browser Client browser identifier. Empty if not found.
     *     @type string uagent Client user agent. Empty if not found.
     *     @type string error Error code.
     * }
     */
    function log_error($con, $user){
        mysqli_query($con, "INSERT INTO sync (client, user, fg, error, ip, os, uagent) VALUES ('$user[client]', '$user[user]', $user[foreground], $user[error], '$user[ip]', '$user[os]', '$user[uagent]');");
    }


    // SCRIPT START


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
?>
