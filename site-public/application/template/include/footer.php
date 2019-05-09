<footer>
    <div id='footer_social'>
        <span id='footer_follow' class='desktop'>
            <?=$page->string["follow_us"]?>
        </span>
        <br/>
        <a title='Facebook' target='_blank' href='<?=$data["social"]["facebook"]?>'>
            <img src='<?=$static["layout"]?>/social/facebook.gif' alt='Facebook'/>
        </a>
        <a title='Twitter' target='_blank' href='<?=$data["social"]["twitter"]?>'>
            <img src='<?=$static["layout"]?>/social/twitter.gif' alt='Twitter'/>
        </a>
        <a title='Youtube' target='_blank' href='<?=$data["social"]["youtube"]?>'>
            <img src='<?=$static["layout"]?>/social/youtube.gif' alt='Youtube'/>
        </a>
        <a title='Instagram' target='_blank' href='<?=$data["social"]["instagram"]?>'>
            <img src='<?=$static["layout"]?>/social/instagram.gif' alt='Instagram'/>
        </a>
        <br/>
        <span id='footer_info' class='desktop'>
            <?=$data["registry"]["name"]?>
            <br/>
            <?=$page->string["registry_number"]?>: <?=$data["registry"]["number"]?>
        </span>
    </div>
    <div id='footer_sponsors'>
        <span class='desktop'>
            <?=$page->string["sponsors"]?>
        </span>
        <br class='desktop'/>
<?php
        foreach ($page->sponsor as $sponsor){
?>
            <a target='_blank' href='<?=$sponsor->link?>'>
                <img src='<?=$static["content"]?>/sponsor/<?=$sponsor->image?>' srcset='<?=srcset("sponsor/" . $sponsor->image)?>'/>
            </a>
<?php
        }
?>
    </div>
    <div id='footer_util'>
        <a href='<?=$base_url?>/ayuda/'>
            <?=$page->string["section_help"]?>
        </a>
        &nbsp;&nbsp;-&nbsp;&nbsp;
        <a href='<?=$base_url?>/ayuda/#section_privacy'>
            <?=$page->string["section_privacy"]?>
        </a>
        <br/>
        <br class='mobile'/>
        <img class='lang' alt='Espanol' src='/img/lang/es.gif' onClick='changeLanguage("es", "<?=$base_url?>");'/>
        <img class='lang' alt='English' src='/img/lang/en.gif' onClick='changeLanguage("en", "<?=$base_url?>");'/>
        <img class='lang' alt='Euskara' src='/img/lang/eu.gif' onClick='changeLanguage("eu", "<?=$base_url?>");'/>
        <br class='desktop'/>
        <a title='Google Play' href='<?=$data["app"]["android"]?>'>
            <img class='app' alt='<?=$page->string["app_android"]?>' src='/img/app/android.gif' />
        </a>
        <br class='desktop'/>
        <a title='App Store' href='<?=$data["app"]["ios"]?>'>
            <img class='app' alt='<?=$page->string["app_ios"]?>' src='/img/app/ios.gif' />
        </a>
    </div>
</footer>
