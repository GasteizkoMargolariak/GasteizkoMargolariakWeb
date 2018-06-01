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
                <h3 class='section_title'>Public APIs</h3>
                <table id='main'>
                    <tr>
                        <td class='entry'>
                            <h3>
                                <img src='<?=$server?>/API/V<?=$v?>/help/img/sync.png'/>
                                Data sync
                            </h3>
                            <p>Sync data from our site in your persistant-storage app so your users can use it offline.</p>
                            <a href='<?=$server?>/API/v<?=$v?>/help/sync/'>Documentation and examples</a>
                        </td>
                        <td class='entry'>
                            <h3>
                                <img src='<?=$server?>/API/V<?=$v?>/help/img/comment.png'/>
                                Comments
                            </h3>
                            <p>Post comment for the content in our site from your apps.</p>
                            <a href='<?=$server?>/API/v<?=$v?>/help/comment/'>Documentation and examples</a>
                        </td>
                    </tr>
                    <tr>
                        <td class='entry'>
                            <h3>
                                <img src='<?=$server?>/API/V<?=$v?>/help/img/notification.png'/>
                                Notifications
                            </h3>
                            <p>Get notifications sent to your app.</p>
                            <a href='<?=$server?>/API/v<?=$v?>/help/notifications/'>Documentation and examples</a>
                        </td>
                        <td class='entry'>
                            <h3>
                                <img src='<?=$server?>/API/V<?=$v?>/help/img/location.png'/>
                                Location
                            </h3>
                            <p>Be able to follow Gasteizko Margolariak in real time during activities and festivals.</p>
                            <a href='<?=$server?>/API/v<?=$v?>/help/location/'>Documentation and examples</a>
                        </td>
                    </tr>
                </table>
            </div> <!-- .section -->
        </div>
    </body>
</body>
