<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Festival.php");

    /**
     * Festivals page model.
     */
    class Festivals_Page extends Page{

        /**
         * The selected {@see Festival}.
         * If a year has been specified, and it exists, it will be defined.
         * If no year is specified, and the setting for the festival is active,
         * it will have the one of the current year.
         * In any other case, it will be null.
         */
        public $festival;

        /**
         * Previous years with festivals.
         */
        public $previous = [];

        /**
         * Constructor.
         *
         * Retrieves the data and initializes the variables.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         * @param $year Year of the festivals.
         */
        public function __construct($db, $lang, $year = null){
            global $path;
            global $base_url;
            global $url;
            global $data;
            parent::__construct($db, $lang);
            if (is_set($year)){
                $s_festival =
                  "SELECT 1 " .
                  "FROM festival " .
                  "WHERE " .
                  "  year = $year;";
                $q_festival = mysqli_query($this->db, $s_festival);
                if (mysqli_num_rows($q_festival) > 0){
                    $this->festival = new Festival($this->db, $this->lang, $year);
                    $this->template = $path["template"] . "festivals_active.php";
                    $this->title = str_replace('#', $year, $this->string['lablanca_title']) . " - " . $data["name"];
                    $this->description = str_replace('#', $year, $this->string['lablanca_title']) . " - " . $data["name"];
                    $this->canonical = $base_url . "/" . $url->festivals . "/" . $year;
                }
                else{
                    $this->template = $path["template"] . "festivals_inactive.php";
                    $this->title = $this->string['lablanca_no_title'] . " - " . $data["name"];
                    $this->description = $this->string['lablanca_no_title'] . " - " . $data["name"];
                    $this->canonical = $base_url . "/" . $url->festivals . "/";
                }
            }
            else{
                $s_setting =
                  "SELECT value " .
                  "FROM settings " .
                  "WHERE " .
                  "  value = 1 AND " .
                  "  name = 'festivals';";
                $q_setting = mysqli_query($this->db, $s_setting);
                if (mysqli_num_rows($q_setting) > 0){
                    $s_festival =
                      "SELECT 1 " .
                      "FROM festival " .
                      "WHERE " .
                      "  year = " . date("Y") . ";";
                    $q_festival = mysqli_query($this->db, $s_festival);
                    if (mysqli_num_rows($q_festival) > 0){
                        $this->festival = new Festival($this->db, $this->lang, date("Y"));
                        $this->template = $path["template"] . "festivals_active.php";
                        $this->title = str_replace('#', date("Y"), $this->string['lablanca_title']) . " - " . $data["name"];
                        $this->description = str_replace('#', date("Y"), $this->string['lablanca_title']) . " - " . $data["name"];
                        $this->canonical = $base_url . "/" . $url->festivals . "/" . date("Y");
                    }
                    else{
                        $this->template = $path["template"] . "festivals_inactive.php";
                        $this->title = $this->string['lablanca_no_title'] . " - " . $data["name"];
                        $this->description = $this->string['lablanca_no_title'] . " - " . $data["name"];
                        $this->canonical = $base_url . "/" . $url->festivals . "/";
                    }
                }
            }
            $s_previous =
              "SELECT year " .
              "FROM festival " .
              "WHERE str_to_date(concat(year, '-08-10'), '%Y-%m-%d') < now() " .
              "ORDER BY year DESC;";
            $q_previous = mysqli_query($this->db, $s_previous);
            while($r_previous = mysqli_fetch_array($q_previous)){
                array_push($this->previous, $r_previous["year"]);
            }
        }
    }
?>
