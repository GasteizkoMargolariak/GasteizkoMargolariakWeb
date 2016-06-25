<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
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
		<title>Miembros - Administracion</title>
		<!-- CSS files -->
		<link rel="stylesheet" type="text/css" href="/css/ui.css"/>
		<link rel="stylesheet" type="text/css" href="/css/miembros.css"/>
		<!-- CSS for mobile version -->
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
		<link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/miembros.css"/>
		<!-- Script files -->
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="/script/ui.js"></script>
	</head>
	<body>
		<?php include('../toolbar.php'); ?>
		<div id='content'>
			<div class="section" id="section_table">
				<div class="entry">
					Filtro: <input type="text" onkeyup='populate_table(this.value)'/>
				</div><br/>
				<div class="entry" id="entry_table">
					<table id="member_table">
						<tr>
							<th>Nombre</th>
							<th>Contacto</th>
							<th style='display:none;'>details</th>
							<th>Acciones</th>
						</tr>
						<?php
							$q = mysqli_query($con, "SELECT * FROM member;");
							$i = 0;
							while ($r = mysqli_fetch_array($q)){
								if ($i % 2 == 0)
									echo "<tr class='member_row_even'>\n";
								else
									echo "<tr class='member_row_odd'>\n";
								$i ++;
								echo "<td>$r[lname]";
								if ($r['lname2'] != null)
									echo " $r[lname2]";
								echo ", $r[name]";
								if ($r['alias'] != null)
									echo "<span style='font-style:italic;'> - ($r[alias])</span>";
								echo "</td>\n";
								echo "<td style='text-align:center;'>";
								if ($r['phone'] != null)
									echo "$r[phone]<br/>";
								if ($r['mail'] != null)
									echo "$r[mail]";
								echo "</td>\n";
								echo "<td style='display:none;'>$r[name]$r[lname]$r[lname2]$r[dni],$r[address]</td>\n";
								echo "<td style='text-align:center;'><a href='http://$http_host/miembros/miembro.php?m=$r[id]'>Ficha completa</a><br/><a href=''>Editar</a></td>\n";
								echo "</tr>\n";
							}
						?>
					</table>
				</div>
			</div>
			<div class="section" id="section_table_actions">
				<div class="entry">
					<h3>Acciones</h3>
					<ul>
						<li><a href="<?php echo "http://$http_host/miembros/add/";?>">Anadir miembro</a></li>
						<li><a href="<?php echo "http://$http_host/miembros/add/batch.php";?>">Anadir miembros en lote</a></li>
					</ul>
				</div><br/>
				<div class="entry">
					<h3>Recuerda</h3>
					<p>Estos datos sobre los miembros de Gasteizko Margolariak son confidenciales y estan protegidos bajo la Ley de Proteccion de Datos. Se responsable al utilizarlos.</p>
					<ul>
						<li>No los uses para propositos personales o no relacionados con Gasteizko Margolariak</li>
						<li>No hagas una copia de estos datos</li>
						<li>No difundas estos a terceras personas</li>
						<li>El acceso y la modificacion de estos datos es monitorizado por el sistema</li>
					</ul>
				</div>
			</div>
		</div>
	</body>
</html>
<?php } ?>
