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
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>gallery.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='<?=$static["script"]?>ui.js'></script>
        <script type='text/javascript' src='<?=$static["script"]?>gallery.js'></script>
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
            <section id='album_list'>
                <h3>
                    <?=$page->title?>
                </h3>
<?php
                foreach($page->album as $album) {
?>
                    <article>
                        <h4>
                            <a href='<?=$base_url?>/galeria/<?=$album->permalink?>'>
                                <?=$album->title?>
                            </a>
                        </h4>
<?php
                        for($i = 0; $i < 4; $i ++) {
                            $r = mt_rand (0, sizeof($album->photo) - 1);
?>
                            <a class='frame' href='<?=$base_url?>/galeria/<?=$album->permalink?>'>
                                <img alt=' <?=$album->photo[$r]->title?>' title=' <?=$album->photo[$r]->title?>' src='<?=$static["content"]?>gallery/<?=$album->photo[$r]->file?>' srcset='<?=srcset("gallery/" . $album->photo[$r]->file)?>'/>
                            </a>
<?php
                        }
?>
                    </article>
<?php
                } // foreach($page->album as $album)
?>
            </section>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
