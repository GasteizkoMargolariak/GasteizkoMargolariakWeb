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

    $cur_section = $lng["section_activities"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$lng["activities_title"]?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?php echo "$server/img/logo/favicon.ico";?>">
        <!-- CSS files -->
        <style>
<?php
            include("../css/ui.css");
            include("../css/actividades.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php
            include("../css/m/ui.css");
            include("../css/m/actividades.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
<?php
            include("../script/ui.js");
            include("../script/actividades.js");
?>
        </script>
        <!-- Meta tags -->
        <link rel='canonical' href='<?=$server?>/actividades/'/>
        <link rel='author' href='<?=$server?>'/>
        <link rel='publisher' href='<?=$server?>'/>
        <meta name='description' content='<?=$lng["activities_description"]?>'/>
        <meta property='og:title' content='<?=$lng["activities_title"]?> - Gasteizko Margolariak'/>
        <meta property='og:url' content='<?=$server?>/activities/'/>
        <meta property='og:description' content='<?=$lng["activities_description"]?>'/>
        <meta property='og:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta property='og:site_name' content='<?=$lng["index_title"]?>'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='<?=$lang?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$lng["activities_title"]?> - Gasteizko Margolariak'/>
        <meta name='twitter:description' content='<?=$lng["activities_description"]?>'/>
        <meta name='twitter:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta name='twitter:url' content='<?=$server?>'/>
        <meta name='robots' content='index follow'/>
    </head>
    <body>
<?php
        include("../header.php")
?>
        <div id='content'>

            <div class='section'>
                <h3 class='section_title'><?=$lng["activities_upcoming"]?></h3>
<?php
                $q_upcoming = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, text_$lang AS text, price, inscription, people, max_people, user, dtime, comments, city FROM activity WHERE visible = 1 AND date > now() ORDER BY date;");
                if (mysqli_num_rows($q_upcoming) > 0){
                    while ($r_activity = mysqli_fetch_array($q_upcoming)){
?>
                        <div class='entry' itemscope itemtype='http://schema.org/Event'>
                            <meta itemprop='inLanguage' content='<?=$lang?>'/>
                            <meta itemprop='name' content='<?=$r_activity["title"]?>'/>
                            <meta itemprop='description' content='<?=$r_activity["text"]?>'/>
                            <meta itemprop='startDate endDate' content='<?=$r_activity["isodate"]?>'/>
                            <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                <meta itemprop='address' itemprop='name' content='<?=$r_activity["city"]?>'/>
                                <meta itemprop='name' content='<?=$r_activity["city"]?>'/>
                            </span>
                            <div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>
                                <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                                <meta itemprop='name' content='Gasteizko Margolariak'/>
                                <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                                <meta itemprop='foundingDate' content='03-02-2013'/>
                                <meta itemprop='telephone' content='+34637140371'/>
                                <meta itemprop='url' content='<?=$server?>'/>
                            </div>
                            <div id='upcoming_text'>
                                <h3 class='entry_title'>
                                    <a itemprop='url' href=<?=$server?>/actividades/<?=$r_activity["permalink"]?>'><?=$r_activity["title"]?></a>
                                    <span class='title_date'> - <?=formatDate($r_activity["date"], $lang, false)?></span>
                                </h3>
                                <table class='future_details'>
                                    <tr>
<?php
                                        //If image, show it
                                        $q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
                                        if (mysqli_num_rows($q_activity_image) > 0){
                                            $r_activity_image = mysqli_fetch_array($q_activity_image);
?>
                                            <td>
                                                <div id='upcoming_image'>
                                                    <a href='<?=$server?>/actividades/<?=$r_activity["permalink"]?>'>
                                                        <meta itemprop='image' content='<?=$server?>/img/actividades/view/<?=$r_activity_image["image"]?>'/>
                                                        <img src='<?=$server?>/img/actividades/preview/<?=$r_activity_image["image"]?>' alt='<?=$r_activity["title"]?>'/>
                                                    </a>
                                                </div>
                                            </td>
<?php
                                        }
?>
                                        <td>
                                            <table class='future_data'>
                                                <tr>
                                                    <td class='title'><?=$lng["activities_city"]?></td>
                                                    <td><?=$r_activity["city"]?></td>
                                                </tr>
<?php
                                                if ($r_activity["price"] == 0){
?>
                                                    <tr>
                                                        <td class='title'><?=$lng["activities_price"]?></td>
                                                        <td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>
                                                            <meta itemprop='priceCurrency' content='EUR'/>
                                                            <meta itemprop='price' content='0'/>
                                                            <?=$lng["activities_price_0"]?>
                                                        </td>
                                                    </tr>
<?php
                                                }
                                                else{
?>
                                                    <tr>
                                                        <td class='title'><?=$lng["activities_price"]?></td>
                                                        <td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>
                                                            <meta itemprop='priceCurrency' content='EUR'/>
                                                            <meta itemprop='price' content='<?=$r_activity["price"]?>'/>
                                                            <?=$r_activity["price"]?>€
                                                        </td>
                                                    </tr>
<?php
                                                }
                                                if ($r_activity["max_people"] != 0){
?>
                                                    <tr>
                                                        <td><?=$lng["activities_maxpeople"]?></td>
                                                        <td><?=$r_activity["max_people"]?></td>
                                                    </tr>
<?php
                                                }
                                                //Show link to schedule (if any)
                                                $q_itinerary = mysqli_query($con, "SELECT id FROM activity_itinerary WHERE activity = $r_activity[id];");
                                                if (mysqli_num_rows($q_itinerary) > 0){
?>
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            <a href='<?=$server?>/actividades/<?=$r_activity["permalink"]?>'><?=$lng["activities_see_itinerary"]?></a>
                                                        </td>
                                                    </tr>
<?php
                                                }
?>
                                            </table> <!-- #future_data -->
                                        </td>
                                    </tr>
                                </table> <!-- #future_details -->
                                <p><?=cutText($r_activity["text"], 300, "$lng[index_read_more]", "$server/actividades/$r_activity[permalink]")?></p>
                            </div> <!-- #upcoming_text -->
                        </div> <!-- .entry -->
<?php
                    } //while ($r_activity = mysqli_fetch_array($q_upcoming))
                } //if (mysqli_num_rows($q_upcoming) > 0)
                else{ //No upcoming activities
?>
                    <div class='entry'>
                        <h3 class='entry_title'><?=$lng["activities_no_upcoming"]?></h3>
                    </div>
<?php
                }
?>
            </div> <!-- .section -->
<?php
            if ($r_activity == null){
                $next_activity_id = -1;
            }
            else{
                $next_activity_id = $r_activity["id"];
            }
            $q_past = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, after_$lang AS text, text_$lang AS alt_text, price, inscription, people, max_people, user, dtime, comments, city FROM activity WHERE id != $next_activity_id AND visible = 1 AND date < now() ORDER BY date DESC;");
            if (mysqli_num_rows($q_past) > 0){
?>
                <br/><br/>
                <div class='section'>
                    <h3 class='section_title'><?=$lng["activities_past"]?></h3>
<?php
                    $i = 0;
                    while ($r_past = mysqli_fetch_array($q_past)){
?>
                        <div class='entry past_activity' itemscope itemtype='http://schema.org/Event'>
                            <meta itemprop='inLanguage' content='<?=$lang?>'/>
                            <meta itemprop='name' content='<?=$r_past["title"]?>'/>
                            <meta itemprop='startDate endDate' content='<?=$r_past["isodate"]?>'/>
                            <span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'>
                                <meta itemprop='address' itemprop='name' content='<?=$r_past["city"]?>'/>
                                <meta itemprop='name' content='<?=$r_past["city"]?>'/>
                            </span>
                            <div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>
                                <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                                <meta itemprop='name' content='Gasteizko Margolariak'/>
                                <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                                <meta itemprop='foundingDate' content='03-02-2013'/>
                                <meta itemprop='telephone' content='+34637140371'/>
                                <meta itemprop='url' content='<?=$server?>'/>
                            </div>
<?php
                            $q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_past[id] ORDER BY idx LIMIT 1;");
                            if ($i == 0 && mysqli_num_rows($q_upcoming) == 0){
                                $past_class = "past_activity_details past_activity_details_first";
?>
                                <h3 class='entry_title'>
                                    <img id='slid_past_activity_<?=$r_past["id"]?>' class='slid' src='<?=$server?>/img/misc/slid-down.png' onclick='showPastActivity("<?=$r_past["id"]?>")'/>
                                    &nbsp;&nbsp;
                                    <a itemprop='url' href='<?=$server?>/actividades/<?=$r_past["permalink"]?>'><?=$r_past["title"]?></a>
                                    <span class='title_date'> - <?=formatDate($r_past["date"], $lang, false)?></span>
                                </h3>
<?php
                            }
                            else{
                                $past_class = "past_activity_details";
?>
                                <h3 class='entry_title'>
                                    <img id='slid_past_activity_<?=$r_past["id"]?>' class='slid' src='<?=$server?>/img/misc/slid-right.png' onclick='showPastActivity("<?=$r_past["id"]?>");'/>
                                    &nbsp;&nbsp;
                                    <a itemprop='url' href='<?=$server?>/actividades/<?=$r_past["permalink"]?>'><?=$r_past["title"]?></a>
                                    <span class='title_date'> - <?=formatDate($r_past["date"], $lang, false)?></span>
                                </h3>
<?php
                            }
                            $i ++;
?>
                            <div class='<?=$past_class?>' id='past_activity_details_<?=$r_past["id"]?>'>
                                <table class='past_activity'>
                                    <tr>
<?php
                                        if (mysqli_num_rows($q_activity_image) > 0){
                                            $r_activity_image = mysqli_fetch_array($q_activity_image);
?>
                                            <td>
                                                <div class='past_image'>
                                                    <a href='<?=$server?>/actividades/<?=$r_past["permalink"]?>'>
                                                        <meta itemprop='image' content='<?=$server?>/img/actividades/view/<?=$r_activity_image["image"]?>'/>
                                                        <img src='<?=$server?>/img/actividades/miniature/<?=$r_activity_image["image"]?>' alt='<?=$r_past["title"]?>'/>
                                                    </a>
                                                </div>
                                            </td>
<?php
                                        }
?>
                                        <td>
                                            <div class='past_text'>
<?php
                                                if (strlen($r_past["text"]) > 0){
?>
                                                    <meta itemprop='description' content='<?=$r_past["text"]?>'/>
                                                    <p><?=cutText($r_past["text"], 300, "$lng[index_read_more]", "$server/actividades/$r_past[permalink]")?></p>
<?php
                                                }
                                                else{
?>
                                                    <meta itemprop='description' content='<?=$r_past["alt_text"]?>'/>
                                                    <p><?=cutText($r_past["alt_text"], 300, "$lng[index_read_more]", "$server/actividades/$r_past[permalink]")?>"</p>
<?php
                                                }
?>
                                            </div> <!-- .past_text -->
                                        </td>
                                    </tr>
                                </table> <!-- .past_activity -->
                            </div> <!-- #past_activity_details -->
                        </div> <!-- .past_activity -->
<?php
                    } // while ($r_past = mysqli_fetch_array($q_past))
?>
                </div> <!-- .section -->
<?php
            }
?>
        </div> <!-- #content -->
<?php
        include("../footer.php");
        $ad = ad($con, $lang, $lng); 
        stats($ad, $ad_static, "actividades", "");
?>
    </body>
</html>
