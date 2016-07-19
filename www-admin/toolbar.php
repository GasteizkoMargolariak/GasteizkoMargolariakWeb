<div id="toolbar">
	<table>
		<tr>
			<td>
				<img src="/img/misc/logo.png"/><span style='font-weight:normal;font-style:italic;font-size:90%;'><?php echo $_SESSION['name']; ?></span>
			</td>
			<td>
				<a href="/" class="pointer toolbar_section">Inicio</a>
			</td>
			<td class="pointer toolbar_section" onClick="showToolbar('blog', this);">
				Blog
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
				<a href="http://<?php echo $http_host; ?>/blog/add/">Nuevo post</a>
			</td>
			<td>
				<a href="http://<?php echo $http_host; ?>/blog/">Gestionar posts</a>
			</td>
			<td>
				<a href="http://<?php echo $http_host; ?>/blog/moderate/">Moderar comentarios</a>
			</td>
			<td>
				<a href="http://<?php echo $http_host; ?>/blog/translate/">Traducir posts</a>
			</td>
		</tr>
	</table>
</div>
<div class="secondary_toolbar" id="toolbar_actividades">
	<table>
		<tr>
			<td>
				<a href="http://<?php echo $http_host; ?>/actividades/add/">Nueva actividad</a>
			</td>
			<td>
				<a href="http://<?php echo $http_host; ?>/actividades/">Gestionar actividades</a>
			</td>
			<td>
				<a href="http://<?php echo $http_host; ?>/actividades/moderate/">Moderar comentarios</a>
			</td>
			<td>
				<a href="http://<?php echo $http_host; ?>/actividades/translate/">Traducir actividades</a>
			</td>
		</tr>
	</table>
</div>
<div class="secondary_toolbar" id="toolbar_galeria">
	<table>
		<tr>
			<td>
				<a href="//">Crear album</a>
			</td>
			<td>
				<a href="//">Gestionar albums</a>
			</td>
			<td>
				<a href="//">Subir fotos</a>
			</td>
			<td>
				<a href="//">Moderar comentarios</a>
			</td>
			<td>
				<a href="//">Traducir galer&iacute;a</a>
			</td>
		</tr>
	</table>
</div>
<div class="secondary_toolbar" id="toolbar_fiestas">
	<table>
		<tr>
			<td>
				<a href="//">Gestionar precios</a>
			</td>
			<td>
				<a href="//">Gestionar programa</a>
			</td>
		</tr>
	</table>
</div>
<div class="secondary_toolbar" id="toolbar_miembros">
	<table>
		<tr>
			<td>
				<a href="//">Consultar</a>
			</td>
			<td>
				<a href="//">A&ntilde;adir miembro</a>
			</td>
			<td>
				<a href="//">A&ntilde;adir miembros en lote</a>
			</td>
		</tr>
	</table>
</div>
<div class="secondary_toolbar" id="toolbar_estadisticas">
	<table>
		<tr>
			<td>
				<a href="//">Web</a>
			</td>
			<td>
				<a href="//">Apps</a>
			</td>
			<td>
				<a href="//">Miembros</a>
			</td>
		</tr>
	</table>
</div>
<div class="secondary_toolbar" id="toolbar_ajustes">
	<table>
		<tr>
			<td>
				<a href="//">Cambiar ajustes</a>
			</td>
			<td>
				<a href="//">Gestionar usuarios</a>
			</td>
			<td>
				<a href="//">Salir</a>
			</td>
		</tr>
	</table>
</div>
