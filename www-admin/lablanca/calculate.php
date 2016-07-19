<?php
	$http_host = $_SERVER['HTTP_HOST'];
	$default_host = substr($http_host, 0, strpos($http_host, ':'));
	include("../functions.php");
	$con = startdb();
	if (!checkSession($con)){
		exit (-1);
	}
	else{
		//Get year
		$year = date("Y");
		
		//Get data to calculate
		$section = mysqli_real_escape_string($con, $_GET['section']);
		
		switch ($section){
		
			//Calculate header section. Only the progress window is requires
			case 'header':
				echo "<h4>Paso 1: Cabecera de la p&aacute;gina de fiestas</h4>\n";
// 				echo "<div class='entry status' id='status_header'>\n";
				echo "Estado:\n";
				$q = mysqli_query($con, "SELECT * FROM festival WHERE year = $year;");
				if (mysqli_num_rows($q) == 0){
					echo "<span class='status_red'> No se ha definido una cabecera</span>\n";
				}
				else{
					$r = mysqli_fetch_array($q);
					echo "<ul><li>Texto castellano: <span class='status_green'>OK</span></li>\n<li>Traducci&oacute;n ingl&eacute;s: ";
					if ($r['text_en'] == $r['text_es'] || strlen($r['text_en']) == 0){
						echo "<span class='status_yellow'>NO</span>\n";
					}
					else{
						echo "<span class='status_green'>OK</span>\n";
					}
					echo "</li>\n<li>Traducci&oacute;n euskera: ";
					if ($r['text_eu'] == $r['text_es'] || strlen($r['text_eu']) == 0){
						echo "<span class='status_yellow'>NO</span>\n";
					}
					else{
						echo "<span class='status_green'>OK</span>\n";
					}
					echo "</li>\n<li>Cartel: ";
					if (strlen($r['img']) == 0){
						echo "<span class='status_yellow'>NO</span>\n";
					}
					else{
						echo "<span class='status_green'>OK</span>\n";
					}
					echo "</li></ul>\n";
				}
				break;
				
			//Calculate prices progress section.
			case 'pricesprogress':
				echo "Estado:<ul><li>\n";
				$q = mysqli_query($con, "SELECT * FROM festival_day WHERE year(date) = '$year' AND price > 0;");
				if (mysqli_num_rows($q) != 6){
					echo "<span class='status_red'> No se han establecido precios</span>\n";
				}
				else{
					echo "Precios establecidos: <span class='status_green'>OK</span>\n";
				}
				echo "</li><li>\n";
				$prices_wrong = 0;
				while ($r = mysqli_fetch_array($q)){
					if ($r['price'] <= 0 || $r['price'] > 200){
						$prices_wrong ++;
					}
				}
				if ($prices_wrong == 0){
					echo "Precios v&aacute;lidos: <span class='status_green'>" . (6 - $prices_wrong) . "/6</span>\n";
				}
				else{
					echo "Precios v&aacute;lidos: <span class='status_red'>" . (6 - $prices_wrong) . "/6</span>\n";
				}
				echo "</li><li>\n";
				$q = mysqli_query($con, "SELECT id FROM festival_offer WHERE year = $year AND price > 0;");
				if (mysqli_num_rows($q) > 0){
					echo "N&uacute;mero de ofertas: <span class='status_green'>" . mysqli_num_rows($q) . "</span>\n";
				}
				else{
					echo "N&uacute;mero de ofertas: <span class='status_yellow'>" . mysqli_num_rows($q) . "</span>\n";
				}
				echo "</li></ul>\n";
				break;
		
			//Calculate offers window
			case 'pricesoffers':
				echo "<h4>Ofertas:</h4>\n";
				$q_offer = mysqli_query($con, "SELECT * FROM festival_offer WHERE year = $year");
				if (mysqli_num_rows($q_offer) == 0){
					echo "No se han configurado ofertas<br/>\n";
				}
				else{
					echo "<table id='offers'><tr>";
					echo "<th>Castellano</th><th>Ingl&eacute;s</th><th>Euskera</th>";
					echo "<th>D&iacute;as</th><th>Precio</th><th>Acci&oacute;n</th></tr>\n";
					while ($r_offer = mysqli_fetch_array($q_offer)){
						if ($r_offer['name_en'] == $r_offer['name_es']){
							$en = '';
						}
						else{
							$en = $r_offer['name_en'];
						}
						if ($r_offer['name_eu'] == $r_offer['name_es']){
							$eu = '';
						}
						else{
							$eu = $r_offer['name_eu'];
						}
						if ($r_offer['description_en'] == $r_offer['description_es']){
							$den = '';
						}
						else{
							$den = $r_offer['description_en'];
						}
						if ($r_offer['description_eu'] == $r_offer['description_es']){
							$deu = '';
						}
						else{
							$deu = $r_offer['description_eu'];
						}
						echo "<tr><td class='large'><input type='text' value='$r_offer[name_es]' onchange='updateField(\"festival_offer\", \"name_es\", this.value, $r_offer[id], \"text\", false);'/><br/>\n";
						echo "<textarea onchange='updateField(\"festival_offer\", \"description_es\", this.value, $r_offer[id], \"text\", false);'>$r_offer[description_es]</textarea></td>\n"; //TODO onchange
						echo "<td class='large'><input type='text' value='$en' onchange='updateField(\"festival_offer\", \"name_en\", this.value, $r_offer[id]);'/><br/>\n";
						echo "<textarea onchange='updateField(\"festival_offer\", \"description_en\", this.value, $r_offer[id]);'>$den</textarea></td>\n";
						echo "<td class='large'><input type='text' value='$eu' onchange='updateField(\"festival_offer\", \"name_eu\", this.value, $r_offer[id]);'/><br/>\n";
						echo "<textarea onchange='updateField(\"festival_offer\", \"description_eu\", this.value, $r_offer[id]);'>$deu</textarea></td>\n";
						echo "<td class='small'><input type='number' value='$r_offer[days]' onchange='updateField(\"festival_offer\", \"days\", this.value, $r_offer[id], \"number\", false);'/></td>\n";
						echo "<td class='small'><input type='number' value='$r_offer[price]' onchange='updateField(\"festival_offer\", \"price\", this.value, $r_offer[id], \"number\", false);'/>&euro;</td>\n";
						echo "<td class='small'><input type='button' value='Eliminar' onClick='if(confirm(\"Borrar oferta $r_offer[name_es]?\"))deleteOffer($r_offer[id]);'/></td></tr>"; //TODO onclick
					}
					echo "</table>\n";
				}
				echo "<input type='button' value='A&ntilde;adir nuevo' onClick='newOffer();'/>\n";
				break;
			case 'schedule':
				$day = $_GET('day');
				switch($day){
					case '25':
						$q = mysqli_query($con, "SELECT * FROM festival_event WHERE gm = 1 AND start > str_to_date('$year-07-25 08:00:00', '%Y-%m-%d %T') AND start < str_to_date('$year-07-26 07:59:59', '%Y-%m-%d %T');");
						break;
					case '5':
					
						break;
					case '6':
					
						break;
					case '7':
					
						break;
					case '8':
					
						break;
					case '9':
					
						break;
					case default:
						exit(-1);
				}
				//Load schedule tables
				echo "<table>\n";
				echo "<tr>\n";
				echo "<th></th>"
				echo "<tr/>\n";
				echo "</table>\n";
				break;
		}
	}
?>
