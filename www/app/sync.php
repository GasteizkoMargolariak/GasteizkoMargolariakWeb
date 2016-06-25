<?php
	include('../php_functions.php');
	$con = startdb();
	
	//Get client version
	$get_version = mysqli_real_escape_string($con, $_GET['v']);
	
	//If client version equials server version, theres is nothing to sync
	$q_v = mysqli_query($con, "SELECT value FROM settings WHERE name = 'version';");
	if (mysqli_num_rows($q_v) == 0){
		//Here Im sending the app an OK code, but its not OK.
		error_log("Trying to sync with a client, but there is no db version!");
		echo "<synced>1</synced>\n";
		exit(-1);
	}
	else{
		$r_v = mysqli_fetch_array($q_v);
		if ($r_v['value'] <= $get_version){
			echo "<synced>1</synced>\n";
			exit(-1);
		}
	}
	
	//Header
	echo "<database>\n";
	echo "\t<name>gm</name>\n";
	//Echo version
	echo "\t<version>$r_v[value]</version>\n";
	
	//Rest of settings
	$q = mysqli_query($con, "SELECT value FROM settings WHERE name = 'photos';");
	if (mysqli_num_rows($q) > 0){
		$r = mysqli_fetch_array($q);
		echo "\t<photos>$r[value]</photos>\n";
	}
	$q = mysqli_query($con, "SELECT value FROM settings WHERE name = 'festivals';");
	if (mysqli_num_rows($q) > 0){
		$r = mysqli_fetch_array($q);
		echo "\t<festivals>$r[value]</festivals>\n";
	}
	
	//Table activity
	echo "\t<table>\n";
	echo "\t\t<name>activity</name>\n";
	$q = mysqli_query($con, "SELECT id, permalink, date, city, title_es, title_en, title_eu, text_es, text_en, text_eu, after_es, after_en, after_eu, price, inscription, max_people, album, dtime, comments FROM activity  WHERE visible = 1;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<permalink>'$r[permalink]'</permalink>\n";
		echo "\t\t\t<date>'$r[date]'</date>\n";
		echo "\t\t\t<city>'$r[city]'</city>\n";
		echo "\t\t\t<title_es>'$r[title_es]'</title_es>\n";
		echo "\t\t\t<title_en>'$r[title_en]'</title_en>\n";
		echo "\t\t\t<title_eu>'$r[title_eu]'</title_eu>\n";
		echo "\t\t\t<text_es>'$r[text_es]'</text_es>\n";
		echo "\t\t\t<text_en>'$r[text_en]'</text_en>\n";
		echo "\t\t\t<text_eu>'$r[text_eu]'</text_eu>\n";
		echo "\t\t\t<after_es>'$r[after_es]'</after_es>\n";
		echo "\t\t\t<after_en>'$r[after_en]'</after_en>\n";
		echo "\t\t\t<after_eu>'$r[after_eu]'</after_eu>\n";
		echo "\t\t\t<price>$r[price]</price>\n";
		echo "\t\t\t<inscription>$r[inscription]</inscription>\n";
		echo "\t\t\t<max_people>$r[max_people]</max_people>\n";
		echo "\t\t\t<album>$r[album]</album>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		echo "\t\t\t<comments>$r[comments]</comments>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table activity_comment
	echo "\t<table>\n";
	echo "\t\t<name>activity_comment</name>\n";
	$q = mysqli_query($con, "SELECT activity_comment.id AS id, activity, text, dtime, fname, username, lang FROM activity_comment, user WHERE activity_comment.user = user.id AND approved = 1;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<activity>$r[activity]</activity>\n";
		echo "\t\t\t<text>'$r[text]'</text>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		if (strlen($r['username'] == 0)){
			echo "\t\t\t\<username>'$r[fname]'</username>\n";
		}
		else{
			echo "\t\t\t\<username>'$r[username]'</username>\n";
		}
		echo "\t\t\t<lang>'$r[lang]'</lang>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table activity_image
	echo "\t<table>\n";
	echo "\t\t<name>activity_image</name>\n";
	$q = mysqli_query($con, "SELECT * FROM activity_image;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<activity>$r[activity]</activity>\n";
		echo "\t\t\t<image>'$r[image]'</image>\n";
		echo "\t\t\t<idx>$r[idx]</idx>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table activity_itinerary
	echo "\t<table>\n";
	echo "\t\t<name>activity_itinerary</name>\n";
	$q = mysqli_query($con, "SELECT * FROM activity_itinerary;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<activity>$r[activity]</activity>\n";
		echo "\t\t\t<name_es>'$r[name_es]'</name_es>\n";
		echo "\t\t\t<name_en>'$r[name_en]'</name_en>\n";
		echo "\t\t\t<name_eu>'$r[name_eu]'</name_eu>\n";
		echo "\t\t\t<description_es>'$r[description_es]'</description_es>\n";
		echo "\t\t\t<description_en>'$r[description_en]'</description_en>\n";
		echo "\t\t\t<description_eu>'$r[description_eu]'</description_eu>\n";
		echo "\t\t\t<start>'$r[start]'</start>\n";
		echo "\t\t\t<end>'$r[end]'</end>\n";
		echo "\t\t\t<place>'$r[place]'</place>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table activity_tag
	echo "\t<table>\n";
	echo "\t\t<name>activity_tag</name>\n";
	$q = mysqli_query($con, "SELECT * FROM activity_tag;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<activity>$r[activity]</activity>\n";
		echo "\t\t\t<tag>'$r[tag]'</tag>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table album
	echo "\t<table>\n";
	echo "\t\t<name>album</name>\n";
	$q = mysqli_query($con, "SELECT id, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, open, dtime FROM album;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<permalink>'$r[permalink]'</permalink>\n";
		echo "\t\t\t<name_es>'$r[title_es]'</name_es>\n";
		echo "\t\t\t<name_en>'$r[title_en]'</name_en>\n";
		echo "\t\t\t<name_eu>'$r[title_eu]'</name_eu>\n";
		echo "\t\t\t<description_es>'$r[description_es]'</description_es>\n";
		echo "\t\t\t<description_en>'$r[description_en]'</description_en>\n";
		echo "\t\t\t<description_eu>'$r[description_eu]'</description_eu>\n";
		echo "\t\t\t<open>$r[open]</open>\n";
		echo "\t\t\t<time>'$r[dtime]'</time>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table festival
	echo "\t<table>\n";
	echo "\t\t<name>festival</name>\n";
	$q = mysqli_query($con, "SELECT * FROM festival;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<year>$r[year]</year>\n";
		echo "\t\t\t<text_es>'$r[text_es]'</text_es>\n";
		echo "\t\t\t<text_en>'$r[text_en]'</text_en>\n";
		echo "\t\t\t<text_eu>'$r[text_eu]'</text_eu>\n";
		echo "\t\t\t<img>'$r[img]'</img>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table festival_day
	echo "\t<table>\n";
	echo "\t\t<name>festival_day</name>\n";
	$q = mysqli_query($con, "SELECT * FROM festival_day;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<date>'$r[date] 00:00:00'</date>\n";
		echo "\t\t\t<name_es>'$r[name_es]'</name_es>\n";
		echo "\t\t\t<name_en>'$r[name_en]'</name_en>\n";
		echo "\t\t\t<name_eu>'$r[name_eu]'</name_eu>\n";
		echo "\t\t\t<price>$r[price]</price>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table festival_event
	echo "\t<table>\n";
	echo "\t\t<name>festival_event</name>\n";
	$q = mysqli_query($con, "SELECT * FROM festival_event;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<gm>$r[gm]</gm>\n";
		echo "\t\t\t<title_es>'$r[title_es]'</title_es>\n";
		echo "\t\t\t<title_en>'$r[title_en]'</title_en>\n";
		echo "\t\t\t<title_eu>'$r[title_eu]'</title_eu>\n";
		echo "\t\t\t<description_es>'$r[description_es]'</description_es>\n";
		echo "\t\t\t<description_en>'$r[description_en]'</description_en>\n";
		echo "\t\t\t<description_eu>'$r[description_eu]'</description_eu>\n";
		echo "\t\t\t<host>$r[host]</host>\n";
		echo "\t\t\t<place>$r[place]</place>\n";
		echo "\t\t\t<start>'$r[start]'</start>\n";
		echo "\t\t\t<end>'$r[end]'</end>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table festival_event_image
	echo "\t<table>\n";
	echo "\t\t<name>festival_event_image</name>\n";
	$q = mysqli_query($con, "SELECT * FROM festival_event_image;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<event>$r[event]</event>\n";
		echo "\t\t\t<image>'$r[image]'</image>\n";
		echo "\t\t\t<idx>$r[idx]</idx>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table festival_offer
	echo "\t<table>\n";
	echo "\t\t<name>festival_offer</name>\n";
	$q = mysqli_query($con, "SELECT * FROM festival_offer;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<year>$r[year]</year>\n";
		echo "\t\t\t<name_es>'$r[name_es]'</name_es>\n";
		echo "\t\t\t<name_en>'$r[name_en]'</name_en>\n";
		echo "\t\t\t<name_eu>'$r[name_eu]'</name_eu>\n";
		echo "\t\t\t<description_es>'$r[description_es]'</description_es>\n";
		echo "\t\t\t<description_en>'$r[description_en]'</description_en>\n";
		echo "\t\t\t<description_eu>'$r[description_eu]'</description_eu>\n";
		echo "\t\t\t<days>$r[days]</days>\n";
		echo "\t\t\t<price>$r[price]</price>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table location
	echo "\t<table>\n";
	echo "\t\t<name>location</name>\n";
	$q = mysqli_query($con, "SELECT id, dtime, lat, lon, manual FROM location;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		echo "\t\t\t<lat>$r[lat]</lat>\n";
		echo "\t\t\t<lon>$r[lon]</lon>\n";
		echo "\t\t\t<manual>$r[manual]</manual>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table notification
	echo "\t<table>\n";
	echo "\t\t<name>notification</name>\n";
	$q = mysqli_query($con, "SELECT id, dtime, duration, action, title_es, title_en, title_eu, text_es, text_en, text_eu, internal FROM notification;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		echo "\t\t\t<duration>$r[duration]</duration>\n";
		echo "\t\t\t<action>'$r[action]'</action>\n";
		echo "\t\t\t<title_es>'$r[title_es]'</title_es>\n";
		echo "\t\t\t<title_en>'$r[title_en]'</title_en>\n";
		echo "\t\t\t<title_eu>'$r[title_eu]'</title_eu>\n";
		echo "\t\t\t<text_es>'$r[text_es]'</text_es>\n";
		echo "\t\t\t<text_en>'$r[text_en]'</text_en>\n";
		echo "\t\t\t<text_eu>'$r[text_eu]'</text_eu>\n";
		echo "\t\t\t<internal>$r[internal]</internal>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table people
	echo "\t<table>\n";
	echo "\t\t<name>people</name>\n";
	$q = mysqli_query($con, "SELECT * FROM people;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<name_es>'$r[name_es]'</name_es>\n";
		echo "\t\t\t<name_en>'$r[name_en]'</name_en>\n";
		echo "\t\t\t<name_eu>'$r[name_eu]'</name_eu>\n";
		echo "\t\t\t<link>'$r[link]'</link>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table photo
	echo "\t<table>\n";
	echo "\t\t<name>photo</name>\n";
	$q = mysqli_query($con, "SELECT photo.id AS id, file, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, dtime, uploaded, place, width, height, size, fname FROM photo, user WHERE photo.user = user.id AND approved = 1;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<file>'$r[file]'</file>\n";
		echo "\t\t\t<permalink>$r[city]</permalink>\n";
		echo "\t\t\t<title_es>'$r[title_es]'</title_es>\n";
		echo "\t\t\t<title_en>'$r[title_en]'</title_en>\n";
		echo "\t\t\t<title_eu>'$r[title_eu]'</title_eu>\n";
		echo "\t\t\t<description_es>'$r[description_es]'</description_es>\n";
		echo "\t\t\t<description_en>'$r[description_en]'</description_en>\n";
		echo "\t\t\t<description_eu>'$r[description_eu]'</description_eu>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		echo "\t\t\t<uploaded>'$r[uploaded]'</uploaded>\n";
		echo "\t\t\t<place>$r[place]</place>\n";
		echo "\t\t\t<width>$r[width]</width>\n";
		echo "\t\t\t<height>$r[height]</height>\n";
		echo "\t\t\t<size>$r[size]</size>\n";
		if (strlen($r['username'] == 0)){
			echo "\t\t\t\<username>'$r[fname]'</username>\n";
		}
		else{
			echo "\t\t\t\<username>'$r[username]'</username>\n";
		}
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table photo_album
	echo "\t<table>\n";
	echo "\t\t<name>photo_album</name>\n";
	$q = mysqli_query($con, "SELECT * FROM photo_album;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<photo>$r[photo]</photo>\n";
		echo "\t\t\t<album>$r[album]</album>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table photo_comment
	echo "\t<table>\n";
	echo "\t\t<name>photo_comment</name>\n";
	$q = mysqli_query($con, "SELECT photo_comment.id AS id, photo, text, dtime, fname, username, lang FROM photo_comment, user WHERE photo_comment.user = user.id AND approved = 1;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<photo>$r[photo]</photo>\n";
		echo "\t\t\t<text>'$r[text]'</text>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		if (strlen($r['username'] == 0)){
			echo "\t\t\t\<username>'$r[fname]'</username>\n";
		}
		else{
			echo "\t\t\t\<username>'$r[username]'</username>\n";
		}
		echo "\t\t\t<lang>'$r[lang]'</lang>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table place
	echo "\t<table>\n";
	echo "\t\t<name>place</name>\n";
	$q = mysqli_query($con, "SELECT * FROM place;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<name_es>'$r[name_es]'</name_es>\n";
		echo "\t\t\t<name_en>'$r[name_en]'</name_en>\n";
		echo "\t\t\t<name_eu>'$r[name_eu]'</name_eu>\n";
		echo "\t\t\t<address_es>'$r[address_es]'</address_es>\n";
		echo "\t\t\t<address_en>'$r[address_en]'</address_en>\n";
		echo "\t\t\t<address_eu>'$r[address_eu]'</address_eu>\n";
		echo "\t\t\t<cp>'$r[cp]'</cp>\n";
		echo "\t\t\t<lat>$r[lat]</lat>\n";
		echo "\t\t\t<lon>$r[lon]</lon>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table post
	echo "\t<table>\n";
	echo "\t\t<name>post</name>\n";
	$q = mysqli_query($con, "SELECT id, permalink, title_es, title_en, title_eu, text_es, text_en, text_eu, dtime, comments FROM post  WHERE visible = 1;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<permalink>'$r[permalink]'</permalink>\n";
		echo "\t\t\t<title_es>'$r[title_es]'</title_es>\n";
		echo "\t\t\t<title_en>'$r[title_en]'</title_en>\n";
		echo "\t\t\t<title_eu>'$r[title_eu]'</title_eu>\n";
		echo "\t\t\t<text_es>'$r[text_es]'</text_es>\n";
		echo "\t\t\t<text_en>'$r[text_en]'</text_en>\n";
		echo "\t\t\t<text_eu>'$r[text_eu]'</text_eu>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		echo "\t\t\t<comments>$r[comments]</comments>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table post_comment
	echo "\t<table>\n";
	echo "\t\t<name>post_comment</name>\n";
	//$q = mysqli_query($con, "SELECT post_comment.id AS id, post, text, dtime, fname, post_comment.username AS username, lang FROM post_comment, user WHERE post_comment.user = user.id AND approved = 1;");
	$q = mysqli_query($con, "SELECT id, post, text, dtime, username, lang FROM post_comment WHERE approved = 1;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<post>$r[post]</post>\n";
		echo "\t\t\t<text>'$r[text]'</text>\n";
		echo "\t\t\t<dtime>'$r[dtime]'</dtime>\n";
		//if (strlen($r['username'] == 0)){
		//	echo "\t\t\t<username>'$r[fname]'</username>\n";
		//}
		//else{
			echo "\t\t\t<username>'$r[username]'</username>\n";
		//}
		echo "\t\t\t<lang>'$r[lang]'</lang>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table post_image
	echo "\t<table>\n";
	echo "\t\t<name>post_image</name>\n";
	$q = mysqli_query($con, "SELECT * FROM post_image;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<id>$r[id]</id>\n";
		echo "\t\t\t<post>$r[post]</post>\n";
		echo "\t\t\t<image>'$r[image]'</image>\n";
		echo "\t\t\t<idx>$r[idx]</idx>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	//Table post_tag
	echo "\t<table>\n";
	echo "\t\t<name>post_tag</name>\n";
	$q = mysqli_query($con, "SELECT * FROM post_tag;");
	while ($r = mysqli_fetch_array($q)){
		echo "\t\t<row>\n";
		echo "\t\t\t<post>$r[post]</post>\n";
		echo "\t\t\t<tag>'$r[tag]'</tag>\n";
		echo "\t\t</row>\n";
	}
	echo "\t</table>\n";
	
	echo "</database>\n";
?>
