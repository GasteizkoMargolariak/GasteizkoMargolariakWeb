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
				<h3 class="section_title">Modificar itinerario</h3>
				<form action='additinerary.php' method='post'>
					<div class="entry">
						<input type='hidden' name='activity' value='<?php echo $id; ?>'/>
						<table id='itinerary'>
						<?php
							$q_place = mysqli_query($con, "SELECT id, name_es FROM place ORDER BY name_es;");
							$i = 0;
							for ($i = 0; $i < 50; $i ++){
								echo "<tr id='itinerary_row_$i' class='itinerary_row'>\n";
								echo "<td class='itinerary_cell time'>\n";
								echo "<h4>Hora y lugar</h4>\n";
								echo "Inicio*:&nbsp<input type='number' maxlength='2' name='sh_$i' id='sh_$i' onChange='showNextRow($i);'/>:<input type='number' maxlength='2' name='sm_$i' id='sm_$i' onChange='showNextRow($i);'/>\n";
								echo "<br/>Final:&nbsp<input type='number' maxlength='2' name='eh_$i' id='eh_$i' onChange='showNextRow($i);'/>:<input type='number' maxlength='2' name='em_$i' id='em_$i' onChange='showNextRow($i);'/>\n";
								echo "<br/><br/>\n";
								echo "Lugar*: <select name='place_$i' id='place_$i' onChange='showNextRow($i);'>\n";
								echo "<option value='-1'>SELECCIONA</option>\n";
								mysqli_data_seek($q_place, 0);
								while ($r_place = mysqli_fetch_array($q_place)){
									echo "<option value='$r_place[id]'>$r_place[name_es]</option>\n";
								}
								echo "</select><br/>\n";
								echo "<span id='add_place' class='pointer' onClick='addPlace($i);'>A&ntilde;adir nuevo lugar</span>\n";
								echo "</td>\n";
								echo "<td class='itinerary_cell itinerary_cell_content'>\n";
							?>
							<div id='lang_tabs'>
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
									<h4>T&iacute;tulo</h4>
									<input type='text' name='title_es_<?php echo($i); ?>' id='title_es_<?php echo($i); ?>' onKeyUp='showNextRow(<?php echo($i); ?>'/>
									<h4>Texto</h4>
									<textarea name='text_es_<?php echo($i); ?>' id='text_es_<?php echo($i); ?>' onKeyUp='showNextRow(<?php echo($i); ?>);'/></textarea>
								</div>
								<div id="content_lang_eu" style="display:none;">
                                                                        <h4>Titulo</h4>
                                                                        <input type='text' name='title_eu_<?php echo($i); ?>' id='title_eu_<?php echo($i); ?>' onKeyUp='showNextRow(<?php echo($i); ?>'/>
                                                                        <h4>Textua</h4>
                                                                        <textarea name='text_eu_<?php echo($i); ?>' id='text_eu_<?php echo($i); ?>' onKeyUp='showNextRow(<?php echo($i); ?>);'/></textarea>
                                                                </div>
								<div id="content_lang_en" style="display:none;">
                                                                        <h4>Title</h4>
                                                                        <input type='text' name='title_en_<?php echo($i); ?>' id='title_en_<?php echo($i); ?>' onKeyUp='showNextRow(<?php echo($i); ?>'/>
                                                                        <h4>Content</h4>
                                                                        <textarea name='text_en_<?php echo($i); ?>' id='text_en_<?php echo($i); ?>' onKeyUp='showNextRow(<?php echo($i); ?>);'/></textarea>
                                                                </div>
								<?php
									echo("</td>\n");
									echo("</tr>\n");
							}
						?>
					</table>
					<input type='submit' id="save_itinerary"  value='Guardar'/>
				</div> <!--Entry-->
			</form>
		</div>
	</body>
</html>
<?php } ?>
