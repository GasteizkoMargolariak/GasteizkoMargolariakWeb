<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * People.
     *
     * Represents an object from the table 'people'.
     */
    class People extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Name of the sponsor.
         */
        public $name;

        /**
         * Person url.
         */
        public $link;

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
              "  link " .
              "FROM people " .
              "WHERE id = '$id';";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->name = $r["name"];
                $this->link = $r["link"];
            }
        }
    }
?>
