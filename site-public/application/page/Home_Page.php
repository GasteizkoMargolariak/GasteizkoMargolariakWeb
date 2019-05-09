<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Post.php");
    require_once($path["entity"] . "Activity.php");

    /**
     * Home page model.
     */
    class Home_Page extends Page{

        /**
         * List of the last 3 {@see Post}.
         */
        public $post = [];

        /**
         * List of {@see Activity} in the future, sorted with the closest one
         * first.
         */
        public $future = [];

        /**
         * List of {@see Activity} in the past, sorted with the closest one
         * first. Limited to 3.
         */
        public $past = [];

        /**
         * Constructor.
         *
         * Retrieves the data and initializes the variables.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         */
        public function __construct($db, $lang){
            global $path;
            global $base_url;
            global $data;
            parent::__construct($db, $lang);
            $this->template = $path["template"] . "home.php";
            $s =
              "SELECT id " .
              "FROM post " .
              "WHERE visible = 1 " .
              "ORDER BY dtime DESC " .
              "LIMIT 3;";
            $q = mysqli_query($this->db, $s);
            while($r = mysqli_fetch_array($q)){
                array_push($this->post, new Post($this->db, $this->lang, $r["id"]));
            }
            $s_future =
              "SELECT id " .
              "FROM activity " .
              "WHERE " .
              "  visible = 1 AND " .
              "  date > now() " .
              "ORDER BY date;";
            $q_future = mysqli_query($this->db, $s_future);
            while($r_future = mysqli_fetch_array($q_future)){
                array_push($this->future, new Activity($this->db, $this->lang, $r_future["id"]));
            }
            $s_past =
              "SELECT id " .
              "FROM activity " .
              "WHERE " .
              "  visible = 1 AND " .
              "  date < now() " .
              "ORDER BY date DESC " .
              "LIMIT 3;";
            $q_past = mysqli_query($this->db, $s_past);
            while($r_past = mysqli_fetch_array($q_past)){
                array_push($this->past, new Activity($this->db, $this->lang, $r_past["id"]));
            }
            $this->title = $data["name"];
            $this->description = $data["description"];
            $this->canonical = $base_url . "/";
        }
    }
?>
