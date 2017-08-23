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
    
    // Get current activity id. Redirect if inexistent or invisible
    $perm = mysqli_real_escape_string($con, $_GET["perm"]);
    $q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, after_$lang AS after, price, inscription, people, max_people, user, date, DATE_FORMAT(date, '%Y-%m-%d %T') AS cdate, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, DATE_FORMAT(date,'%b %d, %Y') as fdate, dtime, comments, city FROM activity WHERE permalink = '$perm' AND visible = 1;");
    if (mysqli_num_rows($q) == 0){
        header("Location: $server/actividades/");
        exit(-1);
    }
    $r = mysqli_fetch_array($q);
    $id = $r["id"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$r["title"]?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
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
        <link rel="canonical" href="<?=$server?>/actividades/<?=$r["permalink"]?>"/>
        <link rel="author" href="<?=$server?>"/>
        <link rel="publisher" href="<?=$server?>"/>
        <meta name="description" content="<?=strip_tags($r["text"])?>"/>
        <meta property="og:title" content="<?=$r["title"]?> - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?=$server?>/actividades/<?=$r["permalink"]?>"/>
        <meta property="og:description" content="<?=strip_tags($r["text"])?>?>"/>
<?php
        $q_i = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $id ORDER BY idx;");
        if (mysqli_num_rows($q_i) == 0){
            $img = "$server/img/logo/cover.png";
        }
        else{
            $r_i = mysqli_fetch_array($q_i);
            $img = "$server/img/actividades/preview/" . $r_i["image"];
        }
?>
        <meta property="og:image" content="<?=$img?>"/>
        <meta property="og:site_name" content="Gasteizko Margolariak"/>
        <meta property="og:type" content="article"/>
        <meta property="og:locale" content="<?=$lang?>"/>
        <meta property="article:section" content=""/>
        <!--     TODO: Review time format     -->
        <meta property="article:published-time" content="<?=$r["dtime"]?>"/>
        <meta property="article:modified-time" content="<?=$r["dtime"]?>"/>
        <meta property="article:author" content="Gasteizko Margolariak"/>
<?php
        $res_tag = mysqli_query($con, "SELECT tag FROM activity_tag WHERE activity = $id;");
        $tag_string = "vitoria cuadrilla gasteizko margolariak ";
        while ($row_tag = mysqli_fetch_array($res_tag)){
            $tag_string = $tag_string . " " . $row_tag["tag"];
        }
?>
        <meta property="article:tag" content="<?=$tag_string?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?=$r["title"]?> - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="<?=strip_tags($r["text"])?>"/>
        <meta name="twitter:image" content="<?=$img?>"/>
        <meta name="twitter:url" content="<?=$server?>/actividades/<?=$r["permalink"]?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
<?php
        include("../header.php");
?>
        <div id="content">
<?php
            //Check if it's a past activity or upcoming activity.
            $c_date = new Datetime();
            $a_date = date_create_from_format('Y-m-d H:i:s', $r["cdate"]);
            $future = false;
            if (date_format($a_date, 'U') > date_format($c_date, 'U')){
                $future = true;
            }
?>
            <div id="middle_column">
                <div class="section">
                    <h3 class='section_title' id="activity_title"><?=$r["title"]?> - <?=formatDate($r["date"], $lang, false)?></h3>
                    <div class="entry" itemscope itemtype='http://schema.org/Event'>
                        <meta itemprop='url' href='<?=$server?>/actividades/<?=$r["permalink"]?>'/>
                        <meta itemprop='inLanguage' content='$<?=lang?>'/>
                        <meta itemprop='name' content='<?=$r["title"]?>'/>
                        <meta itemprop='description' content='<?=$r["text"]?>'/>
                        <meta itemprop='startDate endDate' content='<?$r["isodate"]?>'/>
                        <meta itemprop='location' content='<?=$r["city"]?>'/>
                        <div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>
                            <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                            <meta itemprop='name' content='Gasteizko Margolariak'/>
                            <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                            <meta itemprop='foundingDate' content='03-02-2013'/>
                            <meta itemprop='telephone' content='+34637140371'/>
                            <meta itemprop='url' content='<?=$server?>'/>
                        </div>
<?php
                        if (mysqli_num_rows($q_i) > 0){
?>
                            <meta itemprop='image' content='</=$server?>/img/actividades/<?=$r_i["image"]?>'/>
                            <div id='activity_image'>
                                <img src='<?=$server?>/img/actividades/preview/<?=$r_i["image"]?>'/>
                            </div>
<?php
                        }
?>
                        <div id='activity_description'>
                            <p>
<?php
                                if ($future == false && strlen($r["after"]) > 0){
?>
                                    <?=$r["after"]?>
<?php
                                }
                                else{
?>
                                    <?=$r["text"]?>
<?php
                                }
?>
                            </p>
                        </div> <!-- .activity_description -->
                        <br/>
<?php
                        if ($future){
?>
                            <div itemscope itemtype='http://schema.org/Offer' id='activity_details'>
                                <table id='activity_details'>
                                    <tr>
                                        <td class='field_name'><?=$lng["activities_date"]?></td>
                                        <td><?=formatDate($r["date"], $lang, false)?></td>
                                    </tr>
                                    <tr>
                                        <td class='field_name'><?=$lng["activities_city"]?></td>
                                        <td><?=$r["city"]?></td>
                                    </tr>
                                    <meta itemprop='priceCurrency' content='EUR'/>
                                    <meta itemprop='price' content='<$r["price"]?>'/>
<?php
                                    if ($r["price"] == 0){
?>
                                        <tr>
                                            <td class='field_name'><?=$lng["activities_price"]?></td>
                                            <td><?=$lng["activities_price_0"]?></td>
                                        </tr>
<?php
                                    }
                                    else{
?>
                                        <tr>
                                            <td class='field_name'><?=$lng["activities_price"]?></td>
                                            <td><?=$r["price"]?>€</td>
                                        </tr>
<?php
                                        if ($r["max_people"] != 0){
?>
                                            <tr>
                                                <td class='field_name'><?=$lng["activities_maxpeople"]?></td>
                                                <td><?=$r["max_people"]?></td>
                                            </tr>
<?php
                                        }
                                    }
?>
                                </table> <!-- activity_details -->
                            </div> <!-- activity_details -->
<?php
                        
?>
                            </div> <!-- .entry -->
                            <br/>
                            <div class='entry'>
<?php
                                $q_it = mysqli_query($con, "SELECT activity_itinerary.name_$lang AS name, description_$lang AS description, place.name_$lang AS place_name, place.address_$lang AS place_address, DATE_FORMAT(start, '%H:%i') AS start, DATE_FORMAT(end, '%H:%i') AS end, DATE_FORMAT(start, '%Y-%m-%d') AS isostart, DATE_FORMAT(end, '%Y-%m-%d') AS isoend FROM activity_itinerary, place WHERE place.id = activity_itinerary.place AND activity = $r[id] ORDER BY start;");
                                if (mysqli_num_rows($q_it) > 0){
?>
                                    <div id='activity_itinerary'>
                                        <!-- TODO: Routes -->
                                        <h3 class='entry_title'><?=$lng["activity_itinerary"]?></h3>
                                        <table id='activity_itinerary'>
                                            <tr class='th'>
                                                <th><?=$lng["activities_when"]?></th>
                                                <th><?=$lng["activities_what"]?></th>
                                            </tr>
<?php
                                            while ($r_it = mysqli_fetch_array($q_it)){
?>
                                                <tr>
                                                    <div class='hidden' itemprop='subEvent' itemscope itemtype='http://schema.org/Event'>
                                                        <meta itemprop='inLanguage' content='<?=$lang?>'/>
                                                        <meta itemprop='name' content='<?=$r_it["name"]?>'/>
                                                        <meta itemprop='description' content='<?=$r_it["description"]?>'/>
                                                        <meta itemprop='startDate' content='<?=$r_it["isostart"]?>'/>
                                                        <meta itemprop='location' content='<?=$r_it["place_name"]?>'/>
<?php
                                                        if (strlen($r_it["end"]) > 0){
?>
                                                            <meta itemprop='endDate' content='<?=$r_it["place_end"]?>'/>
<?php
                                                        }
?>
                                                    </div>
<?php
                                                    if (strlen($r_it["end"]) > 0){
?>
                                                        <td><?=$r_it["start"]?> - <?=$r_it["end"]?></td>
<?php
                                                    }
                                                    else{
?>
                                                        <td class='time'><?=$r_it["start"]?></td>
<?php
                                                    }
?>
                                                    <td>
                                                        <h5><?=$r_it["name"]?></h5>
                                                        <p class='description'><?=$r_it["description"]?></p>
                                                        <br/>
<?php
                                                        if ($r_it["place_name"] == $r_it["place_address"]){
?>
                                                            <?=$r_it["place_name"]?>
<?php
                                                        }
                                                        else{
?>
                                                            <?=$r_it["place_name"]?>
                                                            <span class='address'>(<?=$r_it["place_address"]?>)</span>
<?php
                                                        }
?>
                                                    </td>
                                                </tr>
<?php
                                            } // while ($r_it = mysqli_fetch_array($q_it))
?>
                                        </table> <!-- #activity_itinerary -->
                                    </div> <!-- #activity_itinerary -->
<?php
                                } // if (mysqli_num_rows($q_it) > 0)
?>
                            </div> <!-- .entry -->
<?php                    
                        } //  if ($future)
?>                    
                    </div> <!-- .section -->
                </div> <!-- .entry -->
            </div> <!-- #middle_column -->
            <div id="right_column">
                <div id="archive" class="section desktop">
                    <h3 class="section_title"><?=$lng["activities_archive"] ?></h3>
                    <div class='entry'>
<?php
                        $res_year = mysqli_query($con, "SELECT year(date) AS year FROM activity WHERE visible = 1 GROUP BY year(date) ORDER BY year DESC;");
                        while($row_year = mysqli_fetch_array($res_year)){
?>
                            <div class='year pointer' onClick='toggleElement("year_<?=$row_year["year"]?>");'>
                                <img class='slid' id='slid_year_<?=$row_year["year"]?>' src='<?=$server?>/img/misc/slid-right.png' alt='<?=$row_year["year"]?>'/>
                                <span class='fake_a'><?=$row_year["year"]?></span>
                            </div>
                            <div class='list_year pointer' id='list_year_<?=$row_year["year"]?>'>
<?php
                                $res_month = mysqli_query($con, "SELECT month(date) AS month FROM activity WHERE visible = 1 AND year(date) = $row_year[year] GROUP BY month(date) ORDER BY month DESC;");
                                while($row_month = mysqli_fetch_array($res_month)){
?>                                
                                    <div class='month pointer' onClick='toggleElement("month_<?=$row_year["year"]?>_<?=$row_month["month"]?>");'>
                                        <img class='slid' id='slid_month_<?=$row_year["year"]?>_<?=$row_month["month"]?>' src='<?=$server?>/img/misc/slid-right.png' alt='<?=$row_year["year"]?>_<?=$row_month["month"]?>'/>
                                        <span class='fake_a'><?=$lng["months"][$row_month["month"] - 1]?></span>
                                    </div>
                                    <ul id='list_month_<?=$row_year["year"]?>_<?=$row_month["month"]?>' class='activity_list'>
<?php
                                    $res_title = mysqli_query($con, "SELECT id, permalink, title_$lang AS title FROM activity WHERE visible = 1 AND year(date) = $row_year[year] AND month(date) = '$row_month[month]' ORDER BY date DESC;");
                                    while($row_title = mysqli_fetch_array($res_title)){
?>
                                        <li>
                                            <a href='<?=$server?>/actividades/<?=$row_title["permalink"]?>'><?=$row_title["title"]?></a>
                                        </li>
<?php
                                    }
?>
                                    </ul>
<?php
                                }
?>
                            </div> <!-- #list_year_<?=$row_year["year"]?> -->
<?php
                        }
?>
                    </div> <!-- .entry -->
                </div> <!-- #archive -->
            </div> <!-- #right_column -->
        </div> <!-- #content -->
<?php
        include("../footer.php");
        $ad = ad($con, $lang, $lng); 
        stats($ad, $ad_static, "actividades", "$id");
?>
    </body>
</html>
