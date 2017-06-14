 <?php
		
	function startdb(){
		//Include the db configuration file. It's somehow like this
		/*
		<?php
			$host = 'XXXX';
			$db_name = 'XXXX';
			$username_ro = 'XXXX';
			$username_rw = 'XXXX';
			$pass_ro = 'XXXX';
			$pass_rw = 'XXXX';
		?>
		*/
		include('.htpasswd');
		
		//Connect to to database
		$con = mysqli_connect($host, $username_rw, $pass_rw, $db_name);
		
		//Set encoding options
		mysqli_set_charset($con, 'utf-8');
		header('Content-Type: text/html; charset=utf8');
		mysqli_query($con, 'SET NAMES utf8;');
		
		//Return the db connection
		return $con;
	}	
	
?>

<html>
	<head>
		<style>
			@media print {
				div#header, div.day, div.meetings{
					page-break-after: always;
				}
				body{
					background: #ffffff;
					filter: initial;
					font-size: 80%;
					color: #ff0000;
				}
			}
			@font-face{
				font-family: font;
				src: url('/font/FreeSans.ttf')
			}
			body{
				font-family:		font;
				margin:				2em;
				/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#b5d3e5+0,e5f4ff+10,e5f4ff+90,b5d3e5+100 */
				/*background: #b5d3e5; */
				/*background: -moz-linear-gradient(left, #b5d3e5 0%, #e5f4ff 10%, #e5f4ff 90%, #b5d3e5 100%);*/ /* FF3.6-15 */
				/*background: -webkit-linear-gradient(left, #b5d3e5 0%,#e5f4ff 10%,#e5f4ff 90%,#b5d3e5 100%);*/ /* Chrome10-25,Safari5.1-6 */
				/*background: linear-gradient(to right, #b5d3e5 0%,#e5f4ff 10%,#e5f4ff 90%,#b5d3e5 100%);*/ /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
				/*filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5d3e5', endColorstr='#b5d3e5',GradientType=1 );(/ /* IE6-9 */
			}
			div#header{
				width: 100%;
				text-align:	center;
				margin-top: 18em;
			}
			div#header div#title{
				display: inline;
				vertical-align: middle;
				width: 90%;
				margin-top: 2em;
				background-color: #0078ff;
				color: #ffffff;
				border-top-right-radius: 0.5em;
				border-bottom-right-radius: 0.5em;
				border: 0.3em solid #0045cc;
				padding: 4em 4em 4em 0em;
			}
			div#header div#title img{
				display: inline-block;
				/*width: 6em;*/
				/*height: 6em;*/
				vertical-align: middle;
				margin: -1em 1em -1em -2em;
				-webkit-filter: drop-shadow(0 0 0.4em #dde);
				filter: drop-shadow(0 0 0.4em #dde);
				border: 0.1em solid #000011;
				border-radius: 100%;
				-ms-filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=0, OffY=0, Color='#dde')";
				filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=0, OffY=0, Color='#dde')";
			}
			div#header div#title h2{
				display: inline-block;
				font-size: 5em;
				margin: 0.5em;
				vertical-align: middle;
			}
			div#header h4{
				display: inline-block;
				margin: 1em auto 2em auto;
				padding: 4em 2em 7em 2em;
				color: #ffffff;
                font-weight: bold;
                font-size: 4em;
				text-shadow: -0.02em 0 black, 0 0.02em black, 0.02em 0 black, 0 -0.02em #0045cc;
				background: -moz-radial-gradient(center, ellipse cover, rgba(0,120,255,1) 10%, rgba(255,255,255,0) 67%, rgba(255,255,255,0) 100%); /* ff3.6+ */
				background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(10%, rgba(0,120,255,1)), color-stop(67%, rgba(255,255,255,0)), color-stop(100%, rgba(255,255,255,0))); /* safari4+,chrome */
				background:-webkit-radial-gradient(center, ellipse cover, rgba(0,120,255,1) 10%, rgba(255,255,255,0) 67%, rgba(255,255,255,0) 100%); /* safari5.1+,chrome10+ */
				background: -o-radial-gradient(center, ellipse cover, rgba(0,120,255,1) 10%, rgba(255,255,255,0) 67%, rgba(255,255,255,0) 100%); /* opera 11.10+ */
				background: -ms-radial-gradient(center, ellipse cover, rgba(0,120,255,1) 10%, rgba(255,255,255,0) 67%, rgba(255,255,255,0) 100%); /* ie10+ */
				background:radial-gradient(ellipse at center, rgba(0,120,255,1) 10%, rgba(255,255,255,0) 67%, rgba(255,255,255,0) 100%); /* w3c */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0078ff', endColorstr='#ffffff',GradientType=1 ); /* ie6-9 */ 
            }
			div#header h3{
				color: #ffffff;
				font-weight: bold;
				font-size: 5em;
				text-shadow: -0.04em 0 #000033, 0 0.04em #000033, 0.04em 0 #000033, 0 -0.04em #000033;
                display: inline-block;
                margin: -8em auto 2em auto;
			}
			div#header span#warning{
                color: #ff0000;
                font-weight: bold;
                font-size: 1.5em;
                text-shadow: -0.04em 0 #000033, 0 0.04em #000033, 0.04em 0 #000033, 0 -0.04em #000033;
                display: inline-block;
                margin: -12em -7em 2em 7em;
				-ms-transform: rotate(-30deg); /* IE 9 */
				-webkit-transform: rotate(-30deg); /* Chrome, Safari, Opera */
				transform: rotate(-30deg);
            }
			div#header table#contact{
				color: #0056cc;
				font-weight: bold;
				margin: auto 0 auto auto;
				/*text-shadow: -0.02em 0 #000000, 0 0.02em #000000, 0.02em 0 #000000, 0 -0.02em #000000;*/
				/*text-shadow: -0.08em 0.3em black, 0.3em 0.08em black, 0.08em 0.3em black, 0.3em -0.08em #black;*/
			}
			div#header table#contact img{
				height: 2em;
				width: 2em;
			}

			div.tarifas table td{
				vertical_align: middle;
				border-top: 0.07em solid #999999;
				padding: 1em;
			}

			div.tarifas table td.price{
				text-align: right;
				width: 4em;
			}
			div.tarifas table td.info{
				padding-left: 4em;
				color:initial;
				text-align: center;
			}
			div.tarifas table td.warning{
				padding-left: 4em;
				color: red;
				font-weight: bold;
				text-align: center;
			}

			div.meetings td{
				color: initial;
				padding: 1em;
			}


			div.meetings table.location td.pinpoint{
				width: 2em;
			}

			div.meetings table.location img{
                margin-left: 1em;
                margin-right: 0.5em;
            }
            div.meetings table.location span.place{
                color: #444444;
                font-size: 0.9em;
            }
            div.meetings span.address{
                color: #666666;
                margin-left: 0.5em;
                font-style: italic;
                font-size: 0.8em;
			}

			div.day,div.tarifas, div.meetings{
				border-left: 0.1em solid #000011;
				border-right: 0.1em solid #000011;
				border-bottom: 0.1em solid #000011;
				border-top-right-radius: 1em;
				border-top-left-radius: 1em;
				max-width:	80em;
				margin: 2em auto 2em auto;
				padding: 0 0 0.4em 0;
				background-color: #0078ff;
				-webkit-filter: drop-shadow(0 0 1em #0078ff);
				filter: drop-shadow(0 0 1em #0078ff);
				-ms-filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=0, OffY=0, Color='#000011')";
				filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=0, OffY=0, Color='#000011')";
			}
			div.day h3,div.tarifas h3, div.meetings h3{
				font-size: 110%;
				font-style: bold;
				color: #ffffff;
				background-color: #0078ff;
				padding: 0.5em 2em 0.1em 1.5em;
				border-top-left-radius: 0.7em;
				border-top-right-radius: 0.7em;
				border: 0.1em solid #000011;
				margin: 0;
			}
			div.day table.day_content, div.tarifas table, div.meetings table{
				width:	100%;
				/*border-spacing: 10px;*/
				border-collapse: collapse;
				background-color: #ffffff;
				margin: 0;
			}
			div.day table.day_content td{
				padding: 0;
			}
			div.day table.day_content td.time{
				width: 3em;
				text-align: right;
				color: #275573;
				font-size: 1.2em;
				font-weight: bold;
				padding: 0.3em 0.5em 0.3em 0.3em;
				vertical-align: top;
				border-top: 0.07em solid #999999;
			}
			div.day table.day_content td.timeline{
				text-align:center;
				border-left: 0.2em dotted #999999;
				border-top: 0.07em solid #999999;
				vertical-align: top;
			}
			div.day table.day_content td.timeline img{
				width: 1.1em;
				height: 1.1em;
				margin: 0.4em -1.4em 0 -1.4em;
			}
			div.day table.day_content td.info{
				border-top: 0.07em solid #999999;
				vertical-align: top;
				padding: 0.3em 0.5em 0.3em 0.8em;
			}
			div.day table.day_content td.info h4, div.tarifas table td h4, div.meetings table td h4{
				color: #275573;
				/*letter-spacing: 0.07em;*/
				font-size: 1.2em;
				font-weight: bold;
				margin:	0 0 0.2em 0em;
			}
			div.day table.day_content td.info p, div.tarifas table td span{
				font-style: italic;
				/*letter-spacing: 0.07em;*/
				color: #222222;
				font-size: 1em;
				margin: 0 1em 0.5em 0.3em;
			}
			div.day table.day_content td.info table.location img{
				margin-left: 1em;
				margin-right: 0.5em;
			}
			div.day table.day_content td.info table.location span.place{
				color: #444444;
				font-size: 0.9em;
			}
			div.day table.day_content td.info table.location span.address{
				color: #666666;
				margin-left: 0.5em;
				font-style: italic;
				font-size: 0.8em;
			}
		</style>
	</head>
	<body>
