<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Album.php");

    /**
     * Gallery page model.
     */
    class Gallery_Page extends Page{

        /**
         * List of {@see Album}.
         */
        public $album = [];

        /**
         * Constructor.
         *
         * Retrieves the data and initializes the variables.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         */
        public function __construct($db, $lang){
            global $path;
            global $base_url;
            global $data;
            parent::__construct($db, $lang);
            $this->template = $path["template"] . "album.php";
            $s =
              "SELECT id " .
              "FROM album " .
              "ORDER BY dtime DESC;";
            $q = mysqli_query($this->db, $s);
            while($r = mysqli_fetch_array($q)){
                array_push($this->album, new Album($this->db, $this->lang, $r["id"]));
            }
            $this->title = $this->string["section_gallery"] . " - " . $data["name"];
            $this->description = $this->string["section_galery"] . " - " . $data["name"];
            $this->canonical = $base_url . "/galeria/";
        }
    }
?>
