<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_blog'];
	
	// Get current post id. Redirect if inexistent or invisible
	$perm = mysqli_real_escape_string($con, $_GET['perm']);
	$q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, DATE_FORMAT(dtime,'%b %d, %Y T%T') as dtime, comments FROM post WHERE permalink = '$perm' AND visible = 1;");
	if (mysqli_num_rows($q) == 0){
		header("Location: http://$http_host/blog/");
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
		<link rel="canonical" href="<?php echo "http://$http_host/blog/" . $r['permalink']; ?>"/>
		<link rel="author" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "http://$http_host"; ?>"/>
		<meta name="description" content="<?php echo strip_tags($r["text"]);?>"/>
		<meta property="og:title" content="<?php echo $r["title"] . " - Gasteizko Margolariak"; ?>"/>
		<meta property="og:url" content="<?php echo "http://$http_host/blog/" . $r['permalink']; ?>"/>
		<meta property="og:description" content="<?php echo strip_tags($r["text_$lang"]);?>"/>
		<?php
			$q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $id ORDER BY idx;");
			if (mysqli_num_rows($q_image) == 0)
				$img = "http://$http_host/img/logo/cover.png";
			else{
				$r_image = mysqli_fetch_array($q_image);
				$img = "http://$http_host/img/blog/preview/" . $r_image['image'];
			}
		?>
		<meta property="og:image" content="<?php echo $img;?>"/>
		<meta property="og:site_name" content="Gasteizko Margolariak"/>
		<meta property="og:type" content="article"/>
		<meta property="og:locale" content="<?php echo $lang;?>"/>
		<meta property="article:section" content="Blog"/>
		<!-- 	TODO: Review time format	 -->
		<meta property="article:published-time" content="<?php echo $r['time'];?>"/>
		<meta property="article:modified-time" content="<?php echo $r['time'];?>"/>
		<meta property="article:author" content="Gasteizko Margolariak"/>
		<?php
			$q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post = $id;");
			$tag_string = "vitoria cuadrilla gasteizko margolariak ";
			while ($r_tag = mysqli_fetch_array($q_tag))
				$tag_string = $tag_string . " " . $r_tag['tag']
		?>
		<meta property="article:tag" content="<?php echo $tag_string ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $r["title"] . " - Gasteizko Margolariak";?>"/>
		<meta name="twitter:description" content="<?php echo strip_tags($r["text"]);?>"/>
		<meta name="twitter:image" content="<?php echo $img;?>"/>
		<meta name="twitter:url" content="<?php echo "http://$http_host/blog/$r[permalink]"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<?php include("common/leftpanel.php"); ?>
			<div id="middle_column">
				<?php
					echo "<div itemscope itemtype='http://schema.org/BlogPosting' class='section blog_entry'>\n";
					echo "<meta itemprop='inLanguage' content='$lang'/>\n";
					echo "<meta itemprop='datePublished dateModified' content='$r[isodate]'/>\n";
					echo "<meta itemprop='headline name' content='$r[title]'/>\n";
					echo "<meta itemprop='mainEntityOfPage' content='http://$http_host'/>\n";
					echo "<div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>\n";
					echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
					echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
					echo "<meta itemprop='logo' content='http://$http_host/img/logo/logo.png'/>\n";
					echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
					echo "<meta itemprop='telephone' content='+34637140371'/>\n";
					echo "<meta itemprop='url' content='http://$http_host'/>\n";
					echo "</div>\n";
					echo "<div class='entry'>\n";
					echo "<h2>$r[title]</h2>\n";
					if ($r_image != null){
						echo "<div class='image_container'>\n";
						echo "<meta itemprop='image' content='http://$http_host/img/blog/$r_image[image]'/>\n";
						echo "<img class='post_image post_image_large' alt='$r[title]' src='/img/blog/preview/$r_image[image]'/>\n";
						echo "</div>\n";
					}
					echo "<p itemprop='text articleBody'>$r[text]</p>\n";
					//Other images
					if ($r_image != null){
						echo "<table id='secondary_images'><tr>\n";
						while ($r_image = mysqli_fetch_array($q_image)){
							echo "<meta itemprop='image' content='http://$http_host/img/blog/$r_image[image]'/>\n";
							echo "<td><img src='/img/blog/preview/$r_image[image]'/></td>\n";
						}
						echo "</tr></table>\n";
					}
					# Tags (if any) and date
					echo "<hr><table class='post_footer'><tr>\n";
					$q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post= $id;");
					if (mysqli_num_rows($q_tag) > 0){
						$tag_string = "<span id='tags' class='desktop'>Tags: ";
						$tag_raw = "";
						while ($r_tag = mysqli_fetch_array($q_tag)){
							$tag_string = $tag_string . "<a href='/blog/buscar/tag/$r_tag[tag]'>$r_tag[tag]</a>, ";
							$tag_raw = $tag_raw . "$r_tag[tag],";
						}
						$tag_string = substr($tag_string, 0, strlen($tag_string) - 2);
						$tag_string = $tag_string . "</span>";
						$tag_raw = substr($tag_raw, 0, strlen($tag_raw) - 1);
						echo "<meta itemprop='keywords' content='$tag_raw'/>\n";
						echo "<td><span class='date'>" . formatDate($r['dtime'], $lang) . "</span><br/><br class='mobile'/>$tag_string</td>\n";
					}
					else{
						echo "<td><span class='date'><span class='hidden'>" . formatDate($r['dtime'], $lang) . "</span></td>\n";
					}
					#Share
					echo "<td class='r_share'><span class='share'><span class='desktop'>$lng[blog_share]</span>\n";
					$title = urlencode("$r[title] - $http_host");
					$url_f = htmlspecialchars("https://www.facebook.com/sharer/sharer.php?u=http://$http_host$_SERVER[REQUEST_URI]");
					$url_t = htmlspecialchars("https://twitter.com/share?url=http://$http_host$_SERVER[REQUEST_URI]&text=$title");
					$url_g = htmlspecialchars("https://plus.google.com/share?url=http://$http_host$_SERVER[REQUEST_URI]");
					echo "<a href='$url_f' target='_blank'><img class='share_icon' alt='Facebook' src='http://$http_host/img/social/facebook.gif'/></a>\n";
					echo "<a href='$url_t' target='_blank'><img class='share_icon' alt='Twitter' src='http://$http_host/img/social/twitter.gif'/></a>\n";
					echo "<a href='$url_g' target='_blank'><img class='share_icon' src='http://$http_host/img/social/googleplus.gif' alt='Google+'/></a>\n";
					echo "</span></td>\n";
					echo "</tr></table>\n";
					#Comments
					if ($r['comments'] == 1){
						echo "<hr>\n";
						$q_comment = mysqli_query($con, "SELECT id, post, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, user, username, lang, text FROM post_comment WHERE post = $id AND approved = 1 ORDER BY dtime;");
						$count = mysqli_num_rows($q_comment);
						echo "<meta itemprop='commentCount interationCount' content='$count'/>\n";
						switch ($count){
							case 0:
								echo "<h4><meta itemprop='interactionCount' content='0'/></meta>$lng[blog_comments_0]</h4>\n";
								break;
							case 1:
								echo "<h4><span itemprop='interactionCount'>1</span> $lng[blog_comments_1]</h4>\n";
								break;
							default:
								echo "<h4><span itemprop='interactionCount'>$count</span> $lng[blog_comments_multiple]</h4>\n";
						}
						echo "<div id='comment_list'>\n";
						//WARNING: If changes are done here, do the same in comment.php
						while ($r_comment = mysqli_fetch_array($q_comment)){
							echo "<div itemprop='comment' itemscope itemtype='http://schema.org/UserComments' id='comment_$r_comment[id]' class='comment'>\n";
							//When official users are implementes, see if there is user or username
							echo "<span itemprop='creator' class='comment_user'>$r_comment[username]</span>\n";
							echo "<span class='comment_date date'><meta itemprop='commentTime' content='$r_comment[isodate]'/>" . formatDate($r_comment['dtime'], $lang) . "</span>\n";
							echo "<p itemprop='commentText' class='comment_text'>$r_comment[text]</p>";
							echo "<hr class='comment_line'/>\n";
							echo "</div>\n";
						}
						echo "</div>\n";
						#Comment form
						echo "<div class='comment' id='comment_new'>\n";
						echo "<form id='comment_form' method='post' action='/' onsubmit='event.preventDefault();postComment($id, \"$lang\");'>\n";
						echo "<textarea id='new_comment_text' name='text' maxlength='1800' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);' placeholder='$lng[blog_placeholder_text]'></textarea>\n";
						echo "<input type='hidden' name='id' value='$id'>\n";
						echo "<div id='identification_form'>\n";
						echo "<br><input id='new_comment_user' name='user' maxlength='50' type='text' placeholder='$lng[blog_placeholder_name]' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);'/>\n";
						echo "<input type='submit' value='$lng[blog_send]'/>\n";
						echo "</div>\n";
						echo "</form>\n";
						echo "</div>\n";
					}
					else{
						echo "<h4>$lng[blog_comments_closed]</h4>";
					}
					echo "</div></div>\n";
				?>
			</div>
			<?php include("common/rightpanel.php"); ?>
		</div>
		<?php
			include("../footer.php");
			$ad = ad($con, $lang, $lng); 
			stats($ad, $ad_static, "blog", "$id");
		?>
	</body>
</html>
