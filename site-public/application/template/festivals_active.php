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
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>festivals.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='<?=$static["js"]?>ui.js'></script>
        <script type='text/javascript' src='<?=$static["js"]?>festivals.js'></script>
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
            <section id='festivals_active'>
                <h3>
                    <?=str_replace('#', $page->festival->year, $page->string["festival_title_year"])?>
                </h3>
                <article>
<?php
                    if (is_set($page->festival->image)){
?>
                        <img alt='<?=$page->festival->year?>' title='<?=$page->festival->year?>' src='<?=$static["content"]?>festivals/<?=$page->festival->image?>' srcset='<?=srcset("festivals/" . $page->festival->image)?>'/>
<?php
                    }
?>
                    <p>
                        <?=$page->festival->year?>
                    </p>
                </article>
            </section>
            <section id='festival_schedule'>
                <h3>
                    <?=$page->string["festival_schedule"]?>
                </h3>
<?php
                foreach ($page->festival->event->internal as $day){
?>
                    <h4 onClick='expandDay(<?=$day?>);' class='pointer'>
                        <img class='slid' src='<?=$static["layout"]?>/control/slid-right.png' id='slid_day_<?=$day?>'/>
<?php
                        $s_day = $day;
                        if ($day == "25"){
                            $s_day = format_festival_date(date("Y") . "-07-" . $day);
                        }
                        else{
                            $s_day = format_festival_date(date("Y") . "-08-" . $day);
                        }
?>
                        <?=$s_day?>
                        <table>
<?php
                            foreach ($day as $event){
?>
                                <tr>
                                    <td>
                                        <span class='time'>
                                            <?=$event->start?>
                                        </span>
                                    </td>
                                    <td class='timeline'>
                                        <img class='timeline_dot' alt=' ' src='<?=$static["layout"]?>/control/schedule-point.png'/>
                                    </td>
                                    <td>
                                        <span class='title'>
                                            <?=$event->title?>
                                        </span>
<?php
                                        if (strlen($event->description) > 0 && $event->description != $event->title){
?>
                                            <div class='description'>
                                                <?=$event->description?>
                                            </div>
<?php
                                        }
?>
                                        <div class='location'>
                                            <!-- TODO: Implement OSM routes -->
                                            <a target='_blank' href='https://www.openstreetmap.org/#map=16/<?=$event->place->location->lat?>/<?=$event->place->location->lon?>'>
                                                <img alt=' ' src=''<?=$static["layout"]?>/control/pinpoint.png'/>
<?php
                                                //If name and address are the same, show only name
                                                if ($event->place->name == $event->place->address){
?>
                                                    <?=$event->place->name?>
<?php
                                                }
                                                else{
?>
                                                    <?=$event->place->name?>
                                                    <span class='address italic'>
                                                        - <?=$event->place->name?>
                                                    </span>
<?php
                                                }
?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
<?php
                            } // foreach ($day as $event)
?>
                        </table>

                    </h4>
<?php
                } // foreach ($page->festival->event->internal as $day)
?>
            </section>
            <section id='festival_prices'>
                <h3>
                    <?=$page->string["festival_prices"]?>
                </h3>
                <article id='festival_prices_days'>
                    <table>
                        <!-- TODO: Members/public -->
<?php
                        foreach ($page->festival->day as $day){
?>
                            <tr>
                                <td>
                                    <?=format_festival_date($day->date)?>
                                </td>
                                <td class='price'>
                                    <?=$day->price?> &euro;
                                </td>
                            </tr>
<?php
                        }
?>
                    </table>
                </article>
                <article id='festival_prices_offers'>
                    <table>
                        <!-- TODO: Members/public -->
<?php
                        foreach ($page->festival->offer as $offer){
?>
                            <tr>
                                <td>
                                    <?=$offer->name?>
                                    <p>
                                        <?=$offer->description?>
                                    </p>
                                </td>
                                <td class='price'>
                                    <?=$offer->price?> &euro;
                                </td>
                            </tr>
<?php
                        }
?>
                    </table>
                </article>
            </section>
<?php
            if (strlen($page->previous) > 0){
?>
                <section id='festival_previous'>
                    <h3>
<!--                         <?=$page->string["festival_past"]?> -->
                    </h3>
                    <article>
                        <ul>
<?php
                            foreach ($page->previous as $y){
?>
                                <li>
                                    <a href='<?=$base_url?>/<?=$url->festivals?>/<?=$y?>'>
                                        <?=$y?>
                                    </a>
                                </li>
<?php
                            }
?>
                        </ul>
                    </article>
                </section>
<?php
            }
?>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
