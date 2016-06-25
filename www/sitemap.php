<?php
	header('Content-type: application/xml');
	$http_host = $_SERVER['HTTP_HOST'];
	include("functions.php");
	$con = startdb();
	//registerVisit($_SERVER['REQUEST_URI'], false);
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>http://<?php echo $http_host; ?>/</loc>
		<?php
			$res_date = mysqli_query($con, "(SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM post) UNION (SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM activity) UNION (SELECT DATE_FORMAT(uploaded, '%Y-%m-%d') AS cdate FROM photo) ORDER BY cdate DESC LIMIT 1;");
			$row_date = mysqli_fetch_array($res_date);
		?>
		<lastmod><?php echo $row_date['cdate']; ?></lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
	<url>
		<loc>http://<?php echo $http_host; ?>/help/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.5</priority>
	</url>
	<url>
		<loc>http://<?php echo $http_host; ?>/nosotros/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.6</priority>
	</url>
	<url>
		<loc>http://<?php echo $http_host; ?>/blog/</loc>
		<?php
			$res_date = mysqli_query($con, "SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM post ORDER BY cdate DESC LIMIT 1;");
			$row_date = mysqli_fetch_array($res_date);
		?>
		<lastmod><?php echo $row_date['cdate']; ?></lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
	<?php
		$res_post = mysqli_query($con, "SELECT id, permalink, DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM post WHERE visible = 1 ORDER BY cdate DESC;");
		$priority = 0.75;
		while ($row_post = mysqli_fetch_array($res_post)){
			echo "\t<url>\n";
			echo "\t\t<loc>http://$http_host/blog/$row_post[permalink]</loc>\n";
			echo "\t\t<lastmod>$row_post[cdate]</lastmod>\n";
			echo "\t\t<changefreq>weekly</changefreq>\n";
			echo "\t\t<priority>$priority</priority>\n";
			echo "\t</url>\n";
			$priority = $priority - 0.05;
			if ($priority < 0.45)
				$priority = 0.45;
		}
	?>
	<url>
		<loc>http://<?php echo $http_host; ?>/actividades/</loc>
		<?php
			$res_date = mysqli_query($con, "SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM activity ORDER BY cdate DESC LIMIT 1;");
			$row_date = mysqli_fetch_array($res_date);
		?>
		<lastmod><?php echo $row_date['cdate']; ?></lastmod>
		<changefreq>weekly</changefreq>
		<priority>1</priority>
	</url>
	<?php
		$res = mysqli_query($con, "SELECT id, permalink, DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM activity WHERE visible = 1 ORDER BY cdate DESC;");
		$priority = 0.95;
		while ($row = mysqli_fetch_array($res)){
			echo "\t<url>\n";
			echo "\t\t<loc>http://$http_host/actividades/$row[permalink]</loc>\n";
			echo "\t\t<lastmod>$row[cdate]</lastmod>\n";
			echo "\t\t<changefreq>weekly</changefreq>\n";
			echo "\t\t<priority>$priority</priority>\n";
			echo "\t</url>\n";
			$priority = $priority - 0.05;
			if ($priority < 0.65)
				$priority = 0.65;
		}
	?>
	<url>
		<loc>http://<?php echo $http_host; ?>/galeria/</loc>
		<?php
			$res_date = mysqli_query($con, "SELECT DATE_FORMAT(uploaded, '%Y-%m-%d') AS cdate FROM photo ORDER BY cdate DESC LIMIT 1;");
			$row_date = mysqli_fetch_array($res_date);
		?>
		<lastmod><?php echo $row_date['cdate']; ?></lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
	<?php
		$res = mysqli_query($con, "SELECT DISTINCT album.permalink AS permalink, DATE_FORMAT(uploaded, '%Y-%m-%d') AS cdate FROM photo, album, photo_album WHERE photo = photo.id AND album = album.id ORDER BY cdate DESC;");
		$priority = 0.75;
		while ($row = mysqli_fetch_array($res)){
			echo "\t<url>\n";
			echo "\t\t<loc>http://$http_host/galeria/$row[permalink]</loc>\n";
			echo "\t\t<lastmod>$row[cdate]</lastmod>\n";
			echo "\t\t<changefreq>weekly</changefreq>\n";
			echo "\t\t<priority>$priority</priority>\n";
			echo "\t</url>\n";
			$priority = $priority - 0.05;
			if ($priority < 0.45)
				$priority = 0.45;
		}
	?>
	<url>
		<loc>http://<?php echo $http_host; ?>/lablanca/</loc>
		<?php
			$res_lb = mysqli_query($con, 'SELECT value FROM settings WHERE name= "festivals";');
			if (mysqli_num_rows($res_lb) > 0){
				$r_lb = mysqli_fetch_array($res_lb);
				if ($r_lb['value'] == 1){
					echo "\t\t<changefreq>monthly</changefreq>\n";
					echo "\t\t<priority>0.5</priority>\n";
				}
				else{
					echo "\t\t<changefreq>monthly</changefreq>\n";
					echo "\t\t<priority>0.5</priority>\n";
				}
			}
			else{
				echo "\t\t<changefreq>monthly</changefreq>\n";
				echo "\t\t<priority>0.5</priority>\n";
			}
		?>
	</url>
	<?php
		$year = date("Y");
		$res_post = mysqli_query($con, "SELECT year FROM festival WHERE year != " . date("Y") . " ORDER BY year;");
		$priority = 0.55;
		while ($row_post = mysqli_fetch_array($res_post)){
			echo "\t<url>\n";
			echo "\t\t<loc>http://$http_host/lablanca/$r_years[year]</loc>\n";
			echo "\t\t<changefreq>yearly</changefreq>\n";
			echo "\t\t<priority>$priority</priority>\n";
			echo "\t</url>\n";
			$priority = $priority - 0.05;
			if ($priority < 0.40)
				$priority = 0.40;
		}
	?>
</urlset>
