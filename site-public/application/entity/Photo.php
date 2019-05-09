<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Photo_Comment.php");
    require_once($path["entity"] . "Place.php");

    /**
     * Photo.
     *
     * Represents an object from the table 'photo'.
     */
    class Photo extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Filename.
         */
        public $file;

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
         * Time the photo was taken.
         */
        public $dtime;

        /**
         * Time the photo was taken.
         */
        public $uploaded;

        /**
         * {@see Place} the photo was taken in. It may be unknown.
         */
        public $place;

        /**
         * Indicates if the photo has been approven and can be shown.
         */
        public $approved;

        /**
         * Username of the uploader or sender.
         */
        public $username;

        /**
         * Array with the {@see Post_Comment}.
         */
        public $comment = [];

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
              "  file, " .
              "  permalink, " .
              "  title_" . $this->lang . " AS title, " .
              "  description_" . $this->lang . " AS description, " .
              "  dtime, " .
              "  uploaded, " .
              "  place, " .
              "  approved, " .
              "  username " .
              "FROM photo " .
              "WHERE " .
              "  approved = 1 AND " .
              "  ( " .
              "    id = '$id' OR " .
              "    permalink = '$id' " .
              "  );";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->file = $r["file"];
                $this->permalink = $r["permalink"];
                if (!is_null($r["title"])){
                    $this->title = $r["title"];
                }
                if (!is_null($r["description"])){
                    $this->description = $r["description"];
                }
                if (!is_null($r["dtime"])){
                    $this->dtime = $r["dtime"];
                }
                $this->uploaded = $r["uploaded"];
                if (!is_null($r["place"])){
                    $this->place = New Place($this->db, $this->lang, $r["place"]);
                }
                $this->approved = $r["approved"];
                if (!is_null($r["username"])){
                    $this->username = $r["username"];
                }
            }
            $s_comment =
              "SELECT id " .
              "FROM photo_comment " .
              "WHERE " .
              "  photo = " . $this->id . " AND " .
              "  approved = 1;";
            $q_comment = mysqli_query($this->db, $s_comment);
            while($r_comment = mysqli_fetch_array($q_comment)){
                array_push($this->comment, new Photo_Comment($this->db, $r_comment["id"]));
            }
        }
    }
?>
