<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Activity_Image.
     *
     * Represents an object from the table 'activity_image'.
     */
    class Activity_Image extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Identifier of the activity the image belongs to.
         */
        public $activity;

        /**
         * Filename.
         */
        public $image;

        /**
         * Order.
         */
        public $idx;

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
              "  activity, " .
              "  image, " .
              "  idx " .
              "FROM activity_image " .
              "WHERE id = '$id';";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->activity = $r["activity"];
                $this->image = $r["image"];
                $this->idx = $r["idx"];
            }
        }
    }
?>
