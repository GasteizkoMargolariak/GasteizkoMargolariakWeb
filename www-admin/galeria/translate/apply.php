<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb('rw');
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	//Get album fields
	$id = mysqli_real_escape_string($con, $_POST['id']);
	$title_en = mysqli_real_escape_string($con, $_POST['title_en']);
	$desc_en = mysqli_real_escape_string($con, $_POST['description_en']);
	$title_eu = mysqli_real_escape_string($con, $_POST['title_eu']);
	$desc_eu = mysqli_real_escape_string($con, $_POST['description_eu']);
	
	//Get photo fields
	$q_i = mysqli_query($con, "SELECT * FROM photo, photo_album WHERE photo.id = photo_album.photo AND album = $id;");
	$i = 0;
	$total_i = 0;
	while ($r_i = mysqli_fetch_array($q_i)){
		$p_id[$i] = $r_i['id'];
		$p_title_en[$i] = mysqli_real_escape_string($con, $_POST['photo_' . $p_id[$i] . '_title_en']);
		$p_desc_en[$i] = mysqli_real_escape_string($con, $_POST['photo_' . $p_id[$i] . '_description_en']);
		$p_title_eu[$i] = mysqli_real_escape_string($con, $_POST['photo_' . $p_id[$i] . '_title_eu']);
		$p_desc_eu[$i] = mysqli_real_escape_string($con, $_POST['photo_' . $p_id[$i] . '_description_eu']);
		$i ++;
		$total_i ++;
	}
	
	//Get current album data
	$q = mysqli_query($con, "SELECT * FROM album WHERE id = $id");
	if (mysqli_num_rows($q) > 0){
		$r = mysqli_fetch_array($q);
		
		//Update album fields
		if (strlen($title_en) > 0 && $title_en != $r['title_en']){
			mysqli_query($con, "UPDATE album SET title_en = '$title_en' WHERE id = $id;");
		}
		if (strlen($desc_en) > 0 && $desc_en != $r['description_en']){
			mysqli_query($con, "UPDATE album SET description_en = '$desc_en' WHERE id = $id;");
		}
		if (strlen($title_eu) > 0 && $title_eu != $r['title_eu']){
			mysqli_query($con, "UPDATE album SET title_eu = '$title_eu' WHERE id = $id;");
		}
		if (strlen($desc_eu) > 0 && $desc_eu != $r['description_eu']){
			mysqli_query($con, "UPDATE album SET description_eu = '$desc_eu' WHERE id = $id;");
		}
		version();
	}
	
	//Update photo entries
	for ($i = 0; $i < $total_i; $i ++){
		$q_i = mysqli_query($con, "SELECT * FROM photo WHERE id = $p_id[$i];");
		if (mysqli_num_rows($q_i) > 0){
			$r_i = mysqli_fetch_array($q_i);
			if (strlen($p_title_en[$i]) > 0 && $p_title_en[$i] != $r_i['title_en']){
				mysqli_query($con, "UPDATE photo SET title_en = '$p_title_en[$i]' WHERE id = $p_id[$i];");
			}
			if (strlen($p_desc_en[$i]) > 0 && $p_desc_en[$i] != $r_i['description_en']){
				mysqli_query($con, "UPDATE photo SET description_en = '$p_desc_en[$i]' WHERE id = $p_id[$i];");
			}
			if (strlen($p_title_eu[$i]) > 0 && $p_title_eu[$i] != $r_i['title_un']){
				mysqli_query($con, "UPDATE photo SET title_eu = '$p_title_eu[$i]' WHERE id = $p_id[$i];");
			}
			if (strlen($p_desc_eu[$i]) > 0 && $p_desc_eu[$i] != $r_i['description_eu']){
				mysqli_query($con, "UPDATE photo SET description_eu = '$p_desc_eu[$i]' WHERE id = $p_id[$i];");
			}
		}
	}
	header("Location: /galeria/translate/index.php");	
?>
