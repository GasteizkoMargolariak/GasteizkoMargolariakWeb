 <div id='header' class='desktop'>
    <div id='header_content'>
        <img src='<?=$server?>/API/V<?=$v?>/help/img/logo-api.png'/>
        <div id='header_menu'>
            <table>
                <tr>
                    <td><a href='<?=$server?>/API/help/V$v/'>API documentation</a></td>
                    <td><a href='<?=$server?>/API/help/V$v/sync/'>Sync</a></td>
                    <td><a href='<?=$server?>/API/help/V$v/comment/'>Comment</a></td>
                    <td><a href='<?=$server?>/'>Main page</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id='header_m' class='mobile'>
    <img src='/img/logo/logo-api.png' onClick='toggleMobileMenu();' id='mobile_logo'/>
    <div id='header_menu_m'>
        <div id='header_m_title' onClick='openMobileMenu();' class='pointer'><span><img src='<?=$server?>/img/misc/slid-menu.png'/>&nbsp;&nbsp;&nbsp;&nbsp;<?=$cur_section?></span></div>
        <div class='header_m_link'><a href='<?=$server?>/API/help/V$v/'>API documentation</a></div>
        <div class='header_m_link'><a href='<?=$server?>/API/help/V$v/sync/'>Sync</a></div>
        <div class='header_m_link'><a href='<?=$server?>/API/help/V$v/comment/'>Comment</a></div>
        <div class='header_m_link'><a href='<?=$server?>/'>Main page</a></div>
        <div id='header_m_slider' onClick='closeMobileMenu();' class='pointer'><span><img src='<?=$server?>/img/misc/slid-top.png'/></span></div>
    </div><br/><br/>
</div>

