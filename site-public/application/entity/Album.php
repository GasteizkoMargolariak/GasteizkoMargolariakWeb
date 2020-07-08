<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Photo.php");

    /**
     * Album.
     *
     * Represents an object from the table 'album'.
     */
    class Album extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Permalink for linking the entity (relative).
         */
        public $permalink;

        /**
         * Title in the selected language. It may be not set.
         */
        public $title;

        /**
         * The content in the defined language. It may be not set.
         */
        public $description;

        /**
         * Array with every {@see Photo} in the album.
         */
        public $photo = [];

        /**
         * Constructor.
         *
         * Searches the database and retrieves the information about the
         * entity, populating it and it's items.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         * @param id Identifier or permalink.
         */
        public function __construct($db, $lang, $id){
            parent::__construct($db, $lang);
            $s =
              "SELECT " .
              "  id, " .
              "  permalink, " .
              "  title_" . $this->lang . " AS title, " .
              "  description_" . $this->lang . " AS description " .
              "FROM album " .
              "WHERE " .
              "  id = '$id' OR " .
              "  permalink = '$id';";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->permalink = $r["permalink"];
                if (!is_null($r["title"])){
                    $this->title = $r["title"];
                }
                if (!is_null($r["description"])){
                    $this->description = $r["description"];
                }
            }
            $s_photo =
              "SELECT photo " .
              "FROM photo_album " .
              "WHERE " .
              "  album = " . $this->id . ";";
            $q_photo = mysqli_query($this->db, $s_photo);
            while($r_photo = mysqli_fetch_array($q_photo)){
                array_push($this->photo, new Photo($this->db, $this->lang, $r_photo["photo"]));
            }
        }
    }
?>
