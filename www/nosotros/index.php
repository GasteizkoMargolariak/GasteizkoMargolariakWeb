<?php
    session_start();
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $server = "$proto$http_host";

    //Language
    $lang = selectLanguage();
    include("../lang/lang_$lang.php");

    $cur_section = $lng['section_us'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$lng["us_title"]?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
        <!-- CSS files -->
        <style>
<?php
            include("../css/ui.css");
            include("../css/nosotros.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php
            include("../css/m/ui.css");
            include("../css/m/nosotros.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
<?php
            include("../script/ui.js");
?>
        </script>
        <!-- Meta tags -->
        <link rel="canonical" href="<?=$server?>/nosotros/"/>
        <link rel="author" href="<?=$server?>"/>
        <link rel="publisher" href="<?=$server?>"/>
        <meta name="description" content="<?=$lng["us_description"]?>"/>
        <meta property="og:title" content="<?=$lng["us_title"]?> - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?=$server?>"/>
        <meta property="og:description" content="<?=$lng["us_description"]?>"/>
        <meta property="og:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta property="og:site_name" content="<?=$lng["us_title"]?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="<?=$lang?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?=$lng["us_title"]?> - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="<?=$lng["us_description"]?>"/>
        <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta name="twitter:url" content="<?=$server?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
<?php
        include("../header.php");
?>
        <div id="content">
            <div class="content_row">
                <div class="content_cell">
                    <div class="section" id="section_association">
                        <h3 class='section_title'><?=$lng["us_association"] ?></h3>
                        <div class="entry">
                            <img src="<?=$server?>/img/nosotros/logo.png" alt=" "/>
                            <?=$lng["us_association_content"] ?>
                        </div>
                    </div>
                </div>
                <div class="content_cell">
                    <div class="section" id="section_cuadrilla">
                        <h3 class='section_title'><?=$lng["us_cuadrilla"] ?></h3>
                        <div class="entry">
                            <img src="<?=$server?>/img/nosotros/margolo.png" alt=" "/>
                            <?=$lng["us_cuadrilla_content"] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content_row">
                <div class="content_cell">
                    <div class="section" id="section_activities">
                        <h3 class='section_title'><?=$lng["us_activities"] ?></h3>
                        <div class="entry">
                            <img src="<?=$server?>/img/nosotros/actividades.png" alt=" "/>
                            <?=$lng["us_activities_content"] ?>
                        </div>
                    </div>
                </div>
                <div class="content_cell">
                    <div class="section" id="section_transparency">
                        <h3 class='section_title'><?=$lng["us_transparency"] ?></h3>
                        <div class="entry">
                            <img src="<?=$server?>/img/nosotros/transparencia.png" alt=" "/>
                            <?=$lng["us_transparency_content"] ?>
                            <div>
                                 <!-- <a class='button' href="/nosotros/transparencia/">Consulta nuestras cuentas</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
        include("../footer.php");
        $ad = ad($con, $lang, $lng); 
        stats($ad, $ad_static, "nosotros", "");
?>
    </body>
</html>
