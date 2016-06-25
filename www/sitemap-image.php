<?php
	header('Content-type: application/xml');
	$http_host = $_SERVER['HTTP_HOST'];
	include("functions.php");
	$con = startdb();
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
	<url>
		<loc>http://<?php echo $http_host; ?>/</loc>
		<image:image>
			<image:loc>http://<?php echo $http_host; ?>/img/logo/logo.png</image:loc>
			<image:caption>Gasteizko Margolariak</image:caption>
			<image:title>Gasteizko Margolariak</image:title>
		</image:image>
		<?php
			$q = mysqli_query($con, "SELECT title_es, image FROM activity_image, activity WHERE activity = activity.id ORDER BY date DESC;");
			while ($r = mysqli_fetch_array($q)){
				echo("\t\t<image:image>\n");
				echo("\t\t\t<image:loc>http://$http_host/img/actividades/view/$r[image]</image:loc>\n");
				echo("\t\t\t<image:title>$r[title_es]</image:title>\n");
				echo("\t\t</image:image>\n");
			}
			$q = mysqli_query($con, "SELECT title_es, image FROM post_image, post WHERE post = post.id ORDER BY dtime DESC;");
			while ($r = mysqli_fetch_array($q)){
				echo("\t\t<image:image>\n");
				echo("\t\t\t<image:loc>http://$http_host/img/blog/view/$r[image]</image:loc>\n");
				echo("\t\t\t<image:title>$r[title_es]</image:title>\n");
				echo("\t\t</image:image>\n");
			}
			$q = mysqli_query($con, "SELECT album.title_es AS album, photo.title_es AS photo, file FROM photo, album, photo_album WHERE photo.id = photo AND album.id = album ORDER BY uploaded;");
			while ($r = mysqli_fetch_array($q)){
				echo("\t\t<image:image>\n");
				echo("\t\t\t<image:loc>http://$http_host/img/galeria/view/$r[file]</image:loc>\n");
				if (strlen($r['photo']) == 0){
					echo("\t\t\t<image:title>$r[album]</image:title>\n");
				}
				else{
					echo("\t\t\t<image:title>$r[photo]</image:title>\n");
				}
				echo("\t\t</image:image>\n");
			}
		?>
	</url>
</urlset>
