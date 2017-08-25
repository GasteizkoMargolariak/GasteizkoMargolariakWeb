<?php
    session_start();
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $http_host = $_SERVER["HTTP_HOST"];
    $server = "$proto$http_host";
    
    //Language
    $lang = selectLanguage();
    include("../lang/lang_$lang.php");
    
    $cur_section = $lng["section_lablanca"];
    
    //Is festivals enabled in settings?
    $q = mysqli_query($con, "SELECT value FROM settings WHERE name = 'festivals';");
    if (mysqli_num_rows($q) == 0)
        $is_festivals = false;
    else{
        $r = mysqli_fetch_array($q);
        if ($r["value"] == 1){
            $is_festivals = true;
        }
        else{
            $is_festivals = false;
        }
    }
    
    //Get year
    $year = date("Y");
    if ($_GET["year"] != ''){ 
        $q_year = mysqli_query($con, "SELECT id FROM festival WHERE year = " . mysqli_real_escape_string($con, $_GET["year"]));
        if (mysqli_num_rows($q_year) > 0){
            $year = $_GET["year"];
            $is_festivals = true;
        }
    }
    

?>
<!DOCTYPE html>
<html>
    <head>
        <meta content='text/html; charset=utf-8' http-equiv='content-type'/>
        <meta charset='utf-8'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title>
<?php
            if ($is_festivals){
?>
                <?=str_replace('#', $year, $lng["lablanca_title"])?>
<?php
            }
            else{
?>
                <?=$lng["lablanca_no_title"]?>
<?php
             }
?>
             - Gasteizko Margolariak
        </title>
        <link rel='shortcut icon' href='<?=$server?>/img/logo/favicon.ico'>
        <!-- CSS files -->
        <style>
<?php 
            include("../css/ui.css"); 
            include("../css/lablanca.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php 
            include("../css/m/ui.css"); 
            include("../css/m/lablanca.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
<?php
            include("../script/ui.js");
            include("../script/lablanca.js");
?>
        </script>
        <!-- Meta tags -->
        <link rel="canonical" href="<?=$server?>/lablanca/"/>
        <link rel="author" href="<?=$server?>"/>
        <link rel="publisher" href="<?=$server?>"/>
        <meta name='description' content='<?=$lng["lablanca_description"]?>'/>
<?php
        if ($is_festivals){
?>
            <meta property="og:title" content='<?=str_replace('#', $year, $lng["lablanca_title"])?> - Gasteizko Margolariak'/>
<?php
        }
        else{
?>
            <meta property="og:title" content='<?=str_replace('#', $year, $lng["lablanca_no_title"])?> - Gasteizko Margolariak'/>
<?php
        }
?>
        <meta property='og:url' content='<?=$server?>'/>
        <meta property='og:description' content='<?=$lng["lablanca_description"]?>'/>
        <meta property='og:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta property='og:site_name' content='<?=$lng["lablanca_title"]?>'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='<?=$lang?>'/>
        <meta name='twitter:card' content='summary'/>
<?php
        if ($is_festivals){
?>
            <meta property="twitter:title" content='<?=str_replace('#', $year, $lng["lablanca_title"])?> - Gasteizko Margolariak'/>
<?php
        }
        else{
?>
            <meta property="twitter:title" content='<?=str_replace('#', $year, $lng["lablanca_no_title"])?> - Gasteizko Margolariak'/>
<?php
        }
?>
        <meta name="twitter:description" content="<?=$lng["lablanca_description"]?>"/>
        <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png";?>"/>
        <meta name="twitter:url" content="<?=$server?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
<?php
    include("../header.php");
?>
    <div id="content">
<?php                
        //Include file
        if ($is_festivals){
            include("fiestas.php");
        }
        else{
            include("nofiestas.php");
        }
?>
<?php
        //If the program of previous years exist, show a drop list
        $year = date("Y");
        if (date("M") <= 8 || (date("M") == 8 && date("D") < 25)){
            $year --;
        }
        if (date("M") <= 8){
            $q_years = mysqli_query($con, "SELECT year FROM festival WHERE year != " . date("Y") . " ORDER BY year DESC;");
        }
        else{
            $q_years = mysqli_query($con, "SELECT year FROM festival WHERE year <= " . date("Y") . " ORDER BY year DESC;");
        }
        if (mysqli_num_rows($q_years) >= 0){
?>
            <br/><br/>
            <div class='section' id='past_festivals'>
                <h3 class='section_title'><?=$lng["lablanca_past_title"]?></h3>
                <div class='entry'>
                    <ul>
<?php
                        while ($r_years = mysqli_fetch_array($q_years)){
?>
                            <li>
                                <a href='<?=$server?>/lablanca/<?=$r_years["year"]?>'><?=$lng["lablanca_past_link"]?> <?=$r_years["year"]?></a>
                            </li>
<?php
                        }
?>
                    </ul>
                </div> <!-- .entry -->
            </div> <!-- .section -->
<?php
        }
?>
    </div> <!-- #content -->
<?php
    include("../footer.php");
    $ad = ad($con, $lang, $lng); 
    stats($ad, $ad_static, "fiestas", "");
?>
    </body>
</html>
