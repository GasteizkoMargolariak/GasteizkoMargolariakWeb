<?php

    require_once($path["entity"] . "Sponsor.php");

    /**
     * Page superclass.
     *
     * Every visitable page type must inherit from this one.
     */
    abstract class Page{

        /**
         * Database connection.
         */
        public $db;

        /**
         * Template associated with the page.
         */
        public $template;

        /**
         * Language the page will be served on (lowercase, two-letter
         * language code).
         */
        public $lang;

        /**
         * Title of the page, in the defined language.
         */
        public $title;

        /**
         * Site name.
         */
        public $name;

        /**
         * Page description, in the defined language.
         */
        public $description;

        /**
         * Path to the site favicon.
         */
        public $favicon;

        /**
         * Path to the page icon or main image.
         */
        public $icon;

        /**
         * Canonical URL.
         */
        public $canonical;

        /**
         * Author URL.
         */
        public $author;

        /**
         * String array for the layout texts
         */
        public $string;

        /**
         * List of {@see Sponsor}.
         */
        public $sponsor = [];


        /**
         * Constructor.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         */
        public function __construct($db, $lang){
            global $static;
            global $base_url;
            global $path;
            global $data;
            $this->db = $db;
            $this->lang = $lang;
            require_once($path["string"] . "lang_" . $lang . ".php");
            $this->string = $string;
            $this->favicon = $static["layout"] . "logo/logo.svg";
            $this->icon = $static["layout"] . "logo/logo.svg";
            $this->name = $data["name"];
            $this->author = $data["name"];
            $s_sponsor =
              "SELECT id " .
              "FROM sponsor " .
              "WHERE ammount > 0 " .
              "ORDER BY ammount DESC;";
            $q_sponsor = mysqli_query($this->db, $s_sponsor);
            while($r_sponsor = mysqli_fetch_array($q_sponsor)){
                array_push($this->sponsor, new Sponsor($this->db, $this->lang, $r_sponsor["id"]));
            }
        }
    }
?>
