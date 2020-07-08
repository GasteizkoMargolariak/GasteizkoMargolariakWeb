<?php

    require_once($path["page"] . "Page.php");


    /**
     * Us page model.
     */
    class Us_Page extends Page{

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
            parent:: __construct($db, $lang);
            $this->template = $path["template"] . "us.php";
            $this->title = $this->string["section_us"] . " - " . $data["name"];
            $this->description = $this->string["section_us"] . " - " . $data["name"];
            $this->canonical = $base_url . "/" . $url->us . "/"";
        }
    }
?>
