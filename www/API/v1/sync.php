 <?php
	// Gasteizko Margolariak API v1 //
	
	//TODO: Register the call on the database.
		
	//List of available data formatting
	define('FOR_JSON', 'json');
	
	//Default info format
	define('DEF_FORMAT', FOR_JSON);

	//Database section identifiers
	define('SEC_ALL', 'all');
	define('SEC_BLOG', 'blog');
	define('SEC_ACTIVITIES', 'activities');
	define('SEC_GALLERY', 'gallery');
	define('SEC_LABLANCA', 'lablanca');
	
	//Posible actions
	define('ACTION_SYNC', 'sync');
	define('ACTION_VERSION', 'version');
	
	//$_GET valid parameters
	define('GET_CLIENT', 'client');
	define('GET_ACTION', 'action');
	define('GET_SECTION', 'section');
	define('GET_VERSION', 'version');
	define('GET_FOREGROUND', 'foreground');
	define('GET_FORMAT', 'json')
	
	
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
	function sync($con, $section = SEC_ALL, $format = DEF_FORMAT){
		
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
	}
	
	//Connect to the database
	$con = startdb('rw');
	
	//Get data from URL
	$client = mysqli_real_escape_string($con, $_GET[GET_CLIENT]);
	$action = strtolower(mysqli_real_escape_string($con, $_GET[GET_ACTION]));
	$section = strtolower(mysqli_real_escape_string($con, $_GET[GET_SECTION]));
	$version = mysqli_real_escape_string($con, $_GET[GET_VERSION]);
	$foregroud = mysqli_real_escape_string($con, $_GET[GET_FOREGROUND]);
	$format = strtolower(mysqli_real_escape_string($con, $_GET[GET_FORMAT]));
	
	//Validate data
	if (strlen($client) < 1){
		$client = '';
	}
	if ($action != ACTION_SYNC && $action != ACTION_VERSION){
		//Bad request
		var_dump(http_response_code(400));
		exit(-1);
	}
	if (strlen($section) > 0 && $section != SEC_ALL && $section != SEC_BLOG && $section != SEC_ACTIVITIES && $section != SEC_GALLERY && $section != SEC_LABLANCA ){
		//Bad request
		var_dump(http_response_code(400));
		exit(-1);
	}
	if (strlen($section) == 0){
		$section = SEC_ALL;
	}
	if (is_int($version) == false){
		//Bad request
		var_dump(http_response_code(400));
		exit(-1);
	}
	if (strlen($foregroud) < 1){
		$foreground = 1;
	}
	if ($foreground != 0 && $foregroud != 0){
		//Bad request
		var_dump(http_response_code(400));
		exit(-1);
	}
	if (strlen($format) < 1){
		$format = DEF_FORMAT;
	}
	if ($format != FOR_JSON){
		//Bad request
		var_dump(http_response_code(400));
		exit(-1);
	}
	
	//If the client just needs to know the version number
	if ($action == ACTION_VERSION){
		echo (get_version($con, $section));
	}
	//If the clients wants to actually perform a sync
	else{
		//If the client version is up to date, send a no content status
		if ($version >= get_version($con, $section)){
			//No content
			var_dump(http_response_code(204));
			exit(0);
		}
		//If the client needs an update
		else{
			echo(sync($con, $section));
		}	
	}
?>
