<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Route_Point.
     *
     * Represents an object from the table 'route_point'.
     */
    class Route_Point extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Reference of the route it belongs to.
         */
        public $route;

        /**
         * Numberic order of this on the route .
         */
        public $part;

        /**
         * Minutes it takes to cmplete this.
         */
        public $mins;

        /**
         * Indicates if the segment must be visible.
         */
        public $visible;

        /**
         * Starting point.
         */
        public $start = [
            "lat" => null,
            "lon" => null
        ];

        /**
         * End point.
         */
        public $end = [
            "lat" => null,
            "lon" => null
        ];

        /**
         * Sorted array of {@see Route_Point}.
         */
        public $point = [];

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
              "  route, " .
              "  part, " .
              "  lat_o, " .
              "  lon_o, " .
              "  lat_d, " .
              "  lon_d, " .
              "  mins, " .
              "  visible " .
              "FROM route_point " .
              "WHERE id = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->route = $r["route"];
                $this->part = $r["part"];
                $this->mins = $r["mins"];
                if ($r["visible"] == 1){
                   $this->visible = true;
                }
                else{
                    $this->visible = false;
                }
                $this->start->lat = $r["lat_o"];
                $this->start->lon = $r["lon_o"];
                $this->end->lat = $r["lat_d"];
                $this->end->lon = $r["lon_d"];
                $s_point =
                  "SELECT id " .
                  "FROM route_point " .
                  "WHERE " .
                  "  route = " . $this->id . " AND " .
                  "  visible = 1 " .
                  "ORDER BY part;";
                $q_point = mysqli_query($this->db, $s_point);
                while($r_point = mysqli_fetch_array($q_point)){
                    array_push($this->point, new Route_Point($this->db, $r_point["id"]));
                }
            }
        }
    }
?>
