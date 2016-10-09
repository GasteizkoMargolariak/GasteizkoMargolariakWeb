<?php
	session_start();
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_activities'];
	
	// Get current activity id. Redirect if inexistent or invisible
	$perm = mysqli_real_escape_string($con, $_GET['perm']);
	$q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, after_$lang AS after, price, inscription, people, max_people, user, date, DATE_FORMAT(date, '%Y-%m-%d %T') AS cdate, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, DATE_FORMAT(date,'%b %d, %Y') as fdate, dtime, comments, city FROM activity WHERE permalink = '$perm' AND visible = 1;");
	if (mysqli_num_rows($q) == 0){
		header("Location: http://$http_host/actividades/");
		exit(-1);
	}
	$r = mysqli_fetch_array($q);
	$id = $r['id'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $r["title"] . " - Gasteizko Margolariak"; ?></title>
		<link rel="shortcut icon" href="<?php echo "http://$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("../css/ui.css"); 
				include("../css/actividades.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("../css/m/ui.css"); 
				include("../css/m/actividades.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php
				include("../script/ui.js");
				include("../script/actividades.js");
			?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "http://$http_host/actividades/" . $r['permalink']; ?>"/>
		<link rel="author" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "http://$http_host"; ?>"/>
		<meta name="description" content="<?php echo strip_tags($r["text"]);?>"/>
		<meta property="og:title" content="<?php echo $r["title"] . " - Gasteizko Margolariak"; ?>"/>
		<meta property="og:url" content="<?php echo "http://$http_host/actividades/" . $r['permalink']; ?>"/>
		<meta property="og:description" content="<?php echo strip_tags($r["text"]);?>"/>
		<?php
			$q_i = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $id ORDER BY idx;");
			if (mysqli_num_rows($q_i) == 0)
				$img = "http://$http_host/img/logo/cover.png";
			else{
				$r_i = mysqli_fetch_array($q_i);
				$img = "http://$http_host/img/actividades/preview/" . $r_i['image'];
			}
		?>
		<meta property="og:image" content="<?php echo $img;?>"/>
		<meta property="og:site_name" content="Gasteizko Margolariak"/>
		<meta property="og:type" content="article"/>
		<meta property="og:locale" content="<?php echo $lang;?>"/>
		<meta property="article:section" content=""/>
		<!-- 	TODO: Review time format	 -->
		<meta property="article:published-time" content="<?php echo $r['dtime'];?>"/>
		<meta property="article:modified-time" content="<?php echo $r['dtime'];?>"/>
		<meta property="article:author" content="Gasteizko Margolariak"/>
		<?php
			$res_tag = mysqli_query($con, "SELECT tag FROM activity_tag WHERE activity = $id;");
			$tag_string = "vitoria cuadrilla gasteizko margolariak ";
			while ($row_tag = mysqli_fetch_array($res_tag))
				$tag_string = $tag_string . " " . $row_tag['tag']
		?>
		<meta property="article:tag" content="<?php echo $tag_string ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $r["title"] . " - Gasteizko Margolariak";?>"/>
		<meta name="twitter:description" content="<?php echo strip_tags($r["text"]);?>"/>
		<meta name="twitter:image" content="<?php echo $img;?>"/>
		<meta name="twitter:url" content="<?php echo "http://$http_host/actividades/$r[permalink]"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<?php
				//Check if it's a past activity or upcoming activity.
				$c_date = new Datetime();
				$a_date = date_create_from_format('Y-m-d H:i:s', $r['cdate']);
				$future = false;
				if (date_format($a_date, 'U') > date_format($c_date, 'U')){
					$future = true;	
				}
			?>
			<div id="middle_column">
				<div class="section">
					<h3 class='section_title' id="activity_title"><?php echo($r['title']); ?></h3>
					<span id="activity_date"><?php echo(formatDate($r['date'], $lang, false)); ?></span>
					<div class="entry" itemscope itemtype='http://schema.org/Event'>
						<?php
						
							echo "<meta itemprop='url' href='http://$http_host/actividades/$r[permalink]'/>\n";
							echo "<meta itemprop='inLanguage' content='$lang'/>\n";
							echo "<meta itemprop='name' content='$r[title]'/>\n";
							echo "<meta itemprop='description' content='$r[text]'/>\n";
							echo "<meta itemprop='startDate endDate' content='$r[isodate]'/>\n";
							echo "<meta itemprop='location' content='$r[city]'/>\n";
							echo "<div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>\n";
							echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='logo' content='http://$http_host/img/logo/logo.png'/>\n";
							echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
							echo "<meta itemprop='telephone' content='+34637140371'/>\n";
							echo "<meta itemprop='url' content='http://$http_host'/>\n";
							echo "</div>\n";
						
							if (mysqli_num_rows($q_i) > 0){
								echo "<meta itemprop='image' content='http://$http_host/img/actividades/$r_i[image]'/>\n";
								echo "<div id='activity_image'><img src='http://$http_host/img/actividades/preview/$r_i[image]'/></div>\n";
							}
							echo "<div id='activity_description'><p>\n";
							if ($future == false && strlen($r['after']) > 0){
								echo $r['after'];
							}
							else{
								echo $r['text'];
							}
							echo "</p></div></br>\n";
							if ($future){
								echo "<div itemscope itemtype='http://schema.org/Offer' id='activity_details'><table id='activity_details'>\n";
								echo "<tr><td class='field_name'>$lng[activities_date]</td><td>" . formatDate($r['date'], $lang, false) . "</td></tr>\n";
								echo "<meta itemprop='priceCurrency' content='EUR'/><meta itemprop='price' content='$r[price]'/>\n";
								if ($r['price'] == 0){
									echo "<tr><td class='field_name'>$lng[activities_price]</td><td>$lng[activities_price_0]</td></tr>\n";
								}
								else{
									echo "<tr><td class='field_name'>$lng[activities_price]</td><td>$r[price]€</td></tr>\n";
									if ($r['max_people'] != 0){
										echo "<tr><td class='field_name'>$lng[activities_maxpeople]</td><td>$r[max_people]</td></tr>\n";
									}
									if ($r['inscription'] == 1){
										echo "<tr><td class='field_name'>$lng[activities_inscription]</td><td>$lng[yes]</td></tr>\n";
									}
									else{
										echo "<tr><td class='field_name'>$lng[activities_inscription]</td><td>$lng[no]</td></tr>\n";
									}
								}
								echo "</table></div>\n";
								$q_it = mysqli_query($con, "SELECT activity_itinerary.name_$lang AS name, description_$lang AS description, place.name_$lang AS place_name, place.address_$lang AS place_address, DATE_FORMAT(start, '%H:%i') AS start, DATE_FORMAT(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%d') AS isostart, DATE_FORMAT(end, '%Y-%m-%d') AS isoend FROM activity_itinerary, place WHERE place.id = activity_itinerary.place AND activity = $r[id] ORDER BY start;");
								if (mysqli_num_rows($q_it) > 0){
									echo "<div id='activity_itinerary'><h4>Itinerario</h4>\n";
									echo "<table id='activity_itinerary'>\n";
									echo "<tr><th>$lng[activities_when]</th><th>$lng[activities_what]</th><th>$lng[activities_where]</th></tr>\n";
									while ($r_it = mysqli_fetch_array($q_it)){
										echo "<tr>\n";
										echo "<div class='hidden' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>\n";
										echo "<meta itemprop='inLanguage' content='$lang'/>\n";
										echo "<meta itemprop='name' content='$r_it[name]'/>\n";
										echo "<meta itemprop='description' content='$r_it[description]'/>\n";
										echo "<meta itemprop='startDate' content='$r_it[isostart]'/>\n";
										echo "<meta itemprop='location' content='$r_it[place_name]'/>\n";
										if (strlen($r_it['end']) > 0){
											echo "<meta itemprop='endDate' content='$r_it[place_end]'/>\n";
										}
										echo "</div>\n";
										if (strlen($r_it['end']) > 0){
											echo "<td>$r_it[start] - $r_it[end]</td>";
										}
										else{
											echo "<td>$r_it[start]</td>";
										}
										echo "<td><h5>$r_it[name]</h5>$r_it[description]</td>\n";
										if ($r_it['place_name'] == $r_it['place_address']){
											echo "<td>$r_it[place_name]</td>\n";
										}
										else{
											echo "<td>$r_it[place_name]<br/>($r_it[place_address])</td>\n";
										}
										echo "</tr>\n";
									}
									echo "</table></div>";
								}
							}
						?>
					</div>
				</div>
			</div>
			<div id="right_column">
				<div id="archive" class="section desktop">
					<h3 class="section_title"><?php echo $lng['activities_archive']; ?></h3>
					<div class='entry'>
						<?php
							$res_year = mysqli_query($con, "SELECT year(date) AS year FROM activity WHERE visible = 1 GROUP BY year(date) ORDER BY year DESC;");
							while($row_year = mysqli_fetch_array($res_year)){
								echo "<div class='year pointer' onClick=\"toggleElement('year_$row_year[year]');\">";
								echo("<img class='slid' id='slid_year_$row_year[year]' src='http://$http_host/img/misc/slid-right.png' alt=' '/><span class='fake_a'>$row_year[year]</span>");
								echo "</div>\n";
								echo "<div class='list_year pointer' id='list_year_$row_year[year]'>\n";
								$res_month = mysqli_query($con, "SELECT month(date) AS month FROM activity WHERE visible = 1 AND year(date) = $row_year[year] GROUP BY month(date) ORDER BY month DESC;");
								while($row_month = mysqli_fetch_array($res_month)){
									echo "<div class='month pointer' onClick=\"toggleElement('month_$row_year[year]_$row_month[month]');\">";
									echo("<img class='slid' id='slid_month_$row_year[year]_$row_month[month]' src='http://$http_host/img/misc/slid-right.png' alt=' '/><span class='fake_a'>" . $lng['months'][$row_month['month'] - 1] . "</span>");
									echo "</div>\n";
									echo "<ul id='list_month_$row_year[year]_$row_month[month]' class='activity_list'>\n";
									$res_title = mysqli_query($con, "SELECT id, permalink, title_$lang AS title FROM activity WHERE visible = 1 AND year(date) = $row_year[year] AND month(date) = '$row_month[month]' ORDER BY date DESC;");
									while($row_title = mysqli_fetch_array($res_title))
										echo "<li><a href='http://$http_host/actividades/" . $row_title['permalink']  . "'>$row_title[title]</a></li>\n";
									echo "</ul>\n";
								}
								echo "</div>\n";
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
			include("../footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "actividades", "$id");
		?>
	</body>
</html>
