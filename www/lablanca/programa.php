<?php
    session_start();
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $http_host = $_SERVER['HTTP_HOST'];
    $server = "$proto" . "$http_host";
    $gm = $_GET["gm"];

    if (isset($_GET["y"]) && intval($_GET["y"]) > 2013 && intval($_GET["y"]) <= date("Y")){
        $year = $_GET["y"];
    }
    else{
        $year = date("Y");
    }

    if ($gm == 1 || $gm == "margolari" || $gm == "margolariak"){
        $gm = 1;
        $schi = "gm";
        $url = "margolariak";
    }
    elseif ($gm == 0 || $gm == "municipal" || $gm == "ciudad"){
        $gm = 0;
        $schi = "city";
        $url = "municipal";
    }
    else{
        header("Location: $server/lablanca/programa/margolariak/$year");
    }

    //Language
    $lang = selectLanguage();
    include("../lang/lang_" . $lang . ".php");

    $cur_section = $lng['section_lablanca'];

?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title>
            <?=str_replace('#', $year, $lng["lablanca_schedule_year_" . $schi])?> - Gasteizko Margolariak
        </title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
        <!-- CSS files -->
        <style>
<?php
            include("../css/ui.css");
            include("../css/lablanca.css");
            include("../css/map.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php
            include("../css/m/ui.css");
            include("../css/m/lablanca.css");
            include("../css/m/map.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
<?php
            include("../script/ui.js");
            include("../script/lablanca.js");
            include("../script/map.js");
?>
        </script>
        <script src="<?=$server?>/script/OpenLayers/ol.js"></script>

        <!-- Meta tags -->
        <link rel="canonical" href="<?=$server?>/lablanca/programa/<?=$url?>/<?=$year?>"/>
        <link rel="author" href="<?=$server?>"/>
        <link rel="publisher" href="<?=$server?>"/>
        <meta name="description" content="<?=str_replace('#', $year, $lng["lablanca_schedule_year_" . $schi . "_description"])?>"/>
        <meta property="og:title" content="<?=str_replace('#', $year, $lng["lablanca_schedule_year_" . $schi])?> - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?=$server?>/lablanca/programa/<?=$url?>/<?=$year?>"/>
        <meta property="og:description" content="<?=str_replace('#', $year, $lng["lablanca_schedule_year_" . $schi . "_description"])?>"/>
        <meta property="og:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta property="og:site_name" content="Gasteizko Margolariak"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="<?=$lang?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?=str_replace('#', $year, $lng["lablanca_schedule_year_" . $schi])?> - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="<?=str_replace('#', $year, $lng["lablanca_schedule_year_" . $schi . "_description"])?>"/>
        <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta name="twitter:url" content="<?=$server?>/lablanca/programa/<?=$url?>/<?=$year?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
        <?php include("../header.php"); ?>
        <div id="content">
            <div class='section' id='schedule'>
                <h3 class='section_title'><?=str_replace('#', $year, $lng["lablanca_schedule_year_" . $schi])?></h3>
<?php
                $q_days = mysqli_query($con, "SELECT DISTINCT date(DATE_SUB(start, INTERVAL 6 HOUR)) AS date, DATE_FORMAT(DATE_SUB(start, INTERVAL 6 HOUR), '%Y-%m-%d') AS isodate, (SELECT name_$lang FROM festival_day WHERE date = date(DATE_SUB(start, INTERVAL 6 HOUR))) AS name FROM festival_event_$schi WHERE year(start) = 2018 ORDER BY date");
                $i=0;
                while ($r_days = mysqli_fetch_array($q_days)){
?>
                    <div class='entry'>
                        <div onClick='expandDay(<?=$i?>);' class='day_title pointer'>
                            <h4>
                                <img class='slid' src='<?=$server?>/img/misc/slid-right.png' id='slid_day_<?=$i?>'/>
<?php
                                if($gm == 1 && $r_days['name'] != null){
?>
                                    <?=formatFestivalDate($r_days['date'], $lang)?> - <?=$r_days['name']?>
<?php
                                }
                                else{
?>
                                    <?=formatFestivalDate($r_days['date'], $lang)?>
<?php
                                }
?>
                            </h4>
                        </div>
                        <div class='day_schedule' id='day_schedule_<?=$i?>'>
<?php
                            $q_sch = mysqli_query($con, "SELECT festival_event_$schi.id AS id, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon, route FROM festival_event_$schi, place WHERE place.id = festival_event_$schi.place AND date(DATE_SUB(start, INTERVAL 6 HOUR)) = str_to_date('$r_days[date]', '%Y-%m-%d') ORDER BY start");
                            if (mysqli_num_rows($q_sch) > 0){
?>
                                <table class='schedule'>
<?php
                                while ($r_sch = mysqli_fetch_array($q_sch)){
?>
                                    <tr>
                                        <td>
                                            <span class='time'><?=$r_sch['st']?></span>
                                        </td>
                                        <td class='timeline'>
                                            <img class='timeline_dot' alt=' ' src='<?=$server?>/img/misc/schedule-point.png'/>
                                        </td>
                                        <td>
                                            <span class='title'><?=$r_sch['title']?></span>
<?php
                                            if (strlen($r_sch["description"]) > 0 && $r_sch["description"] != $r_sch["title"]){
?>
                                                <br/>
                                                <div class='description'><?=$r_sch["description"]?></div>
<?php
                                            }
?>
                                            <div class='location'>
<?php
                                                if ($r_sch["route"] != null && strlen($r_sch["route"]) != 0){
?>
                                                    <!-- TODO: Add center coordinates and zoom -->
                                                    <span class='fake_a pointer' onClick='showMapRoute(<?=$r_sch["route"]?>, "<?=$r_sch["title"]?>", <?=$r_sch["lat"]?>, <?=$r_sch["lon"]?>);'>
                                                        <img class='pinpoint' alt=' ' src='<?=$server?>/img/misc/pinpoint-route.png'/>
<?php
                                                        //If name and address are the same, show only name
                                                        if ($r_sch["place"] == $r_sch["address"]){
?>
                                                            <?=$r_sch["place"]?>
<?php
                                                        }
                                                        else{
?>
                                                            <span class='desktop route_start'>
                                                                <?=$lng["lablanca_schedule_start"]?>
                                                            </span>
                                                            <span class='address'> - <?=$r_sch["address"]?></span>
<?php
                                                        }
?>
                                                    </span>
<?php
                                                }
                                                else{
?>
                                                    <span class='fake_a pointer' onClick='showMapPoint("<?=$r_sch["title"]?>", <?=$r_sch["lat"]?>, <?=$r_sch["lon"]?>);'>
                                                        <img class='pinpoint' alt=' ' src='<?=$server?>/img/misc/pinpoint.png'/>
<?php
                                                        //If name and address are the same, show only name
                                                        if ($r_sch["place"] == $r_sch["address"]){
?>
                                                            <?=$r_sch["place"]?>
<?php
                                                        }
                                                        else{
?>
                                                            <?=$r_sch["place"]?>
                                                            <span class='address'> - <?=$r_sch["address"]?></span>
<?php
                                                        }
?>
                                                    </span>
<?php
                                                }
?>
                                            </div>
                                        </td>
                                    </tr>
<?php
                                }
?>
                                </table>
<?php
                            }
?>
                        </div>
                    </div>
<?php
                    $i ++;
                } // while ($r_days = mysqli_fetch_array($q_days))
?>
            </div>
            <div id='map_container'>
                <div class='section'>
                    <h3 class='section_title'>
                        <span id='map_title'>MAP</span>
                        <div id='map_close_container'>
                            <img id='map_close' class='pointer' alt=' ' src='<?=$server?>/img/misc/close.png' onClick='hideMap();'/>
                        </div>
                    </h3>
                    <div id='map' class='entry'>
                    </div> <!-- map -->
                </div> <!-- .section -->
            </div> <!-- .map_container -->
        </div> <!-- #content -->
<?php
            include("../footer.php");
            $ad = ad($con, $lang, $lng);
            stats($ad, $ad_static, "fiestas", "");
?>
    </body>
</html>
