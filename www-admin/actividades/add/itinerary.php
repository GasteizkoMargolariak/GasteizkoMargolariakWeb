<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	//Get activity id
	$id = mysqli_real_escape_string($con, $_GET['id']);
	$q = mysqli_query($con, "SELECT * FROM activity WHERE id = $id;");
	//echo "SELECT * FROM activity WHERE id = $id;";
	if(mysqli_num_rows($q) == 0){
		header("Location: $http_host/actividades/");
		exit (-1);
	}
	else{
		$r = mysqli_fetch_array($q);
	?>
<html>
	<head>
		<meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title>Nueva actividad - Administracion</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/actividades.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/actividades.css"/>
		<!-- Script files -->
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="/script/ui.js"></script>
		<script src="../../ckeditor/ckeditor.js"></script>
	</head>
	<body>
		<?php include('../../toolbar.php'); ?>
		<div id='content'>
			<div class='section'>
				<form action='additinerary.php' method='post'>
					<input type='hidden' name='activity' value='<?php echo $id; ?>'/>
					<table id='itinerary'>
					<?php
						$q_place = mysqli_query($con, "SELECT id, name_es FROM place ORDER BY name_es;");
						$i = 0;
						for ($i = 0; $i < 50; $i ++){
							echo "<tr id='itinerary_row_$i' class='itinerary_row'>\n";
							echo "<td class='itinerary_cell time'>\n";
							echo "<h4>Hora y lugar</h4><table>\n";
							echo "<tr><td>Inicio*</td><td><input type='number' maxlength='2' name='sh_$i' id='sh_$i' onChange='showNextRow($i);'/></td><td>: <input type='number' maxlength='2' name='sm_$i' id='sm_$i' onChange='showNextRow($i);'/><td/></tr>\n";
							echo "<tr><td>Final</td><td><input type='number' maxlength='2' name='eh_$i' id='eh_$i' onChange='showNextRow($i);'/></td><td>: <input type='number' maxlength='2' name='em_$i' id='em_$i' onChange='showNextRow($i);'/><td/></tr>\n";
							echo "</table>\n";
							echo "Lugar*: <select name='place_$i' id='place_$i' onChange='showNextRow($i);'>\n";
							echo "<option value='-1'>SELECCIONA</option>\n";
							mysqli_data_seek($q_place, 0);
							while ($r_place = mysqli_fetch_array($q_place)){
								echo "<option value='$r_place[id]'>$r_place[name_es]</option>\n";
							}
							echo "</select><br/>\n";
							echo "<span id='add_place' class='pointer' onClick='addPlace($i);'>A&ntilde;adir nuevo lugar</span>\n";
							
							echo "</td>\n";
							echo "<td class='itinerary_cell title'>\n";
							echo "<h4>T&iacute;tulo</h4><table>\n";
							echo "<tr><td>Castellano*</td><td><input type='text' name='title_es_$i' id='title_es_$i' onKeyUp='showNextRow($i);'/></td></tr>\n";
							echo "<tr><td>Ingl&eacute;s</td><td><input type='text' name='title_en_$i' id='title_en_$i' onKeyUp='showNextRow($i);'/></td></tr>\n";
							echo "<tr><td>Euskera</td><td><input type='text' name='title_eu_$i' id='title_eu_$i' onKeyUp='showNextRow($i);'/></td></tr>\n";
							echo "</table>\n";
							echo "</td>\n";
							echo "<td class='itinerary_cell text'>\n";
							echo "<h4>Texto castellano*</h4><textarea name='text_es_$i' id='text_es_$i' onKeyUp='showNextRow($i);'/></textarea>\n";
							echo "</td>\n";
							echo "<td class='itinerary_cell text'>\n";
							echo "<h4>Texto ingl&eacute;s</h4><textarea name='text_en_$i' id='text_en_$i' onKeyUp='showNextRow($i);'/></textarea>\n";
							echo "</td>\n";
							echo "<td class='itinerary_cell text'>\n";
							echo "<h4>Texto euskera</h4><textarea name='text_eu_$i' id='text_eu_$i' onKeyUp='showNextRow($i);'/></textarea>\n";
							echo "</td>\n";
							echo "</tr>\n";
						}
					?>
				</table>
				<input type='submit' value='Guardar'/>
			</form>
		</div>
	</body>
</html>
<?php } ?>
