<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Photo.php");

    /**
     * Photo page model.
     */
    class Photo_Page extends Page{

        /**
         * Selected {@see Photo}.
         */
        public $photo;

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
            $this->template = $path["template"] . "photo.php";
            $this->photo = new Photo($this->db, $this->lang, $r["id"]));
        }
    }
?>
