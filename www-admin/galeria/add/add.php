<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb('rw');
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{
		
		//Get POST data
		$title_es = mysqli_real_escape_string($con, $_POST['title_es']);
		$title_eu = mysqli_real_escape_string($con, $_POST['title_eu']);
		$title_en = mysqli_real_escape_string($con, $_POST['title_en']);
		$text_es = mysqli_real_escape_string($con, $_POST['text_es']);
		$text_eu = mysqli_real_escape_string($con, $_POST['text_eu']);
		$text_en = mysqli_real_escape_string($con, $_POST['text_en']);
		$comments = mysqli_real_escape_string($con, $_POST['comments']);
		$visible = mysqli_real_escape_string($con, $_POST['visible']);
		$public = mysqli_real_escape_string($con, $_POST['public']);
		$populate = mysqli_real_escape_string($con, $_POST['populate']);
		
		//Check spanish title and generate permalink
		if ($title_es == null){
			exit();
		}
		else{
			$permalink = permalink($title_es);
			$i = 2;
			$ok = false;
			$tmppermalink = $permalink;
			while ($ok == false){
				$query = mysqli_query($con, "SELECT id FROM post WHERE permalink = '$tmppermalink';");
				if (mysqli_num_rows($query) > 0){
					$tmppermalink = $permalink . "-" . $i;
					$i ++;
				}
				else{
					$permalink = $tmppermalink;
					$ok = true;
				}
			}
		}
		
		//Check titles with no text
		if (strlen($title_eu) > 0 && strlen($text_eu) < 1)
			exit();
		if (strlen($title_en) > 0 && strlen($text_en) < 1)
			exit();
		
		//Check text with no titles
		if (strlen($text_eu) > 0 && strlen($title_eu) < 1)
			exit();
		if (strlen($text_en) > 0 && strlen($title_en) < 1)
			exit();
		
		//Check booleans and assign default values if not
		if ($comments == 'off')
			$comments = 0;
		else
			$comments = 1;
		if ($visible == 'off')
			$visible = 0;
		else
			$visible = 1;
		if ($public == 'on')
			$public = 1;
		else
			$public = 0;
		if ($populate == 'on')
			$populate = 1;
		else
			$populate = 0;
	
		// If no translations, same text in all languages
		if (strlen($text_eu) == 0){
			$text_eu = $text_es;
			$title_eu = $title_es; 
		}
		
		if (strlen($text_en) == 0){
			$text_en = $text_es;
			$title_en = $title_es; 
		}
		
		//Insert into database and get id
		mysqli_query($con, "INSERT INTO album (permalink, title_es, title_eu, title_en, description_es, description_eu, description_en, open) VALUES ('$permalink', '$title_es', '$title_eu', '$title_en', '$text_es', '$text_eu', '$text_en', $public);");
		$res_album = mysqli_query($con, "SELECT id FROM album WHERE permalink = '$permalink' ORDER BY dtime DESC LIMIT 1;");
		$row_album = mysqli_fetch_array($res_album);
		$album_id = $row_album['id'];
		
		version();
		
		//Redirect
		if ($populate == 1){
			header("Location: /galeria/");
		}
		else{
			header("Location: /galeria/upload.php?album=$album_id");
		}
	}
?>
