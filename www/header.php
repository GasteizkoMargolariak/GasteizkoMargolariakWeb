<div id="header" class="desktop">
    <div id="header_content">
        <img src="/img/logo/logo.png"/>
        <div id="header_menu">
            <table>
                <tr>
                    <td><a href="<?=$server?>/"><?=$lng["header_home"]?></a></td>
                    <td><a href="<?=$server?>/lablanca/"><?=$lng["header_lablanca"]?></a></td>
                    <td><a href="<?=$server?>/actividades/"><?=$lng["header_activities"]?></a></td>
                    <td><a href="<?=$server?>/blog/"><?=$lng["header_blog"]?></a></td>
                    <td><a href="<?=$server?>/nosotros/"><?=$lng["header_whoarewe"]?></a></td>
                    <td><a href="<?=$server?>/galeria/"><?=$lng["header_gallery"]?></a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="header_m" class="mobile">
    <img src="/img/logo/logo.png" onClick='toggleMobileMenu();' id='mobile_logo'/>
    <div id="header_menu_m">
        <div id='header_m_title' onClick='openMobileMenu();' class='pointer'>
            <span>
                <img src='<?=$server?>/img/misc/slid-menu.png'/>&nbsp;&nbsp;&nbsp;&nbsp;<?=$cur_section?>
            </span>
        </div>
        <div class='header_m_link'><a href="<?=$server?>/"><?=$lng["header_home"]?></a></div>
        <div class='header_m_link'><a href="<?=$server?>/lablanca/"><?=$lng["header_lablanca"]?></a></div>
        <div class='header_m_link'><a href="<?=$server?>/actividades/"><?=$lng["header_activities"]?></a></div>
        <div class='header_m_link'><a href="<?=$server?>/blog/"><?=$lng["header_blog"]?></a></div>
        <div class='header_m_link'><a href="<?=$server?>/nosotros/"><?=$lng["header_whoarewe"]?></a></div>
        <div class='header_m_link'><a href="<?=$server?>/galeria/"><?=$lng["header_gallery"]?></a></div>
        <div id='header_m_slider' onClick='closeMobileMenu();' class='pointer'>
            <span>
                <img src='<?=$server?>/img/misc/slid-top.png'/>
            </span>
        </div>
    </div>
    <br/><br/>
</div>

