<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	//Get photo data
	$path = mysqli_real_escape_string($con, $_GET['path']);
	$q = mysqli_query($con, "SELECT id, file, permalink, title_$lang AS title, description_$lang AS description, uploaded, size, user, username FROM photo WHERE file = '$path' AND approved = 1");
	if (mysqli_num_rows($q) == 0){
		exit(0);
	}
	else{
		$r = mysqli_fetch_array($q);
		echo "<div id='viewer_table'><div class='viewer_row'><div class='viewer_cell' id='cell_image'>\n";
		echo "<img id='photo_viewer_image' src='/img/galeria/preview/$r[file]'/>\n";
		echo "</br>\n";
		//Arrows
		echo "<div id='viewer_controls'>\n";
		echo "<img id='viewer_arrow_left' class='pointer' alt=' ' src='http://$http_host/img/misc/slid-left.png' onClick='scrollPhoto(-1);'/>\n";
		echo "<img id='viewer_arrow_right' class='pointer' alt=' ' src='http://$http_host/img/misc/slid-right.png' onClick='scrollPhoto(1);'/>\n";
		echo "<img id='viewer_close' class='pointer mobile' alt=' ' src='http://$http_host/img/misc/close.png' onClick='closeViewer();'/>\n";
		echo "</div>\n"; //viewer_controls
		echo "</div>\n"; //cell
		echo "<div class='viewer_cell' id='cell_details'>\n";
		echo "<div id='photo_viewer_details'>\n";
		echo "<div class='entry' id='photo_data'>\n";
		if (strlen($r['title']) > 0){
			echo "<h4>$r[title]</h4>\n";
		}
		if (strlen($r['description']) > 0){
			echo "<span id='photo_description'>$r[description]</span>\n";
		}
		echo "<span id='photo_date'>" . formatDate($r['uploaded'], $lang) . "</span>\n";
		if (strlen($r['username']) > 0){
			echo "<span id='photo_user'>$lng[gallery_user]$r[username]</span>\n";
		}
		echo "</div>\n";
		$q_comment = mysqli_query($con, "SELECT * FROM photo_comment WHERE photo = $r[id];");
		$comment_count = mysqli_num_rows($q_comment);
		echo "<div class='entry' id='comments_section'/>\n";
		echo "<div id='photo_viewer_comment_counter'>\n";
		switch ($comment_count){
			case 0:
				echo "<h4><meta itemprop='interactionCount' content='0'/>$lng[gallery_comment_count_0]</h4>\n";
				break;
			case 1:
				echo "<h4><span itemprop='interactionCount'>1</span> $lng[gallery_comment_count_1]</h4>\n";
				break;
			default:
				echo "<h4><span itemprop='interactionCount'>$comment_count</span> $lng[gallery_comment_count_several]</h4>\n";
		}
		echo "</div>\n";
		echo "<div id='comment_list'>\n";
		//WARNING: If changes are done here, do the same in comment.php
		while ($r_comment = mysqli_fetch_array($q_comment)){
			echo "<div itemprop='comment' itemscope itemtype='http://schema.org/UserComments' id='comment_$r_comment[id]' class='comment'>\n";
			//When official users are implementes, see if there is user or username
			echo "<span itemprop='creator' class='comment_user'>$r_comment[username]</span>\n";
			echo "<span class='comment_date date'><meta itemprop='commentTime' content='$r_comment[isodate]'/>" . formatDate($r_comment['dtime'], $lang) . "</span>\n";
			echo "<p itemprop='commentText' class='comment_text'>$r_comment[text]</p>";
			echo "<hr class='comment_line'/>\n";
			echo "</div>\n";
		}
		echo "</div>\n";
		#Comment form
		echo "<div class='comment' id='comment_new'>\n";
		echo "<form id='comment_form' method='post' action='/' onsubmit='event.preventDefault();postComment($r[id], \"$lang\");'>\n";
		echo "<textarea id='new_comment_text' name='text' maxlength='1800' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);' placeholder='$lng[blog_placeholder_text]'></textarea>\n";
		//echo "<input type='hidden' name='id' value='$id'>\n";
		echo "<div id='identification_form'>\n";
		echo "<br><input id='new_comment_user' name='user' maxlength='50' type='text' placeholder='$lng[blog_placeholder_name]' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);'/>\n";
		echo "<input type='submit' value='$lng[blog_send]'/>\n";
		echo "</div>\n";
		echo "</form>\n";
		echo "</div>\n"; //New comment
		echo "</div>\n"; //Comments
		echo "</div>\n"; //Cell
		echo "</div>\n"; //Row
		echo "</div>\n"; //Table
		
		stats(-1, 0, "photo", "$r[id]");
	}
?>
