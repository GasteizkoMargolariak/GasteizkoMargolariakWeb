<?php

    require_once($path["entity"] . "Entity.php");
    require_once($path["entity"] . "Activity_Comment.php");
    require_once($path["entity"] . "Activity_Image.php");
    require_once($path["entity"] . "Activity_Itinerary.php");

    /**
     * Activity.
     *
     * Represents an object from the table 'activity'.
     */
    class Activity extends Entity{

        /**
         * Identifier.
         */
        public $id;

        /**
         * Permalink for linking the entity (relative).
         */
        public $permalink;

        /**
         * Date of the activity.
         */
        public $date;

        /**
         * City the activity takes place in.
         */
        public $city;

        /**
         * Title in the selected language.
         */
        public $title;

        /**
         * Description in the defined language.
         */
        public $text;

        /**
         * Description for when it's in the pase in the defined language.
         */
        public $after;

        /**
         * Price of the activity.
         */
        public $price;

        /**
         * Indicates if an inscription is required.
         */
        public $inscription;

        /**
         * People inscribed to the activity. Undefined if {@see $inscription}
         * is false.
         */
        public $people;

        /**
         * Maximum number of people that can inscribe. Undefined if
         * {@see $inscription} is false.
         */
        public $max_people;

        /**
         * Indicates if the activity must be visible.
         */
        public $visible;

        /**
         * Posting datetime.
         */
        public $dtime;

        /**
         * Photo {@see Album} of the activity.
         */
        public $album;

        /**
         * Array with the {@see Activity_Image}.
         */
        public $image = [];

        /**
         * Array with the tags.
         */
        public $tag = [];

        /**
         * Array with the {@see Activity_Comment}.
         */
        public $comment = [];

        /**
         * Array with the {@see Activity_Itinerary} items.
         */
        public $itinerary = [];

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
              "  date, " .
              "  city, " .
              "  title_" . $this->lang . " AS title, " .
              "  text_" . $this->lang . " AS text, " .
              "  after_" . $this->lang . " AS after, " .
              "  price, " .
              "  inscription, " .
              "  people, " .
              "  max_people, " .
              "  visible, " .
              "  dtime, " .
              "  album " .
              "FROM activity " .
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
                $this->date = $r["date"];
                $this->city = $r["city"];
                if (!is_null($r["after"])){
                    $this->after = $r["after"];
                }
                if ($r["inscription"] == 1){
                    $this->inscription = true;
                    $this->people = $r["people"];
                    $this->max_people = $r["max_people"];
                }
                else{
                    $this->inscription = false;
                }
                if ($r["visible"] == 1){
                    $this->visible = true;
                }
                else{
                    $this->visible = false;
                }
                $this->price = $r["price"];
                $this->dtime = $r["dtime"];
                // TODO
                //if (!is_null($r["album"])){
                //    $this->after = New Album($this->db, $this->lang, $r["album"]);
                //}
            }
            $s_image =
              "SELECT id " .
              "FROM activity_image " .
              "WHERE activity = " . $this->id . " " .
              "ORDER BY idx; ";
            $q_image = mysqli_query($this->db, $s_image);
            while($r_image = mysqli_fetch_array($q_image)){
                array_push($this->image, new Activity_Image($this->db, $r_image["id"]));
            }
            $s_tag =
              "SELECT tag " .
              "FROM activity_tag " .
              "WHERE activity = " . $this->id . ";";
            $q_tag = mysqli_query($this->db, $s_tag);
            while($r_tag = mysqli_fetch_array($q_tag)){
                array_push($this->tag, $r_tag["tag"]);
            }
            $s_comment =
              "SELECT id " .
              "FROM activity_comment " .
              "WHERE " .
              "  activity = " . $this->id . " AND " .
              "  approved = 1;";
            $q_comment = mysqli_query($this->db, $s_comment);
            while($r_tag = mysqli_fetch_array($q_comment)){
                array_push($this->comment, new Activity_Comment($this->db, $r_comment["id"]));
            }
            $s_itinerary =
              "SELECT id " .
              "FROM activity_itinerary " .
              "WHERE " .
              "  activity = " . $this->id . ";";
            $q_itinerary = mysqli_query($this->db, $s_itinerary);
            while($r_itinerary = mysqli_fetch_array($q_itinerary)){
                array_push($this->itinerary, new Activity_Itinerary($this->db, $this->lang, $r_itinerary["id"]));
            }
        }

        /**
         * Checks if an activity is in the future.
         *
         * If the activity takes place during the current day, it's still
         * considered in the future.
         *
         * @return true if future, false if past.
         */
        public function is_future(){
            return (time() - 60 * 60 * 24 < $this->date);
        }

        /**
         * Checks if an activity is in the past.
         *
         * If the activity takes place during the current day, it's still
         * considered in the future.
         *
         * @return true if past, false if future.
         */
        public function is_past(){
            return !$this->is_future();
        }
    }
?>
