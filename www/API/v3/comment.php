<?php
    // Gasteizko Margolariak API v3 //

    //Posible comment target
    define('TARGET_PHOTO', 'photo');
    define('TARGET_POST', 'post');
    define('TARGET_ACTIVITY', 'activity');

    //Default target
    define('DEF_TARGET', TARGET_ALL);

    //$_GET valid parameters
    define('GET_CLIENT', 'client');
    define('GET_USER', 'user');
    define('GET_TARGET', 'target');
    define('GET_ID', 'id');
    define('GET_PERMALINK', 'permalink');
    define('GET_TEXT', 'text');
    define('GET_USERNAME', 'username');
    define('GET_LANG', 'lang');

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

    /*****************************************************
     * Gets information about the comment from the get   *
     * paameters and the browser info.                   *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    get: (string array) Contains the GET           *
     *         parameters.                               *
     * @return: (string array): Array with the keys      *
     *           'client', 'user', 'target', 'id',       *
     *           'permalink', 'username', 'text', 'lang' *
     *           and 'status'. 'status' will contain a   *
     *           4XX status code if some parameter is    *
     *           missing, invalid, or the comment can't  *
     *           be posted.                              *
     *****************************************************/
    function get_comment_info($con, $get){

        $comment = array();

        //Get data from URL
        $comment["client"] = mysqli_real_escape_string($con, $get[GET_CLIENT]);
        $comment["user"] = mysqli_real_escape_string($con, $get[GET_USER]);
        $comment["target"] = strtolower(mysqli_real_escape_string($con, $get[GET_TARGET]));
        $comment["id"] = strtolower(mysqli_real_escape_string($con, $get[GET_ID]));
        $comment["permalink"] = strtolower(mysqli_real_escape_string($con, $get[GET_PERMALINK]));
        $comment["username"] = mysqli_real_escape_string($con, $get[GET_USERNAME]);
        $comment["text"] = mysqli_real_escape_string($con, $get[GET_TEXT]);
        $comment["lang"] = mysqli_real_escape_string($con, $get[GET_LANG]);
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

    /*****************************************************
     * Inserts the comment into the database.            *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    comment: (string array)  Array with the keys   *
     *             'client', 'user', 'target', 'id',     *
     *             'permalink', 'username', 'text',      *
     *             'lang' and 'status'.                  *
     *****************************************************/
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
        $query = $query . ", text, username, app, user) VALUES ($comment[id], '$comment[text]', '$comment[username]', '$comment[client]', '$comment[user]');";
        //echo($query);
        mysqli_query($con, $query);
    }

    //Connect to the database
    $con = startdb('rw');
    $comment = get_comment_info($con, $_GET);
    if ($comment["status"] >= 400){ // 4XX or 5XX are errors.
        http_response_code($comment["status"]);
        exit(-1);
    }
    insert_comment($con, $comment);
    http_response_code(204);

?>
