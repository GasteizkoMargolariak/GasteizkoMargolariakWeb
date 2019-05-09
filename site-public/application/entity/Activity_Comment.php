<?php

    require_once($path["entity"] . "Entity.php");

    /**
     * Activity_Comment.
     *
     * Represents an object from the table 'activity_comment'.
     */
    class Activity_Comment extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Identifier of the activity the comment belongs to.
         */
        public $activity;

        /**
         * The content in the defined language.
         */
        public $text;

        /**
         * Posting datetime.
         */
        public $dtime;

        /**
         * Uername.
         */
        public $username;

        /**
         * Language of the comment (unreliable).
         */
        public $lang;

        /**
         * Approved indicator.
         */
        public $approved;

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
              "  activity, " .
              "  text, " .
              "  dtime, " .
              "  username, " .
              "  lang, " .
              "  approved " .
              "FROM activity_comment " .
              "WHERE id = '$id';";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->activity = $r["activity"];
                $this->text = $r["text"];
                $this->dtime = $r["dtime"];
                $this->username = $r["username"];
                $this->lang = $r["lang"];
                $this->approved = $r["approved"];
            }
        }
    }
?>