<?php
		$con = startdb();
	
		$lang = strtolower($_GET['l']);
		if ($lang != 'es' && $lang!= 'en' && $lang != 'eu'){
			$lang = 'es';
		}
		
		// TODO: Get current year
		$year = 2017;
?>
		<div id='header'>
			<div id='title'>
				<img src='https://margolariak.com/img/logo/GasetizkoMargolariak.png' />
				<h2>Gasteizko Margolariak</h2>
			</div>
<?php
			switch($lang){
				case 'eu':
					$title = "Andre Maria Zuriaren jaiak egunetarako";
					$warning = "";//"Behin-behineko !!";
					break;
				case 'en':
					$title = "La Blanca Festivals schedule.";
					$warning = "";//"Provisional !!";
					break;
				case 'es':
					$title = "Programa Margolari<br/>Fiestas de La Blanca";
					$warning = "";//"¡¡ Provisional !!";
					break;
			}
?>
			<br/>
			<h4><?=$title?></h4>
			<br/>
			<h3><?=$year?></h3>
			<br/>
			<span id="warning"><?=$warning?></span>
			<br/><br/><br/><br/><br/><br/><br/>
			<table id='contact'>
				<tr>
					<td>
						<img src='https://margolariak.com/img/social/whatsapp.png'/>
					</td>
					<td>
						637 14 03 71
					</td>
				</tr>
				<tr>
					<td>
						<img src='https://margolariak.com/img/social/mail.png'/>
					</td>
					<td>
						gasteizko@margolariak.com
					</td>
				</tr>
				<tr>
					<td>
						<img src='https://margolariak.com/img/social/twitter.png'/>
					</td>
					<td>
						@gmargolariak
					</td>
				</tr>
			</table>
		</div>
		<br/><br/>
		<div class='tarifas'>
