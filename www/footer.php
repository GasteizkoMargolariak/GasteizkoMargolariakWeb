<div id="footer">
    <div id="footer_table">
        <div class="tr">
            <div class="td" id="footer_left">
                <span id="footer_follow" class="desktop"><?=$lng["footer_follow"]?></span>
                <br/>
                <a title="Facebook" target="_blank" href="https://www.facebook.com/gmargolariak">
                    <img class="footer_social_icon" src="<?=$server?>/img/social/facebook.gif" alt="Facebook"/>
                </a>
                <a title="Twitter" target="_blank" href="https://twitter.com/gmargolariak">
                    <img class="footer_social_icon" src="<?=$server?>/img/social/twitter.gif" alt="Twitter"/>
                </a>
                <a title="Google+" target="_blank" href="https://plus.google.com/106661466029005469492">
                    <img class="footer_social_icon" src="<?=$server?>/img/social/googleplus.gif" alt="Google+"/>
                </a>
                <br class="desktop"/>
                <a title="Youtube" target="_blank" href="https://www.youtube.com/user/GasteizkoMargolariak">
                    <img class="footer_social_icon" src="<?=$server?>/img/social/youtube.gif" alt="Youtube"/>
                </a>
                <a title="Instagram" target="_blank" href="https://instagram.com/gmargolariak/">
                    <img class="footer_social_icon" src="<?=$server?>/img/social/instagram.gif" alt="Instagram"/>
                </a>
                <br/>
                <span id="footer_info" class='desktop'><?=$lng["footer_info"]?></span>
            </div> <!-- #footer_left-->
            <div class="td" id="footer_center">
                <br class="mobile"/><br class="mobile"/>
<?php
                $q = mysqli_query($con, "SELECT id, name_$lang AS name, image, link FROM sponsor WHERE image != '' ORDER BY ammount DESC;");
                $ad_static = Array();
                if (mysqli_num_rows($q) > 0){
?>
                    <span class='desktop'><?=$lng["footer_sponsors"]?></span>
                    </br class='desktop'>
<?php
                    while ($r = mysqli_fetch_array($q)){
                        array_push($ad_static, $r["id"]);
?>
                        <a target='_blank' href='<?=$r["link"]?>'>
                            <img src='<?=$server?>/img/spo/thumb/<?=$r["image"]?>'/>
                        </a>
<?php
                    }
                }
?>
                <br class="mobile"/><br class="mobile"/><br class="mobile"/>
            </div> <!-- #footer_center -->
            <div  class="td" id="footer_right" class="desktop">
                <a href="<?=$server?>/ayuda/"><?=$lng["footer_help"]?></a>&nbsp;&nbsp;&nbsp;
                <a href="<?=$server?>/ayuda/#section_privacy"><?=$lng["footer_privacy"]?></a>
                <br/><br class='mobile'/>
                <img class='lang' alt='Espa&ntilde;ol' src='/img/lang/es.gif' onClick='changeLanguage("es", "<?=$server?>");'/>
                <img class='lang' alt='English' src='/img/lang/en.gif' onClick='changeLanguage("en", "<?=$server?>");'/>
                <img class='lang' alt='Euskara' src='/img/lang/eu.gif' onClick='changeLanguage("eu", "<?=$server?>");'/>
                <br class='desktop'/>
                <a title="Google Play" href="https://play.google.com/store/apps/details?id=com.ivalentin.margolariak">
                    <img class='app' alt="<?=$lng["footer_app_google"]?>" src='/img/app/android.gif'/>
                </a>
                <br class='desktop'/>
                <a title="App Store" href="https://itunes.apple.com/us/app/gasteizko-margolariak/id1227846624">
                    <img class='app' alt="<?=$lng["footer_app_apple"]?>" src='/img/app/ios.gif'/>
                </a>
            </div> <!-- #footer_right -->
        </div> <!-- .tr -->
    </div> <!-- #footer_table -->
</div> <!-- footer -->
<?php

if (isSet($_COOKIE["cookie"]) == false){
?>
    <div id='cookie_popup'>
        <span id='message'><?=$lng["cookie_message"]?></span><br/><br/>
        <span class='button pointer' style="cursor:pointer" id='button_ok'>
            <a onClick="dismissCookiePopUp('<?=$server?>', false);" ><?=$lng["cookie_ok"]?></a>
        </span>
        <span class='button pointer' style="cursor:pointer" id='button_more'>
            <a onClick="dismissCookiePopUp('<?=$server?>', true);" ><?=$lng["cookie_more"]?></a>
        </span>
    </div>
<?php
}
?>
