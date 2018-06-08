<?php
    session_start();
    $http_host = $_SERVER["HTTP_HOST"];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $server = "$server";

    //Language
    $lang = selectLanguage();
    include("../lang/lang_$lang.php");

    $cur_section = $lng["section_blog"];

    //Search terms
    $search_field = mysqli_real_escape_string($con, $_GET["where"]);
    $search_term = strtolower(mysqli_real_escape_string($con, $_GET["query"]));
    if ($search_field == "" || $search_term == ""){
        header("Location: $server/blog/");
        exit(-1);
    }

    //Get matches
    switch ($search_field){
        case $lng["search_field_all"]:
            $q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, comments FROM post WHERE lower(title_es) LIKE '%$search_term%' OR lower(text_es) LIKE '%$search_term%' OR id IN (SELECT post FROM post_tag WHERE lower(tag) LIKE '%$search_term%') ORDER BY dtime DESC;");
            break;
        case $lng["search_field_tag"]:
            $q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, comments FROM post WHERE id IN (SELECT post FROM post_tag WHERE tag like '$search_term') ORDER BY dtime DESC;");
            break;
        default:
            header("Location: $server/blog/");
            exit(-1);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$lng["blog_title"]?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
        <!-- CSS files -->
        <style>
<?php
            include("../css/ui.css");
            include("../css/blog.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php
            include("../css/m/ui.css");
            include("../css/m/blog.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
            <?php
                include("../script/ui.js");
                include("../script/blog.js");
            ?>
        </script>
        <!-- Meta tags -->
        <link rel='canonical' href='<?=$server?>/blog'/>
        <link rel='author' href='<?=$server?>'/>
        <link rel='publisher' href='<?=$server?>'/>
        <meta name='description' content='<?=$lng["blog_descrption"]?>'/>
        <meta property='og:title' content='<?=$lng["blog_title"]?> - Gasteizko Margolariak'/>
        <meta property='og:url' content='<?=$server?>/blog'/>
        <meta property='og:description' content='<?=$lng["blog_description"]?>'/>
        <meta property='og:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta property='og:site_name' content='<?=$lng["index_title"]?>'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='<?=$lang?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$lng["blog_title"]?> - Gasteizko Margolariak'/>
        <meta name='twitter:description' content='<?=$lng["blog_description"]?>'/>
        <meta name='twitter:image' content='<?=$server?>/img/logo/logo.png'/>
        <meta name='twitter:url' content='<?=$server?>'/>
        <meta name='robots' content='no-index no-follow'/>
    </head>
    <body>
<?php
        include("../header.php");
?>
        <div id='content'>
<?php
            include("common/leftpanel.php");
?>
            <div id='middle_column'>
                <div class='section'>
                    <h3 class='section_title'><?=$lng["blog_search"]?></h3>
                    <div class='entry'>
<?php
                        if (mysqli_num_rows($q) == 1){
?>
                            <?=$lng["blog_search_1"]?>
                            <span class='italic'><?=$search_term?></span>
                            &nbsp: <?=strval(mysqli_num_rows($q))?> <?=$lng["blog_search_result"]?>
<?php
                        }
                        else{
?>
                            <?=$lng["blog_search_1"]?>
                            <span class='italic'><?=$search_term?></span>
                            &nbsp: <?=strval(mysqli_num_rows($q))?> <?=$lng["blog_search_results"]?>
<?php
                        }
?>
                    </div> <!-- .entry -->
<?php
                    while($r = mysqli_fetch_array($q)){
?>
                        <div itemscope itemtype='http://schema.org/BlogPosting' class='entry blog_entry'>
                            <meta itemprop='inLanguage' content='$lang'/>
                            <meta itemprop='datePublished dateModified' content='<?=$r["isodate"]?>'/>
                            <meta itemprop='headline name' content='<?=$r["title"]?>'/>
                            <meta itemprop='articleBody text' content='<?=$r["text"]?>'/>
                            <meta itemprop='mainEntityOfPage' content='<?=$server?>'/>
                            <div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>
                                <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                                <meta itemprop='name' content='Gasteizko Margolariak'/>
                                <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                                <meta itemprop='foundingDate' content='03-02-2013'/>
                                <meta itemprop='telephone' content='+34637140371'/>
                                <meta itemprop='url' content='<?=$server?>'/>
                            </div>
                            <h3 class='entry_title post_search_title'>
                                <a itemprop='url' href='<?=$server?>/blog/<?=$r["permalink"]?>'><?=$r["title"]?></a>
                            </h3>
                            <table class='post_footer post_footer_search'>
                                <tr>
<?php
                                    # Tags (if any) and date
                                    $q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post= $r[id];");
                                    if (mysqli_num_rows($q_tag) > 0){
                                        $tag_string = "<span class='tags desktop'>Tags: ";
                                        $tag_raw = "";
                                        while ($r_tag = mysqli_fetch_array($q_tag)){
                                            $tag_string = $tag_string . "<a href='$server/blog/buscar/tag/$r_tag[tag]'>$r_tag[tag]</a>, ";
                                            $tag_raw = $tag_raw . "$r_tag[tag],";
                                        }
                                        $tag_string = substr($tag_string, 0, strlen($tag_string) - 2);
                                        $tag_string = $tag_string . "</span>";
                                        $tag_raw = substr($tag_raw, 0, strlen($tag_raw) - 1);
?>
                                        <meta itemprop='keywords' content='<?=$tag_raw?>'/>
                                        <td>
                                            <span class='date'><?=formatDate($r["dtime"], $lang, false)?></span>
                                            &nbsp;&nbsp;&nbsp;<?=$tag_string?>
                                        </td>
<?php
                                    }
                                    else{
?>
                                        <td>
                                            <span class='date'><?=formatDate($r["dtime"], $lang)?></span>
                                        </td>
<?php
                                    }
?>
                                </tr>
                            </table> <!-- .post_footer_search -->
                            <hr class='post_search_separator'/>
<?php
                            #Image and text
                            $q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r[id] ORDER BY idx LIMIT 1;");
                            if (mysqli_num_rows($q_image) > 0){
                                $r_image = mysqli_fetch_array($q_image);
?>
                                <a href='<?=$server?>/blog/<?=$r["permalink"]?>'>
                                    <meta itemprop='image' content='<?=$server?>/img/blog/<?=$r_image["image"]?>'/>
                                    <img class='post_search_image alt='<?=$r["title"]?>' src='<?=$server?>/img/blog/preview/<?=$r_image["image"]?>'/>
                                </a>
<?php
                            }
?>
                            <p class='post_search_text'><?=cutText($r["text"], 150, $lng["blog_read_more"], "$server/blog/$r[permalink]/")?></p>
                            <br/>
                        </div> <!-- .blog_entry-->
<?php
                    } //  while($r = mysqli_fetch_array($q))
?>
                </div> <!-- .section -->
            </div> <!-- #middle_column -->
<?php
            include("common/rightpanel.php");
?>
        </div> <!-- #content -->
<?php
        include("../footer.php");
        $ad = ad($con, $lang, $lng); 
        stats($ad, $ad_static, "blog", "");
?>
    </body>
</html>
