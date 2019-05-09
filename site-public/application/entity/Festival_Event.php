<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Route.php");
    require_once($path["entity"] . "Place.php");
    require_once($path["entity"] . "People.php");

    /**
     * Festival_Event.
     *
     * Represents an object from the table 'festival_event'.
     */
    class Festival_Event extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Indicates if the event is internal or an official event.
         */
        public $internal;

        /**
         * Event name.
         */
        public $title;

        /**
         * Description of the event.
         */
        public $description;

        /**
         * {@see People} hosting or organizing the event.
         */
        public $host;

        /**
         * {@see People} sponsoring the event.
         */
        public $sponsor;

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
         * Interest of the event. Arbitrary.
         */
        public $interest;

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
            parent::__construct($db, null);
            $s =
              "SELECT " .
              "  id, " .
              "  gm AS internal, " .
              "  title_$lang AS title, " .
              "  description_$lang AS description, " .
              "  start, " .
              "  end, " .
              "  place, " .
              "  route " .
              "FROM festival_event " .
              "WHERE id = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->internal = $r["internal"];
                $this->title = $r["title"];
                $this->description = $r["description"];
                $this->start = $r["start"];
                if (!is_null($r["end"])){
                    $this->end = $r["end"];
                }
                $this->place = new Place($this->db, $this->lang, $r["place"]);
                if (!is_null($r["route"])){
                    $this->route = new Route($this->db, $this->lang, $r["route"]);
                }
                if (!is_null($r["host"])){
                    $this->host = new People($this->db, $this->lang, $r["host"]);
                }
                if (!is_null($r["sponsor"])){
                    $this->host = new Sponsor($this->db, $this->lang, $r["sponsor"]);
                }
                $this->interest = $r["interest"];
            }
        }
    }
?>
