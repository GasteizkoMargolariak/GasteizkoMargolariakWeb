<?php
    session_start();
    $http_host = $_SERVER["HTTP_HOST"];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
	$server = "$proto$http_host";
    
    //Language
    $lang = selectLanguage();
    include("../lang/lang_" . $lang . ".php");
    
    $cur_section = $lng["section_gallery"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content='text/html; charset=utf-8" http-equiv="content-type'/>
        <meta charset="utf-8"/>
        <meta name='viewport' content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$lng["gallery_title"]?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
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
?>
        </script>
        <!-- Meta tags -->
        <link rel='canonical' href='<?=$server?>'/>
        <link rel='author' href='<?=$server?>'/>
        <link rel='publisher' href='<?=$server?>'/>
        <meta name='description' content='<?=$lng["gallery_description"]?>'/>
        <meta property='og:title' content='<?=$lng["gallery_title"]?> - Gasteizko Margolariak'/>
        <meta property='og:url' content='<?=$server?>'/>
        <meta property='og:description' content='<?=$lng["gallery_description"]?>'/>
        <meta property='og:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta property='og:site_name' content='<?=$lng["gallery_title"]?>'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='<?=$lang?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$lng["gallery_title"]?> - Gasteizko Margolariak'/>
        <meta name='twitter:description' content='<?=$lng["gallery_description"]?>'/>
        <meta name='twitter:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta name='twitter:url' content='<?=$server?>'/>
        <meta name='robots' content='index follow'/>
    </head>
    <body>
<?php include("../header.php"); ?>
        <div id='content'>
            <br/>
            <div class='section' id='album_list'>
                    <h3 class='section_title'><?=$lng["gallery_albums"]?></h3>
<?php
                    //Album with photos with dates
                    $q = mysqli_query($con, "SELECT album.id AS id, album.permalink AS permalink, album, album.title_$lang AS title, album.description_$lang AS description FROM photo_album, photo, album WHERE album.id = photo_album.album AND photo = photo.id GROUP BY album ORDER BY avg(photo.uploaded) DESC;");
                    while ($r = mysqli_fetch_array($q)){
?>
                        <div class='entry album_list' itemscope itemtype='https://schema.org/ImageGallery'>
							<div class='hidden' itemprop='author creator' itemscope itemtype='http://schema.org/Organization'>
								<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
								<meta itemprop='name' content='Gasteizko Margolariak'/>
								<meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
								<meta itemprop='foundingDate' content='03-02-2013'/>
								<meta itemprop='telephone' content='+34637140371'/>
								<meta itemprop='url' content='<?=$server?>'/>
							</div>
							<table class='album_thumbnails'>
								<tr>
<?php
									$i = 0;
									$q_photo = mysqli_query($con, "SELECT * FROM photo, photo_album WHERE id = photo AND album = $r[album] ORDER BY rand() LIMIT 4;");
									while ($r_photo = mysqli_fetch_array($q_photo)){
?>
										<td>
											<a itemprop='url' href='<?=$server?>/galeria/<?=$r["permalink"]?>'>
												<meta itemprop='image' content='<?=$server?>/img/galeria/view/<?=$r_photo["file"]?>'/>
												<img src='<?=$server?>/img/galeria/miniature/<?=$r_photo["file"]?>'/>
											</a>
										</td>
<?php
										$i ++;
										if ($i == 2){
?>
											</tr>
											<tr>
<?php
										}
									}
?>
								</tr>
							</table>
							<h3 class='entry_title' itemprop='name'>
								<a href='<?=$server?>/galeria/<?=$r["permalink"]?>'><?=$r["title"]?></a>
							</h3>
<?php
							$q_count = mysqli_query($con, "SELECT album FROM photo_album WHERE album = $r[id];");
							if (mysqli_num_rows($q_count) == 1){
?>
								1 <?=$lng["gallery_photos_singular"]?>
<?php
							}
							else{
?>
								<?=mysqli_num_rows($q_count)?> <?=$lng["gallery_photos_plural"]?>
<?php
							}
							if (strlen($r["description"]) > 0){
?>
								<p itemprop='description'><?=cutText($r["description"], 100, "$lng[gallery_read_more]", "$server/galeria/$r[permalink]")?></p>
<?php
							}
?>							
                        </div> <!--.album_list -->
<?php
                    }
?>
                <div class='entry' id='gallery_entry_contribute'>
                    <?=$lng["gallery_header"]?>
                </div>
            </div>
        </div>
<?php
        include("../footer.php");
        $ad = ad($con, $lang, $lng); 
        stats($ad, $ad_static, "galeria", "");
?>
    </body>
</html>
