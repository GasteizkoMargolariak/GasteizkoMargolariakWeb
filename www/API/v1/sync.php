 <?php
	// Gasteizko Margolariak API v1 //
		
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
	
	//Default action
	define('DEF_ACTION', ACTION_SYNC);
	
	//Output keys
	define('KEY_VERSION', 'version');
	define('KEY_DATA', 'data');
	
	//$_GET valid parameters
	define('GET_CLIENT', 'client');
	define('GET_USER', 'user');
	define('GET_ACTION', 'action');
	define('GET_SECTION', 'section');
	define('GET_VERSION', 'version');
	define('GET_FOREGROUND', 'foreground');
	define('GET_FORMAT', 'json');
	
	//Error messages
	define('ERR_ACTION', '-ACTION:');
	define('ERR_SECTION', '-SECTION:');
	define('ERR_VERSION', '-VERSION:');
	define('ERR_FOREGROUND', '-FOREGROUND:');
	define('ERR_FORMAT', '-FORMAT:');
	
	/****************************************************
	* This function is called from almost everywhere at *
	* the beggining of the page. It initializes the     *
	* session variables, connect to the db, enabling    *
	* the variable $con for futher use everywhere in    *
	* the php code, and populates the arrays $user      *
	* and $permission, with info about the user.        *
	*                                                   *
	* @return: (db connection): The connection handler. *
	****************************************************/
	function startdb(){
		//Include the db configuration file. It's somehow like this
		/*
		<?php
			$host = 'XXXX';
			$db_name = 'XXXX';
			$username_ro = 'XXXX';
			$username_rw = 'XXXX';
			$pass_ro = 'XXXX';
			$pass_rw = 'XXXX';
		?>
		*/
		include('../../.htpasswd');
		
		//Connect to to database
		$con = mysqli_connect($host, $username_rw, $pass_rw, $db_name);
		
		//Set encoding options
		mysqli_set_charset($con, 'utf-8');
		header('Content-Type: text/html; charset=utf8');
		mysqli_query($con, 'SET NAMES utf8;');
		
		//Return the db connection
		return $con;
	}	
	
	/****************************************************
	* Gives the version of one or more of the sections  *
	* of the database, or the global section.           * 
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	*    section: (string): 'blog', 'activities',       *
	*             'gallery', 'lablanca', 'global'. None *
	*             to get them all.                      *
	* @return: (int): The version of the db section.    *
 	****************************************************/
	function get_version($con, $section = SEC_ALL){
		if ($section == SEC_ALL){
		$q = mysqli_query($con, "SELECT SUM(version) AS version FROM version;");
		}
		else{
			$q = mysqli_query($con, "SELECT version FROM version WHERE section = '$section';");
		}
		if (mysqli_num_rows($q) == 0){
			//'Bad request' status code
			http_response_code(400);
			return 0;
		}
		else{
			$r = mysqli_fetch_array($q);
			return($r['version']);
		}
	}
	
	/****************************************************
	* Gives the version of every section of the db.     *
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	* @return: (Assoc. array): The versions of the db.  *
 	****************************************************/
	function get_all_versions($con){
		$v = array();
		$v[] = [SEC_ALL => get_version($con, SEC_ALL)];
		$v[] = [SEC_BLOG => get_version($con, SEC_BLOG)];
		$v[] = [SEC_ACTIVITIES => get_version($con, SEC_ACTIVITIES)];
		$v[] = [SEC_GALLERY => get_version($con, SEC_GALLERY)];
		$v[] = [SEC_LABLANCA => get_version($con, SEC_LABLANCA)];
		return $v;
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
	* @return: (String): data in the desired format.    *
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
				http_response_code(400);
				return;
		}
		
		$v = array();
		if ($section == SEC_ALL){
			$v = get_all_versions($con);
		}
		else{
			$v[] = [ $section => get_version($con, $section) ];
		}
		
		switch ($format){
			case FOR_JSON:
				$db = array();
				foreach($tables as $table){
					$req_table = get_table($con, $table, $format);
					if ($req_table != -1){
						$db[] = [ $table => $req_table];
					}
				}
				$data = array();
				$data[] = [ KEY_VERSION => $v ];
				$data[] = [ KEY_DATA => $db];
				return(json_encode($data));
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
	* @return: (Assoc Array): Data in the table.        *
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
				$q = mysqli_query($con, "SELECT id, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, open FROM album;");
				break;
			case "photo":
				$q = mysqli_query($con, "SELECT photo.id AS id, file, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, uploaded, place, width, height, size, CONCAT(photo.username, user) AS user FROM photo, user WHERE user.id = photo.user AND approved = 1;");
				break;
			case "post":
				$q = mysqli_query($con, "SELECT post.id AS id, permalink, title_es, title_en, title_eu, text_es, text_en, text_eu, username, dtime FROM post, user WHERE user.id = user AND visible = 1;");
				break;
			case "post_comment":
				$q = mysqli_query($con, "SELECT post_comment.id AS id, post, text, dtime, CONCAT(user.username, user) AS user, lang FROM post_comment, user WHERE post_comment.user = user.id AND approved = 1;");
				break;
			case "sponsor":
				$q = mysqli_query($con, "SELECT id, name_es, name_en, name_eu, text_es, text_en, text_eu, image, address_es, address_en, address_eu, link, lat, lon FROM sponsor;");
				
			//Other cases: 
			default:
				//If the table is a public one and has not been listed above, all of its fields are public.
				if (in_array($table, ['activity_image', 'activity_tag', 'festival', 'festival_day', 'festival_event', 'festival_event_image', 'festival_offer', 'place', 'post_image', 'post_tag', 'settings'])){
					$q = mysqli_query($con, "SELECT * FROM $table;");
				}
				//If forbidden table
				else{
					//'Forbidden' status code
					//TODO: I dont want execution stopping here
					http_response_code(403);
					return;
				}
		}
		
		//If no rows, return
		if (mysqli_num_rows($q) == 0){
			return -1;
		}
		
		//Create result array
		$rows = array();
		while($r = mysqli_fetch_assoc($q)) {
			$rows[] = $r;
		}
		return $rows;
	}
	
	
	/****************************************************
	* gets the IP address of the client.                *
	*                                                   *
	* @return: (String): Client IP address.             *
 	****************************************************/
	function get_user_ip(){
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}
		else{
			$ip = $remote;
		}
		return $ip;
	}
	
	/****************************************************
	* Registers the request in the database.            *
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	*    client: (string): The client identifier.       *
	*    user: (string): A unique end user identifier.  *
	*    action: (string): Requested action.            *
	*    section: (string): Requested database section. *
	*    version: (int): Version of the client db.      *
	*    new_version: (int): Returned version.          *
	*    foreground: (int): 1 for fg syncs, 0 for bg.   *
	*    format: (string): Requested format.            *
	*    error: (string): Error message to store.       *
	****************************************************/
	function log_sync($con, $client, $user, $action, $section, $version, $new_version, $foreground, $format, $error){
		$ip = get_user_ip();
		$browser_data = get_browser(null, true);
		$os = $browser_data['platform'];
		$browser = $browser_data['browser'];
		$uagent = $browser_data['browser_name_pattern'];
		mysqli_query($con, "INSERT INTO sync (client, user, action, section, version_from, version_to, fg, format, error, ip, os, uagent) VALUES ('$client', '$user', '$action', '$section', $version, $new_version, $foreground, '$format', '$error', '$ip', '$os', '$uagent');");
		error_log("INSERT INTO sync (client, user, action, section, version_from, version_to, fg, format, error, ip, os, uagent) VALUES ('$client', '$user', '$action', '$section', $version, $new_version, $foreground, '$format', '$error', '$ip', '$os', '$uagent');");
	}
	
	//Connect to the database
	$con = startdb('rw');
	
	//Get data from URL
	$client = mysqli_real_escape_string($con, $_GET[GET_CLIENT]);
	$user = mysqli_real_escape_string($con, $_GET[GET_USER]);
	$action = strtolower(mysqli_real_escape_string($con, $_GET[GET_ACTION]));
	$section = strtolower(mysqli_real_escape_string($con, $_GET[GET_SECTION]));
	$version = (int) mysqli_real_escape_string($con, $_GET[GET_VERSION]);
	$foregroud = (int) mysqli_real_escape_string($con, $_GET[GET_FOREGROUND]);
	$format = strtolower(mysqli_real_escape_string($con, $_GET[GET_FORMAT]));
	
	//Initialize some variables
	$error = '';
	$new_version = -1;
	
	//Validate data
	if (strlen($client) < 1){
		$client = '';
	}
	if (strlen($user) < 1){
		$user = '';
	}
	if (strlen($action) < 1){
		$action = DEF_ACTION;
	}
	if ($action != ACTION_SYNC && $action != ACTION_VERSION){
		//Bad request
		http_response_code(400);
		$error = $error . ERR_ACTION . mysqli_real_escape_string($con, $_GET[GET_ACTION]);
	}
	if (strlen($section) > 0 && $section != SEC_ALL && $section != SEC_BLOG && $section != SEC_ACTIVITIES && $section != SEC_GALLERY && $section != SEC_LABLANCA ){
		//Bad request
		http_response_code(400);
		$error = $error . ERR_SECTION . mysqli_real_escape_string($con, $_GET[GET_SECTION]);
	}
	if (strlen($section) == 0){
		$section = SEC_ALL;
	}
	if (strlen($version) == 0){
		$version = -1;
	}
	if (is_int($version) == false){
		//Bad request
		http_response_code(400);
		$error = $error . ERR_VERSION . mysqli_real_escape_string($con, $_GET[GET_VERSION]);
		$version = -1;
	}
	if (strlen($foregroud) < 1){
		$foreground = 1;
	}
	if ($foreground != 0 && $foregroud != 0){
		//Bad request
		http_response_code(400);
		$error = $error . ERR_FOREGROUND . mysqli_real_escape_string($con, $_GET[GET_BACKGROUND]);
	}
	if (strlen($format) < 1){
		$format = DEF_FORMAT;
	}
	if ($format != FOR_JSON){
		//Bad request
		http_response_code(400);
		$error = $error . ERR_FORMAT . mysqli_real_escape_string($con, $_GET[GET_FORMAT]);
	}
	
	//If there has not been an error, procede
	if (strlen($error) == 0){
		//If the client just needs to know the version number
		if ($action == ACTION_VERSION){
			$new_version = get_version($con, $section);
			echo ($new_version);
		}
		//If the clients wants to actually perform a sync
		else{
			//If the client version is up to date, send a no content status
			$new_version = get_version($con, $section);
			if ($version >= $new_version){
				//No content
				http_response_code(204);
			}
			//If the client needs an update
			else{
				$out = sync($con, $section);
				$out = str_replace('\"', '\u0022s', $out);
				$out = str_replace(':""', ':null', $out);
				$out = "{\"sync\":$out}";
				echo($out);
			}	
		}
	}
	
	//Log the sync in the database
	log_sync($con, $client, $user, $action, $section, $version, $new_version, $foreground, $format, $error);
	
	
?>
