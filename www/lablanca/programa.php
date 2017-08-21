<?php
    session_start();
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $http_host = $_SERVER['HTTP_HOST'];
    $server = "$proto" . "$http_host";
    $gm = $_GET["gm"];
    $year = $_GET["y"];
    
    if ($gm == 1){
        $schi = "gm";
    }
    else{
        $schi = "city";
    }

    //Language
    $lang = selectLanguage();
    include("../lang/lang_" . $lang . ".php");

    $cur_section = $lng['section_lablanca'];


    //Get year
    $year = $_GET["y"];
    if (strlen($year) < 1){
        $year = date("Y");
        //if (is_int($_GET['year']) == false){
        if ($_GET['year'] != ''){
            $q_year = mysqli_query($con, "SELECT id FROM festival WHERE year = " . mysqli_real_escape_string($con, $_GET['year']));
            if (mysqli_num_rows($q_year) > 0){
                $year = $_GET['year'];
            }
        }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title>
            <?=str_replace('#', $year, $lng["lablanca_schedule_" . $schi])?> - Gasteizko Margolariak
        </title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
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
        <meta name="description" content="<?=str_replace('#', $year, $lng["lablanca_schedule_" . $schi . "_description"])?>"/>
        <meta property="og:title" content="<?=str_replace('#', $year, $lng["lablanca_schedule_" . $schi])?> - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?php echo "$proto$http_host"; ?>"/>
        <meta property="og:description" content="<?=str_replace('#', $year, $lng["lablanca_schedule_" . $schi . "_description"])?>"/>
        <meta property="og:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta property="og:site_name" content="Gasteizko Margolariak"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="<?=$lang?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?=str_replace('#', $year, $lng["lablanca_schedule_" . $schi])?> - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="<?=str_replace('#', $year, $lng["lablanca_schedule_" . $schi . "_description"])?>"/>
        <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta name="twitter:url" content="<?php echo"$proto$http_host"; ?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
        <?php include("../header.php"); ?>
        <div id="content">
            <div class='section' id='schedule'>
                <h3 class='section_title'><?=str_replace('#', $year, $lng["lablanca_schedule_" . $schi])?></h3>
<?php
                $q_days = mysqli_query($con, "SELECT DISTINCT date(DATE_SUB(start, INTERVAL 6 HOUR)) AS date, DATE_FORMAT(DATE_SUB(start, INTERVAL 6 HOUR), '%Y-%m-%d') AS isodate, (SELECT name_$lang FROM festival_day WHERE date = date(DATE_SUB(start, INTERVAL 6 HOUR))) AS name FROM festival_event_$schi WHERE year(start) = 2017 ORDER BY date");
                $i=0;
                while ($r_days = mysqli_fetch_array($q_days)){
?>
                    <div class='entry'>
                        <div onClick='expandDay(<?=$i?>);' class='day_title pointer'>
                            <h4>
                                <img class='slid' src='<?=$server?>/img/misc/slid-right.png' id='slid_day_<?=$i?>'/>
<?php
                                if($r_days['name'] != null){
?>
                                    <?=formatFestivalDate($r_days['date'])?> - <?=$r_days['name']?>
<?php
                                }
                                else{
?>
                                    <?=formatFestivalDate($r_days['date'])?>
<?php
                                }
?>
                            </h4>
                        </div>
                        <div class='day_schedule' id='day_schedule_<?=$i?>'>
<?php
                            $q_sch = mysqli_query($con, "SELECT festival_event_$schi.id AS id, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event_$schi, place WHERE place.id = festival_event_$schi.place AND date(DATE_SUB(start, INTERVAL 6 HOUR)) = str_to_date('$r_days[date]', '%Y-%m-%d') ORDER BY start");
                            if (mysqli_num_rows($q_sch) > 0){
                                echo("<table class='schedule'>\n");
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
                                                <a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch['lat']?>,<?=$r_sch['lon']?>+(My+Point)&z=14&ll=<?=$r_sch['lat']?>,<?=$r_sch['lon']?>'>
                                                    <img alt=' ' src='<?=$server?>/img/misc/pinpoint.png'/>
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
                                                </a>
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
        </div>
<?php
            include("../footer.php");
            $ad = ad($con, $lang, $lng);
            stats($ad, $ad_static, "fiestas", "");
?>
    </body>
</html>

<?php
     }
?>
