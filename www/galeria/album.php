<?php
	session_start();
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	$proto = getProtocol();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_gallery'];
	
	//Get album
	$perm = mysqli_real_escape_string($con, $_GET['perm']);
	$q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, description_$lang AS description, user, open, dtime FROM album WHERE permalink = '$perm';");
	if (mysqli_num_rows($q) == 0){
		header("Location: /galeria/");
		exit (-1);
	}
	else{
		$r = mysqli_fetch_array($q);
		$id = $r['id'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $r['title'];?></title>
		<link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("../css/ui.css"); 
				include("../css/galeria.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("../css/m/ui.css"); 
				include("../css/m/galeria.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php
				include("../script/ui.js");
				include("../script/galeria.js");
			?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "$proto$http_host/galeria/$perm"; ?>"/>
		<link rel="author" href="<?php echo "$proto$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "$proto$http_host"; ?>"/>
		<meta name="description" content="<?php echo strip_tags($r['description']);?>"/>
		<meta property="og:title" content="<?php echo $l['title'];?>"/>
		<meta property="og:url" content="<?php echo "$proto$http_host/galeria/$perm"; ?>"/>
		<meta property="og:description" content="<?php echo strip_tags($r['description']); ?>"/>
		<meta property="og:image" content="<?php  echo "$proto$http_host/img/logo/logo.png";?>"/> 
		<meta property="og:site_name" content="Gasteizko Margolariak"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $l['title'];?>"/>
		<meta name="twitter:description" content="<?php echo strip_tags($r['description']); ?>"/>
		<meta name="twitter:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo "$proto$http_host/galeria/$perm"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body onLoad="populatePhotos();" onkeypress="keyDown(event);">
		<?php include("../header.php"); ?>
		<div id="content">
			<div class="section" id="album">
				<?php
					//Header
					echo "<h3 class='section_title' id='album_title'>$r[title]</h3>\n";
					if ($r['description'] != ''){
						echo "<div class='entry' id='album_header'>\n";
						echo $r['description'];
						echo "</div>\n";
					}
				
					//Photos of the album
					$q_photo = mysqli_query($con, "SELECT id, file, permalink, title_$lang AS title, description_$lang AS description, photo.dtime AS dtime, uploaded, width, height, size, user, username FROM photo, photo_album WHERE photo_album.photo = id AND album = $r[id] ORDER BY photo.dtime DESC;");
					/* TODO AND approved = 1*/
					while ($r_photo = mysqli_fetch_array($q_photo)){
						echo "<div class='entry photo'>\n<div class='photo_container'>\n";
						echo "<img class='pointer photo_img' path='$r_photo[file]' onClick=\"showPhotoByPath('$r_photo[file]');\" src='/img/galeria/miniature/$r_photo[file]' /></div>\n";
						if (strlen($r_photo['title']) > 0 ){
							echo "<h3 class='entry_title'><a href='javascript:;' onClick=\"showPhotoByPath('$r_photo[file]');\">$r_photo[title]</a></h4>\n";
						}
						if (strlen($r_photo['description']) > 0 ){
							echo "<span class='photo_description'>" . cutText($r_photo['description'], 50) . "</span>\n";
						}
						//Count comments
						$q_comments = mysqli_query($con, "SELECT id FROM photo_comment WHERE photo = $r_photo[id];");
						echo "<span class='comment_counter'>" . mysqli_num_rows($q_comments) . "<img src='$proto$http_host/img/misc/comment.png' alt=' '/></span>\n"; //TODO: icon
						echo "\n</div>\n";
					}
				?>
			</div>
		</div>
		<div id="screen_cover" onClick="closeViewer();">
		</div>
		<div id="photo_viewer" class="section">
		</div>
		<?php
			include("../footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "galeria", "$id");
		?>
	</body>
</html>

<?php } ?>
