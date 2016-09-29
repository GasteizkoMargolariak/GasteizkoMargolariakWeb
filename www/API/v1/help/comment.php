<?php 
	$http_host = $_SERVER['HTTP_HOST']; 
	$v = 1;
?>

<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title>Gasteizko Margolariak API v<?php echo($v); ?> Documentation</title>
		<link rel="shortcut icon" href="<?php echo "http://$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("../../../css/ui.css"); 
				include("../../../css/index.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("../../../css/m/ui.css"); 
				include("../../../css/m/index.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php include("../../../script/ui.js"); ?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "http://$http_host/API/help/V$v"; ?>"/>
		<link rel="author" href="<?php echo "http://$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "http://$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['index_description'];?>"/>
		<meta property="og:title" content="Gasteizko Margolariak API v<?php echo($v); ?> Documentation"/>
		<meta property="og:url" content="<?php echo "http://$http_host/API/help/V$v"; ?>"/>
		<meta property="og:description" content="Gasteizko Margolariak API v<?php echo($v); ?> Documentation - Index page"/>
		<meta property="og:image" content="<?php echo "http://$http_host/img/logo/logo-api.png";?>"/>
		<meta property="og:site_name" content="Gasteizko Margolariak"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="en"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="Gasteizko Margolariak API v<?php echo($v); ?> Documentation""/>
		<meta name="twitter:description" content="Gasteizko Margolariak API v<?php echo($v); ?> Documentation - Index page"/>
		<meta name="twitter:image" content="<?php echo "http://$http_host/img/logo/logo-api.png";?>"/>
		<meta name="twitter:url" content="<?php echo "http://$http_host/API/help/V$v"; ?>"/>
		<meta name="robots" content="index follow"/>
	</head>
	<body>
		<?php include("toolbar.php"); ?>
		<div id="content">
		</div>
	</body>
</body>
