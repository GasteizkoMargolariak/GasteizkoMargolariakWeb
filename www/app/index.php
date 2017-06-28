<?php
	include("../functions.php");
	$browser_data = get_browser(null, true);
	$os = $browser_data['platform'];

	if (strpos(strtolower($os), 'android') !== false){
		header('Location: https://play.google.com/store/apps/details?id=com.ivalentin.margolariak');
		exit(0);
	}
	elseif (strpos(strtolower($os), 'ios') !== false){
		header('Location: https://itunes.apple.com/us/app/gasteizko-margolariak/id1227846624');
		exit(0);
	}
	else{
	
		session_start();
		#$proto = $_SERVER['SERVER_PROTOCOL'];
		#$proto = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
		$http_host = $_SERVER['HTTP_HOST'];
		$proto = getProtocol();
		$server = "$server";
		
		//Language
		$lang = selectLanguage();
		include("../lang/lang_" . $lang . ".php");

?>

<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?=$lng['index_title']?> - App</title>
		<link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
		<!-- CSS files -->
		<style>
<?php 
			include("../css/ui.css"); 
			include("../css/app.css");
?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
<?php 
			include("../css/m/ui.css"); 
?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php include("../script/ui.js"); ?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?=$server?>/app/"/>
		<link rel="author" href="<?=$server?>"/>
		<link rel="publisher" href="<?=$server?>"/>
		<meta name="description" content="<?=$lng['index_title']?> - App"/>
		<meta property="og:title" content="<?=$lng['index_title']?> - App"/>
		<meta property="og:url" content="<?=$server?>"/>
		<meta property="og:description" content="<?=$lng['index_title']?> - App"/>
		<meta property="og:image" content="<?$server?>/img/logo/logo.png"/>
		<meta property="og:site_name" content="<?=$lng['index_title']?> - App"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?=$lang?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?=$lng['index_title']?> - App"/>
		<meta name="twitter:description" content="<?=$lng['index_title']?> - App"/>
		<meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
		<meta name="twitter:url" content="<?=$server?>/app/"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
			<table>
				<tr>
					<td>
						<a target="_blank" href="https://play.google.com/store/apps/details?id=com.ivalentin.margolariak">
							<img src="<?=$server?>/img/app/android.gif"/>
						</a>
					</td>
					<td>	
						<a target="_blank" href="https://itunes.apple.com/us/app/gasteizko-margolariak/id1227846624">
							<img src="<?=$server?>/img/app/ios.gif"/>
						</a>
					</td>
				</tr>
			</table>
			<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
		</div>
		<?php include("../footer.php"); ?>
	</body>
</html>

<?php
	}
?>
