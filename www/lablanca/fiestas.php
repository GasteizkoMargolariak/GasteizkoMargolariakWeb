<div class='section'>
	<h3><?php echo str_replace('#', $year, $lng['lablanca_title']);?></h3>
	<div class='entry'>
	<?php
		//Some text for the festivals
		$q_f = mysqli_query($con, "SELECT * FROM festival WHERE year = $year");
		if (mysqli_num_rows($q_f) > 0){
			$r_f = mysqli_fetch_array($q_f);
			if (strlen($r_f['img']) > 0){
				echo "<img id='festivals_image' src='http://$http_host/img/festival/$r_f[img]'>\n";
			}
			echo $r_f["text_$lang"] . "\n";
		}
	?>
	</div>
</div>
<div id='content_table'>
	<div class='content_tr'>
		<div class='content_td' id='content_td_schedule'>
			<div class='section' id='schedule'>
				<h3><?php echo $lng['lablanca_schedule']; ?></h3>
					<?php
						$q_days = mysqli_query($con, "SELECT id, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, name_$lang AS name FROM festival_day WHERE year(date) = $year ORDER BY date");
						while ($r_days = mysqli_fetch_array($q_days)){
							echo "<div class='entry'>\n";
							echo "<div onClick='expandDay($r_days[id]);' class='day_title pointer'>\n";
							echo "<h4><img class='slid' src='http://$http_host/img/misc/slid-right.png' id='slid_day_$r_days[id]'/>\n";
							echo formatFestivalDate($r_days['date']) . " - $r_days[name]</h4>\n";
							echo "</div>\n";
							echo "<div class='day_schedule' id='day_schedule_$r_days[id]'>\n";
							$q_sch = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > str_to_date(addtime('$r_days[isodate] 00:00:00', '08:00:00'), '%Y-%m-%d %T') AND start < str_to_date(addtime('$r_days[isodate] 00:00:00', '32:00:00'), '%Y-%m-%d %T') ORDER BY start;");
							if (mysqli_num_rows($q_sch) > 0){
								echo "<table class='schedule'>\n";
								while ($r_sch = mysqli_fetch_array($q_sch)){
									echo "<tr><td><span class='time'>$r_sch[st]</span></td><td class='timeline'><img class='timeline_dot' alt=' ' src='http://$http_host/img/misc/schedule-point.png'/></td>\n";
									echo "<td><span class='title'>$r_sch[title]\n";
									//if (strlen($r_sch['end']) > 0){
									//	echo " ($r_sch[st] - $r_sch[end])\n";
									//}
									echo "</span>\n";
									if (strlen($r_sch["description"]) > 0 && $r_sch["description"] != $r_sch["title"]){
										echo "<br/><span class='description'>$r_sch[description]</span>\n";
									}
									echo "<table class='location'><tr>\n";
									//TODO: td for image, if any
									echo "<td><a target='_blank' href='http://maps.google.com/maps?q=$r_sch[lat],$r_sch[lon]+(My+Point)&z=14&ll=$r_sch[lat],$r_sch[lon]'><img alt=' ' src='http://$http_host/img/misc/pinpoint.png'/></a></td>\n"; 
									//If name and address are the same, show only name
									if ($r_sch['place'] == $r_sch['address']){
										echo "<td>$r_sch[place]</td></tr></table>\n";
									}
									else{
										echo "<td>$r_sch[place] <span class='address'>- $r_sch[address]</span></td></tr></table>\n";
									}
									echo "</td></tr>\n";
								}
								echo "</table>\n";
							}
							echo "</div>\n";
							echo "</div>\n";
						}
					?>
			</div>
		</div>
		<div class='content_td' id='content_td_prices'>
			<div class='section' id='prices'>
				<h3><?php echo $lng['lablanca_prices']; ?></h3>
				<div class='entry'>
					<?php
						$q_days = mysqli_query($con, "SELECT id, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, name_$lang AS name, price FROM festival_day WHERE year(date) = $year ORDER BY date");
						if (mysqli_num_rows($q_days) > 0){
							echo "<h4>$lng[lablanca_prices_days]</h4>\n";
							echo "<table id='table_days'>\n";
							while ($r_days = mysqli_fetch_array($q_days)){
								echo "<tr><td>" . formatFestivalDate($r_days['date']) . "</td>\n";
								echo "<td class='price'>$r_days[price] &euro;</td></tr>\n";
							}
							echo "</table>\n</div>\n<div class='entry'>\n";
							$q_offers = mysqli_query($con, "SELECT id, name_$lang AS name, description_$lang AS description, price FROM festival_offer WHERE year = $year ORDER BY days;");
							if (mysqli_num_rows($q_offers) > 0){
								echo "<h4>$lng[lablanca_prices_offers]</h4>\n";
								echo "<table id='table_offers'>\n";
								while ($r_offers = mysqli_fetch_array($q_offers)){
									echo "<tr><td><span class='name'>$r_offers[name]</span><br/><p class='description'>$r_offers[description]</p></td>\n";
									echo "<td class='price'>$r_offers[price] &euro;</td></tr>\n";
								}
								echo "</table>\n";
							}
						}
					?>
				</div>
			</div>
		</div> <!--TD-->
	</div> <!--TR-->
</div> <!--Table-->
