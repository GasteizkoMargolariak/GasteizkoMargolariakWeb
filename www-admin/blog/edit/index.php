<?php
    include("../../functions.php");
    $server = get_protocol() . $_SERVER['HTTP_HOST'];
    $default_server = get_protocol() . substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'));
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
        $id = mysqli_real_escape_string($con, $_GET["p"]);
        $q = mysqli_query($con, "SELECT * FROM post WHERE id = $id;");
        if (mysqli_num_rows($q) == 0){
            header("Location: /blog/");
            exit (-1);
        }
        else{
            $r = mysqli_fetch_array($q);
            $title_es = $r["title_es"];
            $title_eu = $r["title_eu"];
            if ($title_eu == $title_es)
                $title_eu = "";
            $title_en = $r["title_en"];
            if ($title_en == $title_es)
                $title_en = "";
            $text_es = $r["text_es"];
            $text_eu = $r["text_eu"];
            if ($text_eu == $text_es)
                $text_eu = "";
            $text_en = $r["text_en"];
            if ($text_en == $text_es)
                $text_en = "";
            $visible = $r["visible"];
            $comments = $r["comments"];
?>

<html>
    <head>
        <meta content='text/html; charset=windows-1252' http-equiv='content-type'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title>Editar post - Administracion</title>
        <!-- CSS files -->
        <link rel='stylesheet' type='text/css' href='/css/ui.css'/>
        <link rel='stylesheet' type='text/css' href='/css/blog.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='script.js'></script>
        <!-- CSS for mobile version -->
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/ui.css'/>
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/blog.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='script.js'></script>
        <script type='text/javascript' src='/script/ui.js'></script>
        <script src='../../ckeditor/ckeditor.js'></script>
    </head>
    <body>
        <?php include('../../toolbar.php'); ?>
        <div id='content'>
            <div class='section'>
                <h3 class='section_title'>Editar post</h3>
                <form action='edit.php' maxlength='120' method='post' enctype='multipart/form-data' onsubmit='return validate_post();'>
                    <input type='hidden' name='id' value='<?=$id?>'/>
                    <div class='entry'>
                        <div id='lang_tabs'>
                            <table>
                                <tr>
                                    <td class='pointer lang_tabs_active' id='lang_tab_es' onclick='showLanguage("es");'>
                                        Castellano
                                    </td>
                                    <td class='pointer' id='lang_tab_eu' onclick='showLanguage("eu");'>
                                        Euskera
                                    </td>
                                    <td class='pointer' id='lang_tab_en' onclick='showLanguage("en");'>
                                        Ingl&eacute;s
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id='content_lang_es' class='blog_add_language'>
                            <input type='text' id='title_es' name='title_es' placeholder='Titulo' value='<?=$title_es?>'/><br/><br/>
                            <textarea name='text_es' id='text_es' placeholder='Texto'><?=$text_es?></textarea>
                            <script>
                                CKEDITOR.replace('text_es');
                            </script>
                        </div>
                        <div id='content_lang_eu' class='blog_add_language' style='display:none;'>
                            <input type='text' name='title_eu' id='title_eu' placeholder='Titulu' value='<?=$title_eu?>'/><br/><br/>
                            <textarea name='text_eu' id='text_eu' placeholder='Textua'><?=$text_eu?></textarea>
                            <script>
                                CKEDITOR.replace('text_eu');
                            </script>
                        </div>
                        <div id='content_lang_en' class='blog_add_language' style='display:none;'>
                            <input type='text' name='title_en' id='title_en' placeholder='Title' value='<?=$title_en?>'/><br><br/>
                            <textarea name='text_en' id='text_en' placeholder='Content'><?=$text_en?></textarea>
                            <script>
                                CKEDITOR.replace('text_es');
                            </script>
                        </div>
                    </div> <!--Entry-->
                    <div class='entry' id='images'>
                        <h4>Im&aacute;genes</h4>
                        <ul>
                            <li>
                                Principal (Aparecera sobre el texto y en las previsualizaciones)<br/>
                                <div class='image_upload_container'>
<?php
                                    $q_img = mysqli_query($con, "SELECT image FROM post_image WHERE post = $id AND idx = 0");
                                    if (mysqli_num_rows($q_img) == 0){
?>
                                        <img class='image_upload_preview' id='image_preview_0' src='/img/misc/alpha.png'/>
                                        <input type='button' style='display:none;' onClick='delete_image(0, image_preview_0, this);' value='Eliminar'/>
<?php
                                    }
                                    else{
                                        $r_img = mysqli_fetch_array($q_img);
?>
                                        <img class='image_upload_preview' style='display:block;' id='image_preview_0' src='<?=$default_server?>/img/blog/miniature/<?=$r_img["image"]?>'/>
                                        <input type='button' style='display:block;' onClick='delete_image(0, image_preview_0, this);' value='Eliminar'/>
<?php
                                    }
?>
                                    <br/><br/>
                                    <input type="hidden" name="delete_image_0" id="delete_image_0" value="no"/>
                                    <input id="image_0" onchange="preview_image(0, this, image_preview_0);" type="file" name="image_0" accept="image/x-png, image/gif, image/jpeg"/>
                                </div>
                            </li>
                            <li>
                                Secundarias (Bajo el texto)<br/>
                                <div class="image_upload_container">
<?php
                                    $q_img = mysqli_query($con, "SELECT image FROM post_image WHERE post = $id AND idx = 1");
                                    if (mysqli_num_rows($q_img) == 0){
?>
                                        <img class='image_upload_preview' id='image_preview_1' src='/img/misc/alpha.png'/>
                                        <input type='button' style='display:none;' onClick='delete_image(1, image_preview_1, this);' value='Eliminar'/>
<?php
                                    }
                                    else{
                                        $r_img = mysqli_fetch_array($q_img);
?>
                                        <img class='image_upload_preview' style='display:block;' id='image_preview_1' src='<?=$default_server?>/img/blog/miniature/<?=$r_img["image"]?>'/>\n";
                                        <input type='button' style='display:block;' onClick='delete_image(1, image_preview_1, this);' value='Eliminar'/>
<?php
                                    }
?>
                                    <br/><br/>
                                    <input type="hidden" name="delete_image_1" id="delete_image_1" value="no"/>
                                    <input id="image_1" onchange="preview_image(1, this, image_preview_1);" type="file" name="image_1" accept="image/x-png, image/gif, image/jpeg"/>
                                </div>
                                <div class="image_upload_container">
<?php
                                    $q_img = mysqli_query($con, "SELECT image FROM post_image WHERE post = $id AND idx = 2");
                                    if (mysqli_num_rows($q_img) == 0){
?>
                                        <img class='image_upload_preview' id='image_preview_2' src='/img/misc/alpha.png'/>
                                        <input type='button' style='display:none;' onClick='delete_image(2, image_preview_2, this);' value='Eliminar'/>
<?php
                                    }
                                    else{
                                        $r_img = mysqli_fetch_array($q_img);
?>
                                        <img class='image_upload_preview' style='display:block;' id='image_preview_2' src='<?=$default_server?>/img/blog/miniature/<?=$r_img["image"]?>'/>\n";
                                        <input type='button' style='display:block;' onClick='delete_image(2, image_preview_2, this);' value='Eliminar'/>
<?php
                                    }
?>
                                    <br/><br/>
                                    <input type="hidden" name="delete_image_2" id="delete_image_2" value="no"/>
                                    <input id="image_2" onchange="preview_image(2, this, image_preview_2);" type="file" name="image_2" accept="image/x-png, image/gif, image/jpeg"/>
                                </div>
                                <div class="image_upload_container">
<?php
                                    $q_img = mysqli_query($con, "SELECT image FROM post_image WHERE post = $id AND idx = 3");
                                    if (mysqli_num_rows($q_img) == 0){
?>
                                        <img class='image_upload_preview' id='image_preview_3' src='/img/misc/alpha.png'/>
                                        <input type='button' style='display:none;' onClick='delete_image(3, image_preview_3, this);' value='Eliminar'/>
<?php
                                    }
                                    else{
                                        $r_img = mysqli_fetch_array($q_img);
?>
                                        <img class='image_upload_preview' style='display:block;' id='image_preview_3' src='<?=$default_server?>/img/blog/miniature/<?=$r_img["image"]?>'/>\n";
                                        <input type='button' style='display:block;' onClick='delete_image(3, image_preview_3, this);' value='Eliminar'/>
<?php
                                    }
?>
                                    <br/><br/>
                                    <input type="hidden" name="delete_image_3" id="delete_image_3" value="no"/>
                                    <input id="image_3" onchange="preview_image(3, this, image_preview_3);" type="file" name="image_3" accept="image/x-png, image/gif, image/jpeg"/>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="entry" id="settings">
                        <h4>Ajustes</h4>
<?php
                        $v_checked = "";
                        if ($r['visible'] == 1){
                            $v_checked = 'checked';
                        }
                        $c_checked = "";
                        if ($r['comments'] == 1){
                            $c_checked = 'checked';
                        }
?>
                        <label>
                            <input type="checkbox" name="visible" <?=$v_checked?>/>
                            Post visible
                        </label>
                        <br/><br/>
                        <label>
                            <input type="checkbox" name="comments" <?=$c_checked?>/>
                            Permitir comentarios
                        </label>
                        <br/><br/><br/><br/><br/><br/>
                        <div id="add_button_container">
                            <input type="button" value="Previsualizar" onClick="alert(validate_post());"/>
                            <br/>
                            <input type="submit" value="Publicar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

<?php
        }
    }
?>
