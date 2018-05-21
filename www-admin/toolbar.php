<div id="toolbar">
    <table>
        <tr>
            <td>
                <img src="<?=$server?>/img/misc/logo.png"/>
                <span style='font-weight:normal;font-style:italic;font-size:90%;'><?=$_SESSION['name']?></span>
            </td>
            <td>
                <a href="<?=$server?>/" class="pointer toolbar_section">Inicio</a>
            </td>
            <td class="pointer toolbar_section" onClick="showToolbar('blog', this);">
                <span class="pointer">Blog</span>
            </td>
            <td class="pointer toolbar_section" onClick="showToolbar('actividades', this);">
                <span class="pointer">Actividades</span>
            </td>
            <td class="pointer toolbar_section" onClick="showToolbar('galeria', this);">
                <span class="pointer">Galer&iacute;a</span>
            </td>
            <td class="pointer toolbar_section" onClick="showToolbar('fiestas', this);">
                <span class="pointer">Fiestas</span>
            </td>
            <td class="pointer toolbar_section" onClick="showToolbar('miembros', this);">
                <span class="pointer">Miembros</span>
            </td>
            <td class="pointer toolbar_section" onClick="showToolbar('estadisticas', this);">
                <span class="pointer">Estad&iacute;sticas</span>
            </td>
            <td class="pointer toolbar_section" onClick="showToolbar('ajustes', this);">
                <span class="pointer">Ajustes</span>
            </td>
        </tr>
    </table>
</div>
<div class="secondary_toolbar" id="toolbar_blog">
    <table>
        <tr>
            <td>
                <a href="<?=$server?>/blog/add/">Nuevo post</a>
            </td>
            <td>
                <a href="<?=$server?>/blog/">Gestionar posts</a>
            </td>
        </tr>
    </table>
</div>
<div class="secondary_toolbar" id="toolbar_actividades">
    <table>
        <tr>
            <td>
                <a href="<?=$server?>/actividades/add/">Nueva actividad</a>
            </td>
            <td>
                <a href="<?=$server?>/actividades/">Gestionar actividades</a>
            </td>
        </tr>
    </table>
</div>
<div class="secondary_toolbar" id="toolbar_galeria">
    <table>
        <tr>
            <td>
                <a href="<?=$server?>/galeria/add/">Crear album</a>
            </td>
            <td>
                <a href="<?=$server?>/galeria/">Gestionar albums</a>
            </td>
            <td>
                <a href="<?=$server?>/galeria/upload/">Subir fotos</a>
            </td>
            <td>
                <a href="<?=$server?>/galeria/moderate/">Moderar comentarios</a>
            </td>
            <td>
                <a href="<?=$server?>/galeria/translate/">Traducir galer&iacute;a</a>
            </td>
        </tr>
    </table>
</div>
<div class="secondary_toolbar" id="toolbar_fiestas">
    <table>
        <tr>
            <td>
                <a href='<?=$server?>/lablanca/'>Preparar las fiestas</a>
            </td>
            <td>
                <a href='<?=$server?>/lablanca/prices.php'>Gestionar precios</a>
            </td>
            <td>
                <a href='<?=$server?>/lablanca/schedule.php'>Gestionar programa</a>
            </td>
        </tr>
    </table>
</div>
<div class="secondary_toolbar" id="toolbar_miembros">
    <table>
        <tr>
            <td>
                <a href='<?=$server?>/miembros/'>Consultar y buscar</a>
            </td>
            <td>
                <a href='<?=$server?>/miembros/add/'>A&ntilde;adir miembros</a>
            </td>
        </tr>
    </table>
</div>
<div class="secondary_toolbar" id="toolbar_estadisticas">
    <table>
        <tr>
            <td>
                <a href='<?=$server?>/estadisticas/web/'>Estadisticas Web</a>
            </td>
            <td>
                <a href='<?=$server?>/estadisticas/app/'>Estadisticas Apps</a>
            </td>
            <td>
                <a href='<?=$server?>/estadisticas/miembros/'>Estadisticas miembros</a>
            </td>
        </tr>
    </table>
</div>
<div class="secondary_toolbar" id="toolbar_ajustes">
    <table>
        <tr>
            <td>
                <a href='<?=$server?>/settings/'>Ajustes</a>
            </td>
            <td>
                <a href='<?=$server?>/usuarios/'>Gestionar usuarios</a>
            </td>
            <td>
                <a href='<?=$server?>/salir/'>Salir</a>
            </td>
        </tr>
    </table>
</div>
