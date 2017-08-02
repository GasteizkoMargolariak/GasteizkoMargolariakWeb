<?php
    session_start();
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $server = "$proto$http_host";

    //Language
    $lang = selectLanguage();
    include("../lang/lang_" . $lang . ".php");
    
    $cur_section = $lng['section_help'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?=$lng['footer_help']?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?="$server/img/logo/favicon.ico";?>">
        <!-- CSS files -->
        <style>
            <?php 
                include("../css/ui.css"); 
                include("../css/ayuda.css");
            ?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
            <?php 
                include("../css/m/ui.css"); 
                include("../css/m/ayuda.css");
            ?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
            <?php
                include("../script/ui.js");
            ?>
        </script>
        <!-- Meta tags -->
        <link rel="canonical" href="<?=$server?>/us/"/>
        <link rel="author" href="<?=$server?>"/>
        <link rel="publisher" href="<?=$server?>"/>
        <meta name="description" content="<?=$lng['us_description']?>"/>
        <meta property="og:title" content="<?=$lng['us_title']?> - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?=$server?>"/>
        <meta property="og:description" content="<?=$lng['us_description']?>"/>
        <meta property="og:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta property="og:site_name" content="<?=$lng['us_title']?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="<?=$lang?>"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="<?=$lng['us_title']?> - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="<?=$lng['us_description']?>"/>
        <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta name="twitter:url" content="<?=$server?>"/>
        <meta name="robots" content="index follow"/>
    </head>
    <body>
        <?php include("../header.php"); ?>
		<div id="shortcouts_container">
			<div class="section" id="shortcouts">
				<h3 class='section_title'><?=$lng['help_shortcouts']?></h3>
				<div class="entry">
					<ul>
						<li><a href="#section_association"><?=$lng['help_contact_title']?></a></li>
						<li><a href="#section_license"><?=$lng['help_license_title']?></a></li>
						<li><a href="#section_privacy"><?=$lng['help_privacy_title']?></a></li>
						<li><a href="#section_cookie"><?=$lng['help_cookie_title']?></a></li>
						<li><a href="#section_ads"><?=$lng['help_ad_title']?></a></li>
					</ul>
				</div> <!-- .entry -->
			 </div> <!-- .section -->
		</div> <!-- #shortcouts_container -->
        <div id="content">
            <div class="content_row">
                <div class="content_cell">
                    <div class="section" id="section_association">
                        <h3 class='section_title'><?=$lng['help_contact_title']?></h3>
                        <div class="entry">
                            <table id="association">
                                <tr>
                                    <td class="name"><?=$lng['help_contact_register']?></td>
                                    <td class="value"><?=$lng['help_contact_register_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_date_constitution']?></td>
                                    <td class="value"><?=$lng['help_contact_date_constitution_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_date_inscription']?></td>
                                    <td class="value"><?=$lng['help_contact_date_inscription_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_city']?></td>
                                    <td class="value"><?=$lng['help_contact_city_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_territory']?></td>
                                    <td class="value"><?=$lng['help_contact_territory_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_country']?></td>
                                    <td class="value"><?=$lng['help_contact_country_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_clasification']?></td>
                                    <td class="value"><?=$lng['help_contact_clasification_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_objectives']?></td>
                                    <td class="value"><?=$lng['help_contact_objectives_value']?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_phone']?></td>
                                    <td class="value"><?=$lng['help_contact_phone_value']; ?></td>
                                </tr>
                                <tr>
                                    <td class="name"><?=$lng['help_contact_email']?></td>
                                    <td class="value"><?=$lng['help_contact_email_value']?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="content_cell">
                    <div class="section" id="section_license">
                        <h3 class='section_title'><?=$lng['help_license_title']?></h3>
                        <div class="entry">
                            <?=$lng['help_license']?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content_row">
                <div class="content_cell">
                    <div class="section" id="section_privacy">
                        <h3 class="section_title"><?=$lng['help_privacy_title']?></h3>
                        <div class="entry">
                            <?=$lng['help_privacy']; ?>
                        </div>
                    </div>
                </div>
                <div class="content_cell">
                    <div class="section" id="section_cookie">
                        <h3 class='section_title'><?=$lng['help_cookie_title']?></h3>
                        <div class="entry">
                            <?=$lng['help_cookie']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content_row">
                <div class="content_cell">
                    <div class="section" id="section_ads">
                        <h3 class="section_title"><?=$lng['help_ad_title']?></h3>
                        <div class="entry">
                            <?=$lng['help_ad']?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            include("../footer.php");
            $ad = ad($con, $lang, $lng); 
            stats($ad, $ad_static, "ayuda", "");
        ?>
    </body>
</html>
