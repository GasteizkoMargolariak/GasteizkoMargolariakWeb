<?php
	session_start();
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	$proto = getProtocol();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_us'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $lng['us_title'];?></title>
		<link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("../css/ui.css"); 
				include("../css/ayuda.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("../css/m/ui.css"); 
				include("../css/m/ayuda.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php
				include("../script/ui.js");
			?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "$proto$http_host/us/"; ?>"/>
		<link rel="author" href="<?php echo "$proto$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "$proto$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['us_description'];?>"/>
		<meta property="og:title" content="<?php echo $lng['us_title'];?>"/>
		<meta property="og:url" content="<?php echo "$proto$http_host"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['us_description'];?>"/>
		<meta property="og:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['us_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $lng['us_title'];?>"/>
		<meta name="twitter:description" content="<?php echo $lng['us_description'];?>"/>
		<meta name="twitter:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"$proto$http_host"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<div class="content_row">
				<div class="content_cell">
					<div class="section" id="section_association">
						<h3 class='section_title'><?php echo $lng['help_contact_title']; ?></h3>
						<div class="entry">
							<table id="association">
								<tr>
									<td class="name"><?php echo $lng['help_contact_register']; ?></td>
									<td class="value"><?php echo $lng['help_contact_register_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_date_constitution']; ?></td>
									<td class="value"><?php echo $lng['help_contact_date_constitution_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_date_inscription']; ?></td>
									<td class="value"><?php echo $lng['help_contact_date_inscription_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_city']; ?></td>
									<td class="value"><?php echo $lng['help_contact_city_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_territory']; ?></td>
									<td class="value"><?php echo $lng['help_contact_territory_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_country']; ?></td>
									<td class="value"><?php echo $lng['help_contact_country_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_clasification']; ?></td>
									<td class="value"><?php echo $lng['help_contact_clasification_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_objectives']; ?></td>
									<td class="value"><?php echo $lng['help_contact_objectives_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_phone']; ?></td>
									<td class="value"><?php echo $lng['help_contact_phone_value']; ?></td>
								</tr>
								<tr>
									<td class="name"><?php echo $lng['help_contact_email']; ?></td>
									<td class="value"><?php echo $lng['help_contact_email_value']; ?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="content_cell">
					<div class="section" id="section_license">
						<h3 class='section_title'><?php echo $lng['help_license_title']; ?></h3>
						<div class="entry">
							<?php echo $lng['help_license']; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="content_row">
				<div class="content_cell">
					<div class="section" id="section_privacy">
						<h3><?php echo $lng['help_privacy_title']; ?></h3>
						<div class="entry">
							<?php echo $lng['help_privacy']; ?>
						</div>
					</div>
				</div>
				<div class="content_cell">
					<div class="section" id="section_cookie">
						<h3 class='section_title'><?php echo $lng['help_cookie_title']; ?></h3>
						<div class="entry">
							<?php echo $lng['help_cookie']; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			include("../footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "ayuda", "");
		?>
	</body>
</html>
