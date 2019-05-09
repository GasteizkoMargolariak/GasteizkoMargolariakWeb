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
            <section id='activity_past'>
                <h3>
                    <?=$page->activity->title?> - <?=format_date($page->activity->date, $page->lang, false)?>
                </h3>
                <article>
<?php
                    if (sizeof($page->activity->image) > 0){
?>
                        <img id='main_image' alt='<?=$page->activity->title?>' title='<?=$page->activity->title?>' src='<?=$static["content"]?>activity/<?=$page->activity->image[0]->image?>' srcset='<?=srcset("activity/" . $page->activity->image[0]->image)?>'/>
<?php
                    }
?>
                    <p>
                        <h5 class='italic'>
                            <?=$page->string["city"]?>
                        </h5>
<?php
                        if (isset($page->activity->after)){
?>
                            <?=$page->activity->after?>
<?php
                        }
                        else{
?>
                            <?=$page->activity->text?>
<?php
                        }
?>
                    </p>
                </article>
            </section>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
