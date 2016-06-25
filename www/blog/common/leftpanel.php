<div id="left_column">
	<div id="search_panel" class="section">
		<h3 id="search_panel_header" class="section_title search_element no_mobile"><?php echo $lng['blog_search'];?></h3>
		<div class="entry" id="search_panel_inputs">
			<form action="#" onSubmit="event.preventDefault();launchSearch('<?php echo $lng['search_field_all']; ?>');">
				<input id="search_panel_input" class="search_element" type="text" name="query" placeholder="<?php echo($lng['blog_search']); ?>"/>
				<input id="search_panel_submit" class="search_element" type="submit" value="<?php echo $lng['blog_search'];?>"/>
			</form>
		</div>
	</div>
	<div id="tag_cloud" class="section desktop">
		<h3 class="section_title"><?php echo $lng['blog_tag_cloud'];?></h3>
		<div class="entry">
			<?php
				//Get the most used tag
				$q_max = mysqli_query($con, "SELECT max(count) AS max FROM (SELECT count(post) AS count FROM post_tag GROUP BY tag) AS t;");
				$r_max = mysqli_fetch_array($q_max);
				$max = $r_max['max'];
				$q_tag = mysqli_query($con, "SELECT tag, count(post) AS count FROM post_tag GROUP BY tag;");
				while($r_tag = mysqli_fetch_array($q_tag)){
					$size = round(60 + 100 * $r_tag['count'] / $max);	//TEST: Relativize
					echo "<a href='http://$http_host/blog/buscar/tag/$r_tag[tag]'><span style='font-size: $size%'>$r_tag[tag]</span></a>\n";
				}
			?>
		</div>
	</div>
</div>
