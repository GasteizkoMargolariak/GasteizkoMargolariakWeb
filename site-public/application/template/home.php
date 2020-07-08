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
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>home.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='<?=$static["script"]?>ui.js'></script>
        <script type='text/javascript' src='<?=$static["script"]?>home.js'></script>
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
            <!-- TODO: Festivals -->
<?php
            if (sizeof($page->future) > 0){
?>
                <section id='future'>
                    <h3>
                        <?=$page->string["activities_upcoming"]?>
                    </h3>
<?php
                    foreach($page->future as $activity) {
?>
                        <article>
                            <h4>
                                <a href='<?=$base_url?>/actividades/<?=$activity->permalink?>'><?=$activity->title?></a>
                            </h4>
                            <h5>
                                <?=$activity->city?> - <?=format_date($activity->date, $page->lang, false)?>
                            </h5>
<?php
                            if (sizeof($activity->image) > 0){
?>
                                <a href='<?=$base_url?>/actividades/<?=$activity->permalink?>'>
                                    <img alt='<?=$activity->title?>' title='<?=$activity->title?>' src='<?=$static["content"]?>actividades/<?=$activity->image[0]->image?>' srcset='<?=srcset("blog/" . $activity->image[0]->image)?>'/>
                                </a>
<?php
                            } // if (strlen($activity->image) > 0)
?>
                            <p>
                                <?=cut_text($activity->text, 100, $page->string["keep_reading"], $base_url . "/actividades/" . $activity->permalink)?>
                            </p>
                            <div class='details'>
                                <div class='details_tags hidden'>
<?php
                                    $tags = "";
                                    foreach($activity->tag as $tag) {
                                        $tags = $tags . $tag . ", ";
                                    }
                                    substr($tags, 0, strlen($tags) - 2);
?>
                                    <?=$tags?>
                                </div>
                                <div class='details_comments'>
<?php
                                    $comment = "";
                                    if (sizeof($activity->comment) == 1){
                                        $comments = $page->string["comment_1"];
                                    }
                                    else if (sizeof($activity->comment) == 0){
                                        $comments = $page->string["comment_0"];
                                    }
                                    else{
                                        $comments = str_replace("#", sizeof($activity->comment), $page->string["comment_n"]);
                                    }
?>
                                    <?=$comments?>
                                </div>
                            </div>
                        </article>
<?php
                    } // foreach($page->future as $activity)
?>
                </section>
<?php
            }
?>
            <section id='past'>
                <h3>
                    <?=$page->string['activities_latest']?>
                </h3>
<?php
                foreach($page->past as $activity) {
?>
                    <article>
                        <h4>
                            <a href='<?=$base_url?>/actividades/<?=$activity->permalink?>'><?=$activity->title?></a>
                        </h4>
                        <h5>
                            <?=$activity->city?> - <?=format_date($activity->date, $page->lang, false)?>
                        </h5>
<?php
                        if (sizeof($activity->image) > 0){
?>
                            <a href='<?=$base_url?>/actividades/<?=$activity->permalink?>'>
                                <img alt='<?=$activity->title?>' title='<?=$activity->title?>' src='<?=$static["content"]?>actividades/<?=$activity->image[0]->image?>' srcset='<?=srcset("blog/" . $activity->image[0]->image)?>'/>
                            </a>
<?php
                        } // if (strlen($activity->image) > 0)
?>
                        <p>
                            <?=cut_text($activity->text, 100, $page->string["keep_reading"], $base_url . "/actividades/" . $activity->permalink)?>
                        </p>
                        <div class='details'>
                            <div class='details_tags hidden'>
<?php
                                $tags = "";
                                foreach($activity->tag as $tag) {
                                    $tags = $tags . $tag . ", ";
                                }
                                substr($tags, 0, strlen($tags) - 2);
?>
                                <?=$tags?>
                            </div>
                            <div class='details_comments'>
<?php
                                $comment = "";
                                if (sizeof($activity->comment) == 1){
                                    $comments = $page->string["comment_1"];
                                }
                                else if (sizeof($activity->comment) == 0){
                                    $comments = $page->string["comment_0"];
                                }
                                else{
                                    $comments = str_replace("#", sizeof($activity->comment), $page->string["comment_n"]);
                                }
?>
                                <?=$comments?>
                            </div>
                        </div>
                    </article>
<?php
                } // foreach($page->future as $activity)
?>
            </section> <!-- #past -->
            <section id='blog'>
                <h3>
                    <?=$page->string["blog_latest"]?>
                </h3>
<?php
                foreach($page->post as $post) {
?>
                    <article>
                        <h4>
                            <a href='<?=$base_url?>/blog/<?=$post->permalink?>'><?=$post->title?></a>
                        </h4>
<?php
                        if (sizeof($post->image) > 0){
?>
                            <a href='<?=$base_url?>/blog/<?=$post->permalink?>'>
                                <img alt='<?=$post->title?>' title='<?=$post->title?>' src='<?=$static["content"]?>blog/<?=$post->image[0]->image?>' srcset='<?=srcset("blog/" . $post->image[0]->image)?>'/>
                            </a>
<?php
                        } // if (strlen($post->image) > 0)
?>
                        <p>
                            <?=cut_text($post->text, 100, $page->string["keep_reading"], $base_url . "/blog/" . $post->permalink)?>
                        </p>
                        <div class='details'>
                            <div class='details_date'>
                                <?=format_date($post->dtime, $page->lang, false)?>
                            </div>
                            <div class='details_tags hidden'>
<?php
                                $tags = "";
                                foreach($post->tag as $tag) {
                                    $tags = $tags . $tag . ", ";
                                }
                                substr($tags, 0, strlen($tags) - 2);
?>
                                <?=$tags?>
                            </div>
                            <div class='details_comments'>
<?php
                                $comment = "";
                                if (sizeof($post->comment) == 1){
                                    $comments = $page->string["comment_1"];
                                }
                                else if (sizeof($post->comment) == 0){
                                    $comments = $page->string["comment_0"];
                                }
                                else{
                                    $comments = $comments = str_replace("#", sizeof($post->comment), $page->string["comment_n"]);
                                }
?>
                                <?=$comments?>
                            </div>
                        </div>
                    </article>
<?php
                } foreach($page->post as $post)
?>
            </section> <!-- #blog -->
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
