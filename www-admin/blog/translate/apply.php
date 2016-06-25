<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	//Get activity fields
	$id = mysqli_real_escape_string($con, $_POST['id']);
	$title_en = mysqli_real_escape_string($con, $_POST['title_en']);
	$text_en = mysqli_real_escape_string($con, $_POST['text_en']);
	$title_eu = mysqli_real_escape_string($con, $_POST['title_eu']);
	$text_eu = mysqli_real_escape_string($con, $_POST['text_eu']);
	
	//Get current activity data
	$q = mysqli_query($con, "SELECT * FROM post WHERE id = $id");
	if (mysqli_num_rows($q) > 0){
		$r = mysqli_fetch_array($q);
		
		//Update activity fields
		if (strlen($title_en) > 0 && $title_en != $r['title_en']){
			mysqli_query($con, "UPDATE post SET title_en = '$title_en' WHERE id = $id;");
		}
		if (strlen($text_en) > 0 && $text_en != $r['text_en']){
			mysqli_query($con, "UPDATE post SET text_en = '$text_en' WHERE id = $id;");
		}
		if (strlen($title_eu) > 0 && $title_eu != $r['title_eu']){
			mysqli_query($con, "UPDATE post SET title_eu = '$title_eu' WHERE id = $id;");
		}
		if (strlen($text_eu) > 0 && $text_eu != $r['text_eu']){
			mysqli_query($con, "UPDATE post SET text_eu = '$text_eu' WHERE id = $id;");
		}
		version();
	}
	
	header("Location: /blog/translate/index.php");	
?>
