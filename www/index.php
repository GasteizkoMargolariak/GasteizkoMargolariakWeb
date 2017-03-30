<?php
	session_start();
	#$proto = $_SERVER['SERVER_PROTOCOL'];
	#$proto = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
	$http_host = $_SERVER['HTTP_HOST'];
	include("functions.php");
	$proto = getProtocol();
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
		<link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
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
		<link rel="canonical" href="<?php echo "$proto$http_host"; ?>"/>
		<link rel="author" href="<?php echo "$proto$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "$proto$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['index_us_content'];?>"/>
		<meta property="og:title" content="<?php echo $lng['index_title'];?>"/>
		<meta property="og:url" content="<?php echo "$proto$http_host"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['index_us_content'];?>"/>
		<meta property="og:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['index_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $lng['index_title'];?>"/>
		<meta name="twitter:description" content="<?php echo $lng['index_us_content'];?>"/>
		<meta name="twitter:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"$proto$http_host"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
<?php include("header.php"); ?>
		<div id="content">

<?php		
				//Location section
				$q_location = mysqli_query($con, "SELECT lat, lon FROM location WHERE action = 'report' AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
				if (mysqli_num_rows($q_location) > 0){
					$r_location = mysqli_fetch_array($q_location);
?>
					<div class='section' id='location'>
					<h3 class='section_title'><?=$lng[index_festivals_location]?></h3>
						<div class='entry' id='map'>
							<iframe src='https://www.google.com/maps/embed/v1/place?key=AIzaSyCZHP7t2on_G3eyyoCTfhGAlDx1mJnX7iI&q=<?=$r_location[lat]?>,<?=$r_location[lon]?>' allowfullscreen></iframe>
						</div>
					</div>
<?php
				}

				//Festivals section
				$year = date("Y");
				$q_settings = mysqli_query($con, "SELECT value FROM settings WHERE name = 'festivals';");
				$r_settings = mysqli_fetch_array($q_settings);
				$festivals = $r_settings['value'];
				if ($festivals == 1){
?>
					<div class='section' id='festivals' itemscope itemtype='http://schema.org/Event'>
						<meta itemprop='inLanguage' content='<?=$lang?>'/>
						<meta itemprop='name' content='<?=$lng['index_festivals_header']?> <?=$year?>'/>
						<meta itemprop='description' content='<?=$lng['index_festivals_header']?> <?=$year?>'/>
						<meta itemprop='startDate' content='<?=$year?>-08-04'/>
						<meta itemprop='endDate' content='<?=$year?>-08-09'/>
						<meta itemprop='url' content='<?=$proto?><?=$http_host?>/lablanca/'/>
						<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'><meta itemprop='address' itemprop='name' content='Vitoria-Gasteiz'/></span>
						<h3 class='section_title'><?=$lng['index_festivals_header']?> <?=$year?></h3>
						<div class='entry' id='festivals_summary'>
<?php
					
							//Summary
							$q_festivals = mysqli_query($con, "SELECT text_$lang AS text, summary_$lang AS summary, img FROM festival WHERE year = $year;");
							if(mysqli_num_rows($q_festivals)){
								$r_festivals = mysqli_fetch_array($q_festivals);
								if ($r_festivals['img'] != ''){
									echo("<meta itemprop='image' content='$proto$http_host/img/fiestas/$r_festivals[img]'/>\n");
									echo("<img id='festivals_image' alt=' ' src='$proto$http_host/img/fiestas/$r_festivals[img]'/>\n");
								}
								if ($r_festivals['summary'] != ''){
									echo "<span class='entry_title' id='festivals_summary_text'><br/>$r_festivals[summary]</span>\n";
									echo "<br/><br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='$proto$http_host/lablanca/'>$lng[index_festivals_link]</a>\n";
								}
								else{
									echo "<span id='festivals_summary_text'><br/>" . cutText($r_festivals['text'], 300, "$lng[index_read_more]", "$proto$http_host/lablanca/") . "</span>\n";
								}
							}
?>
						</div> <!--festivals_summary - entry-->
						<div id='festivals_schedule_table'>
							<div id='festivals_schedule_row'>
								<div class='festivals_schedule_cell'>
<?php
									//GM schedule
									$q_sch_curr = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > NOW() - INTERVAL 45 MINUTE AND start < NOW() + INTERVAL 30 MINUTE ORDER BY start LIMIT 1;");
									$q_sch_next = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > NOW() AND start < NOW() + INTERVAL 240 MINUTE ORDER BY start LIMIT 1;");

									if (mysqli_num_rows($q_sch_curr) > 0 || mysqli_num_rows($q_sch_next) > 0){
										echo("<div class='entry festival_schedule' id='festivals_gm'>\n");
										echo("<h3 class='entry_title'>$lng[index_festivals_gm_schedule]</h3>\n");
										
										if (mysqli_num_rows($q_sch_curr) > 0){
											$r_sch_curr = mysqli_fetch_array($q_sch_curr);
?>
											<div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
												<h4><?=$lng[index_festivals_schedule_now]?></h4>
												<meta itemprop='inLanguage' content='<?=$lang?>'/>
												<meta itemprop='name' content='<?=$r_sch_curr['title']?>'/>
												<meta itemprop='startDate' content='<?=$r_sch_curr['isostart']?>'/>
												<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
													<meta itemprop='address' content='Vitoria-Gasteiz'/>
												<span>
<?php
												if (strlen($r_sch_curr['isoend']) > 0){
													echo("<meta itemprop='endDate' content='$r_sch_curr[isoend]'/>\n");
												}
?>
												<span class='title'><?=$r_sch_curr['title']?></span>
<?php
												if (strlen($r_sch_curr["description"]) > 0 && $r_sch_curr["description"] != $r_sch_curr["title"]){
													echo("<br/><p class='description'>$r_sch_curr[description]</p>\n");
													echo("<meta itemprop='description' content='$r_sch_curr[description]'/>\n");
												}
?>
												<table class='location'>
													<tr>
														<td>
															<a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>+(My+Point)&z=14&ll=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>'>
																<img alt=' ' src='<?=$proto?><?=$http_host?>/img/misc/pinpoint.png'/>
															</a>
														</td>
														<td>
<?php
															//If name and address are the same, show only name
															if ($r_sch_curr['place'] == $r_sch_curr['address']){
																echo("$r_sch_curr[place]\n");
															}
															else{
																echo("$r_sch_curr[place] <span class='address'>- $r_sch_curr[address]</span>\n");
															}
?>
														</td>
													</tr>
												</table>
											</div> <!-- festival-event -->
<?php
										} // if (mysqli_num_rows($q_sch_curr) > 0)
										if (mysqli_num_rows($q_sch_next) > 0){
											$r_sch_next = mysqli_fetch_array($q_sch_next);
?>
											<div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
												<h4><?=$lng['index_festivals_schedule_next']?></h4>
												<meta itemprop='inLanguage' content='<?=$lang?>'/>
												<meta itemprop='name' content='<?=$r_sch_next['title']?>'/>
												<meta itemprop='startDate' content='<?=$r_sch_next['isostart']?>'/>
												<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
													<meta itemprop='address' itemprop='name'>Vitoria-Gasteiz</meta>
												</span>
												<span class='title'><?=$r_sch_next['title']?> - <?=$r_sch_next['st']?></span>
<?php
												if (strlen($r_sch_curr['isoend']) > 0){
													echo "<meta itemprop='endDate' content='$r_sch_next[isoend]'/>\n";
												}
												if (strlen($r_sch_next["description"]) > 0 && $r_sch_next["description"] != $r_sch_next["title"]){
													echo "<br/><p class='description'>$r_sch_next[description]</p>\n";
													echo "<meta itemprclass='entry'op='description' content='$r_sch_next[description]'/>\n";
												}
?>
												<table class='location'>
													<tr>
														<td>
															<a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>+(My+Point)&z=14&ll=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>'>
																<img alt=' ' src='<?=$proto?><?=$http_host?>/img/misc/pinpoint.png'/>
															</a>
														</td>
														<td>
<?php
															//If name and address are the same, show only name
															if ($r_sch_next['place'] == $r_sch_next['address']){
																echo("$r_sch_next[place]\n");
															}
															else{
																echo("$r_sch_next[place] <span class='address'>- $r_sch_next[address]</span>\n");
															}
?>
														</td>
													</tr>
												</table>
											</div> <!-- festival-event -->
<?php
										}
?>
										</div> <!--festivals_gm-->
<?php
									} // if (mysqli_num_rows($q_sch_curr) > 0 || ysqli_num_rows($q_sch_next) > 0)
?>
								</div> <!-- festivals_schedule_cell -->
								<div class='festivals_schedule_cell'>
<?php
												
									//City schedule
									$q_sch_curr = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 0 AND start > NOW() - INTERVAL 45 MINUTE AND start < NOW() + INTERVAL 30 MINUTE ORDER BY start LIMIT 1;");
									$q_sch_next = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 0 AND start > NOW() AND start < NOW() + INTERVAL 240 MINUTE ORDER BY start LIMIT 1;");
									if (mysqli_num_rows($q_sch_curr) > 0 || mysqli_num_rows($q_sch_next) > 0){
										echo "<div class='entry festival_schedule' id='festivals_city'>\n";
										
										echo "<h3 class='entry_title'>$lng[index_festivals_city_schedule]</h3>\n";
									
										if (mysqli_num_rows($q_sch_curr) > 0){
											$r_sch_curr = mysqli_fetch_array($q_sch_curr);
?>
											<div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
												<h4><?=$lng['index_festivals_schedule_now']?></h4>
												<meta itemprop='inLanguage' content='<?=$lang?>'/>
												<meta itemprop='name' content='<?=$r_sch_curr['title']?>'/>
												<meta itemprop='startDate' content='<?=$r_sch_curr['isostart']?>'/>
												<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
													<meta itemprop='address' itemprop='name'>Vitoria-Gasteiz</meta>
												</span>
												<span class='title'><?=$r_sch_curr['title']?></span>
<?php
												if (strlen($r_sch_curr['isoend']) > 0){
													echo "<meta itemprop='endDate' content='$r_sch_curr[isoend]'/>\n";
												}
												if (strlen($r_sch_curr["description"]) > 0 && $r_sch_curr["description"] != $r_sch_curr["title"]){
													echo "<br/><p class='description'>$r_sch_curr[description]</p>\n";
													echo "<meta itemprop='description' content='$r_sch_curr[description]'/>\n";
												}
?>
												<table class='location'>
													<tr>
														<td>
															<a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>+(My+Point)&z=14&ll=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>'>
																<img alt=' ' src='<?=$proto?><?=$http_host?>/img/misc/pinpoint.png'/>
															</a>
														</td>
														<td>
<?php
															//If name and address are the same, show only name
															if ($r_sch_curr['place'] == $r_sch_curr['address']){
																echo("$r_sch_curr[place]\n");
															}
															else{
																echo("$r_sch_curr[place] <span class='address'>- $r_sch_curr[address]</span>\n");
															}
?>
														</td>
													</tr>
												</table>
											</div> <!-- festival-event -->
<?php
										}

										
										if (mysqli_num_rows($q_sch_next) > 0){
											$r_sch_next = mysqli_fetch_array($q_sch_next);
?>
											<div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
												<h4><?=$lng['index_festivals_schedule_next']?></h4>
												<meta itemprop='inLanguage' content='<?=$lang?>'/>
												<meta itemprop='name' content='<?=$r_sch_next['title']?>'/>
												<meta itemprop='startDate' content='<?=$r_sch_next['isostart']?>'/>
												<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
													<meta itemprop='address' itemprop='name'>Vitoria-Gasteiz</meta>
												</span>
												<span class='title'><?=$r_sch_next['title']?> - (<?=$r_sch_next['st']?>)</span>
<?php
												if (strlen($r_sch_curr['isoend']) > 0){
													echo "<meta itemprop='endDate' content='$r_sch_next[isoend]'/>\n";
												}
												if (strlen($r_sch_next["description"]) > 0 && $r_sch_next["description"] != $r_sch_next["title"]){
													echo "<br/><p class='description'>$r_sch_next[description]</p>\n";
													echo "<meta itemprop='description' content='$r_sch_next[description]'/>\n";
												}
?>
												<table class='location'>
													<tr>
														<td>
															<a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>+(My+Point)&z=14&ll=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>'>
																<img alt=' ' src='<?=$proto?><?=$http_host?>/img/misc/pinpoint.png'/>
															</a>
														</td>
														<td>
<?php
															//If name and address are the same, show only name
															if ($r_sch_next['place'] == $r_sch_next['address']){
																echo("$r_sch_next[place]\n");
															}
															else{
																echo("$r_sch_next[place] <span class='address'>- $r_sch_next[address]</span>\n");
															}
?>
														</td>
													</tr>
												</table>
											</div> <!-- festival-event -->
<?php
										}
?>
										</div> <!--festivals_schedule-->
<?php
									}
?>
								</div> <!-- festivals_schedule_cell -->
							</div> <!-- festivals_schedule_row -->
						</div> <!-- festivals_schedule_table -->
						<a class='go_to_section' href='<?=$proto?><?=$http_host?>/lablanca/'><?=$lng['index_festivals_link']?></a>
						<br/>
						
					</div> <!-- festivals - section-->
<?php
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
					echo("<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'><meta itemprop='address' itemprop='name' content='$r_activity[city]'/></span>\n");
					echo "<meta itemprop='url' content='$proto$http_host/actividades/$r_activity[permalink]'/>\n";
					echo "<div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>\n";
					echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
					echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
					echo "<meta itemprop='logo' content='$proto$http_host/img/logo/logo.png'/>\n";
					echo "<meta itemprop='foundingDate' content='2013-02-03'/>\n";
					echo "<meta itemprop='telephone' content='+34637140371'/>\n";
					echo "</div>\n";
					echo "<div id='upcoming_activity' class='table'><div class='tr'>\n";
					
					//If image, show it
					$q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
					if (mysqli_num_rows($q_activity_image) > 0){
						$r_activity_image = mysqli_fetch_array($q_activity_image);
						echo "<div class='td'><div id='upcoming_image'>\n";
						echo "<a href='$proto$http_host/actividades/$r_activity[permalink]'>\n";
						echo "<meta itemprop='image' content='$proto$http_host/img/actividades/$r_activity_image[image]'/>\n";
						echo "<img src='$proto$http_host/img/actividades/miniature/$r_activity_image[image]' alt='$r_activity[title]'/>\n";
						echo "</a>\n";
						echo "</div></div>\n";
					}
					echo "<div class='td'><div id='upcoming_text'>\n";
					echo "<h3 class='entry_title'><a itemprop='url' href='$proto$http_host/actividades/$r_activity[permalink]'>$r_activity[title]</a></h3>\n";
					echo "<p>". cutText($r_activity['text'], 250, "$lng[index_read_more]", "$http_host/actividades/$r_activity[permalink]") . "</p>\n";
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
						echo "<meta itemprop='priceCurrency' content='EUR'/><meta itemprop='price' content='0'/><meta itemprop='url' content='$proto$http_host/actividades/$r_activity[permalink]'/>\n</td>\n";
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
					echo "<a href='$proto$http_host/actividades/$r_activity[permalink]'>$lng[index_upcoming_activity_see]</a><br/><br/>\n";
					
					echo "</div></div></div></div>\n";
					echo "</div>\n";//Entry
					echo "<a class='go_to_section' href='$proto$http_host/actividades/'>$lng[index_upcoming_activity_see_all]</a><br/>\n";
					echo "</div>\n";//Section
				}
?>
			<div class='section' itemscope itemtype='http://schema.org/Organization'>
				<meta itemprop='url' content='<?php echo("$proto$http_host"); ?>'/>
				<h3 class='section_title' itemprop='name'><?php echo($lng['index_us']); ?></h3>
				<div class='entry' id='us'>
					<p itemprop='description'><?php echo($lng['index_us_content']); ?></p>
					<div><img itemprop='logo' src='<?php echo("$proto$http_host/img/logo/GasteizkoMargolariak.png"); ?>'/></div>
				</div>
				<a class='go_to_section' href='<?php echo($proto.$http_host); ?>/nosotros/'><?php echo($lng['index_us_more']) ?></a>
				<br/>
			</div>
			<div id='content_table'>
				<div class='content_row'>
					<div class='content_cell' id='cell_posts'>
						<div class='section' id='latest_posts'>
		<?php
								echo "<h3 class='section_title'>$lng[index_latest_posts]</h3>\n";
								$q_post = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, dtime, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate FROM post WHERE visible = 1 ORDER BY dtime DESC LIMIT 2;");
								if (mysqli_num_rows($q_post) == 0){
									echo "<div class='entry'><h3 class='entry_title'>$lng[index_no_post]</div>\n";
								}
								else{
									while ($r_post = mysqli_fetch_array($q_post)){
										echo "<div class='entry post' itemscope itemtype='http://schema.org/BlogPosting'>\n";
										echo "<meta itemprop='inLanguage' content='$lang'/>\n";
										echo "<meta itemprop='datePublished dateModified' content='$r_post[isodate]'/>\n";
										echo "<meta itemprop='headline name' content='$r_post[title]'/>\n";
										echo "<meta itemprop='articleBody text' content='$r_post[text]'/>\n";
										echo "<meta itemprop='mainEntityOfPage' content='$proto$http_host'/>\n";
										echo "<div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>\n";
										echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
										echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
										echo "<meta itemprop='logo' content='$proto$http_host/img/logo/logo.png'/>\n";
										echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
										echo "<meta itemprop='telephone' content='+34637140371'/>\n";
										echo "<meta itemprop='url' content='$proto$http_host'/>\n";
										echo "</div>\n";
										$q_post_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r_post[id] ORDER BY idx LIMIT 1;");
										if (mysqli_num_rows($q_post_image) > 0){
											$r_post_image = mysqli_fetch_array($q_post_image);
											echo "<meta itemprop='image' content='$proto$http_host/img/blog/preview/$r_post_image[image]'/>\n";
											echo "<a href='$proto$http_host/blog/$r_post[permalink]'><img src='$proto$http_host/img/blog/miniature/$r_post_image[image]'/></a>\n";
										}
										echo "<h3 class='entry_title'><a itemprop='url' href='$proto$http_host/blog/$r_post[permalink]'>$r_post[title]</a></h3>\n";
										echo "<p>". cutText($r_post['text'], 100, "$lng[index_read_more]", "$proto$http_host/blog/$r_post[permalink]") . "</p>\n";
										echo "<span>" . formatDate($r_post['dtime'], $lang, false) . "</span>\n";
										echo "</div>\n";
									}
								echo "<a class='go_to_section' href='$proto$http_host/blog/'>$lng[index_see_all_posts]</a><br/>\n";
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
										echo "<a href='$proto$http_host/galeria/$r_album[permalink]'>\n";
										echo "<meta itemprop='image' content='http://$http_host/img/galeria/$r_photos[file]'/>\n";
										echo "<img src='$proto$http_host/img/galeria/miniature/$r_photos[file]' alt='$r_photos[title]' /></a></td>\n";
										$i ++;
										if ($i % 2 == 0)
										echo "</tr><tr>";
									}
									echo "</tr>\n";
									echo "</table>\n";
									echo "</div>\n";
									echo "<a class='go_to_section' href='$proto$http_host/galeria/'>$lng[index_see_all_photos]</a><br/>\n";
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
							echo("<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'><meta itemprop='address' itemprop='name' content='$r_activity[city]'/><meta itemprop='name' content='$r_activity[city]'/></span>\n");
							echo "<div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>\n";
							echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='logo' content='$proto$http_host/img/logo/logo.png'/>\n";
							echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
							echo "<meta itemprop='telephone' content='+34637140371'/>\n";
							echo "<meta itemprop='url' content='$proto$http_host'/>\n";
							echo "</div>\n";
							echo "<h3 class='entry_title'><a itemprop='url' href='$proto$http_host/actividades/$r_activity[permalink]'>$r_activity[title]</a></h3>\n";
							echo "<table class='latest_activity'><tr>\n";
							$q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
							if (mysqli_num_rows($q_activity_image) > 0){
								$r_activity_image = mysqli_fetch_array($q_activity_image);
								echo "<td class='latest_activity_image'><a href='$proto$http_host/actividades/$r_activity[permalink]'>\n";
								echo "<meta itemprop='image' content='$proto$http_host/img/actividades/$r_activity_image[image]'/>\n";
								echo "<img src='$proto$http_host/img/actividades/miniature/$r_activity_image[image]' alt='$r_activity[title]'/>\n";
								echo "</a></td>\n";
							}
							//echo "<div class='activity_text'>\n";
							//echo "<p>". cutText($r_activity['text'], 300, "$lng[index_read_more]", "$proto$http_host/actividades/$r_activity[permalink]") . "</p>\n";
							if ($r_activity['after'] == ''){
								echo "<td class='latest_activity_text'>" . cutText($r_activity['text'], 300, "$lng[index_read_more]", "$proto$http_host/actividades/$r_activity[permalink]") . "</td>\n";
							}
							else{
								echo "<td class='latest_activity_text'>" . cutText($r_activity['after'], 300, "$lng[index_read_more]", "$proto$http_host/actividades/$r_activity[permalink]") . "</td>\n";
							}
							echo "</tr></table>\n";
							//echo "</div>\n";
							//echo "</div>\n";
							echo "</div>\n";
						}
						echo "<a class='go_to_section' href='$proto$http_host/actividades/'>$lng[index_upcoming_activity_see_all]</a><br/>\n";
						echo "</div>";
						
					}
				}
				//Festivals section (if no festivals shown on top)
				if ($festivals == 0){
?>
						<div class='section'>
							<h3 class='section_title'><?php echo($lng['index_festivals_header']); ?></h3>
							<div class='entry'>
		<?php echo (cutText($lng['lablanca_no_content'] . '<br/>' . $lng['lablanca_no_content_2'], 300, "$lng[index_read_more]", "$proto$http_host/lablanca/")); ?>
							</div>
						</div>
<?php
				}
?>
		</div>
<?php

			//Footer
			include("footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "index", "");
?>
	</body>
</html>
