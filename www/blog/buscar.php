<?php
	session_start();
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	$proto = getProtocol();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_blog'];
	
	//Search terms
	$search_field = mysqli_real_escape_string($con, $_GET['where']);
	$search_term = strtolower(mysqli_real_escape_string($con, $_GET['query']));
	if ($search_field == '' || $search_term == ''){
		header("Location: $proto$http_host/blog/");
		exit(-1);
	}
	
	//Get matches
	switch ($search_field){
		case $lng['search_field_all']:
			$q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, comments FROM post WHERE lower(title_es) LIKE '%$search_term%' OR lower(text_es) LIKE '%$search_term%' OR id IN (SELECT post FROM post_tag WHERE lower(tag) LIKE '%$search_term%') ORDER BY dtime DESC;");
			break;
		case $lng['search_field_tag']:
			$q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, comments FROM post WHERE id IN (SELECT post FROM post_tag WHERE tag like '$search_term') ORDER BY dtime DESC;");
			break;
		default:
			header("Location: $proto$http_host/blog/");
			exit(-1);
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $lng['blog_title'];?></title>
		<link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("../css/ui.css"); 
				include("../css/blog.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("../css/m/ui.css"); 
				include("../css/m/blog.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php
				include("../script/ui.js");
				include("../script/blog.js");
			?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "$proto$http_host/blog"; ?>"/>
		<link rel="author" href="<?php echo "$proto$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "$proto$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['blog_descrption'];?>"/>
		<meta property="og:title" content="<?php echo $lng['blog_title'];?>"/>
		<meta property="og:url" content="<?php echo "$proto$http_host/blog"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['blog_description'];?>"/>
		<meta property="og:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['index_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $lng['blog_title'];?>"/>
		<meta name="twitter:description" content="<?php echo $lng['blog_description'];?>"/>
		<meta name="twitter:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"$proto$http_host"; ?>"/>
		<meta name="robots" content="no-index no-follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<?php include("common/leftpanel.php"); ?>
			<div id="middle_column">
				<div class="section">
					<div class="entry">
						<?php
							if (mysqli_num_rows($q) == 1){
								echo "$lng[blog_search_1]<span class='italic'>$search_term</span>$lng[blog_search_2]<span class='italic'>$search_field</span>: " . strval(mysqli_num_rows($q)) . $lng['blog_search_result'];
							}
							else{
								echo "$lng[blog_search_1]<span class='italic'>$search_term</span>$lng[blog_search_2]<span class='italic'>$search_field</span>: " . strval(mysqli_num_rows($q)) . $lng['blog_search_results'];
							}
						?>
					</div>
				</div>
				<br/>
				<div class="section">
					<?php
						while($r = mysqli_fetch_array($q)){
							echo "<div itemscope itemtype='http://schema.org/BlogPosting' class='entry blog_entry'>\n";
							echo "<meta itemprop='inLanguage' content='$lang'/>\n";
							echo "<meta itemprop='datePublished dateModified' content='$r[isodate]'/>\n";
							echo "<meta itemprop='headline name' content='$r[title]'/>\n";
							echo "<meta itemprop='articleBody text' content='$r[text]'/>\n";
							echo "<meta itemprop='mainEntityOfPage' content='$proto$http_host'/>\n";
							echo "<div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>\n";
							echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
							echo "<meta itemprop='logo' content='$proto$http_host/img/logo/logo.png'/>\n";
							echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
							echo "<meta itemprop='telephone' content='+34637140371'/>\n";
							echo "<meta itemprop='url' content='$proto$http_host'/>\n";
							echo "</div>\n";
							echo "<h3 class='entry_title post_search_title'><a itemprop='url' href='$proto$http_host/blog/$r[permalink]'>$r[title]</a></h2>\n";
							# Tags (if any) and date
							echo "<table class='post_footer post_footer_search'><tr>\n";
							$q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post= $r[id];");
							if (mysqli_num_rows($q_tag) > 0){
								$tag_string = "<span class='tags desktop'>Tags: ";
								$tag_raw = "";
								while ($r_tag = mysqli_fetch_array($q_tag)){
									$tag_string = $tag_string . "<a href='$proto$http_host/blog/buscar/tag/$r_tag[tag]'>$r_tag[tag]</a>, ";
									$tag_raw = $tag_raw . "$r_tag[tag],";
								}
								$tag_string = substr($tag_string, 0, strlen($tag_string) - 2);
								$tag_string = $tag_string . "</span>";
								$tag_raw = substr($tag_raw, 0, strlen($tag_raw) - 1);
								echo "<meta itemprop='keywords' content='$tag_raw'/>\n";
								echo "<td><span class='date'>" . formatDate($r['dtime'], $lang, false) . "</span>&nbsp;&nbsp;&nbsp;$tag_string</td>\n";
							}
							else{
								echo "<td><span class='date'></span>" . formatDate($r['dtime'], $lang) . "</span>/td>\n";
							}
							echo "</tr></table><hr class='post_search_separator'/>\n";
							#Image and text
							$q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r[id] ORDER BY idx LIMIT 1;");
							if (mysqli_num_rows($q_image) > 0){
								$r_image = mysqli_fetch_array($q_image);
								echo "<a href='$proto$http_host/blog/$r[permalink]'>\n";
								echo "<meta itemprop='image' content='$proto$http_host/img/blog/$r_image[image]'/>\n";
								echo "<img class='post_search_image alt='$r[title]' src='$proto$http_host/img/blog/preview/$r_image[image]'/>\n";
								echo "</a>\n";
							}
							echo "<p class='post_search_text'>" . cutText($r['text'], 150, $lng['blog_read_more'], "$proto$http_host/blog/$r[permalink]/") . "</p>\n";
							echo "<br/>\n";
							echo "</div>\n";
						}
					?>
				</div>
			</div>
			<?php include("common/rightpanel.php"); ?>
		</div>
		<?php
			include("../footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "blog", "");
		?>
	</body>
</html>
