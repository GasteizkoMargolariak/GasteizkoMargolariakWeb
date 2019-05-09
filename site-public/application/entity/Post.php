<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Post_Comment.php");
    require_once($path["entity"] . "Post_Image.php");

    /**
     * Post.
     *
     * Represents an object from the table 'post'.
     */
    class Post extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Permalink for linking the entity (relative).
         */
        public $permalink;

        /**
         * Title in the selected language.
         */
        public $title;

        /**
         * The content in the defined language.
         */
        public $text;

        /**
         * Posting datetime.
         */
        public $dtime;

        /**
         * Array with the {@see Post_Image}.
         */
        public $image = [];

        /**
         * Array with the tags.
         */
        public $tag = [];

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
              "  permalink, " .
              "  title_" . $this->lang . " AS title, " .
              "  text_" . $this->lang . " AS text, " .
              "  dtime " .
              "FROM post " .
              "WHERE " .
              "  visible = 1 AND " .
              "  ( " .
              "    id = '$id' OR " .
              "    permalink = '$id' " .
              "  );";
            $q = mysqli_query($this->db, $s);
            if (mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_array($q);
                $this->id = $r["id"];
                $this->permalink = $r["permalink"];
                $this->title = $r["title"];
                $this->text = $r["text"];
                $this->dtime = $r["dtime"];
            }
            $s_image =
              "SELECT id " .
              "FROM post_image " .
              "WHERE post = " . $this->id . " " .
              "ORDER BY idx; ";
            $q_image = mysqli_query($this->db, $s_image);
            while($r_image = mysqli_fetch_array($q_image)){
                array_push($this->image, new Post_Image($this->db, $r_image["id"]));
            }
            $s_tag =
              "SELECT tag " .
              "FROM post_tag " .
              "WHERE post = " . $this->id . ";";
            $q_tag = mysqli_query($this->db, $s_tag);
            while($r_tag = mysqli_fetch_array($q_tag)){
                array_push($this->tag, $r_tag["tag"]);
            }
            $s_comment =
              "SELECT id " .
              "FROM post_comment " .
              "WHERE " .
              "  post = " . $this->id . " AND " .
              "  approved = 1;";
            $q_comment = mysqli_query($this->db, $s_comment);
            while($r_comment = mysqli_fetch_array($q_comment)){
                array_push($this->comment, new Post_Comment($this->db, $r_comment["id"]));
            }
        }
    }
?>
