<?php
	include("../functions.php");
	$con = startdb('rw');
	
	//Get fields
	$user = mysqli_real_escape_string($con, $_GET['user']);
	$code = mysqli_real_escape_string($con, $_GET['code']);
	$lat = mysqli_real_escape_string($con, $_GET['lat']);
	$lon = mysqli_real_escape_string($con, $_GET['lon']);
	$action = mysqli_real_escape_string($con, $_GET['action']);
	$manual = mysqli_real_escape_string($con, $_GET['manual']);
	
	//Validate code
	$q = mysqli_query($con, "SELECT id FROM user WHERE id = $user AND md5(concat(password, md5(salt))) = '$code';");
	//echo "SELECT id FROM user WHERE id = $user AND md5(concat(password, md5(salt))) = '$code'";
	if (mysqli_num_rows($q) == 0){
		echo("Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
		error_log("Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
		echo("<status>0</status>\n");
		exit(-1);
	}
	
	//If its an automatic update, and the last singal from the user was to stop, stop it
// 	if ($manual == 1){
// 		$q = mysqli_query($con, "SELECT action FROM location WHERE user = $user AND (action = 'start' OR action = 'stop') ORDER BY dtime DESC LIMIT 1");
// 		if (mysqli_num_rows($q) == 0){
// 			echo("<status>0</status>\n");
// 			exit(0);
// 		}
// 		else{
// 			$r = mysqli_fetch_array($q);
// 			if ($r['action'] == 'stop'){
// 				echo("<status>0</status>\n");
// 				exit(0);
// 			}
// 		}
// 	}
	
	//Validate fields
	if (is_numeric($lat) == false || is_numeric($lon) == false){
		error_log("Reporting location: invalid coordinates (IP $_SERVER[REMOTE_ADDR])");
		echo("<status>0</status>\n");
		exit(-1);
	}
	
	if ($action != "start" && $action != "stop" && $action != "report"){
		error_log("Reporting location: invalid action '$action' (IP $_SERVER[REMOTE_ADDR])");
		echo("<status>0</status>\n");
		exit(-1);
	}
	
	if ($manual != 0 && $manual != 1){
		error_log("Reporting location: invalid value for manual '$manual' (IP $_SERVER[REMOTE_ADDR])");
		echo("<status>0</status>\n");
		exit(-1);
	}
	
	//Insert
	mysqli_query($con, "INSERT INTO location (lat, lon, manual, action, user) VALUES ($lat, $lon, $manual, '$action', $user);");
	echo("<status>1</status>\n");
?>
