<?php
	session_start();
	include("../functions.php");
	$con = startdb();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_lablanca'];
	
	//Is festivals enabled in settings?
	$q = mysqli_query($con, "SELECT value FROM settings WHERE name = 'festivals';");
	if (mysqli_num_rows($q) == 0)
		$is_festivals = false;
	else{
		$r = mysqli_fetch_array($q);
		if ($r['value'] == 1){
			$is_festivals = true;
		}
		else{
			$is_festivals = false;
		}
	}
	
	//Get year
	$year = date("Y");
	//if (is_int($_GET['year']) == false){ 
	if ($_GET['year'] != ''){ 
		$q_year = mysqli_query($con, "SELECT id FROM festival WHERE year = " . mysqli_real_escape_string($con, $_GET['year']));
		if (mysqli_num_rows($q_year) > 0){
			$year = $_GET['year'];
			$is_festivals = true;
		}
	}
	
	//echo $_GET['year'];

?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title>
			<?php
				if ($is_festivals){
					echo str_replace('#', $year, $lng['lablanca_title']);
				}
				else{
					echo $lng['lablanca_no_title'];
				}
			?>
		</title>
		<link rel="shortcut icon" href="<?php echo "http://$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("../css/ui.css"); 
				include("../css/lablanca.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("../css/m/ui.css"); 
				include("../css/m/lablanca.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php
				include("../script/ui.js");
				include("../script/lablanca.js");
			?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "http://$http_host/lablanca/"; ?>"/>
		<link rel="author" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "http://$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['lablanca_description'];?>"/>
		<meta property="og:title" 
			content="<?php
				if ($is_festivals){
					echo str_replace('#', $year, $lng['lablanca_title']);
				}
				else{
					echo $lng['lablanca_no_title'];
				}
			?>"
		/>
		<meta property="og:url" content="<?php echo "http://$http_host"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['lablanca_description'];?>"/>
		<meta property="og:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['lablanca_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" 
			content="<?php
				if ($is_festivals){
					echo str_replace('#', $year, $lng['lablanca_title']);
				}
				else{
					echo $lng['lablanca_no_title'];
				}
			?>"
		/>
		<meta name="twitter:description" content="<?php echo $lng['lablanca_description'];?>"/>
		<meta name="twitter:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"http://$http_host"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<?php				
				//Include file
				if ($is_festivals){
					include("fiestas.php");
				}
				else{
					include("nofiestas.php");
				}
			?>
			<?php
				//If the program of previous years exist, show a drop list
				$year = date("Y");
				if (date("M") <= 8 || (date("M") == 8 && date("D") < 25)){
					$year --;
				}
				if (date("M") <= 8){
					$q_years = mysqli_query($con, "SELECT year FROM festival WHERE year != " . date("Y") . " ORDER BY year DESC;");
				else{
					$q_years = mysqli_query($con, "SELECT year FROM festival WHERE year <= " . date("Y") . " ORDER BY year DESC;");
				}
				if (mysqli_num_rows($q_years) >= 0){
					echo("<br/><br/><div class='section' id='past_festivals'>\n");
					echo("<h3>$lng[lablanca_past_title]</h3>\n");
					echo("<div class='entry'><ul>\n");
					while ($r_years = mysqli_fetch_array($q_years)){
						echo("<li><a href='http://$http_host/lablanca/$r_years[year]'>$lng[lablanca_past_link] $r_years[year]</a></li>\n");
					}		
					echo("</ul></div>\n");
					echo("</div>\n");
				}
			?>
		</div>
		<?php
			include("../footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "fiestas", "");
		?>
	</body>
</html>
