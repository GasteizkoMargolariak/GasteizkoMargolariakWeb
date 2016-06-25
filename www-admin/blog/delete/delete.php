<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb('rw');
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{
		$id = mysqli_real_escape_string($con, $_GET['p']);
		mysqli_query($con, "DELETE FROM post_comment WHERE post = $id;");
		mysqli_query($con, "DELETE FROM post_image WHERE post = $id;");
		mysqli_query($con, "DELETE FROM post WHERE id = $id;");
		version();
	}
