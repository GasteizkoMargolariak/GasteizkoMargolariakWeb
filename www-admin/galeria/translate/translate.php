 
<?php
	$http_host = $_SERVER['HTTP_HOST'];
	$http_standard = substr($_SERVER['HTTP_HOST'], 0, strrpos($_SERVER['HTTP_HOST'], ':'));
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{
		$id = mysqli_real_escape_string($con, $_GET['id']);
		$q = mysqli_query($con, "SELECT * FROM album WHERE id = $id");
		if(mysqli_num_rows($q) == 0){
			header("Location: $http_host/galeria/translate/");
			exit (-1);
		}
		else{
			$r = mysqli_fetch_array($q);
	?>
<html>
	<head>
		<meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title>Taducir <?php echo $r['title_es']; ?> - Administraci&oacute;n</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/galeria.css"/>
		<!-- Script files -->
		
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
				<?php
					echo "<h3>Taducir &#8216;$r[title_es]&#8217;</h3>\n";
					echo "<div id='translation_instructions' class='entry'><h4>C&oacute;mo traducir</h4>\n";
					echo "<ul><li>Rellena los campos de abajo para el idioma correspondiente (columna de en medio para ingl&eacute;s, derecha para euskera)</li>\n";
					echo "<li>No es necesario traducir todos los campos ni todos los idiomas. Traduce solo lo que sepas y quieras.</li>\n";
					echo "<li>Al terminar, recuerda pulsar el boton &#8216;Guardar&#8217; al final de la p&aacute;gina.</li>\n";
					echo "<li>Muchas gracias por tu trabajo!</li></ul>\n";
					echo "</div><br/><br/><h3>Taducir detalles del album</h3>\n";
					echo "<form method='post' action='http://$http_host/galeria/translate/apply.php'>\n";
					echo "<input type='hidden' name='id' value='$r[id]'/>\n";
					echo "<table id='table_translate_languages'><tr>\n";
					
					echo "<td class='translate_language'><div class='entry translation'>\n";
					echo "<h3>Castellano</h4><div class='translate_row_title'>\n<span class='bold'>T&iacute;tulo: </span>$r[title_es]</div>\n";
					echo "<div class='translate_row_description'><span class='bold'>Descripci&oacute;n</span><br/><p><xmp>$r[description_es]</xmp></p></div>\n";
					echo "</div></td>\n";
					
					echo "<td class='translate_language'><div class='entry translation'>\n";
					echo "<h3>Ingl&eacute;s</h4>\n<div class='translate_row_title'><span class='bold'>T&iacute;tulo: </span><input type='text' name='title_en' required value='$r[title_en]'/></div>\n";
					echo "<div class='translate_row_description'><span class='bold'>Descripci&oacute;n</span><br/><textarea  name='description_en' required>$r[description_en]</textarea></div>\n";
					echo "</div></td>\n";
					
					echo "<td class='translate_language'><div class='entry translation'>\n";
					echo "<h3>Euskera</h4><div class='translate_row_title'>\n<span class='bold'>T&iacute;tulo: </span><input required type='text' name='title_eu' required value='$r[title_eu]'/></div>\n";
					echo "<div class='translate_row_description'><span class='bold'>Descripci&oacute;n</span><br/><textarea name='description_eu' required>$r[description_eu]</textarea></div>\n";
					echo "</div></td>\n";
					
					echo "</tr></table>\n";
					$q_p = mysqli_query($con,  "SELECT * FROM photo, photo_album WHERE photo.id = photo_album.photo AND album = $r[id] AND (title_es != '' OR description_es != '');"); //TODO continue here
					if (mysqli_num_rows($q_p) > 0){
						echo "<h3>Taducir fotograf&iacute;as</h3><span>Nota: Solo se muestran fotograf&iacute;as con t&iacute;tulo o descripci&oacute;n en castellano.</span><br/>\n";
						echo "<table id='table_translate_photos'>\n";
						while ($r_p = mysqli_fetch_array($q_p)){
							echo "<tr><td class='image'><img class='translate_pic' src='http://$http_standard/img/galeria/miniature/$r_p[file]'/></td>\n";
							echo "<td class='details'><h4>Castellano</h4><h5>$r_p[title_es]</h5><p>$r_p[description_es]</p></td>\n";
							echo "<td class='details'><h4>Ingl&eacute;s</h4><input required type='text' name='photo_$r_p[id]_title_en' required value='$r_p[title_en]'/><br/><textarea name='photo_$r_p[id]_description_en' required>$r_p[description_en]</textarea></td>\n";
							echo "<td class='details'><h4>Euskera</h4><input required type='text' name='photo_$r_p[id]_title_eu' required value='$r_p[title_eu]'/><br/><textarea name='photo_$r_p[id]_description_eu' required>$r_p[description_eu]</textarea></td></tr>\n";
						}
						echo "</table>\n";
					}
					echo "<div id='translate_controls'><input type='submit' value='Guardar'/><input type='button' value='Cancelar y salir' onClick='exitTranslation();'/></div></form>\n";
				?>
			</div>
		</div>
	</body>
</html>
<?php } } ?>
