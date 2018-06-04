<?php
    $v = 3;
    session_start();
    $http_host = $_SERVER["HTTP_HOST"];
    include("../../../functions.php");
    $proto = getProtocol();
    $con = startdb();
    $server = "$proto$http_host";

    //Language
    $lang = selectLanguage();
    include("lang/lang_" . $lang . ".php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta content='text/html; charset=utf-8' http-equiv='content-type'/>
        <meta charset='utf-8'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title>Gasteizko Margolariak API</title>
        <link rel='shortcut icon' href='<?=$server?>/img/logo/favicon.ico'>
        <!-- CSS files -->
        <style>
            <?php 
                include("../../../css/ui.css"); 
                include("../../../css/index.css");
                include("styles.css");
            ?>
        </style>
        <!-- CSS for mobile version -->
        <style media='(max-width : 990px)'>
            <?php 
                include("../../../css/m/ui.css"); 
                include("../../../css/m/index.css");
                include("styles-m.css");
            ?>
        </style>
        <!-- Script files -->
        <script type='text/javascript'>
            <?php include("../../../script/ui.js"); ?>
        </script>
        <!-- Meta tags -->
        <link rel='canonical' href='<?=$server?>/API/help/V<?=$v?>'/>
        <link rel='author' href='<?=$server?>'/>
        <link rel='publisher' href='<?=$server?>'/>
        <meta name='description' content='Gasteizko Margolariak API v<?=$v?> documentation'/>
        <meta property='og:title' content='Gasteizko Margolariak API'/>
        <meta property='og:url' content='<?=$server?>/API/help/V<?=$v?>'/>
        <meta property='og:description' content='Gasteizko Margolariak API v<?=$v?> documentation'/>
        <meta property='og:image' content='<?=$server?>/img/logo/logo-api.png'/>
        <meta property='og:site_name' content='Gasteizko Margolariak'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='en'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='Gasteizko Margolariak API'/>
        <meta name='twitter:description' content='Gasteizko Margolariak API v<?=$v?> documentation'/>
        <meta name='twitter:image' content='<?=$server?>/img/logo/logo-api.png'/>
        <meta name='twitter:url' content='<?=$server?>/API/help/V<?=$v?>'/>
        <meta name='robots' content='index follow'/>
    </head>
    <body>
        <?php include("toolbar.php"); ?>
        <div id='content'>
            <div class='section'>
                <h3 class='section_title'>Sync API</h3>
                <div class='entry'>
                    <img class='api_logo' src=""<?=$server?>/API/help/V<?=$v?>/help/img/sync.png">
                    <p>
                        Sync the content of the web site with your storage persistant application. Make it so your users can access the info when they are online.
                    </P>
                    <h3>Overview</h3>
                </div> <!-- .entry -->
            </div> <!-- .section -->
        </div> <!-- #content -->
    </body>
</html>
