<?php
    session_start();
    $http_host = $_SERVER['HTTP_HOST'];
    include("functions.php");
    $proto = getProtocol();
    $con = startdb();
    $server = "$proto$http_host";

    //Language
    $lang = selectLanguage();
    include("lang/lang_" . $lang . ".php");

    $cur_section = $lng['section_home'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1"/>
        <title><?=$lng['index_title']?></title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico"/>
        <!-- CSS files -->
        <style>
<?php
            include("css/ui.css");
            include("css/index.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php
            include("css/m/ui.css");
            include("css/m/index.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
            <?php include("script/ui.js"); ?>
        </script>
        <!-- Meta tags -->
        <link rel="canonical" href="<?=$server?>"/>
        <link rel="author" href="<?=$server?>"/>
        <link rel="publisher" href="<?=$server?>"/>
        <meta name="description" content="<?php echo $lng['index_us_content'];?>"/>
        <meta property="og:title" content="<?php echo $lng['index_title'];?>"/>
        <meta property="og:url" content="<?=$server?>"/>
        <meta property="og:description" content="<?=$lng['index_us_content']?>"/>
        <meta property="og:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta property="og:site_name" content="<?=$lng['index_title']?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="<?php echo $lang; ?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?=$lng['index_title']?>"/>
        <meta name="twitter:description" content="<?=$lng['index_us_content']?>"/>
        <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta name="twitter:url" content="<?=$server?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
        <?php include("header.php"); ?>
        <div id="content">

<?php
                //Location section
                $q_location = mysqli_query($con, "SELECT lat, lon FROM location WHERE action = 'report' AND dtime > NOW() - INTERVAL 30 MINUTE ORDER BY dtime DESC LIMIT 1;");
                if (mysqli_num_rows($q_location) > 0){
                    $r_location = mysqli_fetch_array($q_location);
?>
                    <div class='section' id='location'>
                    <h3 class='section_title'><?=$lng[index_festivals_location]?></h3>
                        <div class='entry' id='map'>
                            <iframe src='https://www.google.com/maps/embed/v1/place?key=AIzaSyCZHP7t2on_G3eyyoCTfhGAlDx1mJnX7iI&q=<?=$r_location[lat]?>,<?=$r_location[lon]?>' allowfullscreen></iframe>
                        </div>
                    </div>
<?php
                }

                //Festivals section
                $year = date("Y");
                $q_settings = mysqli_query($con, "SELECT value FROM settings WHERE name = 'festivals';");
                $r_settings = mysqli_fetch_array($q_settings);
                $festivals = $r_settings['value'];
                if ($festivals == 1){
?>
                    <div class='section' id='festivals' itemscope itemtype='http://schema.org/Event'>
                        <div class='hidden' itemprop='organizer performer' itemscope itemtype='http://schema.org/Organization'>
                            <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                            <meta itemprop='name' content='Gasteizko Margolariak'/>
                            <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                            <meta itemprop='foundingDate' content='2013-02-03'/>
                            <meta itemprop='telephone' content='+34637140371'/>
                        </div> <!-- .hidden -->

                        <meta itemprop='inLanguage' content='<?=$lang?>'/>
                        <meta itemprop='name' content='<?=$lng['index_festivals_header']?> <?=$year?>'/>
                        <meta itemprop='description' content='<?=$lng['index_festivals_header']?> <?=$year?>'/>
                        <meta itemprop='startDate' content='<?=$year?>-08-04'/>
                        <meta itemprop='endDate' content='<?=$year?>-08-09'/>
                        <meta itemprop='url' content='<?=$server?>/lablanca/'/>
                        <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                            <meta itemprop='name' content='Vitoria-Gasteiz'/>
                            <meta itemprop='address' content='Vitoria-Gasteiz'/>
                        </span>
                        <h3 class='section_title'><?=$lng['index_festivals_header']?> <?=$year?></h3>
                        <div class='entry' id='festivals_summary'>
<?php
                            // Offers (for metadata)
                            $q_offer = mysqli_query($con, "SELECT name_$lang AS name, description_$lang AS description, price FROM festival_offer WHERE year = $year");
                            while($r_offer = mysqli_fetch_array($q_offer)){
?>
                                <span class='hidden' itemprop='offers' itemscope itemtype='http://schema.org/Offer'>
                                    <meta itemprop='name' content='<?=$r_offer['name']?>'/>
                                    <meta itemprop='description' content='<?=$r_offer['description']?>'/>
                                    <meta itemprop='price' content='<?=$r_offer['price']?>'/>
                                    <meta itemprop='priceCurrency' content='EUR'/>
                                    <meta itemprop='url' content='<?=$server?>/lablanca/'/>
                                    <meta itemprop='availability' content='Sold Out'/>
                                    <meta itemprop='validfrom' content='<?=$year?>-07-25'/>
                                </span>
<?php
                            }
                            // Summary
                            $q_festivals = mysqli_query($con, "SELECT text_$lang AS text, summary_$lang AS summary, img FROM festival WHERE year = $year;");
                            if(mysqli_num_rows($q_festivals)){
                                $r_festivals = mysqli_fetch_array($q_festivals);
                                if ($r_festivals['img'] != ''){
?>
                                    <meta itemprop='image' content='<?=$server?>/img/fiestas/view/<?=$r_festivals['img']?>'/>
                                    <img id='festivals_image' alt='<?=$lng['index_festivals_header'] . ' ' . $year?>' src='<?=$server?>/img/fiestas/preview/<?=$r_festivals['img']?>'/>
<?php
                                }
                                if ($r_festivals['summary'] != ''){
?>
                                    <br/><span class='entry_title' id='festivals_summary_text'><?=$r_festivals['summary']?></span>
                                    <br/><br/><br/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='<?=$server?>/lablanca/'><?=$lng['index_festivals_link']?></a>
<?php
                                }
                                else{
?>
                                    <span id='festivals_summary_text'>
                                        <br/>
                                        <span class='mobile'><?=cutText($r_festivals['text'], 300, "$lng[index_read_more]", "$server/lablanca/")?></span>
                                        <span class='desktop'><?=cutText($r_festivals['text'], 500, "$lng[index_read_more]", "$server/lablanca/")?></span>
                                    </span>
<?php
                                }
                            }
?>
                        </div> <!--festivals_summary - entry-->
                        <div id='festivals_schedule_table'>
                            <div id='festivals_schedule_row'>
                                <div class='festivals_schedule_cell'>
<?php
                                    //GM schedule
                                    $q_sch_curr = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > NOW() - INTERVAL 45 MINUTE AND start < NOW() + INTERVAL 30 MINUTE ORDER BY start LIMIT 1;");
                                    $q_sch_next = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 1 AND start > NOW() AND start < NOW() + INTERVAL 240 MINUTE ORDER BY start LIMIT 1;");
                                    if (mysqli_num_rows($q_sch_curr) > 0 || mysqli_num_rows($q_sch_next) > 0){
?>
                                        <div class='entry festival_schedule' id='festivals_gm'>
                                            <h3 class='entry_title'><?=$lng['index_festivals_gm_schedule']?></h3>
<?php
                                        if (mysqli_num_rows($q_sch_curr) > 0){
                                            $r_sch_curr = mysqli_fetch_array($q_sch_curr);
?>
                                            <div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
                                                <h4><?=$lng[index_festivals_schedule_now]?></h4>
                                                <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                                <meta itemprop='name' content='<?=$r_sch_curr['title']?>'/>
                                                <meta itemprop='startDate' content='<?=$r_sch_curr['isostart']?>'/>
                                                <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                                    <meta itemprop='address' content='Vitoria-Gasteiz'/>
                                                </span>
<?php
                                                if (strlen($r_sch_curr['isoend']) > 0){
?>
                                                    <meta itemprop='endDate' content='<?=$r_sch_curr['isoend']?>'/>
<?php
                                                }
?>
                                                <div class='schedule_content'>
                                                    <span class='title'><?=$r_sch_curr['title']?></span>
<?php
                                                    if (strlen($r_sch_curr["description"]) > 0 && $r_sch_curr["description"] != $r_sch_curr["title"]){
?>
                                                        <br/><p class='description'><?=$r_sch_curr['description']?></p>
                                                        <meta itemprop='description' content='<?=$r_sch_curr['description']?>'/>
<?php
                                                    }
?>
                                                    <table class='location'>
                                                        <tr>
                                                            <td>
                                                                <a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>+Gasteizko+Margolariak)&z=14&ll=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>'>
                                                                    <img alt='<?=$r_sch_curr['title']?>'' src='<?server?>/img/misc/pinpoint.png'/>
                                                                </a>
                                                            </td>
                                                            <td>
<?php
                                                                //If name and address are the same, show only name
                                                                if ($r_sch_curr['place'] == $r_sch_curr['address']){
?>
                                                                    <?=$r_sch_curr['place']?>
<?php
                                                                }
                                                                else{
?>
                                                                    <?=$r_sch_curr['place']?><br/>
                                                                    <span class='address'><?=$r_sch_curr['address']?></span>
<?php
                                                                }
?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div> <!--.schedule_content  -->
                                            </div> <!-- festival-event -->
<?php
                                        } // if (mysqli_num_rows($q_sch_curr) > 0)
                                        if (mysqli_num_rows($q_sch_next) > 0){
                                            $r_sch_next = mysqli_fetch_array($q_sch_next);
?>
                                            <div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
                                                <h4><?=$lng['index_festivals_schedule_next']?></h4>
                                                <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                                <meta itemprop='name' content='<?=$r_sch_next['title']?>'/>
                                                <meta itemprop='startDate' content='<?=$r_sch_next['isostart']?>'/>
                                                <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                                    <meta itemprop='address' itemprop='name'>Vitoria-Gasteiz</meta>
                                                </span>
<?php
                                                if (strlen($r_sch_curr['isoend']) > 0){
?>
                                                    <meta itemprop='endDate' content='<?=$r_sch_next['isoend']?>'/>
<?php
                                                }
?>
                                                <div class='schedule_content'>
                                                    <span class='title'><?=$r_sch_next['title']?> - <?=$r_sch_next['st']?></span>
<?php
                                                    if (strlen($r_sch_next["description"]) > 0 && $r_sch_next["description"] != $r_sch_next["title"]){
?>
                                                        <br/><p class='description'><?=$r_sch_next['description']?></p>
                                                        <meta itemprclass='entry'op='description' content='<?=$r_sch_next['description']?>'/>
<?php
                                                    }
?>
                                                    <table class='location'>
                                                        <tr>
                                                            <td>
                                                                <a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>+(My+Point)&z=14&ll=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>'>
                                                                    <img alt=' ' src='<?=server?>/img/misc/pinpoint.png'/>
                                                                </a>
                                                            </td>
                                                            <td>
<?php
                                                                //If name and address are the same, show only name
                                                                if ($r_sch_next['place'] == $r_sch_next['address']){
                                                                    echo("$r_sch_next[place]\n");
                                                                }
                                                                else{
                                                                    echo("$r_sch_next[place] </br><span class='address'> $r_sch_next[address]</span>\n");
                                                                }
?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div> <!--.schedule_content  -->
                                            </div> <!-- festival-event -->
<?php
                                        }
?>
                                        </div> <!--festivals_gm-->
<?php
                                    } // if (mysqli_num_rows($q_sch_curr) > 0 || ysqli_num_rows($q_sch_next) > 0)
?>
                                </div> <!-- festivals_schedule_cell -->
                                <div class='festivals_schedule_cell'>
<?php
                                    //City schedule
                                    $q_sch_curr = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 0 AND start > NOW() - INTERVAL 45 MINUTE AND start < NOW() + INTERVAL 30 MINUTE ORDER BY start LIMIT 1;");
                                    $q_sch_next = mysqli_query($con, "SELECT festival_event.id AS id, gm, title_$lang AS title, description_$lang AS description, host, place, date_format(start, '%H:%i') AS st, date_format(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:00') AS isostart, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:00') AS isoend, place.name_$lang AS place, address_$lang AS address, lat, lon FROM festival_event, place WHERE place.id = festival_event.place AND gm = 0 AND start > NOW() AND start < NOW() + INTERVAL 240 MINUTE ORDER BY start LIMIT 1;");
                                    if (mysqli_num_rows($q_sch_curr) > 0 || mysqli_num_rows($q_sch_next) > 0){
                                        echo "<div class='entry festival_schedule' id='festivals_city'>\n";
                                        echo "<h3 class='entry_title'>$lng[index_festivals_city_schedule]</h3>\n";
                                        if (mysqli_num_rows($q_sch_curr) > 0){
                                            $r_sch_curr = mysqli_fetch_array($q_sch_curr);
?>
                                            <div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
                                                <h4><?=$lng['index_festivals_schedule_now']?></h4>
                                                <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                                <meta itemprop='name' content='<?=$r_sch_curr['title']?>'/>
                                                <meta itemprop='startDate' content='<?=$r_sch_curr['isostart']?>'/>
                                                <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                                    <meta itemprop='address' itemprop='name'>Vitoria-Gasteiz</meta>
                                                </span>
<?php
                                                if (strlen($r_sch_curr['isoend']) > 0){
                                                    echo("<meta itemprop='endDate' content='$r_sch_curr[isoend]'/>\n");
                                                }
?>
                                                <div class='schedule_content'>
                                                    <span class='title'><?=$r_sch_curr['title']?></span>
<?php
                                                    if (strlen($r_sch_curr["description"]) > 0 && $r_sch_curr["description"] != $r_sch_curr["title"]){
                                                        echo("<br/><p class='description'>$r_sch_curr[description]</p>\n");
                                                        echo("<meta itemprop='description' content='$r_sch_curr[description]'/>\n");
                                                    }
?>
                                                    <table class='location'>
                                                        <tr>
                                                            <td>
                                                                <a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>+(My+Point)&z=14&ll=<?=$r_sch_curr[lat]?>,<?=$r_sch_curr[lon]?>'>
                                                                    <img alt=' ' src='<?=$server?>/img/misc/pinpoint.png'/>
                                                                </a>
                                                            </td>
                                                            <td>
<?php
                                                                //If name and address are the same, show only name
                                                                if ($r_sch_curr['place'] == $r_sch_curr['address']){
                                                                    echo("$r_sch_curr[place]\n");
                                                                }
                                                                else{
                                                                    echo("$r_sch_curr[place] <br/><span class='address'> $r_sch_curr[address]</span>\n");
                                                                }
?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div> <!--.schedule_content  -->
                                            </div> <!-- festival-event -->
<?php
                                        }
                                        if (mysqli_num_rows($q_sch_next) > 0){
                                            $r_sch_next = mysqli_fetch_array($q_sch_next);
?>
                                            <div class='festival_event' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
                                                <h4><?=$lng['index_festivals_schedule_next']?></h4>
                                                <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                                <meta itemprop='name' content='<?=$r_sch_next['title']?>'/>
                                                <meta itemprop='startDate' content='<?=$r_sch_next['isostart']?>'/>
                                                <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                                    <meta itemprop='address' itemprop='name'>Vitoria-Gasteiz</meta>
                                                </span>
<?php
                                                if (strlen($r_sch_curr['isoend']) > 0){
                                                    echo "<meta itemprop='endDate' content='$r_sch_next[isoend]'/>\n";
                                                }
?>
                                                <div class='schedule_content'>
                                                    <span class='title'><?=$r_sch_next['title']?> - <?=$r_sch_next['st']?></span>
<?php
                                                    if (strlen($r_sch_next["description"]) > 0 && $r_sch_next["description"] != $r_sch_next["title"]){
                                                        echo "<br/><p class='description'>$r_sch_next[description]</p>\n";
                                                        echo "<meta itemprop='description' content='$r_sch_next[description]'/>\n";
                                                    }
?>
                                                    <table class='location'>
                                                        <tr>
                                                            <td>
                                                                <a target='_blank' href='http://maps.google.com/maps?q=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>+(My+Point)&z=14&ll=<?=$r_sch_next[lat]?>,<?=$r_sch_next[lon]?>'>
                                                                    <img alt=' ' src='<?=$server?>/img/misc/pinpoint.png'/>
                                                                </a>
                                                            </td>
                                                            <td>
<?php
                                                                //If name and address are the same, show only name
                                                                if ($r_sch_next['place'] == $r_sch_next['address']){
                                                                    echo("$r_sch_next[place]\n");
                                                                }
                                                                else{
                                                                    echo("$r_sch_next[place] <br/><span class='address'> $r_sch_next[address]</span>\n");
                                                                }
?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div> <!--.schedule_content  -->
                                            </div> <!-- festival-event -->
<?php
                                        }
?>
                                        </div> <!--festivals_schedule-->
<?php
                                    }
?>
                                </div> <!-- festivals_schedule_cell -->
                            </div> <!-- festivals_schedule_row -->
                        </div> <!-- festivals_schedule_table -->
                        <a class='go_to_section' href='<?=$server?>/lablanca/'><?=$lng["index_festivals_link"]?></a>
                        <br/>
                    </div> <!-- festivals - section-->
<?php
                }
                //Upcaming activity section
                $q_activity = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, text_$lang AS text, price, inscription, people, max_people, city FROM activity WHERE visible = 1 AND date >= date(now()) ORDER BY date LIMIT 2;");
                $upcoming_activity_shown = false;
                if (mysqli_num_rows($q_activity) > 0){
                    $upcoming_activity_shown = true;
?>
                    <div class='section'>
                        <h3 class='section_title'><?=$lng['index_upcoming_activity']?></h3>
<?php
                        while($r_activity = mysqli_fetch_array($q_activity)){
?>
                            <div class='entry' itemscope itemtype='http://schema.org/Event'>
                                <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                <meta itemprop='name' content='<?=$r_activity['title']?>'/>
                                <meta itemprop='description' content='<?=$r_activity['text']?>'/>
                                <meta itemprop='startDate endDate' content='<?=$r_activity['isodate']?>'/>
                                <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                    <meta itemprop='address' itemprop='name' content='=<?=$r_activity[city]?>'/>
                                </span>
                                <meta itemprop='url' content='<?=$server?>/actividades/<?=$r_activity['permalink']?>'/>
                                <div class='hidden' itemprop='organizer performer' itemscope itemtype='http://schema.org/Organization'>
                                    <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                                    <meta itemprop='name' content='Gasteizko Margolariak'/>
                                    <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                                    <meta itemprop='foundingDate' content='2013-02-03'/>
                                    <meta itemprop='telephone' content='+34637140371'/>
                                </div> <!-- .hidden -->
                                <div id='upcoming_activity' class='table'>
                                    <div class='tr'>
<?php
                                        //If image, show it
                                        $q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
                                        if (mysqli_num_rows($q_activity_image) > 0){
                                            $r_activity_image = mysqli_fetch_array($q_activity_image);
?>
                                            <div class='td'>
                                                <div id='upcoming_image'>
                                                    <a href='<?=$server?>/actividades/<?=$r_activity['permalink']?>'>
                                                        <meta itemprop='image' content='<?=$server?>/img/actividades/<?=$r_activity_image['image']?>'/>
                                                        <img src='<?=$server?>/img/actividades/miniature/<?=$r_activity_image['image']?>' alt='<?=$r_activity['title']?>'/>
                                                    </a>
                                                </div> <!-- #upcoming_image -->
                                            </div> <!-- .td-->
<?php
                                        }
?>
                                        <div class='td'>
                                            <div id='upcoming_text'>
                                                <h3 class='entry_title'>
                                                    <a itemprop='url' href='<?=$server?>/actividades/<?=$r_activity['permalink']?>'><?=$r_activity['title']?></a>
                                                </h3>
                                                <p class='mobile'><?=cutText($r_activity['text'], 250, $lng['index_read_more'], "$server/actividades/$r_activity[permalink]")?></p>
                                                <p class='desktop'><?=cutText($r_activity['text'], 450, $lng['index_read_more'], "$server/actividades/$r_activity[permalink]")?></p>
                                            </div> <!-- #upcoming_text -->
                                        </div> <!-- .td -->
                                        <div class='td'>
                                            <div id='upcoming_details'>
                                                <table>
                                                    <tr>
                                                        <td><?=$lng['index_upcoming_activity_date']?></td>
                                                        <td><?=formatDate($r_activity['date'], $lang, false)?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?=$lng['index_upcoming_activity_price']?></td>
<?php
                                                        if ($r_activity['price'] == 0){
?>
                                                            <td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>
                                                                <?=$lng['index_upcoming_activity_free']?>
                                                                <meta itemprop='priceCurrency' content='EUR'/>
                                                                <meta itemprop='price' content='0'/>
                                                                <meta itemprop='availability' content='Sold Out'/>
                                                                <meta itemprop='validfrom' content='<?=$r_activity['isodate']?>'/>
                                                                <meta itemprop='url' content='<?=$server?>/actividades/<?=$r_activity['permalink']?>'/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?=$lng['index_upcoming_activity_inscription']?></td>
<?php
                                                            if ($r_activity['inscription'] == 1){
?>
                                                                <td><?=$lng['yes']?></td>
<?php
                                                            }
                                                            else{
?>
                                                                <td><?=$lng['no']?></td>
<?php
                                                            }
                                                        }
                                                        else{
?>
                                                            <td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>
                                                                <?=$r_activity['price']?> €
                                                                    <meta itemprop='priceCurrency' content='EUR'/>
                                                                    <meta itemprop='availability' content='Sold Out'/>
                                                                    <meta itemprop='validfrom' content='<?=$r_activity['isodate']?>'/>
                                                                    <meta itemprop='price' content='<?=$r_activity['price']?>'/>
                                                            </td>
<?php
                                                        }
?>
                                                    </tr>
<?php
                                                    if ($r_activity['max_people'] > 0){
?>
                                                        <tr>
                                                            <td><?=$lng['index_upcoming_activity_max_people']?></td>
                                                            <td><?=$r_activity['max_people']?></td>
                                                        </tr>
<?php
                                                    }
?>
                                                </table>
                                                <a href='<?=$server?>/actividades/<?=$r_activity['permalink']?>'><?=$lng['index_upcoming_activity_see']?></a>
                                               <br/><br/>
                                            </div> <!-- #upcoming_details -->
                                        </div> <!-- .td -->
                                    </div> <!-- .tr -->
                                </div> <!-- .table -->
                            </div> <!-- .entry -->
<?php
                        } // while($r_activity = mysqli_fetch_array($q_activity))
?>
                        <a class='go_to_section' href='<?=$server?>/actividades/'><?=$lng['index_upcoming_activity_see_all']?></a>
                        <br/>
                    </div> <!-- .section -->
<?php
                }
?>
                <div class='section' itemscope itemtype='http://schema.org/Organization'>
                    <meta itemprop='url' content='<?=$server?>'/>
                    <h3 class='section_title' itemprop='name'><?=$lng['index_us']?></h3>
                    <div class='entry' id='us'>
                        <p itemprop='description'><?=$lng['index_us_content']?></p>
                    </div> <!-- .entry -->
                    <a class='go_to_section' href='<?=$server?>/nosotros/'><?=$lng['index_us_more']?></a>
                    </br>
                </div> <!-- .section -->
								<br/>
            <div id='content_table'>
                <div class='content_row'>
                    <div class='content_cell' id='cell_posts'>
                        <div class='section' id='latest_posts'>
                            <h3 class='section_title'><?=$lng['index_latest_posts']?></h3>
<?php
                                $q_post = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, dtime, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate FROM post WHERE visible = 1 ORDER BY dtime DESC LIMIT 2;");
                                if (mysqli_num_rows($q_post) == 0){
?>
                                    <div class='entry'>
                                        <h3 class='entry_title'><?=$lng['index_no_post']?></h3>
                                    </div>
<?php
                                }
                                else{
                                    while ($r_post = mysqli_fetch_array($q_post)){
?>
                                        <div class='entry post' itemscope itemtype='http://schema.org/BlogPosting'>
                                            <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                            <meta itemprop='datePublished dateModified' content='<?=$r_post["isodate"]?>'/>
                                            <meta itemprop='headline name' content='<?=$r_post["title"]?>'/>
                                            <meta itemprop='articleBody text' content='<?=$r_post["text"]?>'/>
                                            <meta itemprop='mainEntityOfPage' content='<?=$server?>'/>
                                            <div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>
                                                <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                                                <meta itemprop='name' content='Gasteizko Margolariak'/>
                                                <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                                                <meta itemprop='foundingDate' content='03-02-2013'/>
                                                <meta itemprop='telephone' content='+34637140371'/>
                                                <meta itemprop='url' content='<?=$server?>'/>
                                            </div>
<?php
                                           $q_post_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r_post[id] ORDER BY idx LIMIT 1;");
                                            if (mysqli_num_rows($q_post_image) > 0){
                                                $r_post_image = mysqli_fetch_array($q_post_image);
?>
                                                <a href='<?=$server?>/blog/<?=$r_post['permalink']?>'>
                                                    <img itemprop='image' src='<?=$server?>/img/blog/miniature/<?=$r_post_image['image']?>'/>
                                                </a>
<?php
                                            } //if (mysqli_num_rows($q_post_image) > 0)
?>
                                            <h3 class='entry_title'>
                                                <a itemprop='url' href='<?=$server?>/blog/<?=$r_post['permalink']?>'><?=$r_post['title']?></a>
                                            </h3>
                                            <p class='mobile'><?=cutText($r_post['text'], 100, $lng['index_read_more'], "$server/blog/$r_post[permalink]")?></p>
                                            <p class='desktop'><?=cutText($r_post['text'], 150, $lng['index_read_more'], "$server/blog/$r_post[permalink]")?></p>
                                            <span><?=formatDate($r_post['dtime'], $lang, false)?></span>
                                        </div> <!-- .entry -->
<?php
                                    } //while ($r_post = mysqli_fetch_array($q_post))
?>

                                    <a class='go_to_section' href='<?=$server?>/blog/'><?$lng['index_see_all_posts']?></a>
                                    <br/>
<?php
                                } //if (mysqli_num_rows($q_post) == 0) ELSE
?>
                            </div> <!-- .section-->
                    </div> <!-- .cell_posts-->
                    <div class='content_cell' id='cell_photos'>
                        <div class='section' id='latest_photos'>
                            <h3 class='section_title'><?=$lng['index_latest_photos']?></h3>
<?php
                                $q_photos = mysqli_query($con, "SELECT id, file, title_$lang AS title, DATE_FORMAT(uploaded, '%Y-%m-%d') AS isodate FROM photo WHERE approved = 1 ORDER BY uploaded DESC LIMIT 6;");
                                if (mysqli_num_rows($q_photos) == 0){
?>
                                    <div class='entry'><?=$lng['index_no_photos']?></div>
<?php
                                }
                                else{
?>
                                    <div class='entry'>
                                        <table id='table_photos'>
                                            <tr>
<?php
                                                $i = 0;
                                                while ($r_photos = mysqli_fetch_array($q_photos)){
                                                    $q_album = mysqli_query($con, "select permalink from album, photo_album WHERE photo = $r_photos[id] AND id = album LIMIT 1;");
                                                    $r_album = mysqli_fetch_array($q_album);
?>
                                                    <td itemscope itemtype='http://schema.org/Photograph'>
                                                        <meta itemprop='datePublished' content='<?=$r_photos['isodate']?>'/>
                                                        <a href='$server/galeria/<?=$r_album['permalink']?>'>
                                                            <meta itemprop='image' content='<?=$server?>/img/galeria/<?=$r_photos["file"]?>'/>
                                                            <img src='<?=$server?>/img/galeria/miniature/<?=$r_photos['file']?>' alt='<?=$r_photos['title']?>' />
                                                        </a>
                                                    </td>
<?php
                                                    $i ++;
                                                    if ($i % 2 == 0){
?>
                                                        </tr>
                                                        <tr>
<?php
                                                    } //if ($i % 2 == 0)
                                                } //while ($r_photos = mysqli_fetch_array($q_photos))
?>
                                            </tr>
                                        </table>
                                    </div> <!-- .entry -->
                                    <a class='go_to_section' href='<?=$server?>/galeria/'><?=$lng['index_see_all_photos']?></a>
                                    <br/>
<?php
                                } //if (mysqli_num_rows($q_photos) == 0) ELSE
?>
                            </div> <!-- .section -->
                        </div> <!-- .content_cell -->
                    </div> <!-- .content_row -->
                </div> <!-- .table -->
<?php
                if ($upcoming_activity_shown == false){
                    $q_activity = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, text_$lang AS text, after_$lang AS after, price, inscription, people, max_people, city FROM activity WHERE visible = 1 ORDER BY date DESC LIMIT 2;");
                    if (mysqli_num_rows($q_activity) > 0){
?>
                        <div class='section' id='latest_activities'>
                            <h3 class='section_title'><?=$lng['index_latest_activities']?></h3>
<?php
                            while ($r_activity = mysqli_fetch_array($q_activity)){
?>
                                <div class='entry' itemscope itemtype='http://schema.org/Event'>
                                    <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                    <meta itemprop='name' content='<?=$r_activity['title']?>'/>
                                    <meta itemprop='description' content='<?=$r_activity['text']?>'/>
                                    <meta itemprop='startDate endDate' content='<?=$r_activity['isodate']?>'/>
                                    <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                        <meta itemprop='address' itemprop='name' content='<?=$r_activity['city']?>'/>
                                        <meta itemprop='name' content='<?=$r_activity['city']?>'/>
                                    </span>
                                    <div class='hidden' itemprop='organizer performer' itemscope itemtype='http://schema.org/Organization'>
                                        <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                                        <meta itemprop='name' content='Gasteizko Margolariak'/>
                                        <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                                        <meta itemprop='foundingDate' content='03-02-2013'/>
                                        <meta itemprop='telephone' content='+34637140371'/>
                                        <meta itemprop='url' content='<?=$server?>'/>
                                    </div> <!-- .hidden -->



                                    <span class='hidden' itemprop='offers' itemscope itemtype='http://schema.org/Offer'>
                                        <meta itemprop='name' content='<?=$r_activity['title']?>'/>
                                        <meta itemprop='description' content='<?=$r_activity['text']?>'/>
                                        <meta itemprop='price' content='<?=$r_activity['price']?>'/>
                                        <meta itemprop='priceCurrency' content='EUR'/>
                                        <meta itemprop='url' content='<?=$server?>/actividades/<?=$r_activity['permalink']?>'/>
                                        <meta itemprop='availability' content='Sold Out'/>
                                        <meta itemprop='validfrom' content='<?=$r_activity['isodate']?>'/>
                                    </span>



                                    <h3 class='entry_title'>
                                        <a itemprop='url' href='<?=$server?>/actividades/<?=$r_activity['permalink']?>'><?=$r_activity['title']?></a>
                                    </h3>
                                    <table class='latest_activity'>
                                        <tr>
<?php
                                            $q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
                                            if (mysqli_num_rows($q_activity_image) > 0){
                                                $r_activity_image = mysqli_fetch_array($q_activity_image);
?>
                                                <td class='latest_activity_image'>
                                                    <a href='<?=$server?>/actividades/<?=$r_activity['permalink']?>'>
                                                        <meta itemprop='image' content='<?=$server?>/img/actividades/<?=$r_activity_image['image']?>'/>
                                                        <img src='<?=$server?>/img/actividades/miniature/<?=$r_activity_image['image']?>' alt='<?=$r_activity['title']?>'/>
                                                    </a>
                                                </td>
<?php
                                            } // if (mysqli_num_rows($q_activity_image) > 0)
                                            if ($r_activity['after'] == ''){
?>
                                                <td class='mobile latest_activity_text'>
                                                    <?=cutText($r_activity['text'], 260, "$lng[index_read_more]", "$server/actividades/$r_activity[permalink]")?>
                                                </td>
                                                <td class='desktop latest_activity_text'>
                                                    <?=cutText($r_activity['text'], 400, "$lng[index_read_more]", "$server/actividades/$r_activity[permalink]")?>
                                                </td>
<?php
                                            } // if ($r_activity['after'] == '')
                                            else{
?>
                                                <td class='mobile latest_activity_text'>
                                                    <?=cutText($r_activity['after'], 260, "$lng[index_read_more]", "$server/actividades/$r_activity[permalink]")?>
                                                </td>
                                                <td class='desktop latest_activity_text'>
                                                    <?=cutText($r_activity['after'], 400, "$lng[index_read_more]", "$server/actividades/$r_activity[permalink]")?>
                                                </td>
<?php
                                            } // if ($r_activity['after'] == '') ELSE
?>
                                        </tr>
                                    </table>
                                </div> <!-- .entry -->
<?php
                            }
?>
                            <a class='go_to_section' href='<?=$server?>/actividades/'><?=$lng['index_upcoming_activity_see_all']?></a>
                            <br/>
                        </div> <!-- .section -->
<?php
                    } // if (mysqli_num_rows($q_activity) > 0)
                } // if ($upcoming_activity_shown == false)

                //Festivals section (if no festivals shown on top)
                if ($festivals == 0){
?>
                    <div class='section'>
                        <h3 class='section_title'><?=$lng['index_festivals_header']?></h3>
                        <div class='entry'>
                            <span class='desktop'>
                                <?=cutText($lng['lablanca_no_content'] . '<br/>' . $lng['lablanca_no_content_2'], 300, $lng['index_read_more'], "$server/lablanca/")?>
                            </span>
                            <span class='desktop'>
                                <?=cutText($lng['lablanca_no_content'] . '<br/>' . $lng['lablanca_no_content_2'], 200, $lng['index_read_more'], "$server/lablanca/")?>
                            </span>
                        </div> <!-- .entry -->
                    </div> <!-- .section -->
<?php
                } // if ($festivals == 0)
?>
        </div> <!-- #content -->
<?php

        //Footer
        include("footer.php");
        $ad = ad($con, $lang, $lng);
        stats($ad, $ad_static, "index", "");
?>
    </body>
</html>
