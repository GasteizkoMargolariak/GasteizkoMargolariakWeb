<?php
	
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb('rw');
	
	//Get post values
	$photo = mysqli_real_escape_string($con, $_POST["photo"]);
	$user = mysqli_real_escape_string($con, $_POST["user"]);
	$text = mysqli_real_escape_string($con, $_POST["text"]);
	$lang = strtolower($_POST["lang"]);

	//Check if photo exists and it allows comments
	$q_photo = mysqli_query($con, "SELECT id FROM photo WHERE id = $photo;"); //Not checking if comments are alowed
	if (mysqli_num_rows($q_photo) == 0){
		error_log("Tried to post a comment in a nonexistent photo or a photo that doesnt allow comments. POST ID: '$id'");
		http_response_code(405);
		exit(-1);
	}
	
	//Check language, set fallback.
	if ($lang != 'es' && $lang != 'en' && $lang != 'eu'){
		$lang = 'es';
	}
	
	//Chack for null fields
	if (strlen($user) <= 0 || strlen($text) <= 0){
		error_log("Tried to post a comment with null text or user on photo. USER: '$user', TEXT: '$text'");
		http_response_code(405);
		exit(-1);
	}
	
	//Format newlines in text
	$text = str_replace(["\r\n", "\r", "\n"], "<br/>", $text);
		
	//Insert row
	mysqli_query($con, "INSERT INTO photo_comment (photo, text, username, lang) VALUES ($photo, '$text', '$user', '$lang');");
	version();
	
	//Prepare the page to update the comment section
	//WARNING: If changes are done here, do the same in album.php
	$q_comment = mysqli_query($con, "SELECT id, photo, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, user, username, lang, text FROM photo_comment WHERE photo = $photo AND approved = 1 ORDER BY dtime;");
	error_log("SELECT id, photo, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, user, username, lang, text FROM photo_comment WHERE photo = $photo AND approved = 1 ORDER BY TIME;");
	while ($r_comment = mysqli_fetch_array($q_comment)){
		echo "<div itemprop='comment' itemscope itemtype='http://schema.org/UserComments' id='comment_$r_comment[id]' class='comment'>\n";
		//When official users are implementes, see if there is user or username
		echo "<span itemprop='creator' class='comment_user'>$r_comment[username]</span>\n";
		echo "<span class='comment_date date'><meta itemprop='commentTime' content='$r_comment[isodate]'/>" . formatDate($r_comment['dtime'], $lang) . "</span>\n";
		echo "<p itemprop='commentText' class='comment_text'>$r_comment[text]</p>";
		echo "<hr class='comment_line'/>\n";
		echo "</div>\n";
	}
?>
