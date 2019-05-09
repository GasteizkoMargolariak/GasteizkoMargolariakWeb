<h3>
<?php
    if (is_set($page->photo->title)){
?>
        <?=$page->photo->title?>
<?php
    }
?>
    <div id='viewer_close_container'>
        <img id='viewer_close' class='pointer' alt=' ' src='<?=$static["layout"]?>/control/close.png' onClick='closeViewer();'/>
    </div>
</h3>
<article>
    <div class='arrow'>
        <img id='viewer_arrow_left' class='viewer_arrow pointer' alt=' ' src='<?=$static["layout"]?>/control/slid-left.png' onClick='scrollPhoto(-1);'/>
    </div>
    <div id='photo_view'>
        <img alt=' <?=$page->photo->title?>' title=' <?=$page->photo->title?>' src='<?=$static["content"]?>gallery/<?=$page->photo->image?>' srcset='<?=srcset("gallery/" . $page->photo->image)?>'/>
<?php
        if (is_set($page->photo->description)){
?>
            <p>
                <?=$page->photo->description?>
            </p>
<?php
        }
        if (is_set($page->photo->username)){
?>
            <span id='photo_user' class='italic'>
                <?=$page->string["photo_user"]?><?=$page->photo->username?>
            </span>
<?php
        }
?>
        <span id='photo_date' class='italic'>
            <?=format_date($page->photo->uploaded, $lang)?>
        </span>
    </div>
    <div class='arrow'>
        <img id='viewer_arrow_right' class='viewer_arrow pointer' alt=' ' src='<?=$static["layout"]?>/control/slid-left.png' onClick='scrollPhoto(1);'/>
    </div>
</article>