<?php
		$title = "";
            switch($lang){
                case 'es':
					$title = "Tarifas y ofertas";
					break;
				case 'en':
					$title = "Prices and offers";
					break;
				case 'eu':
					$title = "Prezioak eta eskaintzak";
			}
?>
			<h3><?=$title?></h3>
				<table>
<?php
					$q_day = mysqli_query($con, "SELECT id, name_$lang AS name, DATE_FORMAT(date, '%e') AS day, DATE_FORMAT(date, '%c') AS month, price FROM festival_day WHERE date >= str_to_date('$year-00-00', '%Y-%m-%d') AND date <= str_to_date('$year-12-31', '%Y-%m-%d') ORDER BY date;");
					while ($r = mysqli_fetch_array($q_day)){
						$day = $r['day'];
						$month_n = $r['month'];
						$title = "";
						switch($lang){
							case 'es':
								if ($month_n == '8'){
									$title = "$day de agosto";
								}
								else{
									$title = "$day de julio";
								}
								break;
							case 'en':
								if ($month_n == '8'){
									$title = "August " . $day . "th";
								}
								else{
									$title = "July " . $day . "th";
								}
								break;
							case 'eu':
								if ($month_n == '8'){
									$title = "Abustuaren " . $day . "a";
								}
								else{
									$title = "Uztailaren " . $day . "a";
								}
								break;
						}
?>
						<tr>
							<td>
								<h4><?=$title?> - <?=$r['name']?></h4>
							</td>
							<td class='price'>
								<h4><?=$r['price']?> €</h4>
							</td>
						</tr>
<?php
					}
					$q_offer = mysqli_query($con, "SELECT name_$lang AS name, description_$lang AS description, price FROM festival_offer WHERE year = $year;");
					while ($r = mysqli_fetch_array($q_offer)){
?>
						<tr>
							<td>
								<h4><?=$r['name']?></h4>
								<span><?=$r['description']?></span>
							</td>
							<td class='price'>
								<h4><?=$r['price']?> €</h4>
							</td>
						</tr>
<?php
					}
