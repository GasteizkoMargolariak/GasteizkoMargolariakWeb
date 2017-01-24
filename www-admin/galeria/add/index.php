<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{
?>
<html>
	<head>
		<meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title>Nuevo &aacute;lbum - Administracion</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/galeria.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/galeria.css"/>
		<!-- Script files -->
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="/script/ui.js"></script>
		<script src="../../ckeditor/ckeditor.js"></script>
	</head>
	<body>
		<?php include('../../toolbar.php'); ?>
		<div id='content'>
			<div class="section">
				<h3 class="section_title">Nuevo &aacute;lbum</h3>
				<form action="add.php" maxlength="120" method="post" enctype="multipart/form-data" onsubmit="return validate_album();">
					<div class="entry">
						<div id="lang_tabs">
							<table>
								<tr>
									<td class="pointer lang_tabs_active" id="lang_tab_es" onclick="showLanguage('es');">
										Castellano
									</td>
									<td class="pointer" id="lang_tab_eu" onclick="showLanguage('eu');">
										Euskera
									</td>
									<td class="pointer" id="lang_tab_en" onclick="showLanguage('en');">
										Ingl&eacute;s
									</td>
								</tr>
							</table>
						</div>
						<div id="content_lang_es" class="album_add_language">
							<input type="text" id="title_es" name="title_es" placeholder="Titulo"/><br/><br/>
							<textarea name="text_es" id="text_es" placeholder="Texto"></textarea>
							<script>
								CKEDITOR.replace('text_es');
							</script>
						</div>
						<div id="content_lang_eu" class="blog_add_language" style="display:none;">
							<input type="text" name="title_eu" id="title_eu" placeholder="Titulu"><br/><br/>
							<textarea name="text_eu" id="text_eu" placeholder="Textua"></textarea>
							<script>
								CKEDITOR.replace('text_eu');
							</script>
						</div>
						<div id="content_lang_en" class="blog_add_language" style="display:none;">
							<input type="text" name="title_en" id="title_en" placeholder="Titlu"><br><br/>
							<textarea name="text_en" id="text_en" placeholder="Content"></textarea>
							<script>
								CKEDITOR.replace('text_en');
							</script>
						</div>
					</div> <!--Entry-->
					<div class="entry" id="settings">
						<h4>Ajustes</h4>
						<label><input type="checkbox" name="visible" checked/>&Aacute;lbum visible</label><br/><br/>
						<label><input type="checkbox" name="comments" checked/>Permitir comentarios</label><br/><br/>
						<label><input type="checkbox" name="public" checked/>Permitir que los usuarios suban sus fotos (previa moderaci&oacute;n).</label>
						<label><input type="checkbox" name="populate" checked/>A&ntilde;adir fotos en la siguiente pantalla</label>
						<br/><br/><br/><br/>
						<div id="add_button_container">
							<input type="button" value="Previsualizar" onClick="alert(validate_album());"/> <!--TODO-->
							<br/>
							<input type="submit" value="Publicar"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>

<?php
	}
?>
