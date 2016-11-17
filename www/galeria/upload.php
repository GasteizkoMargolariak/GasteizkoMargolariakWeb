<?php
	session_start();
	$http_host = $_SERVER['HTTP_HOST'];
	include("../functions.php");
	$con = startdb();
	$proto = getProtocol();
	
	//Language
	$lang = selectLanguage();
	include("../lang/lang_" . $lang . ".php");
	
	$cur_section = $lng['section_gallery'];
	
	//TODO: Set metadata
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<title><?php echo $lng['gallery_title'];?></title>
		<link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
		<!-- CSS files -->
		<style>
			<?php 
				include("../css/ui.css"); 
				include("../css/galeria.css");
			?>
		</style>
		<!-- CSS for mobile version -->
		<style media="(max-width : 990px)">
			<?php 
				include("../css/m/ui.css"); 
				include("../css/m/galeria.css");
			?>
		</style>
		<!-- Script files -->
		<script type="text/javascript">
			<?php
				include("../script/ui.js");
				include("../script/galeria.js");
			?>
		</script>
		<!-- Meta tags -->
		<link rel="canonical" href="<?php echo "$proto$http_host"; ?>"/>
		<link rel="author" href="<?php echo "$proto$http_host"; ?>"/>
		<link rel="publisher" href="<?php echo "$proto$http_host"; ?>"/>
		<meta name="description" content="<?php echo $lng['gallery_description'];?>"/>
		<meta property="og:title" content="<?php echo $lng['gallery_title'];?>"/>
		<meta property="og:url" content="<?php echo "$proto$http_host"; ?>"/>
		<meta property="og:description" content="<?php echo $lng['gallery_description'];?>"/>
		<meta property="og:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta property="og:site_name" content="<?php echo $lng['gallery_title'];?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="twitter:title" content="<?php echo $lng['gallery_title'];?>"/>
		<meta name="twitter:description" content="<?php echo $lng['gallery_description'];?>"/>
		<meta name="twitter:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
		<meta name="twitter:url" content="<?php echo"$proto$http_host"; ?>"/>
		<meta name="robots" content="noindex nofollow"/>
	</head>
	<body>
		<?php include("../header.php"); ?>
		<div id="content">
			<form onSubmit="event.preventDefault(); submitPhotos();">
				<div class="section">
					<div class="entry">
						<?php echo $lng["gallery_upload_header"]; ?>
					</div>
				</div>
				<br/>
				<div class="section">
					<div id="file_box" ondragover="event.preventDefault();" ondrop="dropFile(event, '<?php echo $lng['gallery_upload_placeholder_title'] ?>', '<?php echo $lng['gallery_upload_placeholder_description'] ?>');">
						<br/><br/><br/>
						<span id='drag'><?php echo $lng["gallery_upload_drag"]; ?></span>
						<input class='hidden' multiple accept='image/x-png, image/gif, image/jpeg' type='file' id='file_selector' onChange="selectFile(event, this, '<?php echo $lng['gallery_upload_placeholder_title'] ?>', '<?php echo $lng['gallery_upload_placeholder_description'] ?>');"/>
						<br/><br/>
						<span id='select' class='desktop'><a href='javascript:launchFileSelector();'><?php echo $lng["gallery_upload_select"];?></a></span>
						<br/><br/><br/>
					</div>
					
				</div>
				<br/><br/>
				<div class="section" id="photo_upload_preview">
					<div id="file_list">
					</div>
					<table>
						<tr>
							<td class='entry'>
								<?php echo $lng["gallery_upload_tooltip_album"]; ?>
								<br/><br/>
								<select id="album" type="select">
									<option value="-1" selected="selected"><?php echo $lng['gallery_upload_select_album'] ?></option>
									<?php
										$q_album = mysqli_query($con, "SELECT id, title_$lang AS title FROM album ORDER BY title;");
										while ($r_album = mysqli_fetch_array($q_album)){
											echo "<option value='$r_album[id]'>$r_album[title]</option>\n";
										}
									?>
								</select>
							</td>
							<td class='entry'>
								<?php echo $lng["gallery_upload_tooltip_name"]; ?>
								<br/><br/>
								<input type='text' id='username'/>
							</td>
						</tr>
					</table>
					<input type="submit" id="photo_submit" value="<?php echo $lng["gallery_upload_submit"];?>">
				</div>
			</form>
		</div>
		<?php include("../footer.php"); ad($con, $lang, $lng); ?>
	</body>
</html>
