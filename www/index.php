<?php
	session_start();
	$http_host = $_SERVER['HTTP_HOST'];
	include("functions.php");
	$con = startdb();
	
	//Language
	$lang = selectLanguage();
	include("lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_home'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $lng['index_title'];?></title>
		<link rel="shortcut icon" href="<?php echo "http://$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("css/ui.css"); 
				include("css/index.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("css/m/ui.css"); 
				include("css/m/index.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php include("script/ui.js"); ?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="author" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "http://$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['index_description'];?>"/>
		<meta property="og:title" content="<?php echo $lng['index_title'];?>"/>
		<meta property="og:url" content="<?php echo "http://$http_host"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['index_description'];?>"/>
		<meta property="og:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['index_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $lng['index_title'];?>"/>
		<meta name="twitter:description" content="<?php echo $lng['index_description'];?>"/>
		<meta name="twitter:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"http://$http_host"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("header.php"); ?>
		<div id="content">
			<?php
				//Festivals section
				$year = date("Y");
				$q_settings = mysqli_query($con, "SELECT value FROM settings WHERE name = 'festivals';");
				$r_settings = mysqli_fetch_array($q_settings);
				if ($r_settings['value'] == 1){
					echo "<div class='section' id='festivales'>\n";
					echo "<h3>$lng[index_festivals_header] $year</h3>\n";
					echo "<table class='festival_section_table'><tr>\n";
					
					//Summary
					$q_festivals = mysqli_query($con, "SELECT text_$lang AS text, summary_$lang AS summary, img FROM festival WHERE year = $year;");
					if(mysqli_num_rows($q_festivals)){
						echo "<td><div class='entry' id='festivals_summary'>\n";
						$r_festivals = mysqli_fetch_array($q_festivals);
						if ($r_festivals['img'] != ''){
							echo "<img id='festivals_image' alt=' ' src='http://$http_host/img/fiestas/$r_festivals[img]'/>\n";
						}
						if ($r_festivals['summary'] != ''){
							echo "<span id='festivals_summary_text'><br/>$r_festivals[summary]</span>\n";
							echo "<br/><br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://$http_host/lablanca/'>$lng[index_festivals_link]</a>\n";
						}
						else{
							echo "<span id='festivals_summary_text'><br/>" . cutText($r_festivals['text'], 300, "$lng[index_read_more]", "http://$http_host/lablanca/") . "</span>\n";
							echo "<br/><br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://$http_host/lablanca/'>$lng[index_festivals_link]</a>\n";
						}
						echo "</div></td>\n";
					}
					
					
					//Location
					$q_location = mysqli_query($con, "SELECT lat, lon FROM location WHERE action = 'report' AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
					if (mysqli_num_rows($q_location) > 0){
						$r_location = mysqli_fetch_array($q_location);
						echo "<td><div class='entry' id='festival_entry_map'>\n";
						echo "<h3>$lng[index_festivals_location]</h3>\n";
						echo "<iframe src='https://www.google.com/maps/embed/v1/place?key=AIzaSyCZHP7t2on_G3eyyoCTfhGAlDx1mJnX7iI&q=$r_location[lat],$r_location[lon]' allowfullscreen></iframe>\n";
						echo "</div>\n";
					}
					
					echo "</tr></table>\n";
					echo "<table class='festival_section_table'><tr>\n";
					
					//GM schedule
					$q_sch_curr = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > NOW() - INTERVAL 45 MINUTE AND start < NOW() + INTERVAL 30 MINUTE ORDER BY start LIMIT 1;");
					$q_sch_next = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > NOW() AND start < NOW() + INTERVAL 240 MINUTE ORDER BY start LIMIT 1;");
					if (mysqli_num_rows($q_sch_curr) > 0 || mysqli_num_rows($q_sch_next) > 0){
						echo "<td><div class='entry festival_schedule'>\n";
						echo "<h3>$lng[index_festivals_gm_schedule]</h3>\n";
					
						if (mysqli_num_rows($q_sch_curr) > 0){
							$r_sch_curr = mysqli_fetch_array($q_sch_curr);
							echo "<div id='festival_event'><h4>$lng[index_festivals_schedule_now]</h4>\n";
							echo "<span class='title'>$r_sch_curr[title]\n</span>";
							if (strlen($r_sch_curr["description"]) > 0 && $r_sch_curr["description"] != $r_sch_curr["title"]){
								echo "<br/><span class='description'>$r_sch_curr[description]</span>\n";
							}
							echo "<table class='location'><tr>\n";
							echo "<td><a target='_blank' href='http://maps.google.com/maps?q=$r_sch_curr[lat],$r_sch_curr[lon]+(My+Point)&z=14&ll=$r_sch_curr[lat],$r_sch_curr[lon]'><img alt=' ' src='http://$http_host/img/misc/pinpoint.png'/></a></td>\n"; 
							//If name and address are the same, show only name
							if ($r_sch_curr['place'] == $r_sch_curr['address']){
								echo "<td>$r_sch_curr[place]</td></tr></table>\n";
							}
							else{
								echo "<td>$r_sch_curr[place] <span class='address'>- $r_sch_curr[address]</span></td></tr></table>\n";
							}
							echo "</div\n>";
						}
						
						if (mysqli_num_rows($q_sch_next) > 0){
							$r_sch_next = mysqli_fetch_array($q_sch_next);
							echo "<div id='festival_event'><h4>$lng[index_festivals_schedule_next]</h4>\n";
							echo "<span class='title'>$r_sch_next[title] - $r_sch_next[st]\n</span>";
							if (strlen($r_sch_next["description"]) > 0 && $r_sch_next["description"] != $r_sch_next["title"]){
								echo "<br/><span class='description'>$r_sch_next[description]</span>\n";
							}
							echo "<table class='location'><tr>\n";
							echo "<td><a target='_blank' href='http://maps.google.com/maps?q=$r_sch_next[lat],$r_sch_next[lon]+(My+Point)&z=14&ll=$r_sch_next[lat],$r_sch_next[lon]'><img alt=' ' src='http://$http_host/img/misc/pinpoint.png'/></a></td>\n"; 
							//If name and address are the same, show only name
							if ($r_sch_next['place'] == $r_sch_next['address']){
								echo "<td>$r_sch_next[place]</td></tr></table>\n";
							}
							else{
								echo "<td>$r_sch_next[place] <span class='address'>- $r_sch_next[address]</span></td></tr></table>\n";
							}
							echo "</div\n>";
						}
						echo "</div></td>\n"; //close gm schedule entry;
					}
										
					//city schedule
					$q_sch_curr = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 0 AND start > NOW() - INTERVAL 45 MINUTE AND start < NOW() + INTERVAL 30 MINUTE ORDER BY start LIMIT 1;");
					$q_sch_next = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 0 AND start > NOW() AND start < NOW() + INTERVAL 240 MINUTE ORDER BY start LIMIT 1;");
					//echo "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > NOW() - INTERVAL 30 MINUTE AND start < NOW() + INTERVAL 120000 MINUTE ORDER BY start LIMIT 2;";
					if (mysqli_num_rows($q_sch_curr) > 0 || mysqli_num_rows($q_sch_next) > 0){
						echo "<td><div class='entry festival_schedule'>\n";
						echo "<h3>$lng[index_festivals_city_schedule]</h3>\n";
					
						if (mysqli_num_rows($q_sch_curr) > 0){
							$r_sch_curr = mysqli_fetch_array($q_sch_curr);
							echo "<div id='festival_event'><h4>$lng[index_festivals_schedule_now]</h4>\n";
							echo "<span class='title'>$r_sch_curr[title]\n</span>";
							if (strlen($r_sch_curr["description"]) > 0 && $r_sch_curr["description"] != $r_sch_curr["title"]){
								echo "<br/><span class='description'>$r_sch_curr[description]</span>\n";
							}
							echo "<table class='location'><tr>\n";
							echo "<td><a target='_blank' href='http://maps.google.com/maps?q=$r_sch_curr[lat],$r_sch_curr[lon]+(My+Point)&z=14&ll=$r_sch_curr[lat],$r_sch_curr[lon]'><img alt=' ' src='http://$http_host/img/misc/pinpoint.png'/></a></td>\n"; 
							//If name and address are the same, show only name
							if ($r_sch_curr['place'] == $r_sch_curr['address']){
								echo "<td>$r_sch_curr[place]</td></tr></table>\n";
							}
							else{
								echo "<td>$r_sch_curr[place] <span class='address'>- $r_sch_curr[address]</span></td></tr></table>\n";
							}
							echo "</div\n>";
						}
						
						if (mysqli_num_rows($q_sch_next) > 0){
							$r_sch_next = mysqli_fetch_array($q_sch_next);
							echo "<div id='festival_event'><h4>$lng[index_festivals_schedule_next]</h4>\n";
							echo "<span class='title'>$r_sch_next[title] - ($r_sch_next[st])\n</span>";
							if (strlen($r_sch_next["description"]) > 0 && $r_sch_next["description"] != $r_sch_next["title"]){
								echo "<br/><span class='description'>$r_sch_next[description]</span>\n";
							}
							echo "<table class='location'><tr>\n";
							echo "<td><a target='_blank' href='http://maps.google.com/maps?q=$r_sch_next[lat],$r_sch_next[lon]+(My+Point)&z=14&ll=$r_sch_next[lat],$r_sch_next[lon]'><img alt=' ' src='http://$http_host/img/misc/pinpoint.png'/></a></td>\n"; 
							//If name and address are the same, show only name
							if ($r_sch_next['place'] == $r_sch_next['address']){
								echo "<td>$r_sch_next[place]</td></tr></table>\n";
							}
							else{
								echo "<td>$r_sch_next[place] <span class='address'>- $r_sch_next[address]</span></td></tr></table>\n";
							}
							echo "</div\n>";
						}
						echo "</div></td>\n"; //close city schedule entry;
					}
					echo "</tr></table>\n";
					echo "</div>\n";
				}
			
				//Upcaming activity section
				$q_activity = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, text_$lang AS text, price, inscription, people, max_people, city FROM activity WHERE visible = 1 AND date > now() ORDER BY date LIMIT 1;");
				$upcoming_activity_shown = false;
				if (mysqli_num_rows($q_activity) > 0){
					$upcoming_activity_shown = true;
					$r_activity = mysqli_fetch_array($q_activity);
					echo "<div class='section'>\n";
					echo "<h3 class='section_title'>$lng[index_upcoming_activity]</h3>\n";
					echo "<div class='entry' itemscope itemtype='http://schema.org/Event'>\n";
					echo "<meta itemprop='inLanguage' content='$lang'/>\n";
					echo "<meta itemprop='name' content='$r_activity[title]'/>\n";
					echo "<meta itemprop='description' content='$r_activity[text]'/>\n";
					echo "<meta itemprop='startDate endDate' content='$r_activity[isodate]'/>\n";
					echo "<meta itemprop='location' content='$r_activity[city]'/>\n";
					echo "<div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>\n";
					echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
					echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
					echo "<meta itemprop='logo' content='http://$http_host/img/logo/logo.png'/>\n";
					echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
					echo "<meta itemprop='telephone' content='+34637140371'/>\n";
					echo "<meta itemprop='url' content='http://$http_host'/>\n";
					echo "</div>\n";
					echo "<div id='upcoming_activity' class='table'><div class='tr'>\n";
					
					//If image, show it
					$q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
					if (mysqli_num_rows($q_activity_image) > 0){
						$r_activity_image = mysqli_fetch_array($q_activity_image);
						echo "<div class='td'><div id='upcoming_image'>\n";
						echo "<a href='http://$http_host/actividades/$r_activity[permalink]'>\n";
						echo "<meta itemprop='image' content='http://$http_host/img/actividades/$r_activity_image[image]'/>\n";
						echo "<img src='http://$http_host/img/actividades/miniature/$r_activity_image[image]' alt='$r_activity[title]'/>\n";
						echo "</a>\n";
						echo "</div></div>\n";
					}
					echo "<div class='td'><div id='upcoming_text'>\n";
					echo "<h3><a itemprop='url' href='http://$http_host/actividades/$r_activity[permalink]'>$r_activity[title]</a></h3>\n";
					echo "<p>". cutText($r_activity['text'], 250, "$lng[index_read_more]", "http://$http_host/actividades/$r_activity[permalink]") . "</p>\n";
					echo "</div></div>\n";
					echo "<div class='td'><div id='upcoming_details'>\n";
					echo "<table>\n";
					echo "<tr><td>\n";
					echo "$lng[index_upcoming_activity_date]</td><td>" . formatDate($r_activity['date'], $lang, false) . "\n";
					echo "</td></tr>\n";
					echo "<tr>\n";
					echo "<td>$lng[index_upcoming_activity_price]</td>";
					if ($r_activity['price'] == 0){
						echo "<td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>$lng[index_upcoming_activity_free]\n";
						echo "<meta itemprop='priceCurrency' content='EUR'/><meta itemprop='price' content='0'/></td>\n";
						echo "</tr><tr>\n";
						echo "<td>$lng[index_upcoming_activity_inscription]</td>";
						if ($r_activity['inscription'] == 1)
							echo "<td>$lng[yes]</td>\n";
						else
							echo "<td>$lng[no]</td>\n";
					}
					else{
						echo "<td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>$r_activity[price] €\n";
						echo "<meta itemprop='priceCurrency' content='EUR'/><meta itemprop='price' content='$r_activity[price]'/></td>\n";
					}
					echo "</tr>\n";
					if ($r_activity['max_people'] > 0){
						echo "<tr><td>$lng[index_upcoming_activity_max_people]</td><td>$r_activity[max_people]</td></tr>\n";
					}
					echo "</table>\n";
					echo "<a href='http://$http_host/actividades/$r_activity[permalink]'>$lng[index_upcoming_activity_see]</a><br/>\n";
					echo "<a href='http://$http_host/actividades/'>$lng[index_upcoming_activity_see_all]</a>\n";
					echo "</div></div></div></div>\n";
					echo "</div>\n";//Entry
					echo "</div>\n";//Section
				}
			?>
			<div id='content_table'>
				<div class='content_row'>
					<div class='content_cell' id='cell_posts'>
						<div class='section' id='latest_posts'>
							<?php
								echo "<h3 class='section_title'>$lng[index_latest_posts]</h3>\n";
								$q_post = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, dtime, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate FROM post WHERE visible = 1 ORDER BY dtime DESC LIMIT 2;");
								if (mysqli_num_rows($q_post) == 0){
									echo "<div class='entry'>$lng[index_no_post]</div>\n";
								}
								else{
									while ($r_post = mysqli_fetch_array($q_post)){
										echo "<div class='entry post' itemscope itemtype='http://schema.org/BlogPosting'>\n";
										echo "<meta itemprop='inLanguage' content='$lang'/>\n";
										echo "<meta itemprop='datePublished dateModified' content='$r_post[isodate]'/>\n";
										echo "<meta itemprop='headline name' content='$r_post[title]'/>\n";
										echo "<meta itemprop='articleBody text' content='$r_post[text]'/>\n";
										echo "<meta itemprop='mainEntityOfPage' content='http://$http_host'/>\n";
										echo "<div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>\n";
										echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
										echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
										echo "<meta itemprop='logo' content='http://$http_host/img/logo/logo.png'/>\n";
										echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
										echo "<meta itemprop='telephone' content='+34637140371'/>\n";
										echo "<meta itemprop='url' content='http://$http_host'/>\n";
										echo "</div>\n";
										$q_post_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r_post[id] ORDER BY idx LIMIT 1;");
										if (mysqli_num_rows($q_post_image) > 0){
											$r_post_image = mysqli_fetch_array($q_post_image);
											echo "<meta itemprop='image' content='http://$http_host/img/blog/$r_post_image[image]'/>\n";
											echo "<a href='http://$http_host/blog/$r_post[permalink]'><img src='http://$http_host/img/blog/miniature/$r_post_image[image]'/></a>\n";
										}
										echo "<h3><a itemprop='url' href='http://$http_host/blog/$r_post[permalink]'>$r_post[title]</a></h3>\n";
										echo "<p>". cutText($r_post['text'], 100, "$lng[index_read_more]", "http://$http_host/blog/$r_post[permalink]") . "</p>\n";
										echo "<span>" . formatDate($r_post['dtime'], $lang, false) . "</span>\n";
										echo "</div>\n";
									}
								echo "<a class='go_to_section' href='http://$http_host/blog/'>$lng[index_see_all_posts]</a><br/>\n";
								}
							?>
						</div>
					</div>
					<div class='content_cell' id='cell_photos'>
						<div class='section' id='latest_photos'>
							<?php
								echo "<h3 class='section_title'>$lng[index_latest_photos]</h3>\n";
								$q_photos = mysqli_query($con, "SELECT id, file, title_$lang AS title, DATE_FORMAT(uploaded, '%Y-%m-%d') AS isodate FROM photo ORDER BY uploaded DESC LIMIT 6;");
								//TODO WHERE approved = 1
								if (mysqli_num_rows($q_photos) == 0){
									echo "<div class='entry'>$lng[index_no_photos]</div>\n";
								}
								else{
									echo "<div class='entry'>\n";
									echo "<table id='table_photos'>\n";
									echo "<tr>\n";
									$i = 0;
									while ($r_photos = mysqli_fetch_array($q_photos)){
										$q_album = mysqli_query($con, "select permalink from album, photo_album WHERE photo = $r_photos[id] AND id = album LIMIT 1;");
										$r_album = mysqli_fetch_array($q_album);
										echo "<td itemscope itemtype='http://schema.org/Photograph'>\n";
										echo "<meta itemprop='datePublished' content='$r_photos[isodate]'/>\n";
										echo "<a href='http://$http_host/galeria/$r_album[permalink]'>\n";
										echo "<meta itemprop='image' content='http://$http_host/img/galeria/$r_photos[file]'/>\n";
										echo "<img src='http://$http_host/img/galeria/miniature/$r_photos[file]' alt='$r_photos[title]' /></a></td>\n";
										$i ++;
										if ($i % 2 == 0)
										echo "</tr><tr>";
									}
									echo "</tr>\n";
									echo "</table>\n";
									echo "</div>\n";
									echo "<a class='go_to_section' href='http://$http_host/galeria/'>$lng[index_see_all_photos]</a><br/>\n";
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
				if ($upcoming_activity_shown == false){
					$q_activity = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, text_$lang AS text, after_$lang AS after, price, inscription, people, max_people, city FROM activity WHERE visible = 1 ORDER BY date DESC LIMIT 2;");
					if (mysqli_num_rows($q_activity) > 0){
						echo "<div class='section' id='latest_activities'>\n";
						echo "<h3 class='section_title'>$lng[index_latest_activities]</h3>\n";
						while ($r_activity = mysqli_fetch_array($q_activity)){
							echo "<div class='entry' itemscope itemtype='http://schema.org/Event'>\n";
							echo "<meta itemprop='inLanguage' content='$lang'/>\n";
							echo "<meta itemprop='name' content='$r_activity[title]'/>\n";
							echo "<meta itemprop='description' content='$r_activity[text]'/>\n";
							echo "<meta itemprop='startDate endDate' content='$r_activity[isodate]'/>\n";
							echo "<meta itemprop='location' content='$r_activity[city]'/>\n";
							echo "<div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>\n";
							echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='logo' content='http://$http_host/img/logo/logo.png'/>\n";
							echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
							echo "<meta itemprop='telephone' content='+34637140371'/>\n";
							echo "<meta itemprop='url' content='http://$http_host'/>\n";
							echo "</div>\n";
							echo "<h3><a itemprop='url' href='http://$http_host/actividades/$r_activity[permalink]'>$r_activity[title]</a></h3>\n";
							echo "<table class='latest_activity'><tr>\n";
							$q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
							if (mysqli_num_rows($q_activity_image) > 0){
								$r_activity_image = mysqli_fetch_array($q_activity_image);
								echo "<td class='latest_activity_image'><a href='http://$http_host/actividades/$r_activity[permalink]'>\n";
								echo "<meta itemprop='image' content='http://$http_host/img/actividades/$r_activity_image[image]'/>\n";
								echo "<img src='http://$http_host/img/actividades/miniature/$r_activity_image[image]' alt='$r_activity[title]'/>\n";
								echo "</a></td>\n";
							}
							//echo "<div class='activity_text'>\n";
							//echo "<p>". cutText($r_activity['text'], 300, "$lng[index_read_more]", "http://$http_host/actividades/$r_activity[permalink]") . "</p>\n";
							if ($r_activity['after'] == ''){
								echo "<td class='latest_activity_text'>" . cutText($r_activity['text'], 300, "$lng[index_read_more]", "http://$http_host/actividades/$r_activity[permalink]") . "</td>\n";
							}
							else{
								echo "<td class='latest_activity_text'>" . cutText($r_activity['after'], 300, "$lng[index_read_more]", "http://$http_host/actividades/$r_activity[permalink]") . "</td>\n";
							}
							echo "</tr></table>\n";
							//echo "</div>\n";
							//echo "</div>\n";
							echo "</div>\n";
						}
						echo "<a class='go_to_section' href='http://$http_host/actividades/'>$lng[index_upcoming_activity_see_all]</a><br/>\n";
						echo "</div>";
						
					}
				}
			?>
		</div>
		<?php
			include("footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "index", "");
		?>
	</body>
</html>
