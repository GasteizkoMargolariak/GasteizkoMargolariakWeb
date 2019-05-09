<?php

    require_once($path["page"] . "Page.php");
    require_once($path["entity"] . "Activity.php");


    /**
     * Activity page model.
     */
    class Activity_Page extends Page{

        /**
         * The selected {@see Activity}.
         */
        public $activity;

        /**
         * Constructor.
         *
         * Retrieves the data and initializes the variables.
         *
         * @param db Connection to the database.
         * @param lang Lowercase, two-letter language code.
         * @param id Activity id or permalink.
         */
        public function __construct($db, $lang, $id){
            global $path;
            global $base_url;
            global $static;
            global $data;
            parent:: __construct($db, $lang);
            $this->activity = new Activity($this->db, $this->lang, $id);
            if ($this->activity->is_past()){
                $this->template = $path["template"] . "activity_past.php";
            }
            else{
                $this->template = $path["template"] . "activity_future.php";
            }
            $this->title = $this->activity->title . " - " . $data["name"];
            $this->description = $this->activity->title . " - " . $data["name"];
            if (sizeof($this->activity->image) > 0){
                $this->icon = $static["content"] . "activity/" . $this->activity->image[0]->image;
            }
            else{
                $this->icon = $static["layout"] . "logo/logo.svg";
            }
            $this->canonical = $base_url . "/actividades/" . $this->activity->permalink . "/";
        }
    }
?>
