<?php
    session_start();
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    
    //Language
    $lang = selectLanguage();
    include("../lang/lang_" . $lang . ".php");
    
    $cur_section = $lng['section_us'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?php echo $lng['us_title'];?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
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
        <link rel="canonical" href="<?php echo "$proto$http_host/nosotros/"; ?>"/>
        <link rel="author" href="<?php echo "$proto$http_host"; ?>"/>
        <link rel="publisher" href="<?php echo "$proto$http_host"; ?>"/>
        <meta name="description" content="<?php echo $lng['us_description'];?>"/>
        <meta property="og:title" content="<?php echo $lng['us_title'];?> - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?php echo "$proto$http_host"; ?>"/>
        <meta property="og:description" content="<?php echo $lng['us_description'];?>"/>
        <meta property="og:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
        <meta property="og:site_name" content="<?php echo $lng['us_title'];?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="<?php echo $lang; ?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?php echo $lng['us_title'];?> - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="<?php echo $lng['us_description'];?>"/>
        <meta name="twitter:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
        <meta name="twitter:url" content="<?php echo"$proto$http_host"; ?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
        <?php include("../header.php"); ?>
        <div id="content">
            <div class="content_row">
                <div class="content_cell">
                    <div class="section" id="section_association">
                        <h3 class='section_title'><?php echo $lng['us_association']; ?></h3>
                        <div class="entry">
                            <img src="/img/nosotros/logo.png" alt=" "/>
                            <?php echo $lng['us_association_content']; ?>
                        </div>
                    </div>
                </div>
                <div class="content_cell">
                    <div class="section" id="section_cuadrilla">
                        <h3 class='section_title'><?php echo $lng['us_cuadrilla']; ?></h3>
                        <div class="entry">
                            <img src="/img/nosotros/margolo.png" alt=" "/>
                            <?php echo $lng['us_cuadrilla_content']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content_row">
                <div class="content_cell">
                    <div class="section" id="section_activities">
                        <h3 class='section_title'><?php echo $lng['us_activities']; ?></h3>
                        <div class="entry">
                            <img src="/img/nosotros/actividades.png" alt=" "/>
                            <?php echo $lng['us_activities_content']; ?>
                        </div>
                    </div>
                </div>
                <div class="content_cell">
                    <div class="section" id="section_transparency">
                        <h3 class='section_title'><?php echo $lng['us_transparency']; ?></h3>
                        <div class="entry">
                            <img src="/img/nosotros/transparencia.png" alt=" "/>
                            <?php echo $lng['us_transparency_content']; ?>
                            <div>
                                <!-- TODO: Link like button -->
<!--                                 <a href="/nosotros/transparencia/">Consulta nuestras cuentas</a> -->
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
