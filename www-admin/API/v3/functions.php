<?php

    $IMG_SIZE_PREVIEW = 600;
    $IMG_SIZE_MINIATURE = 200;

    /*****************************************************
     * Finds out the protocol the user is connecting to  *
     * the site with (http or https)                     *
     * @return: (string): "http://" or "https://".       *
     ****************************************************/
    function getProtocol(){
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                $protocol = 'https://';
        }
        else {
                $protocol = 'http://';
        }
        return $protocol;
    }

    /*****************************************************
     * Connects to the database, in 'r' or 'rw' mode.    *
     * @params:                                          *
     *    mode: (string) Indicates required permissions  *
     *          on database. 'ro' gives read             *
     *          permissions, and 'rw' read and write     *
     *          permissions. Other values will result in *
     *          errors.                                  *
     * @return: (db connection): The connection handler. *
     *****************************************************/
    function startdb($mode = 'ro'){
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
        include('../../../www/.htpasswd');

        //Connect to to database
        if ($mode == 'ro')
            $con = mysqli_connect($host, $username_ro, $pass_ro, $db_name);
        else if ($mode == 'rw')
            $con = mysqli_connect($host, $username_rw, $pass_rw, $db_name);

        // Check connection
        if (mysqli_connect_errno()){
            error_log("Failed to connect to database: " . mysqli_connect_error());
            return -1;
        }

        //Set encoding options
        mysqli_set_charset($con, 'utf-8');
        header('Content-Type: text/html; charset=utf8');
        mysqli_query($con, 'SET NAMES utf8;');

        //Return the db connection
        return $con;
    }


    /*****************************************************
     * This function closes all the opened HTML tags in  *
     * a given string.                                   *
     *                                                   *
     * @params:                                          *
     *    html: (string): The string with HTML tags      *
     *****************************************************/
    function closeTags($html) {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }

    /*****************************************************
     * Text shortener. Given a string, it trims in the   *
     * proximity of the desired streng, ut to the next   *
     * white character. If indicated, it will append a   *
     * link to the full text.                            *
     *                                                   *
     * @params:                                          *
     *    text: (string): The text to shorten.           *
     *    length: (int): The desired length.             *
     *    linktext: (string): Text fot the link.         *
     *    link: (string): URI of the full text.          *
     *****************************************************/
     function cutText($text, $length, $linktext, $link){
        if (strlen($text) < $length){
            return $text;
        }
        $cut = substr($text, 0, strpos($text, " ", $length));
        $cut = closeTags($cut);
        if (strlen($cut) == 0){
            $cut = $text;
        }
        if (strlen($text) != strlen($cut)){
            $cut = $cut . "... <a href='$link'>$linktext</a>";
        }
        return $cut;
    }

    /*****************************************************
     * Generates a URL-valid string from a regular one.  *
     *                                                   *
     * @params:                                          *
     *    text: (string): Original string.               *
     * @return: (string): URL-valid string.              *
     *****************************************************/
    function permalink($text){
        $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        $str = strtr( $text, $unwanted_array );
        $str = preg_replace('/[^\da-zA-Z ]/i', '', $str);
        $str = str_replace(' ', '-', $str);
        return $str;
    }


    /*****************************************************
     * Validates a user/password combo.                  *
     *                                                   *
     * @params:                                          *
     *    con: (Mysql connectrion): DB connection.       *
     *    user: (string): Username.                      *
     *    pass: (string): Password.                      *
     * @return: (boolean): True if user pass match,      *
     *           false otherwise.                        *
     *****************************************************/
    function login($con, $user, $pass){
        session_start();
        $q = mysqli_query($con,"SELECT id, salt, username AS username, sha1(salt) AS s FROM user WHERE (lower(username) = lower('$user') OR lower(email) = lower('$user')) AND password = sha1(concat('$pass', sha1(salt)));");
        if (mysqli_num_rows($q) == 1){
            $r = mysqli_fetch_array($q);
            $_SESSION['id'] = $r['id'];
            $_SESSION['salt'] = $r['s'];
            $_SESSION['name'] = $r['username'];
            return true;
        }
        else{
            error_log("Invalid login. username = $user, id = $_SESSION[id]");
            return false;
        }
    }

    /*****************************************************
     * Tries to login, using parameters sent via POST.   *
     *                                                   *
     * @params:                                          *
     *    con: (Mysql connectrion): DB connection.       *
     * @return: (boolean): True if user pass match,      *
     *           false otherwise.                        *
     *****************************************************/
    function fastLogin($con){
        return login($con, mysqli_real_escape_string($con, $_POST["user"]), mysqli_real_escape_string($con, $_POST["pass"]));
    }


    /*****************************************************
     * Finds out the IP address of the client.           *
     *                                                   *
     * @return: (String): Client IP address.             *
     *****************************************************/
    function getUserIP(){
        $client  = @$_SERVER["HTTP_CLIENT_IP"];
        $forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
        $remote  = $_SERVER["REMOTE_ADDR"];
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

?>
