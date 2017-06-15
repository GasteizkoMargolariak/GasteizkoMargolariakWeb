<?php
    // Gasteizko Margolariak API v1 //
    
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
    
    //Get data from URL
    $client = mysqli_real_escape_string($con, $_GET[GET_CLIENT]);
    $user = mysqli_real_escape_string($con, $_GET[GET_USER]);
    $target = strtolower(mysqli_real_escape_string($con, $_GET[GET_TARGET]));
    $id = strtolower(mysqli_real_escape_string($con, $_GET[GET_ID]));
    $permalink = strtolower(mysqli_real_escape_string($con, $_GET[GET_PERMALINK]));
    $ink = strtolower(mysqli_real_escape_string($con, $_GET[GET_PERMALINK]));
    $permalink = strtolower(mysqli_real_escape_string($con, $_GET[GET_PERMALINK]));
    $username = mysqli_real_escape_string($con, $_GET[GET_USERNAME]);
    $text = mysqli_real_escape_string($con, $_GET[GET_TEXT]);
    
    //Validate data
    if (strlen($client) < 1){
        //Bad request
        http_response_code(400);
        exit();
    }
    if (strlen($user) < 1){
        $user = '';
    }
    if (strlen($target) < 1){
        //Bad request
        http_response_code(400);
        exit();
    }
    if ($target != TARGET_PHOTO && $target != TARGET_POST && $target != TARGET_ACTIVITY){
        //Bad request
        http_response_code(400);
        exit();
    }
    if (strlen($username) < 1){
        //Bad request
        http_response_code(400);
        exit();
    }
    
    
    //Check id and/or permalink. Several cases:
    
    //1st case: id and permalink empty: Error.
    if (strlen($id) < 1 && strlen($permalink) < 1){
        //Bad request
        http_response_code(400);
        exit();
    }
    
    //2nd case: Comment for post, permalink and no id.
    elseif ($target == TARGET_POST && strlen($id) < 1 && strlen($permalink) >= 1){
    
        //Check if post exists...
        $q = mysqli_query($con, "SELECT id, comments FROM post WHERE visible = 1 AND permalink = '$permalink';");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
        
            //... and if it does, check if can be commented.
            $r = mysqli_fetch_array($q);
            $item_id = $r['id'];
            if ($r['comments'] != 1){
                //'Forbidden' status code
                http_response_code(403);
                exit();
            }
        }
    }
    
    //3rd case: Comment for post, id and no permalink.
    elseif ($target == TARGET_POST && strlen($id) >= 1 && strlen($permalink) < 1){
    
        //Check if post exists...
        $q = mysqli_query($con, "SELECT id, comments FROM post WHERE visible = 1 AND id = $id;");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
        
            //... and if it does, check if can be commented.
            $r = mysqli_fetch_array($q);
            $item_id = $r['id'];
            if ($r['comments'] != 1){
                //'Forbidden' status code
                http_response_code(403);
                exit();
            }
        }
    }
    
    //4th case: Comment for post, permalink and id.
    elseif ($target == TARGET_POST && strlen($id) >= 1 && strlen($permalink) >= 1){
    
        //Check if post exists...
        $q = mysqli_query($con, "SELECT id, comments FROM post WHERE visible = 1 AND permalink = '$permalink' AND id = $id ;");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
        
            //... and if it does, check if can be commented.
            $r = mysqli_fetch_array($q);
            $item_id = $r['id'];
            if ($r['comments'] != 1){
                //'Forbidden' status code
                http_response_code(403);
                exit();
            }
        }
    }
    
    //5th case: Comment for photo, permalink and no id.
    elseif ($target == TARGET_PHOTO && strlen($id) < 1 && strlen($permalink) >= 1){
    
        //Check if photo exists.
        $q = mysqli_query($con, "SELECT id FROM photo WHERE approved = 1 AND permalink = '$permalink';");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
            $item_id = $r['id'];
        }
    }
    
    //6th case: Comment for photo, id and no permalink.
    elseif ($target == TARGET_PHOTO && strlen($id) >= 1 && strlen($permalink) < 1){
    
        //Check if photo exists.
        $q = mysqli_query($con, "SELECT id FROM photo WHERE approved = 1 AND id = $id;");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
            $item_id = $r['id'];
        }
    }
    
    //7th case: Comment for photo, permalink and id.
    elseif ($target == TARGET_PHOTO && strlen($id) >= 1 && strlen($permalink) >= 1){
    
        //Check if photo exists.
        $q = mysqli_query($con, "SELECT id FROM photo WHERE approved = 1 AND permalink = '$permalink' AND id = $id;");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
            $item_id = $r['id'];
        }
    }
    
    //8nd case: Comment for activity, permalink and no id.
    elseif ($target == TARGET_ACTIVITY && strlen($id) < 1 && strlen($permalink) >= 1){
    
        //Check if activity exists...
        $q = mysqli_query($con, "SELECT id, comments FROM activity WHERE visible = 1 AND permalink = '$permalink';");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
        
            //... and if it does, check if can be commented.
            $r = mysqli_fetch_array($q);
            $item_id = $r['id'];
            if ($r['comments'] != 1){
                //'Forbidden' status code
                http_response_code(403);
                exit();
            }
        }
    }
    
    //9rd case: Comment for activity, id and no permalink.
    elseif ($target == TARGET_ACTIVITY && strlen($id) >= 1 && strlen($permalink) < 1){
    
        //Check if activity exists...
        $q = mysqli_query($con, "SELECT id, comments FROM activity WHERE visible = 1 AND id = $id;");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
        
            //... and if it does, check if can be commented.
            $r = mysqli_fetch_array($q);
            $item_id = $r['id'];
            if ($r['comments'] != 1){
                //'Forbidden' status code
                http_response_code(403);
                exit();
            }
        }
    }
    
    //10th case: Comment for activity, permalink and id.
    elseif ($target == TARGET_ACTIVITY && strlen($id) >= 1 && strlen($permalink) >= 1){
    
        //Check if activity exists...
        $q = mysqli_query($con, "SELECT id, comments FROM activity WHERE visible = 1 AND permalink = '$permalink' AND id = $id ;");
        if (mysqli_num_rows($q) == 0){
            //Bad request
            http_response_code(400);
            exit();
        }
        else{
        
            //... and if it does, check if can be commented.
            $r = mysqli_fetch_array($q);
            $item_id = $r['id'];
            if ($r['comments'] != 1){
                //'Forbidden' status code
                http_response_code(403);
                exit();
            }
        }
    }
    
    //If code gets here, there were no errors. Build query.
    $query = "INSERT INTO ";
    $section = "";
    switch ($target){
        case TARGET_POST:
            $query = $query . "post_comment (post";
            $section = "blog";
            break;
        case TARGET_PHOTO:
            $query = $query . "photo_comment (photo";
            $section = "gallery";
            break;
        case TARGET_ACTIVITY:
            $query = $query . "activity_comment (activity";
            $section = "activity";
            break;
    }
    $query = $query . ", text, username, app) VALUES ($item_id, \"$text\", \"$username\", \"client\");";
    //echo($query);
    mysqli_query($con, $query);
    mysqli_query($con, "UPDATE version SET version = version + 1 WHERE section = '$section';");

?>
