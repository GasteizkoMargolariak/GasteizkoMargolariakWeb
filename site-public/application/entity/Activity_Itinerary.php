<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Route.php");
    require_once($path["entity"] . "Place.php");

    /**
     * Activity_Itinerary.
     *
     * Represents an object from the table 'activity_itinerary'.
     */
    class Activity_Itinerary extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Short name of the itinerary item.
         */
        public $name;

        /**
         * Description of the itinerary entry.
         */
        public $description;

        /**
         * Start time of the item.
         */
        public $start;

        /**
         * End time of the item. It may not be defined.
         */
        public $end;

        /**
         * Place of the item.
         */
        public $place;

        /**
         * Route the itinerary will travel during the item. It may be undefined.
         */
        public $route;

        /**
         * Constructor.
         *
         * Searches the database and retrieves the information about the
         * entity, populating it and it's items.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         * @param id Identifier.
         */
        public function __construct($db, $lang, $id){
            parent::__construct($db, $lang);
            $s =
              "SELECT " .
              "  id, " .
              "  name_$lang AS name, " .
              "  description_$lang AS description, " .
              "  start, " .
              "  end, " .
              "  place, " .
              "  route " .
              "FROM activity_itinerary " .
              "WHERE id = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->name = $r["name"];
                $this->description = $r["description"];
                $this->start = $r["start"];
                if (!is_null($r["end"])){
                    $this->end = $r["end"];
                }
                $this->place = new Place($this->db, $this->lang, $r["place"]);
                if (!is_null($r["route"])){
                    $this->route = new Route($this->db, $r["route"]);
                }
            }
        }
    }
?>
