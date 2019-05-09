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
            <section id='activity_future'>
                <h3>
                    <?=$page->activity['title']?> - <?=format_date($page->activity['date'], $page->lang, false)?>
                </h3>
                <article>
<?php
                    if (strlen($page->activity->image) > 0){
?>
                        <img id='main_image' alt='<?=$page->activity->title?>' title='<?=$page->activity->title?>' src='<?=$static["content"]?>activity/<?=$page->activity->image[0]->image?>' srcset='<?=srcset("activity/" . $page->activity->image[0]->image)?>'/>
<?php
                    }
?>
                    <p>
                        <?=$page->activity->text?>
                    </p>
                    <table id='activity_details'>
                        <tr>
                            <td class='field_name'>
                                <?=$page->string["activities_city"]?>
                            </td>
                            <td>
                                <?=$page->activity->city?>
                            </td>
                        </tr>
                        <tr>
                            <td class='field_name'>
                                <?=$page->string["city"]?>
                            </td>
                            <td>
<?php
                                if ($page->activity->price == 0){
?>
                                    <?=$page->string["price_free"]?>
<?php
                                }
                                else{
?>
                                    <?=$page->activity->price?>€
<?php
                                }
?>
                            </td>
                        </tr>
<?php
                            if ($page->activity->price == 0){
?>
                                <tr>
                                    <td class='field_name'>
                                        <?=$page->string["activities_inscription"]?>
                                    </td>
                                    <td>
<?php
                                        if ($page->activity->inscription){
?>
                                            <?=$page->string["yes"]?>
<?php
                                        }
                                        else{
?>
                                            <?=$page->string["no"]?>
<?php
                                        }
?>
                                    </td>
                                </tr>
<?php
                            }
                            if ($page->activity->inscription && $page->activity->max_people > 0){
?>
                                <tr>
                                    <td class='field_name'>
                                        <?=$page->string["activities_maxpeople"]?>
                                    </td>
                                    <td>
                                        <?=$page->activity->max_people?>
                                    </td>
                                </tr>
<?php
                            }
?>
                    </table>
<?php
                    if (sizeof($page->activity->itinerary) > 0){
?>
                        <table id='activity_itinerary'>
                            <tr>
                                <th>
                                    <?=$page->string["activities_when"]?>
                                </th>
                                <th>
                                    <?=$page->string["activities_what"]?>
                                </th>
                            </tr>
<?php
                            foreach ($page->activity->itinerary as $itin){
?>
                                <tr>
                                    <td>
<?php
                                        if (is_set($itin->end)){
?>
                                            <?=date("H:i", $itin->start)?> - <?=date("H:i", $itin->end)?>
<?php
                                        }
                                        else{
?>
                                            <?=date("H:i", $itin->start)?>
<?php
                                        }
?>
                                    </td>
                                    <td>
                                        <h5>
                                            <?=$itin->name?>
                                        </h5>
                                        <p>
                                            <?=$itin->description?>
                                        </p>
                                        <span class='place'>
                                            <?=$itin->place->name?>
<?php
                                            if ($itin->place->name != $itin->place->address){
?>
                                                <span class='address'>
                                                    <?=$itin->place->address?>
                                                </span>
<?php
                                            }
?>
                                        </span>
                                    </td>
                                </tr>
<?php
                            }
?>
                        </table>
<?php
                    }
?>
                </article>
            </section>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
