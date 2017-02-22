<?php
	$http_host = $_SERVER['HTTP_HOST'];
	$default_host = substr($http_host, 0, strpos($http_host, ':'));
	include("../functions.php");
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
		<title>Estadisticas Web - Administracion</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/estadisticas.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/estadisticas.css"/>
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
							<h3 class='section_title'>Visitantes &Uacute;nicos</h3>
							<?php
								$q_total = mysqli_query($con, "SELECT LOW_PRIORITY COUNT(id) AS c FROM stat_visit;");
								$q_week = mysqli_query($con, "SELECT LOW_PRIORITY COUNT(id) AS c FROM stat_visit WHERE id IN (SELECT visit FROM stat_view WHERE dtime > DATE_FORMAT(LAST_DAY(NOW()) - ((7 + WEEKDAY(LAST_DAY(NOW())) - 7) % 7), '%Y-%m-%d'));");
								$q_month = mysqli_query($con, "SELECT LOW_PRIORITY COUNT(id) AS c FROM stat_visit WHERE id IN (SELECT visit FROM stat_view WHERE dtime > LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY);");
								$q_year =  mysqli_query($con, "SELECT LOW_PRIORITY COUNT(id) AS c FROM stat_visit WHERE id IN (SELECT visit FROM stat_view WHERE dtime > DATE_FORMAT(NOW() ,'%Y-01-01'));");
								$r_total = mysqli_fetch_array($q_total);
								$r_week = mysqli_fetch_array($q_week);
								$r_month = mysqli_fetch_array($q_month);
								$r_year = mysqli_fetch_array($q_year);
							?>
							<div class='entry'>
								<ul>
									<li>Totales: <?php echo($r_total['c']); ?> </li>
									<li>Semana: <?php echo($r_week['c']); ?> </li>
									<li>Mes: <?php echo($r_month['c']); ?> </li>
									<li>A&ntilde;o: <?php echo($r_year['c']); ?> </li>
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
