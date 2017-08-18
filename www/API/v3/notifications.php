<?php
    // Gasteizko Margolariak API v3 //

    //Posible notification target
    define('TARGET_ALL', 'all');
    define('TARGET_GM', 'gm');

    //Default target
    define('DEF_TARGET', TARGET_ALL);

    //$_GET valid parameters
    define('GET_TARGET', 'target');

    //Error messages
    define('ERR_TARGET', '-TARGET:');

    /*****************************************************
     * Initializes the session variables and connects to * 
     * the db.                                           *
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
     * Discerns the type of notificatios to look for     *
     * using the parameter 'target' from the list of get *
     * arguments. Valid values are 'all'or 'gm'. The     *
     * default is 'all'.                                 *
     *                                                   *
     * @params:                                          *
     *    get: (String array) List of GET parameters.    *
     * @return: (String): 'all' if it was passed as GET  *
     *          parameter, 'gm' if it was passed or if   *
     *          the parameter was not passed, or null if *
     *          some other value was passed.             *
     *****************************************************/
    function get_target($get){
        $target = $get[GET_TARGET];
        if (strlen($target) < 1){
            return DEF_TARGET;
        }
        else{
            if ($target != TARGET_ALL && $target != TARGET_GM){
                return null;
            }
            else{
                return $target;
            }
        }
    }

    /*****************************************************
     * Retrieves the notificatios that are still on time *
     * to be delivered.                                  *
     *                                                   *
     * @params:                                          *
     *    con: (MySQL server connection) Db connector.   *
     *    target: (String) 'gm' or 'all'.                *
     * @return: (String): The list of notifications to   *
     *          be sent to the app, in JSON format, or   *
     *          null if there are none.                  *
     *****************************************************/
    function select_notifications($con, $target){
        if ($target == TARGET_GM){
            $query = "SELECT id, title_es, title_en, title_eu, text_es, text_en, text_eu, dtime, internal AS gm, duration, action, 0 AS seen FROM notification WHERE internal = 1 AND dtime > NOW() - INTERVAL duration MINUTE ORDER BY dtime DESC";
        }
        else{
            $query = "SELECT id, title_es, title_en, title_eu, text_es, text_en, text_eu, dtime, internal AS gm, duration, action, 0 AS seen FROM notification WHERE dtime > NOW() - INTERVAL duration MINUTE ORDER BY dtime DESC";
        }
        $q = mysqli_query($con, $query);
        if (mysqli_num_rows($q) == 0){
            return null;
        }
        else{
            $rows = array();
            while($r = mysqli_fetch_assoc($q)) {
                $rows[] = $r;
            }
            return(json_encode($rows));
        }
    }

    /*****************************************************
     * Prints the notifications.                         *
     *                                                   *
     * @params:                                          *
     *    notifications: (String) Notification list, in  *
     *                   JSON format.                    *
     *****************************************************/
    function print_notifications($notifications){
        print($notifications);
    }

    // Connect to the database
    $con = startdb('rw');

    // Select target
    $target = get_target($_GET);
    if ($target == null){
        // Bad request
        http_response_code(400);
        exit(-1);
    }

    // Get notifications
    $notifications = select_notifications($con, $_GET[GET_TARGET]);
    if ($notifications == null){
        // No content
        http_response_code(204);
    }
    else{
        print_notifications($notifications);
    }

?>
