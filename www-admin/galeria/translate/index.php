<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{?>
<html>
	<head>
		<meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title>Taducir galer&iacute; - Administraci&oacute;n</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/galeria.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/galeria.css"/>
		<!-- Script files -->
		<script type="text/javascript" src="/script/ui.js"></script>
	</head>
	<body>
		<?php include('../../toolbar.php'); ?>
		<div id='content'>
			<div class='section'>
				<h3>Albums</h3>
				<div class='entry'>
					<?php
						$q = mysqli_query($con, "SELECT * FROM album;");
						echo "<table class='translation_table'><tr><th>Nombre</th><th>Ingles</th><th>Euskera</th></tr>\n";
						while ($r = mysqli_fetch_array($q)){
							echo "<tr><td><a href='http://$http_host/galeria/translate/translate.php?id=$r[id]'>$r[title_es]</a></td>";
							$total = 1; //Title is mandatory
							$translated_en = 0;
							$translated_eu = 0;
							
							//Calculate field number
							if ($r['title_en'] != $r['title_es']){
								$translated_en ++;
							}
							if ($r['title_eu'] != $r['title_es']){
								$translated_eu ++;
							}
							if (strlen($r['description_es']) > 0){
								if ($r['description_en'] != $r['description_es']){
									$translated_en ++;
								}
								if ($r['description_eu'] != $r['description_es']){
									$translated_eu ++;
								}
							}
							$q_i = mysqli_query($con, "SELECT title_es, title_en, title_eu, description_es, description_en, description_eu FROM photo, photo_album where photo = id AND album = $r[id]");
							while ($r_i = mysqli_fetch_array($q_i)){
								$total ++;
								if ($r_i['title_en'] != $r_i['title_es']){
									$translated_en ++;
								}
								if ($r_i['title_eu'] != $r_i['title_es']){
									$translated_eu ++;
								}
								if (strlen($r_i['description_es']) > 0){
									$total ++;
									if ($r_i['description_en'] != $r_i['description_es']){
										$translated_en ++;
									}
									if ($r_i['description_eu'] != $r_i['description_es']){
										$translated_eu ++;
									}
								}
							}
							
							//Calculate percents
							echo "<td>" . intval($translated_en * 100 / $total) . "%</td><td>" . intval($translated_eu * 100 / $total) . "%</td></tr>\n";
						}
						echo "</table>\n";
					?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php } ?>
