<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Place.
     *
     * Represents an object from the table 'sponsor'.
     */
    class Place extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Name of the place.
         */
        public $name;

        /**
         * Place address.
         */
        public $address;

        /**
         * Postal code.
         */
        public $cp;

        /**
         * Geographical location.
         */
        public $location = [
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
         * @param lang Lowercase, two-letter language code.
         * @param id Identifier.
         */
        public function __construct($db, $lang, $id){
            parent::__construct($db, $lang);
            $s =
              "SELECT " .
              "  id, " .
              "  name_$lang AS name, " .
              "  address_$lang AS address, " .
              "  cp, " .
              "  lat, " .
              "  lon " .
              "FROM place " .
              "WHERE id = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->name = $r["name"];
                $this->address = $r["address"];
                $this->cp = $r["cp"];
                $this->location["lat"] = $r["lat"];
                $this->location["lon"] = $r["lon"];
            }
        }
    }
?>
