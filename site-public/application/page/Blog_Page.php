<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Post.php");

    /**
     * Blog page model.
     */
    class Blog_Page extends Page{

        /**
         * List of {@see Post}.
         */
        public $post = [];

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
            $this->template = $path["template"] . "blog.php";
            $s =
              "SELECT id " .
              "FROM post " .
              "WHERE visible = 1 " .
              "ORDER BY dtime DESC;";
            $q = mysqli_query($this->db, $s);
            while($r = mysqli_fetch_array($q)){
                array_push($this->post, new Post($this->db, $this->lang, $r["id"]));
            }
            $this->title = $this->string["section_blog"] . " - " . $data["name"];
            $this->description = $this->string["section_blog"] . " - " . $data["name"];
            $this->canonical = $base_url . "/blog/";
        }
    }
?>
