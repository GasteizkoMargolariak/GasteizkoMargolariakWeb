<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{
		$id = mysqli_real_escape_string($con, $_GET['id']);
		$q = mysqli_query($con, "SELECT * FROM post WHERE id = $id");
		if(mysqli_num_rows($q) == 0){
			header("Location: $http_host/blog/translate/");
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
		<link rel="stylesheet" type="text/css" href="/css/blog.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/blog.css"/>
		<!-- Script files -->
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="/script/ui.js"></script>
		<script src="../../ckeditor/ckeditor.js"></script>
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
					echo "<li>Al terminar, recuerda pulsar el boton &#8216;Guardar&#8217; al final de la p&acute;gina.</li>\n";
					echo "<li>Muchas gracias por tu trabajo!</li></ul>\n";
					echo "</div><br/><br/>\n";
					echo "<form method='post' action='http://$http_host/blog/translate/apply.php'>\n";
					echo "<input type='hidden' name='id' value='$r[id]'/>\n";
					echo "<table id='table_translate_languages'><tr>\n";
					
					echo "<td class='translate_language'><div class='entry translation'>\n";
					echo "<h3>Castellano</h4><div class='translate_row_title'>\n<span class='bold'>T&iacute;tulo: </span>$r[title_es]</div>\n";
					echo "<div class='translate_row_description'><span class='bold'>Descripci&oacute;n</span><br/><p>$r[text_es]</p></div>\n";
					echo "</div></td>\n";
					
					echo "<td class='translate_language'><div class='entry translation'>\n";
					echo "<h3>Ingl&eacute;s</h4>\n<div class='translate_row_title'><span class='bold'>T&iacute;tulo: </span><input type='text' name='title_en' required value='$r[title_en]'/></div>\n";
					echo "<div class='translate_row_description'><span class='bold'>Descripci&oacute;n</span><br/><textarea  name='text_en' required>$r[text_en]</textarea></div>\n";
					echo "<script>CKEDITOR.replace('text_en');</script>\n";
					echo "</div></td>\n";
					
					echo "<td class='translate_language'><div class='entry translation'>\n";
					echo "<h3>Euskera</h4><div class='translate_row_title'>\n<span class='bold'>T&iacute;tulo: </span><input required type='text' name='title_eu' required value='$r[title_eu]'/></div>\n";
					echo "<div class='translate_row_description'><span class='bold'>Descripci&oacute;n</span><br/><textarea name='text_eu' required>$r[text_eu]</textarea></div>\n";
					echo "<script>CKEDITOR.replace('text_eu');</script>\n";
					echo "</div></td>\n";
					
					echo "</tr></table><div id='translate_controls'><input type='submit' value='Guardar'/><input type='button' value='Cancelar y salir' onClick='exitTranslation();'/></div></form>\n";
				?>
			</div>
		</div>
	</body>
</html>
<?php } } ?>
