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
	function getProtocol(){
		if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
			$protocol = 'https://';
		}
		else {
			$protocol = 'http://';
		}
		return $protocol;
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
	* Generates a URL-valid string from a regular one.  *
	*                                                   *
	* @params:                                          *
	*    text: (string): Original string.               *
	* @return: (string): URL-valid string.              *
	****************************************************/
	function permalink($text){
		$unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
							'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
							'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
							'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
							'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
		$str = strtr( $text, $unwanted_array );
		$str = preg_replace('/[^\da-zA-Z ]/i', '', $str);
		$str = str_replace(' ', '-', $str);
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
	* proximity of the desired string, ut to the next   * 
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
	
	/****************************************************
        * Shows an ad at random. It does that one in three  *
        * times, and only one per session (inserts a cookie *
        * to know if the ad has been shown. A sponsor has   *
        * more chances of having his ad popping the more it *
	* contributes with Gasteizko Margolariak.           *
        ****************************************************/
	function ad($con, $lang, $lng){
	$id = -1;
		//If the user hasn't still see an add on this session
		if (isset($_SESSION['ad']) == false){
			//25% chance of seeing an add
			if (rand (0, 3) == 0){
				//Create an array with weighted values
				$q = mysqli_query($con, "SELECT id, round(ammount/10) AS value FROM sponsor;");
				if (mysqli_num_rows($q) == 0){
					return $id;
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
				echo("<h3 class='section_title'><a target='_blank' href='$r[link]'>$r[name]</a></h3>\n");
				echo("<div class='entry'>\n");
				echo("<div id='ad_details'>\n");
				echo($lng['ad_title']);
				echo("<img class='pointer' src='/img/misc/slid-close.png' onClick='closeAd();' />\n");
				echo("</div>\n");
				if (strlen($r['image']) > 0){
					echo("<div id='ad_image_container'>\n");
					echo("<img src='/img/spo/miniature/$r[image]'/>\n");
					echo("</div>\n");
				}
				echo($r['text']);
				if(strlen($r["address"]) > 0){
					echo("<br/><br/><a target='_blank' href='https://www.google.es/maps/@$r[lat],$r[lon],14z'><img id='ad_pinpoint' src='/img/misc/pinpoint.png'\>$r[address]</a>\n");
				}
				echo("</div>\n");
				echo("</div>\n");
				echo("<script type='text/javascript'>showAd();</script>\n");
				
				//Set ad as seen
				$_SESSION['ad'] = 1;
			}
			
		}
		return $id;
	}
	
	// Function to get visitor ip
	function getUserIP(){
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}
		else{
			$ip = $remote;
		}
		return $ip;
	}
	
	function stats($ad, $ad_static, $section, $id){
		
		// Get client data
		$ip = getUserIP();
		$browser_data = get_browser(null, true);
		$os = $browser_data['platform'];
		$browser = $browser_data['browser'];
		$uagent = $browser_data['browser_name_pattern'];
		//If bot, do nothing
		$bot_kw = Array();
		$bot_kw[0] = 'bot';
		$bot_kw[1] = 'spider';
		$bot_kw[2] = 'crawl';
		$bot_kw[3] = '.com';
		$bot_kw[4] = '.ru';
		$bot_kw[5] = 'baidu';
		$bot_kw[6] = 'survey';
		$bot_kw[7] = 'scan';
		$bot_kw[8] = 'feed';
		$bot_kw[9] = 'bing';
		$bot_kw[10] = 'yahoo';
		$bot_kw[11] = 'engine';
		$bot_kw[12] = 'preview';
		$bot_kw[13] = 'checker';
		$bot_kw[14] = 'catalog';
		$bot_kw[15] = 'accelerator';
		$bot_kw[16] = 'python';
		$bot_kw[14] = 'qt';
		$bot_kw[15] = 'webdav';
		$bot_kw[16] = 'http';
		$bot_kw[17] = 'url';
		$bot_kw[18] = 'fake';
		$bot_kw[19] = 'library';
		$bot_kw[20] = 'commerce';
		$bot_kw[21] = 'html';
		$bot_kw[22] = 'fetch';
		
		$i = 0;
		while ($i < sizeof($bot_kw)) {
			if (strpos(strtolower($uagent), $bot_kw[$i]) !== false){
				mysqli_close($con);
				return;
			}
			$i ++;
		}
		
		//Look for a visit with the same IP in the last 30 mins.
		$con = startdb('rw');
		$q = mysqli_query($con, "SELECT stat_visit.id AS visitid FROM stat_view, stat_visit WHERE visit = stat_visit.id AND dtime > DATE_SUB(now(), INTERVAL 30 MINUTE) AND ip = '$ip' AND uagent = '$uagent';");
		if (mysqli_num_rows($q) == 0){
			mysqli_query($con, "INSERT INTO stat_visit (ip, uagent, os, browser) VALUES ('$ip', '$uagent', '$os', '$browser');");
			$q = mysqli_query($con, "SELECT stat_visit.id AS visitid FROM stat_visit WHERE ip = '$ip' AND uagent = '$uagent' ORDER BY stat_visit.id DESC LIMIT 1;");
		}
		$r = mysqli_fetch_array($q);
		$visit = $r['visitid'];
		mysqli_query($con, "INSERT INTO stat_view (visit, section, entry) VALUES ($visit, '$section', '$id');");
		
		//Increase pop ad advertiser count
		if ($ad > 0){
			mysqli_query($con, "UPDATE sponsor SET print = print + 1 WHERE id = $ad;");
		}
		
		if (is_array($ad_static)){
			foreach ($ad_static as &$sponsor) {
				mysqli_query($con, "UPDATE sponsor SET print_static = print_static + 1 WHERE id = $sponsor;");
			}
		}
	}
?>
	
