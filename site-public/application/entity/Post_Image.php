<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Post_Image.
     *
     * Represents an object from the table 'post_image'.
     */
    class Post_Image extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Identifier of the post the image belongs to.
         */
        public $post;

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
              "  post, " .
              "  image, " .
              "  idx " .
              "FROM post_image " .
              "WHERE id = '$id';";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->post = $r["post"];
                $this->image = $r["image"];
                $this->idx = $r["idx"];
            }
        }
    }
?>
