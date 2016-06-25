<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../../php_functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	//Get activity fields
	$id = mysqli_real_escape_string($con, $_POST['id']);
	$title_en = mysqli_real_escape_string($con, $_POST['title_en']);
	$text_en = mysqli_real_escape_string($con, $_POST['text_en']);
	$after_en = mysqli_real_escape_string($con, $_POST['after_en']);
	$title_eu = mysqli_real_escape_string($con, $_POST['title_eu']);
	$text_eu = mysqli_real_escape_string($con, $_POST['text_eu']);
	$after_eu = mysqli_real_escape_string($con, $_POST['after_eu']);
	
	//Get activity_itinerary fields
	$q_i = mysqli_query($con, "SELECT * FROM activity_itinerary WHERE activity = $id;");
	$i = 0;
	$total_i = 0;
	while ($r_i = mysqli_fetch_array($q_i)){
		$i_id[$i] = $r_i['id'];
		$i_name_en[$i] = mysqli_real_escape_string($con, $_POST['it_' . $i_id[$i] . '_name_en']);
		$i_desc_en[$i] = mysqli_real_escape_string($con, $_POST['it_' . $i_id[$i] . '_desc_en']);
		$i_name_eu[$i] = mysqli_real_escape_string($con, $_POST['it_' . $i_id[$i] . '_name_eu']);
		$i_desc_eu[$i] = mysqli_real_escape_string($con, $_POST['it_' . $i_id[$i] . '_desc_eu']);
		$i ++;
		$total_i ++;
	}
	
	//Get current activity data
	$q = mysqli_query($con, "SELECT * FROM activity WHERE id = $id");
	if (mysqli_num_rows($q) > 0){
		$r = mysqli_fetch_array($q);
		
		//Update activity fields
		if (strlen($title_en) > 0 && $title_en != $r['title_en']){
			mysqli_query($con, "UPDATE activity SET title_en = '$title_en' WHERE id = $id;");
		}
		if (strlen($text_en) > 0 && $text_en != $r['text_en']){
			mysqli_query($con, "UPDATE activity SET text_en = '$text_en' WHERE id = $id;");
		}
		if (strlen($after_en) > 0 && $after_en != $r['after_en']){
			mysqli_query($con, "UPDATE activity SET after_en = '$after_en' WHERE id = $id;");
		}
		if (strlen($title_eu) > 0 && $title_eu != $r['title_eu']){
			mysqli_query($con, "UPDATE activity SET title_eu = '$title_eu' WHERE id = $id;");
		}
		if (strlen($text_eu) > 0 && $text_eu != $r['text_eu']){
			mysqli_query($con, "UPDATE activity SET text_eu = '$text_eu' WHERE id = $id;");
		}
		if (strlen($after_eu) > 0 && $after_eu != $r['after_eu']){
			mysqli_query($con, "UPDATE activity SET after_eu = '$after_eu' WHERE id = $id;");
		}
	}
	
	//Update itinerary entries
	//echo $total_i;
	for ($i = 0; $i < $total_i; $i ++){
		$q_i = mysqli_query($con, "SELECT * FROM activity_itinerary WHERE activity = $id AND id = $i_id[$i];");
		//echo "SELECT * FROM activity_itinerary WHERE activity = $id AND id = $i_id[$i];";
		if (mysqli_num_rows($q_i) > 0){
			$r_i = mysqli_fetch_array($q_i);
			if (strlen($i_name_en[$i]) > 0 && $i_name_en[$i] != $r_i['name_en']){
				mysqli_query($con, "UPDATE activity_itinerary SET name_en = '$i_name_en[$i]' WHERE id = $i_id[$i];");
			}
			if (strlen($i_desc_en[$i]) > 0 && $i_desc_en[$i] != $r_i['description_en']){
				mysqli_query($con, "UPDATE activity_itinerary SET description_en = '$i_desc_en[$i]' WHERE id = $i_id[$i];");
			}
			if (strlen($i_name_eu[$i]) > 0 && $i_name_eu[$i] != $r_i['name_eu']){
				mysqli_query($con, "UPDATE activity_itinerary SET name_eu = '$i_name_eu[$i]' WHERE id = $i_id[$i];");
			}
			if (strlen($i_desc_eu[$i]) > 0 && $i_desc_eu[$i] != $r_i['description_eu']){
				mysqli_query($con, "UPDATE activity_itinerary SET description_eu = '$i_desc_eu[$i]' WHERE id = $i_id[$i];");
			}
		}
	}
	header("Location: /actividades/translate/index.php");	
?>