?>
					<tr>
						<td class='info'>
							Para apuntarte, solo tienes que hacer el ingreso correspondiente en la cuenta de Gasteizko Margolariak 
							<br/><br/>
							ES91 3035 0012 71 0120111936 de la Caja Laboral
							<br/><br/>
							indicando tu nombre y apellidos en el concepto. Si coges d&iacute;as sueltos o el pack de 3 d&iacute;as, deber&aacute;s indicar adem&aacute;s los d&iacute;as.
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td class='warning'>
							¡Importante! El &uacute;ltimo d&iacute;a para realizar los ingresos es el 15 de julio.
						</td>
						<td>
						</td>
					</tr>
				</table>
		</div>
		<br/><br/><br/><br/>
		<div class='meetings'>
<?php
			$title = "";
			switch($lang){
				case 'es':
					$title = "Reuniones";
					break;
				case 'en':
					$title = "Meetings";
					break;
				case 'eu':
					$title = "Bilerak";
				}
?>
            <h3><?=$title?></h3>
			<table>
				<tr>
					<td>
						Antes de Fiestas, se organizar&aacute;n dos reuniones informativas, donde adem&aacute;s podr&aacute;s recoger las pulseras de control, parches, pines...
					</td>
				</tr>
				<tr>
					<td>
						<h4>Domingos 9 y 23 de julio, 20:00</h4>
						<table class="location">
								<tr>
									<td class='pinpoint'><img src="https://margolariak.com/img/misc/pinpoint.png"></td>
									<td>
										<span class="place">Café Prado</span>
										<br>
										<span class="address">C/. Felicia Olave, S/N</span>
									</td>
								</tr>
							</table>
					</td>
				</tr>

			</table>
		</div>
<?php
		// Gey day list
		$q = mysqli_query($con, "SELECT id, name_$lang AS name, DATE_FORMAT(date, '%e') AS day, DATE_FORMAT(date, '%c') AS month FROM festival_day WHERE date >= str_to_date('$year-00-00', '%Y-%m-%d') AND date <= str_to_date('$year-12-31', '%Y-%m-%d') UNION SELECT 0, '' AS name, 10 AS day, 8 AS month FROM dual;");
		while ($r = mysqli_fetch_array($q)){
			$day = $r['day'];
			$month_n = $r['month'];
			$title = "";
			switch($lang){
				case 'es':
					if ($month_n == '8'){
						$title = "$day de agosto";
					}
					else{
						$title = "$day de julio";
					}
					break;
				case 'en':
					if ($month_n == '8'){
						$title = "August " . $day . "th";
					}
					else{
						$title = "July " . $day . "th";
					}
					break;
				case 'eu':
					if ($month_n == '8'){
						$title = "Abustuaren " . $day . "a";
					}
					else{
						$title = "Uztailaren " . $day . "a";
					}
					break;
			}
			if (strlen($r['name']) > 0){
				$title = "$title - $r[name]";
			}

?>
			<br/><br/>
			<div class='day'>
				<h3><?=$title?></h3>
				<table class='day_content'>
<?php
				$s_date = date_create_from_format('Y-m-d H:i', "$year-0" . $month_n . "-$day 07:00");
				$e_date = date_create_from_format('Y-m-d H:i', "$year-0" . $month_n . "-$day 06:59");
				$e_date = date_add($e_date, date_interval_create_from_date_string('1 days'));
				$s_date = date_format($s_date, 'Y-m-d H:i');
				$e_date = date_format($e_date, 'Y-m-d H:i');
				$q_e = mysqli_query($con, "SELECT festival_event.id AS id, title_$lang AS title, description_$lang AS description, name_$lang AS place, address_$lang AS address, DATE_FORMAT(start, '%k:%i') AS time FROM festival_event, place WHERE place.id = place AND gm = 1 AND start >= str_to_date('$s_date', '%Y-%m-%d %H:%i') AND start <= str_to_date('$e_date', '%Y-%m-%d %H:%i') ORDER BY start;");
				while ($r_e = mysqli_fetch_array($q_e)){
?>
					<tr>
						<td class='time'><?=$r_e['time']?></td>
						<td class='timeline'>
							<img src='https://margolariak.com/img/misc/schedule-point.png'>
						</td>
						<td class='info'>
							<h4><?=$r_e['title']?></h4>
							<p><?=$r_e['description']?></p>
							<table class='location'>
								<tr>
									<td><img src='https://margolariak.com/img/misc/pinpoint.png'/></td>
									<td>
										<span class='place'><?=$r_e['place']?></span>
										<br/>
										<span class='address'><?=$r_e['address']?></span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
<?php
				}
?>
			
				</table> <!-- .day_content -->
<?php			
				//}
?>
			</div> <!-- .day -->
<?php
		}
?>
	</body>
</html>
