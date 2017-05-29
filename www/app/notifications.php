<?php
	include("../functions.php");
	$con = startdb();
		
	//Get user entry
	// 1=0 Old API is now disabled
	$q = mysqli_query($con, "SELECT id, action, title_es, title_en, title_eu, text_es, text_en, text_eu FROM notification WHERE 1 = 0 AND dtime > NOW() - INTERVAL duration MINUTE ORDER BY dtime DESC ;");
	while ($r = mysqli_fetch_array($q)){
		echo("<notification>\n");
		echo("\t<id>$r[id]</id>\n");
		echo("\t<action>$r[action]</action>\n");
		echo("\t<title_es>$r[title_es]</title_es>\n");
		echo("\t<title_en>$r[title_en]</title_en>\n");
		echo("\t<title_eu>$r[title_eu]</title_eu>\n");
		echo("\t<text_es>$r[text_es]</text_es>\n");
		echo("\t<text_en>$r[text_en]</text_en>\n");
		echo("\t<text_eu>$r[text_eu]</text_eu>\n");
		echo("</notification>\n");
	}
?>
