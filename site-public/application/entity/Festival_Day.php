<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Festival_Day.
     *
     * Represents an object from the table 'festival_day'.
     */
    class Festival_Day extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Date of the day.
         */
        public $date;

        /**
         * Day name.
         */
        public $name;

        /**
         * Prices of the day.
         */
        public $price = [
            "public" => null,
            "member" => null
        ];

        /**
         * Number of people inscribed for the day.
         */
        public $people;

        /**
         * Maximum number of people that can inscribe.
         */
        public $max_people;

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
              "  name_$lang AS name, " .
              "  price, " .
              "  price_public, " .
              "  people, " .
              "  max_people " .
              "FROM festival_day " .
              "WHERE id = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->name = $r["name"];
                $this->price->member = $r["price"];
                $this->price->public = $r["price_public"];
                $this->people = $r["people"];
                $this->max_people = $r["max_people"];
            }
        }
    }
?>
