<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Activity.php");

    /**
     * Activities page model.
     */
    class Activities_Page extends Page{

        /**
         * List of {@see Activity} in the future, sorted with the closest one
         * first.
         */
        public $future = [];

        /**
         * List of {@see Activity} in the past, sorted with the closest one
         * first.
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
            $this->template = $path["template"] . "activities.php";
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
              "ORDER BY date DESC;";
            $q_past = mysqli_query($this->db, $s_past);
            while($r_past = mysqli_fetch_array($q_past)){
                array_push($this->past, new Activity($this->db, $this->lang, $r_past["id"]));
            }
            $this->title = $this->string["section_activities"] . " - " . $data["name"];
            $this->description = $this->string["section_activities"] . " - " . $data["name"];
            $this->canonical = $base_url . "/actividades/";
        }
    }
?>
