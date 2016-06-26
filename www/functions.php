<?php

	/****************************************************
	* This function is called from almost everywhere at *
	* the beggining of the page. It initializes the     *
	* session variables, connect to the db, enabling    *
	* the variable $con for futher use everywhere in    *
	* the php code, and populates the arrays $user      *
	* and $permission, with info about the user.        *
	* @params:                                          *
	*    mode: (string) Indicates required permissions  *
	*          on database. 'ro' gives read             *
	*          permissions, and 'rw' read and write     *
	*          permissions. Other values will result in *
	*          errors.                                  *
	* @return: (db connection): The connection handler. *
	****************************************************/
	function startdb($mode = 'ro'){
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
		if ($mode == 'ro')
			$con = mysqli_connect($host, $username_ro, $pass_ro, $db_name);
		else if ($mode == 'rw'){
			$con = mysqli_connect($host, $username_rw, $pass_rw, $db_name);
		}
			
		// Check connection
		if (mysqli_connect_errno()){
			error_log("Failed to connect to database: " . mysqli_connect_error());
			return -1;
		}
		
		//Set encoding options
		mysqli_set_charset($con, 'utf-8');
		header('Content-Type: text/html; charset=utf8');
		mysqli_query($con, 'SET NAMES utf8;');
		
		//Return the db connection
		return $con;
	}
	
	/****************************************************
	* This function selects the language the page will  *
	* be displayed inf the page. Several methods are    *
	* used: cookie detection, and browser language      *
	* preferences.                                      *
	* @return: (string): Language code.                 *
	****************************************************/
	function selectLanguage(){
		//Try to read cookie.
		header('Cache-control: private');
		if (isSet($_COOKIE['lang'])){
			$lang = $_COOKIE['lang'];
			if ($lang == 'es' || $lang == 'en' || $lang == 'eu'){
				return $lang;
			}
			else{
				return 'es';
			}
		}
		
		//If no cookie, select from client browser preferences.
		else{
			$available_languages = array("en", "eu", "es");
			$langs = prefered_language($available_languages, $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
			$lang = $langs[0];
			if ($lang != 'es' && $lang != 'en' && $lang != 'eu'){
				return 'es';
			}
			else{
				return $langs[0]; //TODO test this
			}
		}
	}
	
	/****************************************************
	* This function parses the language prefrences of   *
	* the client broser, comparing them to th languages *
	* offered by the site.                              *
	* the variable $con for futher use everywhere in    *
	* the php code, and populates the arrays $user      *
	* and $permission, with info about the user.        *
	* @params:                                          *
	*    available_languages: (string array) Contains   *
	*                         a list of strins offered  *
	*                         by the site.              *
	*    http_accept_language: (string) Raw header with *
	*                          info about client        *
	*                          language preferences.    *
	* @return: (string array) List of the languages     *
	*          offered, sorted by prefference.          *
	****************************************************/
	function prefered_language(array $available_languages, $http_accept_language) {

		$available_languages = array_flip($available_languages);

		$langs;
		preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
		foreach($matches as $match) {

			list($a, $b) = explode('-', $match[1]) + array('', '');
			$value = isset($match[2]) ? (float) $match[2] : 1.0;

			if(isset($available_languages[$match[1]])) {
				$langs[$match[1]] = $value;
				continue;
			}

			if(isset($available_languages[$a])) {
				$langs[$a] = $value - 0.1;
			}

		}
		arsort($langs);

		return $langs;
	}

	/****************************************************
	* This function turns a date string into a human    *
	* readable string, deppending o the specified       * 
	* language.                                         *
	* @params:                                          *
	*    strdate: (string): Date string.                *
	*    lang: (string): Language code (es, en, eu).    *
	*    http_accept_language: (string) Raw header with *
	*                          info about client        *
	*                          language preferences.    *
	*    time: (boolean): Append the time at the end.   *
	* @return: (string array) List of the languages     *
	*          offered, sorted by prefference.          *
	****************************************************/	
	function formatDate($strdate, $lang, $time = true){
		$date = strtotime($strdate);
		$year = date('o', $date);
		$month = date('n', $date);
		$month --;
		$day = date('j', $date);
		$wday = date('N', $date);
		$wday --;
		$hour = date('H', $date);
		$minute = date('i', $date);
		switch ($lang){
			case 'en':
				$week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
				$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
				if ($time){
					$str = "$week[$wday], $months[$month] $day, $year at $hour:$minute";
				}
				else{
					$str = "$week[$wday], $months[$month] $day, $year";
				}
				break;
			case 'eu':
				$week = ['astelehena', 'asteartea', 'asteazkena', 'osteguna', 'ostirala', 'larumbata', 'igandea'];
				$months = ['urtarrilaren', 'otsailaren', 'martxoaren', 'apirilaren', 'maiatzaren', 'ekainaren', 'uztailaren', 'abuztuaren', 'irailaren', 'urriaren', 'azaroaren', 'abenduaren'];
				if ($time){
					$str = $year . "ko $months[$month] $day" . "an, $week[$wday], $hour:$minute";
				}
				else{
					$str = $year . "ko $months[$month] $day" . "an, $week[$wday]";
				}
				break;
			default:
				$week = ['Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado', 'Domingo'];
				$months = ['enero', ' febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
				if ($time){
					$str = "$week[$wday] $day de $months[$month] de $year a las $hour:$minute";
				}
				else{
					$str = "$week[$wday] $day de $months[$month] de $year";
				}
		}
		return $str;
	}
	
	/****************************************************
	* This function turns a date string into a human    *
	* readable string, deppending o the specified       * 
	* language. It is designed to be used for festival  *
	* days only, since it doesnt return the weekday.    *
	* @params:                                          *
	*    strdate: (string): Date string.                *
	*    lang: (string): Language code (es, en, eu).    *
	*    http_accept_language: (string) Raw header with *
	*                          info about client        *
	*                          language preferences.    *
	* @return: (string array) List of the languages     *
	*          offered, sorted by prefference.          *
	****************************************************/	
	function formatFestivalDate($strdate, $lang){
		$date = strtotime($strdate);
		$month = date('n', $date);
		$month --;
		$day = date('j', $date);
		switch ($lang){
			case 'en':
				$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
				$str = "$months[$month] $day";
				break;
			case 'eu':
				$months = ['urtarrilaren', 'otsailaren', 'martxoaren', 'apirilaren', 'maiatzaren', 'ekainaren', 'uztailaren', 'abuztuaren', 'irailaren', 'urriaren', 'azaroaren', 'abenduaren'];
				$str = "$months[$month] $day";
				break;
			default:
				$months = ['enero', ' febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
				$str = "$day de $months[$month]";
		}
		return $str;
	}
	
	/****************************************************
	* This function closes all the opened HTML tags in  *
	* a given string.                                   * 
	*                                                   *
	* @params:                                          *
	*    html: (string): The string with HTML tags      *
 	****************************************************/
	function closeTags($html) {
		preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];
		$len_opened = count($openedtags);
		if (count($closedtags) == $len_opened) {
			return $html;
		}
		$openedtags = array_reverse($openedtags);
		for ($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	}
	
	/****************************************************
	* Text shortener. Given a string, it trims in the   *
	* proximity of the desired streng, ut to the next   * 
	* white character. If indicated, it will append a   *
	* link to the full text.                            *
	*                                                   *
	* @params:                                          *
	*    text: (string): The text to shorten.           *
	*    length: (int): The desired length.             *
	*    linktext: (string): Text fot the link.         *
	*    link: (string): URI of the full text.          *
 	****************************************************/
 	function cutText($text, $length, $linktext, $link){
		if (strlen($text) < $length){
			return $text;
		}
		$cut = substr($text, 0, strpos($text, " ", $length));
		$cut = closeTags($cut);
		if (strlen($cut) == 0){
			$cut = $text;
		}
		if (strlen($text) != strlen($cut)){
			$cut = $cut . "... <a href='$link'>$linktext</a>";
		}
		return $cut;
	}
	
	/****************************************************
	* Increases version value in table settings by one. *
	* Helpfull for the app to know when to perform a    * 
	* full sync. Must be called after every INSERT,     *
	* UPDATE or DELETE query to the database.           *
 	****************************************************/
	function version(){
		$con = startdb('rw');
		mysqli_query($con, 'UPDATE settings SET value = value + 1 WHERE name = "version";');
	}
	
	function ad($con, $lang, $lng){
		//If the user hasn't still see an add on this session
		if (isset($_SESSION['ad']) == false){
			//25% chance of seeing an add
			if (rand (0, 3) == 0){
				//Create an array with weighted values
				$q = mysqli_query($con, "SELECT id, round(ammount/10) AS value FROM sponsor;");
				if (mysqli_num_rows($q) == 0){
					return;
				}
				$total = 0;
				$sponsors = array();
				while ($r = mysqli_fetch_array($q)){
					$times = $r['value'];
					$id = $r['id'];
					for ($i = 0; $i < $times; $i ++) {
						array_push($sponsors, $id);
						$total ++;
					}
				}
				
				//Get a random id from the array
				$id = $sponsors[rand (0, $total - 1)];
				
				//Get sponsor data
				$q = mysqli_query($con, "SELECT name_$lang AS name, text_$lang AS text, image, address_$lang AS address, link, lat, lon FROM sponsor WHERE id = $id;");
				$r = mysqli_fetch_array($q);
				echo("<div id='ad' class='section'>\n");
				echo("<div id='ad_details'>\n");
				echo($lng['ad_title']);
				echo("<img class='pointer' src='/img/misc/slid-close.png' onClick='closeAd();' />\n");
				echo("</div>\n");
				echo("<h3><a target='_blank' href='$r[link]'>$r[name]</a></h3>\n");
				echo("<div class='entry'>\n");
				if (strlen($r['image']) > 0){
					echo("<div id='ad_image_container'>\n");
					echo("<img src='/img/spo/miniature/$r[image]'/>\n");
					echo("</div>\n");
				}
				echo($r['text']);
				echo("<br/><br/><a target='_blank' href='https://www.google.es/maps/@$r[lat],$r[lon],14z'><img id='ad_pinpoint' src='/img/misc/pinpoint.png'\>$r[address]</a>\n");
				echo("</div>\n");
				echo("</div>\n");
				echo("<script type='text/javascript'>showAd();</script>\n");
				
				//Set ad as seen
				$_SESSION['ad'] = 1;
			}
			
		}
	}
?>
	
