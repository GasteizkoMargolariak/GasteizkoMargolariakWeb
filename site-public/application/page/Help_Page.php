<?php

    require_once($path["page"] . "Page.php");


    /**
     * Help page model.
     */
    class Help_Page extends Page{

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
            global $static;
            global $data;
            parent:: __construct($db, $lang);
            $this->template = $path["template"] . "help.php";
            $this->title = $this->string["section_help"] . " - " . $data["name"];
            $this->description = $this->string["section_help"] . " - " . $data["name"];
            $this->canonical = $base_url . "/" . $url->help . "/";
        }
    }
?>
