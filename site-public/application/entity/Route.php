<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Route_Point.php");

    /**
     * Route.
     *
     * Represents an object from the table 'route'.
     */
    class Route extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Name of the route. Keyword with no actual value.
         */
        public $name;

        /**
         * Duration of the route.
         */
        public $mins;

        /**
         * Default zoom level. Arbitrary. 14 is OK for urban routes.
         */
        public $zoom;

        /**
         * Aproximate center of the route. Used to center map.
         */
        public $center = [
            "lat" => null,
            "lon" => null
        ];

        /**
         * Constructor.
         *
         * Searches the database and retrieves the information about the
         * entity, populating it and it's items.
         *
         * @param db Connection to the database.
         * @param id Identifier.
         */
        public function __construct($db, $id){
            parent::__construct($db, null);
            $s =
              "SELECT " .
              "  id, " .
              "  name, " .
              "  mins, " .
              "  c_lat, " .
              "  c_lon, " .
              "  zoom " .
              "FROM route " .
              "WHERE id = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->name = $r["name"];
                $this->mins = $r["mins"];
                $this->zoom = $r["zoom"];
                $this->center["lat"] = $r["c_lat"];
                $this->center["lon"] = $r["c_lon"];
            }
        }
    }
?>
