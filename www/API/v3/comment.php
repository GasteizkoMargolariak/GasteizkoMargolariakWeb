<?php
    /**
     * Gasteizko Margolariak API v3 - Comment
     *
     * Used to post comments from apps.
     * This file is to be called directly from a URL request.
     *
     * https://margolariak.com/API/v3/help/
     *
     * @since 1.0.0
     */


    // Valid comment target
    define('TARGET_PHOTO', 'photo');
    define('TARGET_POST', 'post');
    define('TARGET_ACTIVITY', 'activity');

    // Default target
    define('DEF_TARGET', TARGET_ALL);

    // $_GET valid parameters
    define('GET_CLIENT', 'client');
    define('GET_USER', 'user');
    define('GET_TARGET', 'target');
    define('GET_ID', 'id');
    define('GET_PERMALINK', 'permalink');
    define('GET_TEXT', 'text');
    define('GET_USERNAME', 'username');
    define('GET_LANG', 'lang');


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
            $comment["username"]_ro = 'XXXX';
            $comment["username"]_rw = 'XXXX';
            $pass_ro = 'XXXX';
            $pass_rw = 'XXXX';
        ?>
        */
        include('../../.htpasswd');

        //Connect to to database
        $con = mysqli_connect($host, $comment["username"]_rw, $pass_rw, $db_name);

        //Set encoding options
        mysqli_set_charset($con, 'utf-8');
        header('Content-Type: text/html; charset=utf8');
        mysqli_query($con, 'SET NAMES utf8;');

        //Return the db connection
        return $con;
    }


    /**
     * Detects client language.
     *
     * Tries to detect the client language by detecting language cookie. It is
     * not to be used from external apps, only from the site. Only detects
     * spanish, basque or english.
     * 
     * @since 3.0.0
     * @return string Two letter language code ('es', 'en' 'eu') or null.
     */
    function detect_language(){
        //Try to read cookie.
        header('Cache-control: private');
        if (isSet($_COOKIE['lang'])){
            $lang = $_COOKIE['lang'];
            if ($lang == 'es' || $lang == 'en' || $lang == 'eu'){
                return $lang;
            }
            else{
                return null;
            }
        }
    }


    /**
     * Gets information about the comment.
     *
     * Gets all the information about the comment and the client from the
     * request parameters and the browser info and packs it into an array.
     * It also validates the data and check the permissions for posting the
     * comment.
     * 
     * @since 3.0.0
     * @param object $con Open database connection.
     * @param array $get Optional. Array with the request parameters. Default
     *                   is $_GET.
     * @return array {
     *     @type string client Client identifier. Empty if not provided.
     *     @type string user User identifier. Empty if not provided.
     *     @type string target The kind of content that the comment is meant
     *                         to. 'photo', 'post' or 'activity'.
     *     @type int id Photo, post, or activity ID.
     *     @type string permalink Optional. Photo, post, or activity permalink.
     *     @type string username Poster username.
     *     @type string text Comment text.
     *     @type string lang Two letter language code.
     *     @type string status Request status to be returned based on comment 
     *                  content. 204 (all good), 400 (bad data) or 403 (good
     *                  data, but comments in the content are closed).
     * }
     */
    function get_comment_info($con, $get = $_GET){

        $comment = array();

        //Get data from URL
        $comment["client"] = mysqli_real_escape_string($con, $get[GET_CLIENT]);
        $comment["user"] = mysqli_real_escape_string($con, $get[GET_USER]);
        $comment["target"] = strtolower(mysqli_real_escape_string($con, $get[GET_TARGET]));
        $comment["id"] = strtolower(mysqli_real_escape_string($con, $get[GET_ID]));
        $comment["permalink"] = strtolower(mysqli_real_escape_string($con, $get[GET_PERMALINK]));
        $comment["username"] = mysqli_real_escape_string($con, $get[GET_USERNAME]);
        $comment["text"] = mysqli_real_escape_string($con, $get[GET_TEXT]);
        if(isset($get[GET_LANG])){
            $comment["lang"] = mysqli_real_escape_string($con, $get[GET_LANG]);
        }
        else{
            $comment["lang"] = null;
        }
        $comment["status"] = 204; // No content status code: No error.

        //Validate data
        if (strlen($comment["client"]) < 1){
            $comment["status"] = 400; // Bad request status code.
        }
        if (strlen($comment["user"]) < 1){
            $comment["user"] = '';
        }
        if (strlen($comment["target"]) < 1){
            $comment["status"] = 400; // Bad request status code.
        }
        if ($comment["target"] != TARGET_PHOTO && $comment["target"] != TARGET_POST && $comment["target"] != TARGET_ACTIVITY){
            $comment["status"] = 400; // Bad request status code.
        }
        if (strlen($comment["username"]) < 1){
            $comment["status"] = 400; // Bad request status code.
        }
        if (strlen($comment["lang"]) == null){
            strlen($comment["lang"]) = detect_language()
        }

        //Check id and/or permalink. Several cases:

        //1st case: id and permalink empty: Error.
        if (strlen($comment["id"]) < 1 && strlen($comment["permalink"]) < 1){
            $comment["status"] = 400; // Bad request status code.
        }

        //2nd case: Comment for post, permalink and no id.
        elseif ($comment["target"] == TARGET_POST && strlen($comment["id"]) < 1 && strlen($comment["permalink"]) >= 1){

            //Check if post exists...
            $q = mysqli_query($con, "SELECT id, comments FROM post WHERE visible = 1 AND permalink = '$comment[permalink]';");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{

                //... and if it does, check if can be commented.
                $r = mysqli_fetch_array($q);
                $item_id = $r['id'];
                if ($r['comments'] != 1){
                    $comment["status"] = 403; // Forbidden status code.
                }
            }
        }


        //3rd case: Comment for post, id and no permalink.
        elseif ($comment["target"] == TARGET_POST && strlen($comment["id"]) >= 1 && strlen($comment["permalink"]) < 1){

            //Check if post exists...
            $q = mysqli_query($con, "SELECT id, comments FROM post WHERE visible = 1 AND id = $comment[id];");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{

                //... and if it does, check if can be commented.
                $r = mysqli_fetch_array($q);
                $item_id = $r['id'];
                if ($r['comments'] != 1){
                    $comment["status"] = 403; // Forbidden status code.
                }
            }
        }

        //4th case: Comment for post, permalink and id.
        elseif ($comment["target"] == TARGET_POST && strlen($comment["id"]) >= 1 && strlen($comment["permalink"]) >= 1){

            //Check if post exists...
            $q = mysqli_query($con, "SELECT id, comments FROM post WHERE visible = 1 AND permalink = '$comment[permalink]' AND id = $comment[id] ;");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{

                //... and if it does, check if can be commented.
                $r = mysqli_fetch_array($q);
                $item_id = $r['id'];
                if ($r['comments'] != 1){
                    $comment["status"] = 403; // Forbidden status code.
                }
            }
        }

        //5th case: Comment for photo, permalink and no id.
        elseif ($comment["target"] == TARGET_PHOTO && strlen($comment["id"]) < 1 && strlen($comment["permalink"]) >= 1){

            //Check if photo exists.
            $q = mysqli_query($con, "SELECT id FROM photo WHERE approved = 1 AND permalink = '$comment[permalink]';");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{
                $item_id = $r['id'];
            }
        }

        //6th case: Comment for photo, id and no permalink.
        elseif ($comment["target"] == TARGET_PHOTO && strlen($comment["id"]) >= 1 && strlen($comment["permalink"]) < 1){

            //Check if photo exists.
            $q = mysqli_query($con, "SELECT id FROM photo WHERE approved = 1 AND id = $comment[id];");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{
                $item_id = $r['id'];
            }
        }

        //7th case: Comment for photo, permalink and id.
        elseif ($comment["target"] == TARGET_PHOTO && strlen($comment["id"]) >= 1 && strlen($comment["permalink"]) >= 1){

            //Check if photo exists.
            $q = mysqli_query($con, "SELECT id FROM photo WHERE approved = 1 AND permalink = '$comment[permalink]' AND id = $comment[id];");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{
                $item_id = $r['id'];
            }
        }

        //8th case: Comment for activity, permalink and no id.
        elseif ($comment["target"] == TARGET_ACTIVITY && strlen($comment["id"]) < 1 && strlen($comment["permalink"]) >= 1){

            //Check if activity exists...
            $q = mysqli_query($con, "SELECT id, comments FROM activity WHERE visible = 1 AND permalink = '$comment[permalink]';");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{

                //... and if it does, check if can be commented.
                $r = mysqli_fetch_array($q);
                $item_id = $r['id'];
                if ($r['comments'] != 1){
                    $comment["status"] = 403; // Forbidden status code.
                }
            }
        }

        //9th case: Comment for activity, id and no permalink.
        elseif ($comment["target"] == TARGET_ACTIVITY && strlen($comment["id"]) >= 1 && strlen($comment["permalink"]) < 1){
            //Check if activity exists...
            $q = mysqli_query($con, "SELECT id, comments FROM activity WHERE visible = 1 AND id = $comment[id];");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{
                //... and if it does, check if can be commented.
                $r = mysqli_fetch_array($q);
                $item_id = $r['id'];
                if ($r['comments'] != 1){
                    $comment["status"] = 403; // Forbidden status code.
                }
            }
        }

        //10th case: Comment for activity, permalink and id.
        elseif ($comment["target"] == TARGET_ACTIVITY && strlen($comment["id"]) >= 1 && strlen($comment["permalink"]) >= 1){
            //Check if activity exists...
            $q = mysqli_query($con, "SELECT id, comments FROM activity WHERE visible = 1 AND permalink = '$comment[permalink]' AND id = $comment[id] ;");
            if (mysqli_num_rows($q) == 0){
                $comment["status"] = 400; // Bad request status code.
            }
            else{
                //... and if it does, check if can be commented.
                $r = mysqli_fetch_array($q);
                $item_id = $r['id'];
                if ($r['comments'] != 1){
                    $comment["status"] = 403; // Forbidden status code.
                }
            }
        }
    }


    /**
     * Inserts the comment into the database.
     * 
     * Inserts the comment in the database. The table will be post_comment,
     * photo_comment or activity_comment.
     *
     * @since 3.0.0
     * @param object $con Open database connection.
     * @param array $comment As returned by {@see get_comment_info($con, $get)}.
     */
    function insert_comment($con, $comment){
        $query = "INSERT INTO ";
        switch ($comment["target"]){
            case TARGET_POST:
                $query = $query . "post_comment (post";
                break;
            case TARGET_PHOTO:
                $query = $query . "photo_comment (photo";
                break;
            case TARGET_ACTIVITY:
                $query = $query . "activity_comment (activity";
                break;
        }
        if ($comment["lang"] == null){
            $lang = "'$comment[lang]'";
        }
        else{
            $lang = "null";
        }
        $query = $query . ", text, username, app, app_user, lang) VALUES ($comment[id], '$comment[text]', '$comment[username]', '$comment[client]', '$comment[user]', $lang);";
        mysqli_query($con, $query);
    }


    // SCRIPT START


    //Connect to the database
    $con = startdb('rw');
    // Get all the info
    $comment = get_comment_info($con, $_GET);
    // Set return status
    http_response_code($comment["status"]);
    // Save comment or exit badly.
    if ($comment["status"] == 204){ // 4XX or 5XX are errors.
        insert_comment($con, $comment);
    }
    else{
        exit(-1);
    }
?>
