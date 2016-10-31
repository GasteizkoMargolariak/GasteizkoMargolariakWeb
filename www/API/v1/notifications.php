<?php
	// Gasteizko Margolariak API v1 //
		
	//List of available data formatting
	define('FOR_JSON', 'json');
	
	//Default info format
	define('DEF_FORMAT', FOR_JSON);
	
	//Posible notification target
	define('TARGET_ALL', 'all');
	define('TARGET_GM', 'gm');
	
	//Default target
	define('DEF_TARGET', TARGET_ALL);
		
	//$_GET valid parameters
	define('GET_CLIENT', 'client');
	define('GET_USER', 'user');
	define('GET_TARGET', 'target');
	define('GET_FORMAT', 'json');
	
	//Error messages
	define('ERR_TARGET', '-TARGET:');
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
	
	function show_notifications($con, $target = DEF_TARGET, $format = DEF_FORMAT){
		if ($target == TARGET_GM){
			$query = "SELECT id, title_es, title_en, title_eu, text_es, text_en, text_eu, dtime, internal AS gm, duration, 0 AS seen FROM notification WHERE internal = 1 AND dtime > NOW() - INTERVAL duration MINUTE ORDER BY dtime DESC";
		}
		else{
			$query = "SELECT id, title_es, title_en, title_eu, text_es, text_en, text_eu, dtime, internal AS gm, duration, 0 AS seen FROM notification WHERE dtime > NOW() - INTERVAL duration MINUTE ORDER BY dtime DESC";
		}
		$q = mysqli_query($con, $query);
		switch ($format){
			case FOR_JSON:
				//Create result array
				$rows = array();
				while($r = mysqli_fetch_assoc($q)) {
					$rows[] = $r;
				}
				return(json_encode($rows));
				break;
		}
	}
	
	//Connect to the database
	$con = startdb('rw');
	
	//Get data from URL
	$client = mysqli_real_escape_string($con, $_GET[GET_CLIENT]);
	$user = mysqli_real_escape_string($con, $_GET[GET_USER]);
	$target = strtolower(mysqli_real_escape_string($con, $_GET[GET_TARGET]));
	$format = strtolower(mysqli_real_escape_string($con, $_GET[GET_FORMAT]));
	
	//Initialize some variables
	$error = '';
	
	//Validate data
	if (strlen($client) < 1){
		$client = '';
	}
	if (strlen($user) < 1){
		$user = '';
	}
	if (strlen($target) < 1){
		$target = DEF_TARGET;
	}
	if ($target != TARGET_ALL && $action != TARGET_GM){
		//Bad request
		http_response_code(400);
		$error = $error . ERR_TARGET . mysqli_real_escape_string($con, $_GET[GET_TARGET]);
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
		echo(show_notifications($con, $target, $format));
	}
	
	//Log the request
	//log_sync(); //TODO;
