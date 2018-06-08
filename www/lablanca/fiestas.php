<div class='section'>
    <h3 class='section_title'><?=str_replace('#', $year, $lng['lablanca_title'])?></h3>
    <div class='entry'>
<?php
    //Some text for the festivals
    $q_f = mysqli_query($con, "SELECT * FROM festival WHERE year = $year");
    if (mysqli_num_rows($q_f) > 0){
        $r_f = mysqli_fetch_array($q_f);
        if (strlen($r_f['img']) > 0){
?>
            <div id='festivals_image_top_container'>
                <div id='festivals_image_container'>
                    <img id='festivals_image' src='<?=$server?>/img/fiestas/preview/<?=$r_f["img"]?>'/>
                </div>
            </div>
            <br/>
<?php
        }
?>
        <?=$r_f["text_$lang"]?>
<?php
    }
?>
    </div>
</div>
<div id='content_table'>
    <div class='content_tr'>
        <div class='content_td' id='content_td_schedule'>
            <div class='section' id='schedule'>
                <h3 class='section_title'><?=$lng['lablanca_schedules']?></h3>
                <div class='entry'>
                    <div class='button'>
                        <a href='<?=$server?>/lablanca/programa/margolariak/<?=$year?>'><?=str_replace('#', $year, $lng["lablanca_schedule_year_gm"])?></a>
                    </div>
                    <div class='button'>
                        <a href='<?=$server?>/lablanca/programa/ciudad/<?=$year?>'><?=str_replace('#', $year, $lng["lablanca_schedule_year_city"])?></a>
                    </div>
                </div> <!-- .entry -->
            </div>
        </div>
        <div class='content_td' id='content_td_prices'>
            <div class='section' id='prices'>
                <h3 class='section_title'><?=$lng["lablanca_prices"]?></h3>
                <div class='entry'>
<?php
                    $q_days = mysqli_query($con, "SELECT id, date, DATE_FORMAT(date, '%Y-%m-%d') AS isodate, name_$lang AS name, price FROM festival_day WHERE year(date) = $year ORDER BY date");
                    if (mysqli_num_rows($q_days) > 0){
?>
                        <h4><?=$lng["lablanca_prices_days"]?></h4>
                        <table id='table_days'>
<?php
                            while ($r_days = mysqli_fetch_array($q_days)){
?>
                                <tr>
                                    <td><?=formatFestivalDate($r_days["date"])?></td>
                                    <td class='price'><?=$r_days["price"]?> &euro;</td>
                                </tr>
<?php
                            }
?>
                        </table>
<?php
                    }
?>
                </div>
                <div class='entry'>
<?php
                    $q_offers = mysqli_query($con, "SELECT id, name_$lang AS name, description_$lang AS description, price FROM festival_offer WHERE year = $year ORDER BY days;");
                    if (mysqli_num_rows($q_offers) > 0){
?>
                        <h4><?=$lng["lablanca_prices_offers"]?></h4>
                        <table id='table_offers'>
<?php
                            while ($r_offers = mysqli_fetch_array($q_offers)){
?>
                                <tr>
                                    <td>
                                        <span class='name'><?=$r_offers["name"]?></span>
                                        <br/>
                                        <p class='description'><?=$r_offers["description"]?></p>
                                    </td>
                                    <td class='price'><?=$r_offers["price"]?> &euro;</td>
                                </tr>
<?php
                            }
?>
                        </table>
<?php
                    }
?>
                </div> <!-- .entry -->
            </div> <!-- .section -->
        </div> <!-- .content_td -->
    </div> <!-- .content_tr -->
</div> <!-- #content_table -->
