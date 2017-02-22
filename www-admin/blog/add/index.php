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
		<title>Nuevo post - Administracion</title>
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
			<div class="section">
				<h3 class="section_title">Nuevo post</h3>
				<form action="add.php" maxlength="120" method="post" enctype="multipart/form-data" onsubmit="return validate_post();">
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
						<div id="content_lang_es" class="blog_add_language">
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
					<div class="entry" id="images">
						<h4>Im&aacute;genes</h4>
						<ul>
							<li>
								Principal (Aparecera sobre el texto y en las previsualizaciones)<br/>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_0" src="/img/misc/alpha.png"/><br/><br/>
									<input id="image_0" onchange="preview_image(this, image_preview_0);" type="file" name="image_0" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
							</li>
							<li>
								Secundarias (Bajo el texto)<br/>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_1" src="/img/misc/alpha.png"/><br/><br/>
									<input id="image_1" onchange="preview_image(this, image_preview_1);" type="file" name="image_1" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_2" src="/img/misc/alpha.png"/><br/><br/>
									<input id="image_2" onchange="preview_image(this, image_preview_2);" type="file" name="image_2" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_3" src="/img/misc/alpha.png"/><br/><br/>
									<input id="image_3" onchange="preview_image(this, image_preview_3);" type="file" name="image_3" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
							</li>
						</ul>
					</div>
					<div class="entry" id="settings">
						<h4>Ajustes</h4>
						<label><input type="checkbox" name="visible" checked/>Post visible</label><br/><br/>
						<label><input type="checkbox" name="comments" checked/>Permitir comentarios</label><br/><br/>
						<label><input type="checkbox" name="admin"/>Publicar como Gasteizko Margolariak en lugar de mi nombre</label>
						<br/><br/><br/><br/>
						<div id="add_button_container">
							<input type="button" value="Previsualizar" onClick="alert(validate_post());"/> <!--TODO-->
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
