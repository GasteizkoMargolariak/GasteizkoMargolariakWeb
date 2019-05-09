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
        <link rel='stylesheet' type='text/css' href='<?=$static["css"]?>help.css'/>
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
            <section id='shortcout'>
                <h3>
                    <?=$page->string["help_shortcouts"]?>
                </h3>
                <article>
                    <ul>
                        <li>
                            <a href="#association">
                                <?=$page->string["help_contact_title"]?>
                            </a>
                        </li>
                        <li>
                            <a href="#license">
                                <?=$page->string["help_license_title"]?>
                            </a>
                        </li>
                        <li>
                            <a href="#privacy">
                                <?=$page->string["help_privacy_title"]?>
                            </a>
                        </li>
                        <li>
                            <a href="#cookie">
                                <?=$page->string["help_cookie_title"]?>
                            </a>
                        </li>
                        <li>
                            <a href="#ad">
                                <?=$page->string["help_ad_title"]?>
                            </a>
                        </li>
                    </ul>
                </article>
            </section>
            <section id='association'>
                <h3 class='section_title'><?=$page->string["help_shortcouts"]?></h3>
                <article>
                    <table>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_register"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->number?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_date_constitution"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->constitution_value?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_date_inscription"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->registry_date?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_city"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->city?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_territory"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->county?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_country"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->country?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_clasification"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->type?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_objectives"]?>
                            </td>
                            <td class='value'>
                                <?=$data->registry->target?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_phone"]?>
                            </td>
                            <td class='value'>
                                <?=$data->contact->phone?>
                            </td>
                        </tr>
                        <tr>
                            <td class='name'>
                                <?=$page->string["help_contact_email"]?>
                            </td>
                            <td class='value'>
                                <a target='_blank' href='mailto:<?=$data->contact->mail?>'>
                                    <?=$data->contact->mail?>
                                </a>
                            </td>
                        </tr>
                    </table>
                </article>
            </section>
            <section id='license'>
                <h3>
                    <?=$page->string["help_license_title"]?>
                </h3>
                <article>
                    <?=$page->string["help_license"]?>
                </article>
            </section>
            <section id='privacy'>
                <h3>
                    <?=$page->string["help_privacy_title"]?>
                </h3>
                <article>
                    <?=$page->string["help_privacy"]?>
                </article>
            </section>
            <section id='cookie'>
                <h3>
                    <?=$page->string["help_cookie_title"]?>
                </h3>
                <article>
                    <?=$page->string["help_cookie"]?>
                </article>
            </section>
            <section id='ad'>
                <h3>
                    <?=$page->string["help_ad_title"]?>
                </h3>
                <article>
                    <?=$page->string["help_ad"]?>
                </article>
            </section>
        </main>
<?php
        include $path["include"] . "footer.php";
?>
    </body>
</html>
