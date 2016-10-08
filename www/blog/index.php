<?php
	session_start();
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_blog'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $lng['blog_title'];?></title>
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
		<link rel="canonical" href="<?php echo "http://$http_host/blog"; ?>"/>
		<link rel="author" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "http://$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['blog_descrption'];?>"/>
		<meta property="og:title" content="<?php echo $lng['blog_title'];?>"/>
		<meta property="og:url" content="<?php echo "http://$http_host/blog"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['blog_description'];?>"/>
		<meta property="og:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['index_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $lng['blog_title'];?>"/>
		<meta name="twitter:description" content="<?php echo $lng['blog_description'];?>"/>
		<meta name="twitter:image" content="<?php echo "http://$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"http://$http_host"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<?php include("common/leftpanel.php"); ?>
			<div id="middle_column" class="section">
				<?php
					echo("<h3 class='section_title'>$lng[section_blog]</h3>\n");
					$offset = (int) $_GET['o'];
					$q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, comments FROM post WHERE visible = 1 ORDER BY dtime DESC LIMIT 5 OFFSET $offset;");
					while($r = mysqli_fetch_array($q)){
						echo "<div itemscope itemtype='http://schema.org/BlogPosting' class='entry blog_entry'>\n";
						echo "<meta itemprop='inLanguage' content='$lang'/>\n";
						echo "<meta itemprop='datePublished dateModified' content='$r[isodate]'/>\n";
						echo "<meta itemprop='headline name' content='$r[title]'/>\n";
						echo "<meta itemprop='articleBody text' content='$r[text]'/>\n";
						echo "<meta itemprop='mainEntityOfPage' content='http://$http_host'/>\n";
						echo "<div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>\n";
						echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
						echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
						echo "<meta itemprop='logo' content='http://$http_host/img/logo/logo.png'/>\n";
						echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
						echo "<meta itemprop='telephone' content='+34637140371'/>\n";
						echo "<meta itemprop='url' content='http://$http_host'/>\n";
						echo "</div>\n";
						echo "<h2><a itemprop='url' href='http://$http_host/blog/$r[permalink]'>$r[title]</a></h2>\n";
						$q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r[id] ORDER BY idx LIMIT 1;");
						if (mysqli_num_rows($q_image) > 0){
							$r_image = mysqli_fetch_array($q_image);
							echo "<div class='post_list_image_container'>\n";
							echo "<a href='http://$http_host/blog/$r[permalink]'>\n";
							echo "<meta itemprop='image' content='http://$http_host/img/blog/view/$r_image[image]'/>\n";
							echo "<img class='post_list_image alt='$r[title]' src='http://$http_host/img/blog/miniature/$r_image[image]'/>\n";
							echo "</a>\n";
							echo "</div>\n";
						}
						echo "<p>" . cutText($r['text'], 800, $lng['blog_read_more'], "http://$http_host/blog/$r[permalink]/") . "</p>\n";
						# Tags (if any), date, and comment counter
						echo "<hr><table class='post_footer'><tr>\n";
						$q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post= $r[id];");
						if (mysqli_num_rows($q_tag) > 0){
							$tag_string = "<span class='tags desktop'>Tags: ";
							$tag_raw = "";
							while ($r_tag = mysqli_fetch_array($q_tag)){
								$tag_string = $tag_string . "<a href='http://$http_host/blog/buscar/tag/$r_tag[tag]'>$r_tag[tag]</a>, ";
								$tag_raw = $tag_raw . "$r_tag[tag],";
							}
							$tag_string = substr($tag_string, 0, strlen($tag_string) - 2);
							$tag_string = $tag_string . "</span>";
							$tag_raw = substr($tag_raw, 0, strlen($tag_raw) - 1);
							echo "<meta itemprop='keywords' content='$tag_raw'/>\n";
							echo "<td><span class='date'>" . formatDate($r['dtime'], $lang, false) . "</span>&nbsp;&nbsp;&nbsp;$tag_string</td>\n";
						}
						else
							echo "<td><span class='date'>" . formatDate($r['dtime'], $lang, false) . "</span></td>\n";
						#Comment counter
						if ($r['comments'] == 1){
							$q_comment = mysqli_query($con, "SELECT count(id) AS count FROM post_comment WHERE post = $r[id] AND visible = 1;");
							$r_comment = mysqli_fetch_array($q_comment);
							echo "<meta itemprop='commentCount interationCount' content='$r_comment[count]'/>\n";
							if ($r_comment['count'] == 1)
								echo "<td class='r_comment'><span class='comment_counter'>1 $lng[blog_comments_1]</span>\n";
							else if ($r_comment['count'] == 0)
								echo "<td class='r_comment'><span class='comment_counter'>$lng[blog_comments_0]</span>\n";
							else 
								echo "<td class='r_comment'><span class='comment_counter'>$r_comment[count] $lng[blog_comments_multiple]</span>\n";
						}
						echo "</td>\n";
						echo "</tr></table>\n";
						echo "</div></br>\n";
					}
					#Pager
					$q_count = mysqli_query($con, "SELECT COUNT(id) AS count FROM post WHERE visible = 1;");
					$r_count = mysqli_fetch_array($q_count);
					$cur_page = (int) ($offset / 5) + 1;
					$max_page = (int) ($r_count['count'] / 5);
					if ($r_count['count'] % 5 != 0)
						$max_page ++;
					echo "<div id='pager'><table id='pager'><tr>\n";
					if ($cur_page > 4){
						echo "<td><div class='entry'><a href='http://$http_host/blog/0'>1</a></div></td><td class='pager_ellipse'><div class='entry'>...</div></td>\n";
						for ($i = $cur_page - 2; $i < $cur_page; $i ++)
							echo "<td><div class='entry'><a href='http://$http_host/blog/" . (5 * $i - 5) . "'>" . $i . "</a></div></td>\n";
					}
					else
						for ($i = 1; $i < $cur_page; $i ++)
							echo "<td><div class='entry'><a href='http://$http_host/blog/" . (5 * $i - 5) . "'>" . $i . "</a></div></td>\n";
					echo "<td class='pager_current'><div class='entry'>$cur_page</div></td>\n";
					if ($cur_page + 4 < $max_page){
						for ($i = $cur_page + 1; $i < $cur_page + 3; $i ++)
							echo "<td><div class='entry'><a href='http://$http_host/blog/" . (5 * $i - 5) . "'>" . $i . "</div></a></td>";
						echo "<td class='pager_ellipse'><div class='entry'>...</div></td><td><div class='entry'><a href='/blog/" . ($max_page * 5 - 5) . "'>$max_page</a></div></td>";
					}
					else
						for ($i = $cur_page + 1; $i <= $max_page; $i ++)
							echo "<td><div class='entry'><a href='http://$http_host/blog/" . (5 * $i - 5) . "'>" . $i . "</a></div></td>";
					echo "</tr></table></div>\n";
				?>
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
