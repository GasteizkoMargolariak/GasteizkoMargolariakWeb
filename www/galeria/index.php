<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_gallery'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $lng['gallery_title'];?></title>
		<link rel="shortcut icon" href="<?php echo "http://$http_host/img/logo/favicon.ico";?>">
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
			?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="author" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "http://$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['gallery_description'];?>"/>
		<meta property="og:title" content="<?php echo $lng['gallery_title'];?>"/>
		<meta property="og:url" content="<?php echo "http://$http_host"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['gallery_description'];?>"/>
		<meta property="og:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['gallery_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $lng['gallery_title'];?>"/>
		<meta name="twitter:description" content="<?php echo $lng['gallery_description'];?>"/>
		<meta name="twitter:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"http://$http_host"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<div class="section">
				<div class="entry">
					<?php echo $lng['gallery_header']; ?>
				</div>
			</div>
			<br/>
			<div class="section" id="album_list">
				<?php
					echo "<h3>$lng[gallery_albums]</h3>\n";
					//Album with photos with dates
					$q = mysqli_query($con, "SELECT album.id AS id, album.permalink AS permalink, album, album.title_$lang AS title, album.description_$lang AS description FROM photo_album, photo, album WHERE album.id = photo_album.album AND photo = photo.id GROUP BY album ORDER BY avg(photo.dtime) DESC;");
					while ($r = mysqli_fetch_array($q)){
						echo "<div class='entry album_list'>\n";
							echo "<table class='album_thumbnails'>\n";
							echo "<tr>\n";
							$i = 0;
							$q_photo = mysqli_query($con, "SELECT * FROM photo, photo_album WHERE id = photo AND album = $r[album] ORDER BY rand() LIMIT 4;");
							while ($r_photo = mysqli_fetch_array($q_photo)){
								echo "<td><a href='http://$http_host/galeria/$r[permalink]'><img src='http://$http_host/img/galeria/miniature/$r_photo[file]'/></a></td>\n";
								$i ++;
								if ($i == 2)
									echo "</tr>\n<tr>";
							}
							echo "</tr>\n</table>\n";
							$q_count = mysqli_query($con, "SELECT album FROM photo_album WHERE album = $r[id];");
							if (mysqli_num_rows($q_count) == 1)
								echo "<h4><a href='http://$http_host/galeria/$r[permalink]'>$r[title]</a> - 1 $lng[gallery_photos_singular]</h4>\n";
							else
								echo "<h4><a href='http://$http_host/galeria/$r[permalink]'>$r[title]</a> - " . mysqli_num_rows($q_count) . " $lng[gallery_photos_plural]</h4>\n";
							if (strlen($r['description']) > 0){
								echo "<p>". cutText($r['description'], 100, "$lng[gallery_read_more]", "http://$http_host/galeria/$r[permalink]") . "</p>\n";
							}
						echo "</div>\n";
					}
					
					//Albums with no photos or photos without dates
					$q = mysqli_query($con, "SELECT id, title_es FROM album WHERE id NOT IN (SELECT album FROM photo_album, photo WHERE photo = photo.id GROUP BY album);");
				?>
			</div>
		</div>
		<?php include("../footer.php"); ?>
	</body>
</html>
