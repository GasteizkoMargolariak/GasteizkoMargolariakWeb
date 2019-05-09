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
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>us.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='<?=$static["js"]?>ui.js'></script>
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
            <section id='association'>
                <h3>
                    <?=$page->string["us_association"]?>
                </h3>
                <article>
                    <img src="<?=$static["layout"]?>/us/logo.png" alt=" "/>
                    <?=$page->string["us_association_content"]?>
                </div>
            </section>
            <section id='cuadrilla'>
                <h3>
                    <?=$page->string["us_cuadrilla"]?>
                </h3>
                <article>
                    <img src="<?=$static["layout"]?>/us/margolo.png" alt=" "/>
                    <?=$page->string["us_cuadrilla_content"]?>
                </div>
            </section>
            <section id='activities'>
                <h3>
                    <?=$page->string["us_activities"]?>
                </h3>
                <article>
                    <img src="<?=$static["layout"]?>/us/activities.png" alt=" "/>
                    <?=$page->string["us_activities_content"]?>
                </div>
            </section>
            <section id='transparency'>
                <h3>
                    <?=$page->string["us_transparency"]?>
                </h3>
                <article>
                    <img src="<?=$static["layout"]?>/us/transparency.png" alt=" "/>
                    <?=$page->string["us_transparency_content"]?>
                </div>
            </section>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
