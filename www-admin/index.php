<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("functions.php");
	$con = startdb();
	if (checkSession($con)){
		header("Location: /main.php");
		exit (-1);
	}
	else{?>
<html>
	<head>
		<meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title>Gasteizko Margolariak - Administracion</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/index.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/index.css"/>
	</head>
	<body>
		<div class='section'>
			<h3 class='section_title'>Credenciales</h3>
			<div class='entry'>
				<form method='post' action='/login.php'>
					<table>
						<tr>
							<td>Usuario:</td>
							<td><input name="user" type="text"/></td>
						</tr>
						<tr>
							<td>Contrasena:</td>
							<td><input name="pass" type="password"/></td>
						</tr>
						<tr>
							<td></td><td><input type="submit" value="Entrar"/></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>
<?php } ?>
