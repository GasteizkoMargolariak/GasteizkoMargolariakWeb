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

    $cur_section = $lng["section_blog"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content='text/html; charset=utf-8' http-equiv='content-type'"/>
        <meta charset='utf-8'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title><?=$lng["blog_title"];?> - Gasteizko Margolariak</title>
        <link rel='shortcut icon' href='<?=$server?>/img/logo/favicon.ico'>
        <!-- CSS files -->
        <style>
<?php
            include("../css/ui.css");
            include("../css/blog.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media='(max-width : 990px)'>
<?php
            include("../css/m/ui.css");
            include("../css/m/blog.css");
?>
        </style>
        <!-- Script files -->
        <script type='text/javascript'>
<?php
            include("../script/ui.js");
            include("../script/blog.js");
?>
        </script>
        <!-- Meta tags -->
        <link rel='canonical' href='<?=$server?>/blog/'>
        <link rel='author' href='<?=$server?>'/>
        <link rel='publisher' href='<?=$server?>'/>
        <meta name='description' content='<?=$lng["blog_descrption"]?>'/>
        <meta property='og:title' content='<?=$lng["blog_title"]?> - Gasteizko Margolariak'/>
        <meta property='og:url' content='<?=$server?>/blog/'>
        <meta property='og:description' content='<?=$lng["blog_description"]?>'/>
        <meta property='og:image' content='<?=$server?>/img/logo/logo.png"?>'/>
        <meta property='og:site_name' content='<?=$lng["index_title"]?>'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='<?=$lang?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$lng["blog_title"]?> - Gasteizko Margolariak'/>
        <meta name='twitter:description' content='<?=$lng["blog_description"]?>'/>
        <meta name='twitter:image' content='<?=$server?>/img/logo/logo.png"?>'/>
        <meta name='twitter:url' content='<?=$server?>/blog/'/>
        <meta name='robots' content='index follow'/>
    </head>
    <body>
<?php
        include("../header.php");
?>
        <div id='content'>
<?php
            include("common/leftpanel.php");
?>
            <div id='middle_column' class='section'>
                <h3 class='section_title'><?=$lng["section_blog"]?></h3>
<?php
                    $q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, comments FROM post WHERE visible = 1 ORDER BY dtime DESC;");
                    while($r = mysqli_fetch_array($q)){
?>
                        <div itemscope itemtype='http://schema.org/BlogPosting' class='entry blog_entry'>
                            <meta itemprop='inLanguage' content='<?=$lang?>'/>
                            <meta itemprop='datePublished dateModified' content='<?=$r["isodate"]?>'/>
                            <meta itemprop='headline name' content='$<?=r["title"]?>'/>
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
                            <h3 class='entry_title'>
                                <a itemprop='url' href='<?=$server?>/blog/<?=$r["permalink"]?>'><?=$r["title"]?></a>
                            </h3>
<?php
                            $q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r[id] ORDER BY idx LIMIT 1;");
                            if (mysqli_num_rows($q_image) > 0){
                                $r_image = mysqli_fetch_array($q_image);
?>
                                <div class='post_list_image_container'>
                                    <a href='<?=$server?>/blog/<?=$r["permalink"]?>'>
                                        <meta itemprop='image' content='<?=$server?>/img/blog/view/<?=$r_image["image"]?>'/>
                                        <img class='post_list_image alt='<?=$r["title"]?>' src='<?=$server?>/img/blog/miniature/<?=$r_image["image"]?>'/>
                                    </a>
                                </div>
<?php
                            }
?>
                            <p><?=cutText($r["text"], 800, $lng["blog_read_more"], "$server/blog/$r[permalink]/")?></p>
                            <hr/>
                            <table class='post_footer'>
                                <tr>
<?php
                                    // Tags (if any), date, and comment counter
                                    $q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post= $r[id];");
                                    if (mysqli_num_rows($q_tag) > 0){
                                        $tag_string = "<span class='tags desktop'>Tags: ";
                                        $tag_raw = "";
                                        while ($r_tag = mysqli_fetch_array($q_tag)){
                                            $tag_string = $tag_string . "<a href='$proto$http_host/blog/buscar/tag/$r_tag[tag]'>$r_tag[tag]</a>, ";
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
                                            <span class='date'><?=formatDate($r['dtime'], $lang, false)?></span>
                                        </td>
<?php
                                    }
                                    #Comment counter
                                    if ($r["comments"] == 1){
                                        $q_comment = mysqli_query($con, "SELECT count(id) AS count FROM post_comment WHERE post = $r[id] AND visible = 1;");
                                        $r_comment = mysqli_fetch_array($q_comment);
?>
                                        <meta itemprop='commentCount interationCount' content='<?=$r_comment["count"]?>'/>
                                        <td class='r_comment'>
<?php
                                            if ($r_comment['count'] == 1){
?>
                                                <span class='comment_counter'>1 <?=$lng["blog_comments_1"]?></span>
<?php
                                            }
                                            else if ($r_comment['count'] == 0){
?>
                                                <span class='comment_counter'><?=$lng["blog_comments_0"]?></span>
<?php
                                            }
                                            else{
?>
                                                <span class='comment_counter'>$<?=r_comment["count"]?> <?=$lng["blog_comments_multiple"]?></span>
<?php
                                            }
?>
                                        </td>
<?php
                                    } // if ($r["comments"] == 1)
?>
                                </tr>
                            </table> <!-- .post_footer -->
                        </div> <!-- .blog_entry -->
                        </br>
<?php
                    } // while($r = mysqli_fetch_array($q))
?>
            </div> <!-- .section -->
<?php
            include("common/rightpanel.php");
?>
        </div>
<?php
            include("../footer.php");
            $ad = ad($con, $lang, $lng);
            stats($ad, $ad_static, "blog", "");
?>
    </body>
</html>
