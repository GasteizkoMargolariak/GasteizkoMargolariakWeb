<?php

    /**
     * Entity superclass.
     *
     * Every other entity must inherit from this one. It represents a database
     * entity.
     */
    abstract class Entity{

        /**
         * Database connection.
         */
        public $db;

        /**
         * The language of the class's text fields (lowercase, two-letter
         * language code).
         */
        public $lang;

        /**
         * Constructor.
         *
         * Sets the database conection and the language.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         */
        public function __construct($db, $lang){
            $this->db = $db;
            $this->lang = $lang;
        }
    }
?>
