<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Festival_Offer.
     *
     * Represents an object from the table 'festival_offer'.
     */
    class Festival_Offer extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Year the offer applies on.
         */
        public $year;

        /**
         * Offer name.
         */
        public $name;

        /**
         * Offer description.
         */
        public $description;

        /**
         * Prices of the offer.
         */
        public $price = [
            "public" => null,
            "member" => null
        ];

        /**
         * Number of days included in the offer.
         */
        public $days;

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
              "  year, " .
              "  name_$lang AS name, " .
              "  description_$lang AS description, " .
              "  price, " .
              "  price_public, " .
              "  days " .
              "FROM festival_offer " .
              "WHERE id = $id;";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->year = $r["year"];
                $this->name = $r["name"];
                $this->description = $r["description"];
                $this->price->member = $r["price"];
                $this->price->public = $r["price_public"];
                $this->days = $r["days"];
            }
        }
    }
?>
