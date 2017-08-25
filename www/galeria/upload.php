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
    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content='text/html; charset=utf-8" http-equiv="content-type'/>
        <meta charset="utf-8"/>
        <meta name='viewport' content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$lng["gallery_title"]?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico";?>">
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
        <meta property='og:locale' content='<?php echo $lang; ?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$lng["gallery_title"]?> - Gasteizko Margolariak'/>
        <meta name='twitter:description' content='<?=$lng["gallery_description"]?>'/>
        <meta name='twitter:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta name='twitter:url' content='<?=$server?>'/>
        <meta name='robots' content='noindex nofollow'/>
    </head>
    <body>
<?php
        include("../header.php");
?>
        <div id='content'>
            <form onSubmit='event.preventDefault(); submitPhotos();'>
                <div class='section'>
                    <h3 class='section_title'><?=$lng["gallery_upload_submit"]?></h3>
                    <div class='entry'>
                        <?=$lng["gallery_upload_header"]?>
                    </div>
                
                    <div id='file_box' ondragover='event.preventDefault();' ondrop='dropFile(event, "<?=$lng["gallery_upload_placeholder_title"]?>", "<?=$lng["gallery_upload_placeholder_description"]?>");'>
                        <br/><br/><br/>
                        <span id='drag'><?=$lng["gallery_upload_drag"]?></span>
                        <input class='hidden' multiple accept='image/x-png, image/gif, image/jpeg' type='file' id='file_selector' onChange='selectFile(event, this, "<?=$lng["gallery_upload_placeholder_title"]?>", "<?=$lng["gallery_upload_placeholder_description"]?>");'/>
                        <br/><br/>
                        <span id='select' class='desktop'>
                            <a href='javascript:launchFileSelector();'><?=$lng["gallery_upload_select"]?></a>
                        </span>
                        <br/><br/><br/>
                    </div>
                    
                    <div  id='photo_upload_preview'>
                        <div id='file_list'>
                        </div>
                        <table>
                            <tr>
                                <td class='entry'>
                                    <?=$lng["gallery_upload_tooltip_album"]?>
                                    <br/><br/>
                                    <select id='album' type='select'>
                                        <option value="-1" selected='selected'>
                                            <?=$lng["gallery_upload_select_album"]?>
                                        </option>
<?php
                                            $q_album = mysqli_query($con, "SELECT id, title_$lang AS title FROM album ORDER BY title;");
                                            while ($r_album = mysqli_fetch_array($q_album)){
?>                                                
                                                <option value='<?=$r_album["id"]?>'><?=$r_album[title]?></option>
<?php
                                            }
?>
                                    </select>
                                </td>
                                <td class='entry'>
                                    <?=$lng["gallery_upload_tooltip_name"]?>
                                    <br/><br/>
                                    <input type='text' id='username'/>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" id='photo_submit' value="<?=$lng["gallery_upload_submit"]?>">
                    </div>
                </div>
            </form>
        </div>
<?php
        include("../footer.php");
?>
    </body>
</html>
