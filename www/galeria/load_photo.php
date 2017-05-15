<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	$proto = getProtocol();
	$server = "$proto$http_host";

	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	//Get photo data
	$path = mysqli_real_escape_string($con, $_GET['path']);
	$q = mysqli_query($con, "SELECT id, file, permalink, title_$lang AS title, description_$lang AS description, uploaded, size, user, username FROM photo WHERE file = '$path' AND approved = 1");
	if (mysqli_num_rows($q) == 0){
		exit(0);
	}
	else{
		$r = mysqli_fetch_array($q);

		// Get album name
		$q_album = mysqli_query($con, "SELECT album.title_$lang AS title FROM album, photo_album WHERE album = album.id AND photo = $r[id]");
		$r_album = mysqli_fetch_array($q_album);
?>
		<h3 class='section_title'>
<?php
			if (strlen($r['title']) > 0){
				echo($r['title']);
			}
			else{
				echo($r_album['title']);
			}
?>
			<div id='viewer_close_container'>
				<img id='viewer_close' class='pointer' alt=' ' src='<?=$server?>/img/misc/close.png' onClick='closeViewer();'/>
			</div>
		</h3>
		<div id='viewer_container'>
			<div id='viewer_table'>
				<div class='viewer_row'>
					<div class='viewer_cell' id='cell_image'>
						<div class='entry' id='photo_data'>
							<table>
								<tr>
									<td class='arrow'>
										<img id='viewer_arrow_left' class='viewer_arrow pointer' alt=' ' src='<?=$server?>/img/misc/slid-left.png' onClick='scrollPhoto(-1);'/>
									</td>
									<td id='image' style='background-image:url(<?=$server?>/img/galeria/preview/<?=$r['file']?>);'>
										<!-- <div id='photo_viewer_image_container' style='background-image:url(<?=$server?>/img/galeria/preview/<?=$r['file']?>);'> -->
											<!-- <img id='photo_viewer_image' src='/img/galeria/preview/<?=$r['file']?>'/> -->
										</div>
									</td>
									<td class='arrow'>
										<img id='viewer_arrow_right' class='viewer_arrow pointer' alt=' ' src='<?=$server?>/img/misc/slid-right.png' onClick='scrollPhoto(1);'/>
									</td>
								</tr>
							</table>
							<!-- Arrows -->
							<!--<div id='viewer_controls'>
								<img id='viewer_arrow_left' class='viewer_arrow pointer' alt=' ' src='<?=$server?>/img/misc/slid-left.png' onClick='scrollPhoto(-1);'/>
								<img id='viewer_arrow_right' class='viewer_arrow pointer' alt=' ' src='<?=$server?>/img/misc/slid-right.png' onClick='scrollPhoto(1);'/>
							</div>  #viewer_controls -->
<?php
							if (strlen($r['description']) > 0){
								echo("<span id='photo_description'>$r[description]</span>\n");
							}
							echo("<span id='photo_date'>" . formatDate($r['uploaded'], $lang) . "</span>\n");
							if (strlen($r['username']) > 0){
								echo("<span id='photo_user'>$lng[gallery_user]$r[username]</span>\n");
							}
?>
						</div> <!-- #photo_data -->
					</div>  <!-- #cell_image -->
					<div class='viewer_cell' id='cell_comments'>
						<div id='photo_viewer_details'>
<?php
							$q_comment = mysqli_query($con, "SELECT * FROM photo_comment WHERE photo = $r[id];");
							$comment_count = mysqli_num_rows($q_comment);
?>
							<div class='entry' id='comments_section'/>
								<div id='photo_viewer_comment_counter'>
<?php
									switch ($comment_count){
										case 0:
											echo("<h3 class='entry_title'><meta itemprop='interactionCount' content='0'/>$lng[gallery_comment_count_0]</h3>\n");
											break;
										case 1:
											echo("<h3 class='entry_title'><span itemprop='interactionCount'>1</span> $lng[gallery_comment_count_1]</h3>\n");
											break;
										default:
											echo("<h3 class='entry_title'><span itemprop='interactionCount'>$comment_count</span> $lng[gallery_comment_count_several]</h3>\n");
									}
?>
								</div> <!-- #photo_viewer_comment_counter -->
								<div id='comment_list'>
<?php
									//WARNING: If changes are done here, do the same in comment.php
									while ($r_comment = mysqli_fetch_array($q_comment)){
?>
										<div itemprop='comment' itemscope itemtype='http://schema.org/UserComments' id='comment_<?=$r_comment['id']?>' class='comment'>
											<span itemprop='creator' class='comment_user'><?=$r_comment['username']?></span>
											<span class='comment_date date'><meta itemprop='commentTime' content='<?=$r_comment['isodate']?>'/><?=formatDate($r_comment['dtime'], $lang)?></span>
											<p itemprop='commentText' class='comment_text'><?=$r_comment['text']?></p>
											<hr class='comment_line'/>
										</div>
<?php
									}
?>
								</div> <!-- #comment_list -->
								<!-- Comment form -->
								<div class='comment' id='comment_new'>
									<form id='comment_form' method='post' action='/' onsubmit='event.preventDefault();postComment(<?=$r['id']?>, "<?=$lang?>");'>
										<textarea id='new_comment_text' name='text' maxlength='1800' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);' placeholder='<?=$lng[blog_placeholder_text]?>'></textarea>
										<div id='identification_form'>
											<br/>
											<input id='new_comment_user' name='user' maxlength='50' type='text' placeholder='<?=$lng['blog_placeholder_name']?>' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);'/>
											<input type='submit' value='<?=$lng['blog_send']?>'/>
										</div> <!-- #identification_form -->
									</form>
								</div> <!-- #comment_new -->
							</div> <!-- #comments_section -->
						<!-- </div>  #cell_details -->
<?php
		echo "</div>\n"; //Row
		echo "</div></div>\n"; //Container
		
		stats(-1, 0, "photo", "$r[id]");
	}
?>
