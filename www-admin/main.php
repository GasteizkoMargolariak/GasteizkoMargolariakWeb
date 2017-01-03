<?php
	$http_host = $_SERVER['HTTP_HOST'];
	$default_host = substr($http_host, 0, strpos($http_host, ':'));
	include("functions.php");
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
		<title>Gasteizko Margolariak - Administracion</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/main.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/main.css"/>
		<!-- Script files -->
		<script type="text/javascript" src="/script/ui.js"></script>
	</head>
	<body>
		<?php include('toolbar.php'); ?>
		<div id='content'>
			<div id='section_table'>
				<div class='section_row'>
					<div class='section_cell'>
						<div class='section'>
							<h3 class='section_title'>Blog</h3>
							<div class='entry'>
								<ul>
									<li><a href='/blog/add/'>Nuevo post</a></li>
									<li><a href='/blog/'>Gestionar posts</a></li>
									<li><a href='/blog/moderate/'>Moderar comentarios</a></li>
									<li><a href='/blog/translate/'>Traducir posts</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class='section_cell'>
						<div class='section'>
							<h3 class='section_title'>Actividades</h3>
							<div class='entry'>
								<ul>
									<li><a href='/actividades/add/'>Nueva actividad</a></li>
									<li><a href='/actividades/'>Gestionar actividades</a></li>
									<li><a href='/actividades/moderate/'>Moderar comentarios</a></li>
									<li><a href='/actividades/translate/'>Traducir actividades</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class='section_row'>
					<div class='section_cell'>
						<div class='section'>
							<h3 class='section_title'>Galeria</h3>
							<div class='entry'>
								<ul>
									<li><a href='/galeria/add/'>Crear album</a></li>
									<li><a href='/galeria/'>Gestionar albums</a></li>
									<li><a href='/galeria/upload/'>Subir fotos</a></li>
									<li><a href='/galeria/moderate/'>Moderar comentarios</a></li>
									<li><a href='/galeria/translate/'>Traducir galeria</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class='section_cell'>
						<div class='section'>
							<h3 class='section_title'>Fiestas</h3>
							<div class='entry'>
								<ul>
									<li><a href='/lablanca/'>Preparar las fiestas</a></li>
									<li><a href='/lablanca/prices.php'>Gestionar precios</a></li>
									<li><a href='/lablanca/schedule.php'>Gestionar programa</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class='section_row'>
					<div class='section_cell'>
						<div class='section'>
							<h3 class='section_title'>Miembros</h3>
							<div class='entry'>
								<ul>
									<li><a href='/miembros/'>Consultar y buscar</a></li>
									<li><a href='/miembros/add/'>Anadir miembros</a></li>
									<li><a href='/miembros/editable.php'>Gestionar lista</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class='section_cell'>
						<div class='section'>
							<h3 class='section_title'>Estadisticas</h3>
							<div class='entry'>
								<ul>
									<li><a href='/estadisticas/web.php'>Estadisticas Web</a></li>
									<li><a href='/estadisticas/app.php'>Estadisticas Apps</a></li>
									<li><a href='/estadisticas/miembros'>Estadisticas miembros</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class='section_row'>
					<div class='section_cell'>
						<div class='section'>
							<h3 class='section_title'>Ajustes</h3>
							<div class='entry'>
								<ul>
									<li><a href=''>Cambiar ajustes</a></li>
									<li><a href=''>Gestionar usuarios</a></li>
									<li><a href=''>Salir</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php } ?>
