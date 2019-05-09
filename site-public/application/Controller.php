<?php

    require_once(__DIR__ . "/config/config.php");
    require_once($path["util"] . "db.php");
    require_once($path["util"] . "text.php");
    require_once($path["util"] . "net.php");


    /**
     * Application controller.
     *
     * Handles every request, creating the required models and selecting the
     * view.
     */
    class Controller {

        /**
         * Constructor.
         *
         * Handles every request, creating the required models and selecting the
         * view.
         */
        public function __construct($params){

            global $path;
            global $base_url;
            global $url_l10n;
            global $static;
            global $static_url;
            global $base_dir;
            global $data;
            $page;

            $db = start_db();

            // Parse parameters, get lang and build the route.
            $pars = [];
            foreach($params as $p){
                if (strlen($p) > 0){
                    array_push($pars, $p);
                }
            }
            $route = [];
            if (sizeof($pars) > 0 && preg_match("/^[a-zA-Z]{2}$/", $pars[0])){
                $lang = $pars[0];
                array_splice($pars, 0, 1);
                // Override global to include language in URL
                $base_url = get_protocol() . $_SERVER["HTTP_HOST"] . "/" . $lang;
            }
            else{
                $lang = select_language($db);
            }
            if (isset($url_l10n[$lang])){
                $url = $url_l10n[$lang];
            }
            else{
                $url = $url_l10n["es"];
            }
            foreach($pars as $p){
                array_push($route, $p);
            }

            // Select the model to load.
            if (sizeof($route) == 0){
                require_once($path["page"] . "Home_Page.php");
                $page = new Home_Page($db, $lang);
            }
            else{
                if (strtolower($route[0]) == $url["help"]){
                    require_once($path["page"] . "Help_Page.php");
                    $page = new Help_Page($db, $lang);
                }
                if (strtolower($route[0]) == $url["activities"]){
                    if (sizeof($route) > 1){
                        require_once($path["page"] . "Activity_Page.php");
                        $page = new Activity_Page($db, $lang, $route[1]);
                    }
                    else{
                        require_once($path["page"] . "Activities_Page.php");
                        $page = new Activities_Page($db, $lang);
                    }
                }
                if (strtolower($route[0]) == $url["blog"]){//$url->blog){
                    if (sizeof($route) > 1){
                        require_once($path["page"] . "Post_Page.php");
                        $page = new Post_Page($db, $lang, $route[1]);
                    }
                    else{
                        require_once($path["page"] . "Blog_Page.php");
                        $page = new Blog_Page($db, $lang);
                    }
                }
                if (strtolower($route[0]) == $url["us"]){
                    require_once($path["page"] . "Us_Page.php");
                    $page = new Us_Page($db, $lang);
                }
                if (strtolower($route[0]) == $url["gallery"]){
                    if (sizeof($route) > 1){
                        require_once($path["page"] . "Album_Page.php");
                        $page = new Album_Page($db, $lang, $route[1]);
                    }
                    else{
                        require_once($path["page"] . "Gallery_Page.php");
                        $page = new Gallery_Page($db, $lang);
                    }
                }
                if (strtolower($route[0]) == $url["festivals"]){
                    // TODO: Manage year and subsections
                    require_once($path["page"] . "Festivals_Page.php");
                    $page = new Festivals_Page($db, $lang);
                }
            }

            // Load the view, or set an error code.
            if (!isset($page)){
                http_response_code(404);
                // TODO: Set up error page
                require_once($path["page"] . "Home_Page.php");
                $page = new Home_Page($db, $lang);
                //require_once($path["page"] . "Error_Page.php");
                //$page = new Error_Page($db, $lang);
            }
            require_once($page->template);
        }
    }
?>
