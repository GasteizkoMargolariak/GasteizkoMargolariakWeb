<div id="footer">
	<div id="footer_table">
		<div class="tr">
			<div class="td" id="footer_left">
				<span id="footer_follow" class="desktop"><?php echo $lng['footer_follow']?></span><br/>
				<a title="Facebook" target="_blank" href="https://www.facebook.com/gmargolariak"><img class="footer_social_icon" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/social/facebook.gif" alt="Facebook"/></a>
				<a title="Twitter" target="_blank" href="https://twitter.com/gmargolariak"><img class="footer_social_icon" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/social/twitter.gif" alt="Twitter"/></a>
				<a title="Google+" target="_blank" href="https://plus.google.com/106661466029005469492"><img class="footer_social_icon" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/social/googleplus.gif" alt="Google+"/></a>
				<br class="desktop"/>
				<a title="Youtube" target="_blank" href="https://www.youtube.com/user/GasteizkoMargolariak"><img class="footer_social_icon" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/social/youtube.gif" alt="Youtube"/></a>
				<a title="Instagram" target="_blank" href="https://instagram.com/gmargolariak/"><img class="footer_social_icon" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/social/instagram.gif" alt="nstagram"/></a>
<!--  				<a title="RSS" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/rss.xml"><img class="footer_social_icon" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/social/rss.gif" alt="RSS"/></a> -->
				<br/><span class='desktop'><?php echo($lng['footer_info']); ?></span>
			</div>
			<div class="td" id="footer_center">
				<br class="mobile"/><br class="mobile"/>
				<?php
					$q = mysqli_query($con, "SELECT id, name_$lang AS name, image, link FROM sponsor WHERE image != '' ORDER BY ammount DESC;");
					$ad_static = Array();
					if (mysqli_num_rows($q) > 0){
						echo("<span class='desktop'>$lng[footer_sponsors]</span></br class='desktop'>\n");
						$idx = 0;
						while ($r = mysqli_fetch_array($q)){
							
							array_push($ad_static, $r['id']);
						
							if ($idx == 0){
								echo("<a target='_blank' href='$r[link]'><span class='desktop'>$r[name]</span><img src='/img/spo/miniature/$r[image]'/></a>");
							}
							elseif ($idx == 1){
								echo("&nbsp;&nbsp;&nbsp;<a target='_blank' href='$r[link]'><img src='/img/spo/thumb/$r[image]'/><span class='desktop'>$r[name]</span></a></br class='desktop'>");
							}
							else{
								echo("<a target='_blank' href='$r[link]'><img src='/img/spo/thumb/$r[image]'/></a>");
							}
							$idx ++;
						}
						
					}
				?>
				<br class="mobile"/><br class="mobile"/><br class="mobile"/>
			</div>
			<div  class="td" id="footer_right" class="desktop">
				<a href="<?php echo "http://$_SERVER[HTTP_HOST]/ayuda/";?>"><?php echo $lng['footer_help']; ?></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo "http://$_SERVER[HTTP_HOST]/ayuda/#section_privacy";?>"><?php echo $lng['footer_privacy'];?></a>
				<br/><br class='mobile'/>
				<img class='lang' alt='Espanol' src='/img/lang/es.gif' onClick='changeLanguage("es", "<?php echo $http_host; ?>");'/>
				<img class='lang' alt='English' src='/img/lang/en.gif' onClick='changeLanguage("en", "<?php echo $http_host; ?>");'/>
				<img class='lang' alt='Euskara' src='/img/lang/eu.gif' onClick='changeLanguage("eu", "<?php echo $http_host; ?>");'/>
				<br class='desktop'/>
				<a title="Google Play" href="https://play.google.com/store/apps/details?id=com.ivalentin.margolariak"><img class='app' alt="<?php echo $lng['footer_app_google']; ?>" src='/img/app/android.gif' /></a>
			</div>
		</div>
	</div>
</div>
<?php
	//header('Cache-control: private');
	if (isSet($_COOKIE['cookie']) == false){
?>
<div id='cookie_popup'>
	<span id='message'><?php echo $lng['cookie_message']; ?></span><br/><br/>
	<span class='button pointer' style="cursor:pointer" id='button_ok'><a href="#" onClick="dismissCookiePopUp('<?php echo $http_host; ?>', false);" ><?php echo $lng['cookie_ok']; ?></a></span>
	<span class='button pointer' style="cursor:pointer" id='button_more'><a href="#" onClick="dismissCookiePopUp('<?php echo $http_host; ?>', true);" ><?php echo $lng['cookie_more']; ?></a></span>
</div>
<?php
}
?>
