<!-- DELETE ME -->
<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{
		
		//Get POST data
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$title_eu = mysqli_real_escape_string($con, $_POST['title_eu']);
		$title_en = mysqli_real_escape_string($con, $_POST['title_en']);
		$text_eu = mysqli_real_escape_string($con, $_POST['text_eu']);
		$text_en = mysqli_real_escape_string($con, $_POST['text_en']);
		
		//Check if the post exists
		$q = mysqli_query($con, "SELECT id, title_en, title_eu, text_en, text_eu FROM post");
		if (mysqli_num_rows($q) == 0){
			header("Location: /blog/translate/");
			exit(-1);
		}
		else{
			if ($title_en != "" && $title_en != $r['title_en'])
				mysqli_query($con, "UPDATE post SET title_en = '$title_en' WHERE id = $id;");
				//echo "UPDATE post SET title_en = '$title_en' WHERE id = $id;\n";
			if ($title_eu != "" && $title_eu != $r['title_eu'])
				mysqli_query($con, "UPDATE post SET title_eu = '$title_eu' WHERE id = $id;");
				//echo "UPDATE post SET title_eu = '$title_eu' WHERE id = $id;\n";
			if ($text_en != "" && $text_en != $r['text_en'])
				mysqli_query($con, "UPDATE post SET text_en = '$text_en' WHERE id = $id;");
				//echo "UPDATE post SET text_en = '$text_en' WHERE id = $id;\n";
			if ($text_eu != "" && $text_eu != $r['text_eu'])
				mysqli_query($con, "UPDATE post SET text_eu = '$text_eu' WHERE id = $id;");
				//echo "UPDATE post SET text_eu = '$text_eu' WHERE id = $id;\n";
		}
		
	}
?>
