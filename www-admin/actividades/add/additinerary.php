<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	//Get activity id
	$id = mysqli_real_escape_string($con, $_POST['activity']);
	//echo "ID " . $id;
	$q = mysqli_query($con, "SELECT id, DATE_FORMAT(date, '%Y-%m-%d') AS date FROM activity WHERE id = $id;");
	if(mysqli_num_rows($q) == 0){
		header("Location: $http_host/actividades/");
		exit (-1);
	}
	
	//Get date
	$r = mysqli_fetch_array($q);
	$date = $r['date'];
	
	//Initialize data arrays
	$starth = [];
	$startm = [];
	$endh = [];
	$endm = [];
	$place = [];
	$title_es = [];
	$title_en = [];
	$title_eu = [];
	$text_es = [];
	$text_en = [];
	$text_eu = [];
	
	//Loop all rows
	for ($i = 0; $i < 100; $i ++){
	
		//Get post data
		$starth[$i] = mysqli_real_escape_string($con, $_POST["sh_$i"]);
		$startm[$i] = mysqli_real_escape_string($con, $_POST["sm_$i"]);
		$endh[$i] = mysqli_real_escape_string($con, $_POST["eh_$i"]);
		$endm[$i] = mysqli_real_escape_string($con, $_POST["em_$i"]);
		$place[$i] = mysqli_real_escape_string($con, $_POST["place_$i"]);
		$title_es[$i] = mysqli_real_escape_string($con, $_POST["title_es_$i"]);
		$title_en[$i] = mysqli_real_escape_string($con, $_POST["title_en_$i"]);
		$title_eu[$i] = mysqli_real_escape_string($con, $_POST["title_eu_$i"]);
		$text_es[$i] = mysqli_real_escape_string($con, $_POST["text_es_$i"]);
		$text_en[$i] = mysqli_real_escape_string($con, $_POST["text_en_$i"]);
		$text_eu[$i] = mysqli_real_escape_string($con, $_POST["text_eu_$i"]);
		
		//Check data
		$insert = true;
		
		//Preconfigure query strings with known data
		$fields = "INSERT INTO activity_itinerary VALUES (activity, place, start, ";
		$values = " VALUES ($id, $place[i] ";
		if ($starth[$i] == '' || $starth[$i] < 0 || $starth[$i] > 24 || $startm[$i] == '' || $startm[$i] < 0 || $startm[$i] > 60){ //Validate start time
			$insert = false;
		}
		else{
			$values = $values . "STR_TO_DATE('$date " . $starth[$i] . ":" . $startm[$i] . ":00', '%Y-%m-%d %H:%i:%s'), ";
		}
		if ($endh[$i] == '' ||$endh[$i] < 0 || $endh[$i] > 24 || $endm[$i] == '' || $endm[$i] < 0 || $endm[$i] > 60){ //Validate end time
			//$insert = false;
		}
		else{
			$fields = $fields . "end, ";
			$values = $values . "STR_TO_DATE('$date " . $endh[$i] . ":" . $endm[$i] . ":00', '%Y-%m-%d %H:%i:%s'), ";
		}
		if ($title_es[$i] == ''){ //Validate title
			$insert = false;
		}
		else{
			if ($title_en[$i] == ''){
				$title_en[$i] = $title_es[$i];
			}
			if ($title_eu[$i] == ''){
				$title_eu[$i] = $title_es[$i];
			}
			$fields = $fields . "title_es, title_en, title_eu, ";
			$values = $values . "'" . $title_es[$i] . "', '" . $title_en[$i] . "', '" . $title_eu[$i] . "', ";
		}
		if ($text_es[$i] == ''){ //Validate text
			$insert = false;
		}
		else{
			if ($text_en[$i] == ''){
				$text_en[$i] = $text_es[$i];
			}
			if ($text_eu[$i] == ''){
				$text_eu[$i] = $text_es[$i];
			}
			$fields = $fields . "text_es, text_en, text_eu)";
			$values = $values . "'" . $text_es[$i] . "', '" . $text_en[$i] . "', '" . $text_eu[$i] . "');";
		}
		
		//Insert if appropiate
		if ($insert == true){
			$query = $fields . $values;
			mysqli_query($con, $query); //TODO further test
			version();
		}
		
		//Redirect
		header("Location: $http_host/actividades/");
	}
?>
