<?php
	include("../functions.php");
	$con = startdb();
		
	//Get user entry
	$q = mysqli_query($con, "SELECT lat, lon, dtime, username, manual, action FROM location, user WHERE action = 'report' AND user.id = location.user AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
	if (mysqli_num_rows($q) > 0){
		$r = mysqli_fetch_array($q);
		echo("<reporting>1</reporting>\n");
		echo("<lat>$r[lat]</lat>\n");
		echo("<lon>$r[lon]</lon>\n");
		echo("<user>$r[username]</user>\n");
		echo("<dtime>$r[dtime]</dtime>\n");
		echo("<manual>$r[manual]</manual>\n");
		echo("<action>$r[action]</action>\n");
	}
	else{
		echo("<reporting>0</reporting>\n");
	}
?>
