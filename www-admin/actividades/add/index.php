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
			<div class="section">
				<h3 class="section_title">Nueva actividad</h3>
				<form action="/actividades/add/add.php" maxlength="120" method="post" enctype="multipart/form-data" onsubmit="return validateActivity();">
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
						<div id="content_lang_es">
							<input type="text" id="title_es" name="title_es" placeholder="Titulo"/><br/><br/>
							<textarea name="text_es" id="text_es" placeholder="Texto"></textarea>
							<script>
								CKEDITOR.replace('text_es');
							</script>
						</div>
						<div id="content_lang_eu" style="display:none;">
							<input type="text" id="title_eu" name="title_eu" placeholder="Titulu"/><br/><br/>
							<textarea name="text_eu" id="text_eu" placeholder="Textua"></textarea>
							<script>
								CKEDITOR.replace('text_eu');
							</script>
						</div>
						<div id="content_lang_en" style="display:none;">
							<input type="text" id="title_en" name="title_en" placeholder="Title"/><br/><br/>
							<textarea name="text_en" id="text_en" placeholder="Content"></textarea>
							<script>
								CKEDITOR.replace('text_en');
							</script>
						</div>
					</div> <!--Entry-->
					<div class="entry" id="activity_images">
						<h4>Detalles</h4>
						Ciudad:<input type="text" name="city" length="30" value="Vitoria-Gasteiz"/><br/><br/>
						Fecha:
						<input type="text" length="4" name="year" placeholder="yyyy"/> -
						<input type="text" length="2" name="month" placeholder="mm"/> -
						<input type="text" length="2" name="day" placeholder="dd"/>
						<!--<br/>Hora (opcional):
						<input type="text" length="2" name="hour" placeholder="hh"/> :
						<input type="text" length="2" name="minute" placeholder="mm"/>-->
						<br/><br/>Precio (en blanco si es gratuita):
						<input type="text" length="4" name="price" placeholder="eur"/>
						<br/><br/><label><input type="checkbox" name="inscription" checked/>Se requiere inscripcion</label>
						<br/><br/>Numero de plazas (en blanco para ilimitadas): <input type="text" length="4" name="people" placeholder="#"/>
						
						<h4>Imagenes</h4>
						<ul>
							<li>
								Principal (Sobre el texto y en previsualizaciones)<br/>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_0" src="/img/misc/alpha.png"/>
									<input id="image_0" onchange="previewImage(this, image_preview_0);" type="file" name="image_0" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
								<br/>
							</li>
							<li>
								Secundarias (Bajo el texto)<br/>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_1" src="/img/misc/alpha.png"/>
									<input id="image_1" onchange="previewImage(this, image_preview_1);" type="file" name="image_1" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_2" src="/img/misc/alpha.png"/>
									<input id="image_2" onchange="previewImage(this, image_preview_2);" type="file" name="image_2" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
								<div class="image_upload_container">
									<img class="image_upload_preview" id="image_preview_3" src="/img/misc/alpha.png"/>
									<input id="image_3" onchange="previewImage(this, image_preview_3);" type="file" name="image_3" accept="image/x-png, image/gif, image/jpeg"/>
								</div>
							</li>
						</ul>
					</div>
					<div class="entry" id="activity_options">
						<h4>Ajustes</h4>
						<label><input type="checkbox" name="schedule" checked/>Editar itinerario en la siguiente pantalla</label><br/><br/>
						<label><input type="checkbox" name="visible" checked/>Actividad visible</label><br/><br/>
						<label><input type="checkbox" name="comments" checked/>Permitir comentarios</label><br/><br/>
						<label><input type="checkbox" name="admin"/>Publicar como Gasteizko Margolariak en lugar de mi nombre</label>
						<br/><br/><br/><br/>
						<div id="button_container">
							<input type="button" value="Previsualizar" onClick="alert(validateActivity());"/> <!--TODO-->
							</br>
							<input type="submit" value="Publicar"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
<?php } ?>
