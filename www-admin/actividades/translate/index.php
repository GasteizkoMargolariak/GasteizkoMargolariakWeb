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
		<title>Taducir actividades - Administraci&oacute;n</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/actividades.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/actividades.css"/>
		<!-- Script files -->
		<script type="text/javascript" src="/script/ui.js"></script>
	</head>
	<body>
		<?php include('../../toolbar.php'); ?>
		<div id='content'>
			<div class='section'>
				<h3>Lista de actividades</h3>
				<div class='entry'>
					<?php
						$q = mysqli_query($con, "SELECT id, title_es, title_eu, title_en, text_es, text_en, text_eu, DATE_FORMAT(date, '%b %d, %Y') AS dat FROM activity ORDER BY date;");
						echo "<table class='translation_table'><tr><th>Nombre</th><th>Fecha</th><th>Ingles</th><th>Euskera</th></tr>\n";
						while ($r = mysqli_fetch_array($q)){
							echo "<tr><td><a href='http://$http_host/actividades/translate/translate.php?id=$r[id]'>$r[title_es]</a></td>";
							echo("<td>$r[dat]</td>");
							$total = 2; //Title and text are mandatory
							$translated_en = 0;
							$translated_eu = 0;
							
							//Calculate field number
							if ($r['title_en'] != $r['title_es']){
								$translated_en ++;
							}
							if ($r['title_eu'] != $r['title_es']){
								$translated_eu ++;
							}
							if ($r['text_en'] != $r['text_es']){
								$translated_en ++;
							}
							if ($r['text_eu'] != $r['text_es']){
								$translated_eu ++;
							}
							if (strlen($r['after_es']) > 0){
								$total ++;
								if ($r['after_en'] != $r['after_es']){
									$translated_en ++;
								}
								if ($r['after_eu'] != $r['after_es']){
									$translated_eu ++;
								}
							}
							$q_i = mysqli_query($con, "SELECT * FROM activity_itinerary WHERE activity = $r[id]");
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
