<?php
	// Gasteizko Margolariak API v1 //
	
	//TODO: Include another JSON structure with database section versions.
	//TODO: Read and parse $_GET params.
	//TODO: Register the call on the database.
	
	//List of available data formatting
	define('FOR_JSON', 1);
	
	//Default info format
	define('DEF_FORMAT', 1);

	//Database section identifiers
	define('SEC_ALL', 0);
	define('SEC_BLOG', 1);
	define('SEC_ACTIVITIES', 2);
	define('SEC_GALLERY', 3);
	define('SEC_LABLANCA', 4);
	
	
	/****************************************************
	* Echoes the version of one or more of the sections *
	* of the database, or the global section.           * 
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	*    section: (string): 'blog', 'activities',       *
	*             'gallery', 'lablanca', 'global'. None *
	*             to get them all.                      *
 	****************************************************/
	function get_version($con, $section = SEC_ALL){
		if ($section == SEC_ALL){
			$q = mysqli_query($con, "SELECT SUM(version) AS version FROM version;");
		}
		else{
			$q = mysqli_query($con, "SELECT version FROM version WHERE section = '$section';");
		}
		if (mysqli_num_rows == 0){
			//'Bad request' status code
			var_dump(http_response_code(400));
			return 0;
		}
		else{
			$r = mysqli_fetch_array($q);
			return($r['version']);
		}
	}
	
	/****************************************************
	* Prepares the info of the database or a portion of *
	* it in the selected format.                        * 
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	*    section: (string): 'blog', 'activities',       *
	*             'gallery', 'lablanca', 'global'. None *
	*             to get them all.                      *
	*    format: (int): 1: json (default).              *
 	****************************************************/
	function sync($con, $section = SEC_ALL, $version = 0, $format = DEF_FORMAT){
		
		//Skip if current version equal or higher than the one in the database.
		if ($version >= get_version($con, $section)){
			//'No content' status code
			var_dump(http_response_code(204));
			return;
		}
		
		switch ($section){
			case SEC_BLOG:
				$tables = [ 'post', 'post_comment', 'post_image', 'post_tag' ];
				break;
			case SEC_ACTIVITIES:
				$tables = [ 'activity', 'activity_comment', 'activity_image', 'activity_tag', 'activity_itinerary', 'location', 'album', 'photo', 'photo_album', 'photo_comment', 'place' ];
				break;
			case SEC_GALLERY:
				$tables = [ 'album', 'photo', 'photo_album', 'photo_comment', 'place' ];
				break;
			case SEC_LABLANCA:
				$tables = [ 'festival', 'festival_day', 'festival_event', 'festival_event_image', 'festival_offer', 'place', 'people' ];
				break;
			case SEC_ALL:
				$tables = [ 'activity', 'activity_comment', 'activity_image', 'activity_tag', 'album', 'photo', 'festival', 'festival_day', 'festival_event', 'festival_event_image', 'festival_offer', 'place', 'post', 'post_comment', 'post_image', 'post_tag', 'settings', 'sponsor' ];
				break;
			default:
				//'Bad request' staus code
				var_dump(http_response_code(400));
				return;
		}
		
		switch ($format){
			case FOR_JSON:
				$db = array();
				foreach($tables as $table){
					$db[] = [ $table => get_table($con, $table, $format) ];
				}
				return(json_encode($db));
				break;
		}
		
	}
	
	/****************************************************
	* Echoes the contents of a table from the database. *
	* Inaccessible or sensitive tables or fields are    *
	* not printed.                                      *
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	*    table (string): The name of the table.         *
 	****************************************************/
	function get_table($con, $table){
		$table = strtolower($table);
		switch ($table){
			case "activity":
				$q = mysqli_query($con, "SELECT id, permalink, date, city, title_es, title_en, title_eu, text_es, text_eu, text_en, after_es, after_en, after_eu, price, inscription, max_people, album FROM activity WHERE visible = 1;");
				break;
			case "activity_comment":
				$q = mysqli_query($con, "SELECT activity_comment.id AS id, activity, text, dtime, CONCAT(user.username, user) AS user, lang FROM activity_comment, user WHERE activity_comment.user = user.id AND approved = 1;");
				break;
			case "album":
				$q = mysqli_query($con, "SELECT id, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, open;");
				break;
			case "photo":
				$q = mysqli_query($con, "SELECT photo.id AS id, file, permalink, text_es, text_en, text_eu, description_es, description_en, description_eu, uploaded, place, width, height, size, CONCAT(username, user) AS user FROM photo, user WHERE user.id = photo.user AND approved = 1;");
				break;
			case "post":
				$q = mysqli_query($con, "SELECT post.id AS id, permalink, title_es, title_en, title_eu, text_es, text_en, text_eu, username, dtime FROM post, user WHERE user.id = user AND visible = 1;");
				break;
			case "post_comment":
				$q = mysqli_query($con, "SELECT post_comment.id AS id, post, text, dtime, CONCAT(user.username, user) AS user, lang FROM post_comment, user WHERE post_comment.user = user.id AND approved = 1;");
				break;
				
			//Other cases: 
			default:
				//If the table is a public one and has not been listed above, all of its fields are public.
				if (in_array($table, ['activity_image', 'activity_tag', 'festival', 'festival_day', 'festival_event', 'festival_event_image', 'festival_offer', 'place', 'post_image', 'post_tag', 'settings', 'sponsor'])){
					$q = mysqli_query($con, "SELECT * FROM $table;");
				}
				//If forbidden table
				else{
					//'Forbidden' status code
					var_dump(http_response_code(403));
					return;
				}
		}
		
		//Create result array
		$rows = array();
		while($r = mysqli_fetch_assoc($q)) {
			$rows[] = $r;
		}
		return $rows;
		
		//Print array
		//return(json_encode($rows));
	}
	
	//TEST: Remove when tested
	//include("../../functions.php");
	//$con = startdb('ro');
	//echo(get_table($con, 'post'));
	//echo(sync($con, SEC_ALL));
?>
