<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Album.php");

    /**
     * Album page model.
     */
    class Album_Page extends Page{

        /**
         * Selected {@see Album}.
         */
        public $album;

        /**
         * Constructor.
         *
         * Retrieves the data and initializes the variables.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         */
        public function __construct($db, $lang, $id){
            global $path;
            global $base_url;
            global $data;
            parent::__construct($db, $lang);
            $this->template = $path["template"] . "album.php";
            $this->album, new Album($this->db, $this->lang, $id));
            $this->title = $this->album->title . " - " . $data["name"];
            $this->description = $this->album->title . " - " . $data["name"];
            $this->canonical = $base_url . "/galeria/" . $this->album->permalink;
        }
    }
?>
