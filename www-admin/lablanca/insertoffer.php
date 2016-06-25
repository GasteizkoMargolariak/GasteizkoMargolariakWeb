<?php
	$http_host = $_SERVER['HTTP_HOST'];
	$default_host = substr($http_host, 0, strpos($http_host, ':'));
	include("../functions.php");
	$con = startdb('rw');
	if (!checkSession($con)){
		exit (-1);
	}
	else{
		$year = mysqli_real_escape_string($con, $_POST['year']);
		$name_es = mysqli_real_escape_string($con, urldecode($_POST['name_es']));
		$name_en = mysqli_real_escape_string($con, urldecode($_POST['name_en']));
		$name_eu = mysqli_real_escape_string($con, urldecode($_POST['name_eu']));
		$text_es = mysqli_real_escape_string($con, urldecode($_POST['text_es']));
		$text_en = mysqli_real_escape_string($con, urldecode($_POST['text_en']));
		$text_eu = mysqli_real_escape_string($con, urldecode($_POST['text_eu']));
		$price = intval(mysqli_real_escape_string($con, $_POST['price']));
		$days = intval(mysqli_real_escape_string($con, $_POST['days']));
		
		//Verify parameters
		if (strlen($name_es) <= 0){
			error_log('GMADMIN: Not inserting an offer with an empty spanish name.');
			exit(-1);
		}
		if(strlen($text_es) <= 0){
			error_log('GMADMIN: Not inserting an offer with an empty spanish text.');
			exit(-1);
		}
		if ($price < 0){
			error_log("GMADMIN: Not inserting offer '$name_es' with price $price.");
			exit(-1);
		}
		if ($days < 1){
			error_log("GMADMIN: Not inserting offer '$name_es' for $days day(s).");
			exit(-1);
		}
		if ($year < 2000){
			error_log("GMADMIN: Not inserting offer '$name_es' for year $year.");
			exit(-1);
		}
		
		//Check tanslations
		if (strlen($name_en) == 0 || $name_en = $name_es){
			$name_en = $name_es;
		}
		if (strlen($name_eu) == 0 || $name_eu = $name_es){
			$name_eu = $name_es;
		}
		if (strlen($text_en) == 0 || $text_en = $text_es){
			$text_en = $text_es;
		}
		if (strlen($text_eu) == 0 || $text_eu = $text_es){
			$text_eu = $text_es;
		}
		
		//Insert into database
		mysqli_query($con, "INSERT INTO festival_offer (year, name_es, name_en, name_eu, description_es, description_en, description_eu, days, price) VALUES ($year, '$name_es', '$name_en', '$name_eu', '$text_es', '$text_en', '$text_eu', $days, $price);");
	}
?>
