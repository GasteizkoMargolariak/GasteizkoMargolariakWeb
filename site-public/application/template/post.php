<!DOCTYPE html>
<html lang='<?=$page->lang?>'>
    <head>
        <meta content='text/html; charset=utf-8' http-equiv='content-type'/>
        <meta charset='utf-8'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'/>
        <title><?=$page->title?></title>
        <link rel='shortcut icon' href='<?=$page->favicon?>'/>
        <!-- CSS files -->
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>ui.css'/>
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>blog.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='<?=$static["script"]?>ui.js'></script>
        <script type='text/javascript' src='<?=$static["script"]?>blog.js'></script>
        <!-- Meta tags -->
        <link rel='canonical' href='<?=$page->canonical?>'/>
        <link rel='author' href='<?=$page->author?>'/>
        <link rel='publisher' href='<?=$page->author?>'/>
        <meta name='description' content='<?=$page->description?>'/>
        <meta property='og:title' content='<?=$page->title?>'/>
        <meta property='og:url' content='<?=$page->canonical?>'/>
        <meta property='og:description' content='<?=$page->description?>'/>
        <meta property='og:image' content='<?=$page->icon?>'/>
        <meta property='og:site_name' content='<?=$page->name?>'/>
        <meta property='og:type' content='website'/>
        <meta property='og:locale' content='<?=$page->lang?>'/>
        <meta name='twitter:card' content='summary'/>
        <meta name='twitter:title' content='<?=$page->title?>'/>
        <meta name='twitter:description' content='<?=$page->description?>'/>
        <meta name='twitter:image' content='<?=$page->icon?>'/>
        <meta name='twitter:url' content='<?=$page->canonical?>'/>
        <meta name='robots' content='index follow'/>
    </head>
    <body>
<?php
        include $path["include"] . "header.php";
?>
        <main>
            <section id='post'>
                <h3>
                    <?=$page->post->title?>
                </h3>
                <article>
<?php
                    if (sizeof($page->post->image) > 0){
?>
                        <img id='main_image' alt='<?=$page->post->title?>' title='<?=$page->post->title?>' src='<?=$static["content"]?>blog/<?=$page->post->image[0]->image?>' srcset='<?=srcset("blog/" . $page->post->image[0]->image)?>'/>
<?php
                    }
?>
                    <p>
                        <?=$page->post->text?>
                    </p>
                    <div id='details'>
                        <div class='post_details_date'>
                            <?=format_date($page->post->dtime, $page->lang, false)?>
                        </div>
                        <!--<div class='post_details_tags'>
<?php
                            $tags = "";
                            foreach($page->post->tag as $tag) {
                                $tags = $tags . $tag . ", ";
                            }
                            substr($tags, 0, strlen($tags) - 2);
?>
                            <?=$tags?>
                        </div>-->
                    </div>
                    <div id='comments'>
<?php
                        $comment = "";
                        if (sizeof($page->post->comment) == 1){
                            $comments =  $page->string["comment_1"];
                        }
                        else if (sizeof($page->post->comment) == 0){
                            $comments = $page->string["comment_0"];
                        }
                        else{
                            $comments =  str_replace("#", sizeof($page->post->comment), $page->string["comment_n"]);
                        }
?>
                        <?=$comments?>
<?php
                        foreach ($page->post->comment as $comment){
?>
                            <div id='comment_<?=$comment->id?>' class='comment'>
                                <span class='comment_user'>
<!--                                     <?=$comment->username?> -->
                                </span>
                                <span class='comment_date date'>
                                    <?=format_date($comment->dtime, $page->lang)?>
                                </span>
                                <p class='comment_text'>
                                    <?=$comment->text?>
                                </p>
                            </div>
<?php
                        }
?>
                    </div>
                    <div class='comment' id='comment_new'>
                        <form id='comment_form' method='post' action='/' onsubmit='event.preventDefault();postComment($id, "<?=$page->lang?>");'>
                            <textarea id='new_comment_text' name='text' maxlength='1800' onChange='dissmiss_comment_error();' onKeyDown='dissmiss_comment_error();' placeholder='<?=$page->string["comment_form_text"]?>'>
                            </textarea>
                            <input type='hidden' name='id' value='<?=$page->post->id?>'/>
                            <br/>
                            <div id='identification_form'>
                                <input id='new_comment_user' name='user' maxlength='50' type='text' placeholder='<?=$page->string["comment_form_name"]?>' onChange='dissmiss_comment_error();' onKeyDown='dissmiss_comment_error();'/>
                            <input type='submit' value='<?=$page->string["comment_form_send"]?>'/>
                            </div>
                        </form>
                    </div>
                </article>
            </section>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
