<?php
    $http_host = $_SERVER["HTTP_HOST"];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $server = "$proto$http_host";

    //Language
    $lang = selectLanguage();
    include("../lang/lang_$lang.php");

    $cur_section = $lng["section_blog"];

    // Get current post id. Redirect if inexistent or invisible
    $perm = mysqli_real_escape_string($con, $_GET["perm"]);
    $q = mysqli_query($con, "SELECT id, permalink, title_$lang AS title, text_$lang AS text, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, DATE_FORMAT(dtime,'%b %d, %Y T%T') as dtime, comments FROM post WHERE permalink = '$perm' AND visible = 1;");
    if (mysqli_num_rows($q) == 0){
        header("Location: $server/blog/");
        exit(-1);
    }
    $r = mysqli_fetch_array($q);
    $id = $r["id"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content='text/html; charset=windows-1252' http-equiv='content-type'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title><?=$r["title"]?> - Gasteizko Margolariak</title>
        <link rel='shortcut icon' href='<?=$server?>/img/logo/favicon.ico'>
        <!-- CSS files -->
        <style>
<?php
            include("../css/ui.css");
            include("../css/blog.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media='(max-width: 990px)'>
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
        <link rel='canonical' href='<?=$server?>/blog/<?=$r["permalink"]?>'/>
        <link rel='author' href='<?=$server?>'/>
        <link rel='publisher' href='<?=$server?>'/>
        <meta name='description' content='<?=strip_tags($r["text"])?>'/>
        <meta property='og:title' content='<?=$r["title"]?> - Gasteizko Margolariak'/>
        <meta property='og:url' content='<?=$server?>/blog/<?=$r["permalink"]?>'/>
        <meta property='og:description' content='<?=strip_tags($r["text"])?>'/>
<?php
        $q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $id ORDER BY idx;");
        if (mysqli_num_rows($q_image) == 0)
            $img = "$server/img/logo/cover.png";
        else{
            $r_image = mysqli_fetch_array($q_image);
            $img = "$server/img/blog/preview/" . $r_image["image"];
        }
?>
        <meta property='og:image' content='<?=$img?>'/>
        <meta property='og:site_name' content='Gasteizko Margolariak'/>
        <meta property='og:type' content='article'/>
        <meta property='og:locale' content='<?=$lang?>'/>
        <meta property='article:section' content='Blog'/>
        <meta property='article:published-time' content='<?=$r["isodate"]?>'>
        <meta property='article:modified-time' content='<?=$r["isodate"]?>'/>
        <meta property='article:author' content="Gasteizko Margolariak'/>
<?php
        $q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post = $id;");
        $tag_string = "vitoria cuadrilla gasteizko margolariak ";
        while ($r_tag = mysqli_fetch_array($q_tag))
            $tag_string = $tag_string . " " . $r_tag["tag"]
?>
        <meta property='article:tag' content='<?=$tag_string?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$r["title"]?> - Gasteizko Margolariak'/>
        <meta name='twitter:description' content='<?=strip_tags($r["text"])?>'/>
        <meta name='witter:image' content='<?=$img?>'/>
        <meta name='twitter:url' content='<?=$server?>/blog/<?=$r["permalink"]?>'/>
        <meta name='robots' content="index follow"/>
    </head>
    <body>
<?php
        include("../header.php");
?>
        <div id='content'>
<?php
            include("common/leftpanel.php");
?>
            <div id="middle_column">
                <div itemscope itemtype='http://schema.org/BlogPosting' class='section blog_entry'>
                    <meta itemprop='inLanguage' content='<?=$lang?>'/>
                    <meta itemprop='datePublished dateModified' content='<?=$r["isodate"]?>'/>
                    <meta itemprop='headline name' content='<?=$r["title"]?>'/>
                    <meta itemprop='mainEntityOfPage' content='<?=$server?>'/>
                    <div class='hidden' itemprop='author publisher' itemscope itemtype='http://schema.org/Organization'>
                        <meta itemprop='legalName' content='Asociaci&oacute;n Cultural Recreativa Gasteizko Margolariak'/>
                        <meta itemprop='name' content='Gasteizko Margolariak'/>
                        <meta itemprop='logo' content='<?=$server?>/img/logo/logo.png'/>
                        <meta itemprop='foundingDate' content='03-02-2013'/>
                        <meta itemprop='telephone' content='+34637140371'/>
                        <meta itemprop='url' content='<?=$server?>'/>
                    </div>
                    <h3 class='section_title'><?=$r["title"]?></h3>
                    <div class='entry'>
<?php
                        if ($r_image != null){
?>
                            <div class='image_container'>
                                <meta itemprop='image' content='<?=$server?>/img/blog/<?=$r_image["image"]?>'/>
                                <img class='post_image post_image_large' alt='<?=$r["title"]?>' src='<?=$server?>/img/blog/preview/<?=$r_image["image"]?>'/>
                            </div>
<?php
                        }
?>
                        <p itemprop='text articleBody'><?=$r["text"]?></p>
<?php
                        //Other images
                        if ($r_image != null){
?>
                            <table id='secondary_images'>
                                <tr>
<?php
                                    while ($r_image = mysqli_fetch_array($q_image)){
?>
                                        <meta itemprop='image' content='<?=$server?>/img/blog/<?=$r_image["image"]?>'/>
                                        <td>
                                            <img src='<?=$server?>/img/blog/preview/<?=$r_image["image"]?>'/>
                                        </td>
<?php
                                    }
?>
                                </tr>
                            </table>
<?php
                        }
?>
                        <hr/>
                        <table class='post_footer'>
                            <tr>
<?php
                                # Tags (if any) and date
                                $q_tag = mysqli_query($con, "SELECT tag FROM post_tag WHERE post= $id;");
                                if (mysqli_num_rows($q_tag) > 0){
                                    $tag_string = "<span id='tags' class='desktop'>Tags: ";
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
                                        <span class='date'><?=formatDate($r["dtime"], $lang)?></span>
                                        <br/><br class='mobile'/>
                                        <?=$tag_string?>
                                    </td>
<?php
                                }
                                else{
?>
                                    <td>
                                        <span class='date'>
                                            <span class='hidden'><?=formatDate($r["dtime"], $lang)?>
                                        </span>
                                    </td>
<?php
                                }
                                #Share
?>
                                <td class='r_share'>
                                    <span class='share'>
                                        <span class='desktop'><?=$lng["blog_share"]?></span>
<?php
                                        $title = urlencode("$r[title] - $http_host");
                                        $url_f = htmlspecialchars("https://www.facebook.com/sharer/sharer.php?u=$server$_SERVER[REQUEST_URI]");
                                        $url_t = htmlspecialchars("https://twitter.com/share?url=$server$_SERVER[REQUEST_URI]&text=$title");
                                        $url_g = htmlspecialchars("https://plus.google.com/share?url=$server$_SERVER[REQUEST_URI]");
?>
                                        <a href='$url_f' target='_blank'><img class='share_icon' alt='Facebook' src='$server/img/social/facebook.gif'/></a>
                                        <a href='$url_t' target='_blank'><img class='share_icon' alt='Twitter' src='$server/img/social/twitter.gif'/></a>
                                        <a href='$url_g' target='_blank'><img class='share_icon' src='$server/img/social/googleplus.gif' alt='Google+'/></a>
                                    </span>
                                </td>
                            </tr>
                        </table> <!-- .post_footer -->
<?php
                        #Comments
                        if ($r["comments"] == 1){
                            $q_comment = mysqli_query($con, "SELECT id, post, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, user, username, lang, text FROM post_comment WHERE post = $id AND approved = 1 ORDER BY dtime;");
                            $count = mysqli_num_rows($q_comment);
?>
                            <hr/>
                            <meta itemprop='commentCount interactionCount' content='<?=$count?>'/>
<?php
                            switch ($count){
                                case 0:
                                    $comment_count = "<h4><meta itemprop='interactionCount' content='0'/></meta>$lng[blog_comments_0]</h4>";
                                    break;
                                case 1:
                                    $comment_count = "<h4><span itemprop='interactionCount'>1</span> $lng[blog_comments_1]</h4>";
                                    break;
                                default:
                                    $comment_count = "<h4><span itemprop='interactionCount'>$count</span> $lng[blog_comments_multiple]</h4>";
                            }
?>
                            <?=$comment_count?>
                            <div id='comment_list'>
<?php
                                while ($r_comment = mysqli_fetch_array($q_comment)){
?>
                                    <div itemprop='comment' itemscope itemtype='http://schema.org/UserComments' id='comment_<?=$r_comment["id"]?>' class='comment'>
                                        <span itemprop='creator' class='comment_user'><?=$r_comment["username"]?></span>
                                        <span class='comment_date date'>
                                            <meta itemprop='commentTime' content='<?=$r_comment["isodate"]?>'/>
                                            <?=formatDate($r_comment["dtime"], $lang)?>
                                        </span>
                                        <p itemprop='commentText' class='comment_text'><?=$r_comment["text"]?></p>
                                        <hr class='comment_line'/>
                                    </div>
<?php
                                }
?>
                            </div> <!-- #comment_list -->
                            <div class='comment' id='comment_new'>
                                <form id='comment_form' method='post' action='/' onsubmit='event.preventDefault();postComment(<?=$id?>, "<?=$lang?>");'>
                                    <textarea id='new_comment_text' name='text' maxlength='1800' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);' placeholder='<?=$lng["blog_placeholder_text"]?>'></textarea>
                                    <input type='hidden' name='id' value='<?=$id?>'/>
                                    <div id='identification_form'>
                                        <br/>
                                        <input id='new_comment_user' name='user' maxlength='50' type='text' placeholder='<?=$lng["blog_placeholder_name"]?>' onChange='defaultInputBorder(this);' onKeyDown='defaultInputBorder(this);'/>
                                        <input type='submit' value='<?=$lng["blog_send"]?>'/>
                                    </div>
                                </form>
                            </div> <!-- .comment_new -->
<?php
                        }
                        else{
?>
                            <h4><?=$lng["blog_comments_closed"]?></h4>
<?php
                        }
?>
                    </div> <!-- .entry -->
                </div> <!-- .section -->
            </div> <!-- #middle_column -->
<?php
            include("common/rightpanel.php");
?>
        </div> <!-- .content -->
<?php
            include("../footer.php");
            $ad = ad($con, $lang, $lng); 
            stats($ad, $ad_static, "blog", "$id");
?>
    </body>
</html>
