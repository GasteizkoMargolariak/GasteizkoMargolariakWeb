<?php
    session_start();
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    
    //Language
    $lang = selectLanguage();
    include("../lang/lang_" . $lang . ".php");
    
    $cur_section = $lng['section_activities'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?php echo $lng['activities_title'];?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
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
        <link rel="canonical" href="<?php echo "$proto$http_host/actividades/"; ?>"/>
        <link rel="author" href="<?php echo "$proto$http_host"; ?>"/>
        <link rel="publisher" href="<?php echo "$proto$http_host"; ?>"/>
        <meta name="description" content="<?php echo $lng['activities_description'];?>"/>
        <meta property="og:title" content="<?php echo $lng['activities_title'];?> - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?php echo "$proto$http_host/activities/"; ?>"/>
        <meta property="og:description" content="<?php echo $lng['activities_description'];?>"/>
        <meta property="og:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
        <meta property="og:site_name" content="<?php echo $lng['index_title'];?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="<?php echo $lang; ?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?php echo $lng['activities_title'];?> - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="<?php echo $lng['activities_description'];?>"/>
        <meta name="twitter:image" content="<?php echo "$proto$http_host/img/logo/logo.png";?>"/>
        <meta name="twitter:url" content="<?php echo"$proto$http_host"; ?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
        <?php include("../header.php"); ?>
        <div id="content">
        <?php
            //Upcoming activities
            echo "<div class='section'>\n";
            echo "<h3 class='section_title'>$lng[activities_upcoming]</h3>\n";
            $q_upcoming = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, text_$lang AS text, price, inscription, people, max_people, user, dtime, comments, city FROM activity WHERE visible = 1 AND date > now() ORDER BY date;");
            if (mysqli_num_rows($q_upcoming) > 0){
                while ($r_activity = mysqli_fetch_array($q_upcoming)){
                    echo "<div class='entry' itemscope itemtype='http://schema.org/Event'>\n";
                    echo "<meta itemprop='inLanguage' content='$lang'/>\n";
                    echo "<meta itemprop='name' content='$r_activity[title]'/>\n";
                    echo "<meta itemprop='description' content='$r_activity[text]'/>\n";
                    echo "<meta itemprop='startDate endDate' content='$r_activity[isodate]'/>\n";
                    echo("<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'><meta itemprop='address' itemprop='name' content='$r_activity[city]'/><meta itemprop='name' content='$r_activity[city]'/></span>\n");
                    echo "<div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>\n";
                    echo "<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n";
                    echo "<meta itemprop='name' content='Gasteizko Margolariak'/>\n";
                    echo "<meta itemprop='logo' content='$proto$http_host/img/logo/logo.png'/>\n";
                    echo "<meta itemprop='foundingDate' content='03-02-2013'/>\n";
                    echo "<meta itemprop='telephone' content='+34637140371'/>\n";
                    echo "<meta itemprop='url' content='$proto$http_host'/>\n";
                    echo "</div>\n";
                    echo "<div id='upcoming_text'>\n";
                    echo "<h3 class='entry_title'><a itemprop='url' href='$proto$http_host/actividades/$r_activity[permalink]'>$r_activity[title]</a><span class='title_date'> - " . formatDate($r_activity['date'], $lang, false) . "</span></h3>\n";
                    //Table with details
                    echo "<table class='future_details'><tr>\n";
                    //If image, show it
                    $q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_activity[id] ORDER BY idx LIMIT 1;");
                    if (mysqli_num_rows($q_activity_image) > 0){
                        $r_activity_image = mysqli_fetch_array($q_activity_image);
                        echo "<td><div id='upcoming_image'>\n";
                        echo "<a href='$proto$http_host/actividades/$r_activity[permalink]'>\n";
                        echo "<meta itemprop='image' content='$proto$http_host/img/actividades/view/$r_activity_image[image]'/>\n";
                        echo "<img src='$proto$http_host/img/actividades/preview/$r_activity_image[image]' alt='$r_activity[title]'/>\n";
                        echo "</a>\n";
                        echo "</div></td>\n";
                    }
                    echo("<td>\n<table class='future_data'>\n");
                    echo("<tr><td class='title'>$lng[activities_city]</td><td>$r_activity[city]</td></tr>\n");
                    if ($r_activity['price'] == 0){
                        echo "<tr><td class='title'>$lng[activities_price]</td><td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>\n";
                        echo "<meta itemprop='priceCurrency' content='EUR'/><meta itemprop='price' content='0'/>\n";
                        echo "$lng[activities_price_0]</td></tr>\n";
                    }
                    else{
                        echo "<tr><td class='title'>$lng[activities_price]</td><td itemprop='offers' itemscope itemtype='http://schema.org/Offer'>\n";
                        echo "<meta itemprop='priceCurrency' content='EUR'/><meta itemprop='price' content='$r_activity[price]'/>\n";
                        echo("$r_activity[price]€</td></tr>\n");
                    }
                    if ($r_activity['max_people'] != 0){
                        echo "<tr><td>$lng[activities_maxpeople]</td><td>$r_activity[max_people]</td></tr>\n";
                    }
                    //Show link to schedule (if any)
                    $q_itinerary = mysqli_query($con, "SELECT id FROM activity_itinerary WHERE activity = $r_activity[id];");
                    if (mysqli_num_rows($q_itinerary) > 0){
                        echo "<tr><td></td><td><a href='$proto$http_host/actividades/$r_activity[permalink]'>$lng[activities_see_itinerary]</a></td></tr>\n";
                    }
                    echo "</table>";
                    echo "</td></tr></table>\n";
                    echo "<p>". cutText($r_activity['text'], 300, "$lng[index_read_more]", "$proto$http_host/actividades/$r_activity[permalink]") . "</p>\n";
                    echo "</div>\n";
                    echo "</div>\n"; //Entry
                }

            }
            else{ //No upcoming activities
                echo("<div class='entry'><h3 class='entry_title'>\n$lng[activities_no_upcoming]\n</h3></div>\n");
            }
            echo "</div>\n";
                
            //Past activities TODO: Meta
            if ($r_activity == null){
                $next_activity_id = -1;
            }
            else{
                $next_activity_id = $r_activity['id'];
            }
            $q_past = mysqli_query($con, "SELECT id, permalink, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, title_$lang AS title, after_$lang AS text, text_$lang AS alt_text, price, inscription, people, max_people, user, dtime, comments, city FROM activity WHERE id != $next_activity_id AND visible = 1 AND date < now() ORDER BY date DESC;");
            if (mysqli_num_rows($q_past) > 0){
                echo "<br/><br/><div class='section'>\n";
                echo "<h3 class='section_title'>$lng[activities_past]</h3>\n";
                $i = 0;
                while ($r_past = mysqli_fetch_array($q_past)){
                    echo("<div class='entry past_activity' itemscope itemtype='http://schema.org/Event'>\n");
                    echo("<meta itemprop='inLanguage' content='$lang'/>\n");
                    echo("<meta itemprop='name' content='$r_past[title]'/>\n");
                    echo("<meta itemprop='startDate endDate' content='$r_past[isodate]'/>\n");
                    echo("<span class='hidden' itemprop='location' itemscope itemtype='http://schema.org/Place'><meta itemprop='address' itemprop='name' content='$r_past[city]'/><meta itemprop='name' content='$r_past[city]'/></span>\n");
                    echo("<div class='hidden' itemprop='organizer' itemscope itemtype='http://schema.org/Organization'>\n");
                    echo("<meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>\n");
                    echo("<meta itemprop='name' content='Gasteizko Margolariak'/>\n");
                    echo("<meta itemprop='logo' content='$proto$http_host/img/logo/logo.png'/>\n");
                    echo("<meta itemprop='foundingDate' content='03-02-2013'/>\n");
                    echo("<meta itemprop='telephone' content='+34637140371'/>\n");
                    echo("<meta itemprop='url' content='$proto$http_host'/>\n");
                    echo("</div>\n");
                    $q_activity_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r_past[id] ORDER BY idx LIMIT 1;");
                    if ($i == 0 && mysqli_num_rows($q_upcoming) == 0){
                        echo("<h3 class='entry_title'>");
                        echo("<img id='slid_past_activity_$r_past[id]' class='slid' src='/img/misc/slid-down.png' onclick=\"showPastActivity('$r_past[id]')\"/>&nbsp;&nbsp;\n");
                        echo("<a itemprop='url' href='$proto$http_host/actividades/$r_past[permalink]'>$r_past[title]</a>\n");
                        echo("<span class='title_date'> - " . formatDate($r_past['date'], $lang, false) . "</span>\n");
                        echo("</h3>\n");
                        echo("<div class='past_activity_details past_activity_details_first' id='past_activity_details_$r_past[id]'>\n");
                    }
                    else{
                        echo("<h3 class='entry_title'>");
                        echo("<img id='slid_past_activity_$r_past[id]' class='slid' src='/img/misc/slid-right.png' onclick=\"showPastActivity('$r_past[id]')\"/>&nbsp;&nbsp;");
                        echo("<a itemprop='url' href='$proto$http_host/actividades/$r_past[permalink]'>$r_past[title]</a>");
                        echo("<span class='title_date'> - " . formatDate($r_past['date'], $lang, false) . "</span>");
                        echo("</h3>\n");
                        echo("<div class='past_activity_details' id='past_activity_details_$r_past[id]'>\n");
                    }
                    $i ++;
                    echo "<table class='past_activity'><tr>\n";
                    if (mysqli_num_rows($q_activity_image) > 0){
                        $r_activity_image = mysqli_fetch_array($q_activity_image);
                        echo "<td><div class='past_image'>\n";
                        echo "<a href='$proto$http_host/actividades/$r_past[permalink]'>\n";
                        echo("<meta itemprop='image' content='$proto$http_host/img/actividades/view/$r_activity_image[image]'/>\n");
                        echo "<img src='$proto$http_host/img/actividades/miniature/$r_activity_image[image]' alt='$r_past[title]'/>\n"; 
                        echo "</a>\n";
                        echo "</div></td>\n";
                    }
                    echo "<td><div class='past_text'>\n";
                    if (strlen($r_past['text']) > 0){
                        echo("<meta itemprop='description' content='$r_past[text]'/>\n");
                        echo "<p>". cutText($r_past['text'], 300, "$lng[index_read_more]", "$proto$http_host/actividades/$r_past[permalink]") . "</p>\n";
                    }
                    else{
                        echo("<meta itemprop='description' content='$r_past[alt_text]'/>\n");
                        echo "<p>". cutText($r_past['alt_text'], 300, "$lng[index_read_more]", "$proto$http_host/actividades/$r_past[permalink]") . "</p>\n";
                    }
                    echo "</div></td></tr></table>\n";
                    echo "</div>\n"; //past_activity_details
                    echo "</div>\n"; //past_activity
                }
                echo "</div>\n";
            }
        ?>
        </div>
        <?php
            include("../footer.php");
            $ad = ad($con, $lang, $lng); 
            stats($ad, $ad_static, "actividades", "$r_activity[id]");
        ?>
    </body>
</html>
