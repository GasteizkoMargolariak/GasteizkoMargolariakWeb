<?php $http_host = $_SERVER['HTTP_HOST']; ?>
<div id="header" class="desktop">
	<div id="header_content">
		<img src="/img/logo/logo.png"/>
		<div id="header_menu">
			<table>
				<tr>
					<td><a href="<?php echo $proto . $http_host; ?>/"><?php echo $lng['header_home'];?></a></td>
					<td><a href="<?php echo $proto . $http_host; ?>/lablanca/"><?php echo $lng['header_lablanca'];?></a></td>
					<td><a href="<?php echo $proto . $http_host; ?>/actividades/"><?php echo $lng['header_activities'];?></a></td>
					<td><a href="<?php echo $proto . $http_host; ?>/blog/"><?php echo $lng['header_blog'];?></a></td>
					<td><a href="<?php echo $proto . $http_host; ?>/nosotros/"><?php echo $lng['header_whoarewe'];?></a></td>
					<td><a href="<?php echo $proto . $http_host; ?>/galeria/"><?php echo $lng['header_gallery'];?></a></td>
	<!-- 				<td><a href="<?php echo $proto . $http_host; ?>/prensa/"><?php echo $lng['header_press'];?></a></td> -->
<!-- 					<td><a href="<?php echo $proto . $http_host; ?>/unete/"><?php echo $lng['header_join'];?></a></td> -->
				</tr>
			</table>
		</div>
	</div>
</div>
<div id="header_m" class="mobile">
	<img src="/img/logo/logo.png" onClick='toggleMobileMenu();' id='mobile_logo'/>
	<div id="header_menu_m">
		<div id='header_m_title' onClick='openMobileMenu();' class='pointer'><span><img src='<?php echo $proto . $http_host; ?>/img/misc/slid-menu.png'/>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cur_section; ?></span></div>
		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/"><?php echo $lng['header_home'];?></a></div>
		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/lablanca/"><?php echo $lng['header_lablanca'];?></a></div>
		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/actividades/"><?php echo $lng['header_activities'];?></a></div>
		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/blog/"><?php echo $lng['header_blog'];?></a></div>
		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/nosotros/"><?php echo $lng['header_whoarewe'];?></a></div>
		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/galeria/"><?php echo $lng['header_gallery'];?></a></div>
<!-- 		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/prensa/"><?php echo $lng['header_press'];?></a></div> -->
<!-- 		<div class='header_m_link'><a href="<?php echo($proto . $http_host); ?>/unete/"><?php echo $lng['header_join'];?></a></div> -->
		<div id='header_m_slider' onClick='closeMobileMenu();' class='pointer'><span><img src='<?php echo($proto . $http_host); ?>/img/misc/slid-top.png'/></span></div>
	</div><br/><br/>
</div>

