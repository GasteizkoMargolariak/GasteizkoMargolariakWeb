<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Sponsor.
     *
     * Represents an object from the table 'sponsor'.
     */
    class Sponsor extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Name of the sponsor.
         */
        public $name;

        /**
         * Short text describing the sponsor.
         */
        public $text;

        /**
         * Sponsor logo.
         */
        public $image;

        /**
         * Sponsor url.
         */
        public $link;

        /**
         * Indicates if the sponsor has a phisical location.
         */
        public $local;

        /**
         * Address, if any.
         */
        public $address;

        /**
         * Geographical location, if any.
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
              "  text_$lang AS text, " .
              "  image, " .
              "  link, " .
              "  local, " .
              "  address_$lang AS address, " .
              "  lat, " .
              "  lon " .
              "FROM sponsor " .
              "WHERE id = '$id';";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->name = $r["name"];
                $this->text = $r["text"];
                $this->image = $r["image"];
                $this->link = $r["link"];
                if ($r["local"] == 1){
                    $this->local = true;
                    $this->address = $r["address"];
                    $this->location["lat"] = $r["lat"];
                    $this->location["lon"] = $r["lon"];
                }
                else{
                    $this->local = false;
                }
            }
        }
    }
?>
