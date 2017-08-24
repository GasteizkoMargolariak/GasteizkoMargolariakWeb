<?php
    session_start();
    $http_host = $_SERVER["HTTP_HOST"];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $server = "$proto$http_host";
    
    //Language
    $lang = selectLanguage();
    include("../lang/lang_$lang.php");
    
    $cur_section = $lng["section_gallery"];
    
    //Get album
    $perm = mysqli_real_escape_string($con, $_GET["perm"]);
    $q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, description_$lang AS description, user, open, dtime FROM album WHERE permalink = '$perm';");
    if (mysqli_num_rows($q) == 0){
        header("Location: /galeria/");
        exit (-1);
    }
    else{
        $r = mysqli_fetch_array($q);
        $id = $r["id"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content='text/html; charset=utf-8" http-equiv="content-type'/>
        <meta charset="utf-8"/>
        <meta name='viewport' content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$r["title"]?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?php echo "$server/img/logo/favicon.ico";?>">
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
        <link rel='canonical' href='<?=$server?>/galeria/<?=$perm?>'/>
        <link rel='author' href='<?=$server?>'/>
        <link rel='publisher' href='<?=$server?>'/>
        <meta name='description' content='<?=strip_tags($r["description"])?>'/>
        <meta property='og:title' content='<?=$r["title"]?> - Gasteizko Margolariak'/>
        <meta property='og:url' content='<?=$server?>/galeria/<?=$perm?>'/>
        <meta property='og:description' content='<?=strip_tags($r["description"])?>'/>
        <meta property='og:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta property='og:site_name' content='Gasteizko Margolariak'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='<?=$lang?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$r["title"]?> - Gasteizko Margolariak'/>
        <meta name='twitter:description' content='<?=strip_tags($r["description"]) ?>'/>
        <meta name='twitter:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta name='twitter:url' content='<?=$server?>/galeria/<?=$perm?>'/>
        <meta name='robots' content='index follow'/>
    </head>
    <body onLoad="populatePhotos();" onkeypress="keyDown(event);">
<?php
        include("../header.php");
?>
        <div id='content'>
            <div class='section' id='album' itemscope itemtype='https://schema.org/ImageGallery'>
                <div class='hidden' itemprop='author creator' itemscope itemtype='http://schema.org/Organization'>
                    <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                    <meta itemprop='name' content='Gasteizko Margolariak'/>
                    <meta itemprop='logo' content='$server/img/logo/logo.png'/>
                    <meta itemprop='foundingDate' content='03-02-2013'/>
                    <meta itemprop='telephone' content='+34637140371'/>
                    <meta itemprop='url' content='<?=$server ?>'/>
                </div>
                <h3 class='section_title' itemprop='name' id='album_title'><?=$r["title"]?></h3>
<?php
                if ($r["description"] != ''){
?>
                    <div class='entry' id='album_header'>
                        <?=$r["description"]?>
                    </div>
<?php
                }
                //Photos of the album
                $q_photo = mysqli_query($con, "SELECT id, file, permalink, title_$lang AS title, description_$lang AS description, photo.dtime AS dtime, uploaded, width, height, size, user, username FROM photo, photo_album WHERE photo_album.photo = id AND album = $r[id] AND approved = 1 ORDER BY photo.dtime DESC;");
                while ($r_photo = mysqli_fetch_array($q_photo)){
?>
                    <div class='entry photo'>
                        <div class='photo_container'>
                            <img class='pointer photo_img' path='<?=$r_photo["file"]?>' onClick='showPhotoByPath("<?=$r_photo["file"]?>");' src='<?=$server?>/img/galeria/miniature/<?=$r_photo["file"]?>'/>
                        </div>
                        <meta itemprop='image' content='<?=$server?>/img/galeria/view/<?=$r_photo["file"]?>'/>
<?php
                        if (strlen($r_photo["title"]) > 0 ){
?>
                            <h3 class='entry_title'>
                                <a onClick='showPhotoByPath("<?=$r_photo["file"]?>");'><?=$r_photo["title"]?></a>
                            </h3>
<?php
                        }
                        if (strlen($r_photo["description"]) > 0 ){
?>
                            <span class='photo_description'><?=cutText($r_photo["description"], 50)?></span>
<?php
                        }
                        //Count comments
                        $q_comments = mysqli_query($con, "SELECT id FROM photo_comment WHERE photo = $r_photo[id];");
?>
                        <span class='comment_counter'><?=mysqli_num_rows($q_comments)?><img src='<?=$server?>/img/misc/comment.png' alt=' '/></span>
                    </div> <!-- entry photo -->
<?php
                }
?>
            </div>
        </div>
        <div id='screen_cover' onClick="closeViewer();">
        </div>
        <div id='photo_viewer' class='section'>
        </div>
<?php
        include("../footer.php");
        $ad = ad($con, $lang, $lng); 
        stats($ad, $ad_static, "galeria", "$id");
?>
    </body>
</html>

<?php
    }
?>
