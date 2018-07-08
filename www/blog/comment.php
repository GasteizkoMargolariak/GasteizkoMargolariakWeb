<?php

    $http_host = $_SERVER["HTTP_HOST"];
    include("../functions.php");
    $con = startdb("rw");
    $proto = getProtocol();
    $server = "$proto$http_host";

    //Get post values
    $from =mysqli_real_escape_string($con, $_POST["from"]);
    $post = mysqli_real_escape_string($con, $_POST["post"]);
    $user = mysqli_real_escape_string($con, $_POST["user"]);
    $text = mysqli_real_escape_string($con, $_POST["text"]);
    $lang = strtolower($_POST["lang"]);

    //Check if post exists and it allows comments
    $q_post = mysqli_query($con, "SELECT id FROM post WHERE id = $post AND comments = 1;");
    error_log("SELECT id FROM post WHERE id = $post AND comments = 1;");
    if (mysqli_num_rows($q_post) == 0){
        error_log("Tried to post a comment in a nonexistent post or a post that doesnt allow comments. POST ID: '$post'");
        http_response_code(405);
        exit(-1);
    }

    //Check language, set fallback.
    if ($lang != 'es' && $lang != 'en' && $lang != 'eu'){
        $lang = 'es';
    }

    //Chack for null fields
    if (strlen($user) <= 0 || strlen($text) <= 0){
        error_log("Tried to post a comment with null text or user on post. USER: '$user', TEXT: '$text'");
        http_response_code(405);
        exit(-1);
    }

    if ($from == 'web'){
        //Format newlines in text
        $text = str_replace(["\r\n", "\r", "\n"], "<br/>", $text);

        //Get visit
        $ip = getUserIP();
        $visit = '';
        $q = mysqli_query($con, "SELECT id FROM stat_visit WHERE ip = '$ip';");
        if (mysqli_num_rows($q) > 0){
            $r = mysqli_fetch_array($q);
            $visit = $r["id"];
        }

        //Insert row
        mysqli_query($con, "INSERT INTO post_comment (post, text, username, lang, visit) VALUES ($post, '$text', '$user', '$lang', '$visit');");
    }
    elseif ($from == 'app'){
        $code = mysqli_real_escape_string($con, $_POST["code"]);
        mysqli_query($con, "INSERT INTO post_comment (post, text, username, lang, app) VALUES ($post, '$text', '$user', '$lang', '$code');");
    }

    //Prepare the page to update the comment section
    $q_comment = mysqli_query($con, "SELECT id, post, DATE_FORMAT(dtime, '%Y-%m-%dT%T') AS isodate, dtime, user, username, lang, text FROM post_comment WHERE post = $post AND approved = 1 ORDER BY dtime;");
    while ($r_comment = mysqli_fetch_array($q_comment)){
?>
        <div itemprop='comment' itemscope itemtype='http://schema.org/UserComments' id='comment_<?=$r_comment["id"]?>' class='comment'>
            <span itemprop='creator' class='comment_user'><?=$r_comment["username"]?></span>
            <span class='comment_date date'><meta itemprop='commentTime' content='<?=$r_comment["isodate"]?>'/><?=formatDate(<?=$r_comment["dtime"], $lang)?></span>
            <p itemprop='commentText' class='comment_text'><?=$r_comment["text"]?></p>
            <hr class='comment_line'/>
        </div>
<?php
    }
?>
