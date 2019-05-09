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
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>activity.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='<?=$static["script"]?>ui.js'></script>
        <script type='text/javascript' src='<?=$static["script"]?>activity.js'></script>
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
<?php
            if (sizeof($page->future) > 0){
?>
                <section id='future_list'>
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
<?php
                            if (sizeof($activity->image) > 0){
?>
                                <a href='<?=$base_url?>/actividades/<?=$activity->permalink?>'>
                                    <img alt='<?=$activity->title?>' title='<?=$activity->title?>' src='<?=$static["content"]?>activity/<?=$activity->image[0]->image?>' srcset='<?=srcset("activity/" . $activity->image[0]->image)?>'/>
                                </a>
<?php
                            } // if (sizeof($activity->image) > 0)
?>
                            <div class='activity_details'>
                                <span class='date'>
                                    <?=format_date($activity->date, $page->lang, false)?>
                                </span>
                                <span class='city'>
                                    <?=$activity->city?>
                                </span>
                                <span class='price'>
<?php
                                    if ($activity->price == 0){
?>
                                        <?=$page->string["price_free"]?>
<?php
                                    }
                                    else{
?>
                                    <?=$activity->price?>€
<?php
                                    }
?>
                                </span>
                            </div>
                            <p>
                                <?=cut_text($activity->text, 800, $page->string['keep_reading'], $base_url . "/actividades/" . $actividades->permalink)?>
                            </p>
                            <div class='activity_footer'>
                                <div class='activity_footer_tags'>
<?php
                                    $tags = "";
                                    foreach($activity->tag as $tag) {
                                        $tags = $tags . $tag . ", ";
                                    }
                                    substr($tags, 0, strlen($tags) - 2);
?>
                                    <?=$tags?>
                                </div>
                                <div class='activity_footer_comments'>
<?php
                                    $comment = "";
                                    if (sizeof($post->comment) == 1){
                                        $comments = $page->string["comment_1"];
                                    }
                                    else if (sizeof($post->comment) == 0){
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
            } // if (sizeof($page->future) > 0)
?>
            <section id='past_list'>
                <h3>
                    <?=$page->string["activities_past"]?>
                </h3>
<?php
                foreach($page->past as $activity) {
?>
                    <article>
                        <h4>
                            <a href='<?=$base_url?>/actividades/<?=$activity->permalink?>'><?=$activity->title?></a>
                        </h4>
<?php
                        if (strlen($activity->image) > 0){
?>
                            <a href='<?=$base_url?>/actividades/<?=$activity->permalink?>'>
                                <img alt='<?=$activity->title?>' title='<?=$activity->title?>' src='<?=$static["content"]?>activity/<?=$activity->image[0]->image?>' srcset='<?=srcset("activity/" . $activity->image[0]->image)?>'/>
                            </a>
<?php
                        } // if (strlen($activity->image) > 0)
?>
                        <div class='activity_details'>
                            <span class='date'>
                                <?=format_date($activity->date, $page->lang, false)?>
                            </span>
                            <span class='city'>
                                <?=$activity->city?>
                            </span>
                        </div>
                        <div class='activity_footer'>
                            <div class='activity_footer_tags'>
<?php
                                $tags = "";
                                foreach($activity->tag as $tag) {
                                    $tags = $tags . $tag . ", ";
                                }
                                substr($tags, 0, strlen($tags) - 2);
?>
                                <?=$tags?>
                            </div>
                            <div class='activity_footer_comments'>
<?php
                                $comment = "";
                                if (strlen($activity->comment) == 1){
                                    $comments = $page->string["comments_1"];
                                }
                                else if (strlen($activity->comment) == 0){
                                    $comments = $page->string["comments_0"];
                                }
                                else{
                                    $comments = str_replace("#", sizeof($activity->comment), $page->string["comments_n"]);
                                }
?>
                                <?=$comments?>
                            </div>
                        </div>

                    </article>
<?php
                } // foreach($page->past as $activity)
?>
            </section>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
