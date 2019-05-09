<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Festival_Day.php");
    require_once($path["entity"] . "Festival_Offer.php");
    require_once($path["entity"] . "Festival_Event.php");

    /**
     * Festival.
     *
     * Represents an object from the table 'festival'.
     */
    class Festival extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Year of the festivals.
         */
        public $year;

        /**
         * Short text.
         */
        public $text;

        /**
         * Description of the festivals.
         */
        public $description;

        /**
         * Filename of the festivals banner.
         */
        public $image;

        /**
         * Array of {@see Festival_Day}.
         */
        public $day = [];

        /**
         * Array of {@see Festival_Offer}.
         */
        public $offer = [];

        /**
         * Array of {@see Festival_Event}.
         */
        public $event = [
            "internal" => [],
            "public" => []
        ];

        /**
         * Constructor.
         *
         * Searches the database and retrieves the information about the
         * entity, populating it and it's items.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         * @param year Year or identifier.
         */
        public function __construct($db, $lang, $year){
            parent::__construct($db, null);
            $s =
              "SELECT " .
              "  id, " .
              "  year, " .
              "  text_$lang AS text, " .
              "  description_$lang AS description, " .
              "  img AS image " .
              "FROM festival " .
              "WHERE " .
              "  id = $id OR " .
              "  year = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->internal = $r["year"];
                $this->text = $r["text"];
                $this->description = $r["description"];
                if (!is_null($r["image"])){
                    $this->image = $r["image"];
                }
            }
            $s_day =
              "SELECT id " .
              "FROM festival_day " .
              "WHERE year(date) = " . $this->year . " " .
              "ORDER BY date; ";
            $q_day = mysqli_query($this->db, $s_day);
            while($r_day = mysqli_fetch_array($q_day)){
                array_push($this->day, new Festival_Day($this->db, $this->lang, $r_day["id"]));
            }
            $s_offer =
              "SELECT id " .
              "FROM festival_offer " .
              "WHERE year = " . $this->year . " " .
              "ORDER BY days; ";
            $q_offer = mysqli_query($this->db, $s_offer);
            while($r_day = mysqli_fetch_array($q_offer)){
                array_push($this->offer, new Festival_Offer($this->db, $this->lang, $r_offer["id"]));
            }
            // TODO Check
            $s_g_day =
              "SELECT DISTINCT " .
              "  date(start) AS date, " .
              "  day(start) AS day, " .
              "FROM festival_event " .
              "WHERE " .
              "  gm = 0 AND " .
              "  year(start) = " . $this->year . " " .
              "ORDER BY date;";
            $q_g_day = mysqli_query($this->db, $s_g_day);
            $events = [];
            while($r_g_day = mysqli_fetch_array($q_g_day)){
                $events->$r_g_day["day"] = [];
                $s_event =
                  "SELECT id " .
                  "FROM festival_event " .
                  "WHERE " .
                  "  gm = 0 AND " .
                  "  date(date_add(start, INTERVAL -4 HOUR)) = '" . $r_g_day["date"] . "' " .
                  "ORDER BY start;";
                $q_event = mysqli_query($this->db, $s_event);
                while($r_event = mysqli_fetch_array($q_event)){
                    array_push($events->$r_g_day["day"], new Festival_Event($this->db, $this->lang, $r_event["id"]));
                }
            }
            array_push($this->event->public, $events);
            $s_g_day =
              "SELECT DISTINCT " .
              "  date(start) AS date, " .
              "  day(start) AS day, " .
              "FROM festival_event " .
              "WHERE " .
              "  gm = 1 AND " .
              "  year(start) = " . $this->year . " " .
              "ORDER BY date;";
            $q_g_day = mysqli_query($this->db, $s_g_day);
            $events = [];
            while($r_g_day = mysqli_fetch_array($q_g_day)){
                $events->$r_g_day["day"] = [];
                $s_event =
                  "SELECT id " .
                  "FROM festival_event " .
                  "WHERE " .
                  "  gm = 1 AND " .
                  "  date(date_add(start, INTERVAL -4 HOUR)) = '" . $r_g_day["date"] . "' " .
                  "ORDER BY start;";
                $q_event = mysqli_query($this->db, $s_event);
                while($r_event = mysqli_fetch_array($q_event)){
                    array_push($events->$r_g_day["day"], new Festival_Event($this->db, $this->lang, $r_event["id"]));
                }
            }
            array_push($this->event->internal, $events);
        }
    }
?>
