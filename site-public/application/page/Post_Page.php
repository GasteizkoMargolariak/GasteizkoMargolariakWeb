<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Post.php");


    /**
     * Post page model.
     */
    class Post_Page extends Page{

        /**
         * The selected {@see Post}.
         */
        public $post;

        /**
         * Constructor.
         *
         * Retrieves the data and initializes the variables.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         * @param id Post id or permalink.
         */
        public function __construct($db, $lang, $id){
            global $path;
            global $base_url;
            global $static;
            global $data;
            parent:: __construct($db, $lang);
            $this->template = $path["template"] . "post.php";
            $this->post = new Post($this->db, $this->lang, $id);
            $this->title = $this->post->title . " - " . $data["name"];
            $this->description = $this->post->title . " - " . $data["name"];
            if (sizeof($this->post->image) > 0){
                $this->icon = $static["content"] . "blog/" . $this->post->image[0]->image;
            }
            else{
                $this->icon = $static["layout"] . "logo/logo.svg";
            }
            $this->canonical = $base_url . "/blog/" . $this->post->permalink . "/";
        }
    }
?>
