<?php
    $http_host = $_SERVER['HTTP_HOST'];
    $proto = getProtocol();
    $default_host = substr($http_host, 0, strpos($http_host, ':'));
    $server = "$proto$http_host";
    $default_server = "$proto$default_host";
    include("../functions.php");
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
?>
<html>
    <head>
        <meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title>Blog - Administracion</title>
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="/css/ui.css"/>
        <link rel="stylesheet" type="text/css" href="/css/blog.css"/>
        <!-- CSS for mobile version -->
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/blog.css"/>
        <!-- Script files -->
        <script type="text/javascript" src="script.js"></script>
        <script type="text/javascript" src="/script/ui.js"></script>
    </head>
    <body>
<?php
        include('../toolbar.php');
?>
        <div id='content'>
            <div class="section">
                <h3 class="section_title">Entradas del blog - <a href="/blog/add/">A&ntilde;adir nueva</a></h3>
                <div class="entry">
                    <table id='blog_post_list'>
                        <tr>
                            <th>Titulo</th>
                            <th>Texto</th>
                            <th>Detalles</th>
                            <th>Traducciones</th>
                            <th>Accion</th>
                        </tr>
<?php
                        $query = mysqli_query($con, "SELECT id, title_es, title_eu, title_en, text_es, text_eu, text_en, user, visible, comments, DATE_FORMAT(dtime, '%b %d, %Y %H:%i:%s') AS ptime, permalink FROM post ORDER BY id DESC;");
                        while ($r = mysqli_fetch_array($query)){
?>
                            <tr>
                                <td class='blog_column_title'>
                                    <a target='blank' href='<?=$default_server?>/blog/<?=$r["permalink"]?>'><?=cutText($r["title_es"], 35, "", "")?></a>
                                </td>
                                <td class='blog_column_text'>
                                    <?=str_replace("<br/>", " ", cutText($r["text_es"], 60, "", ""))?>
<?php
                                    $q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r[id] ORDER BY idx;");
                                    if (mysqli_num_rows($q_image) > 0){
?>
                                        <br/>
<?php
                                        while ($r_image = mysqli_fetch_array($q_image)){
?>
                                            <img src='<?=$default_server?>/img/blog/miniature/<?=$r_image["image"]?>'/>
<?php
                                        }
                                    }
?>
                                </td>
<?php
                                $q_user = mysqli_query($con, "SELECT username FROM user WHERE id = $r[user];");
                                $r_user = mysqli_fetch_array($q_user);
?>
                                <td class='blog_column_details'>
                                    <?=$r["ptime"]?><br/>
                                    Por <?=$r_user["username"]?><br/>
                                    Comentarios:
<?php
                                    if ($r['comments'] == 1){
                                        $q_comments = mysqli_query($con, "SELECT id FROM post_comment WHERE post = $r[id];");
?>
                                        S&iacute; (<?=mysqli_num_rows($q_comments)?>)
<?php
                                    }
                                    else{
?>
                                        No
<?php
                                    }
                                    echo "<br/>Visible: ";
                                    if ($r['visible'] == 1){
?>
                                        S&iacute;
<?php
                                    }
                                    else{
?>
                                        No
<?php
                                    }
                                    {
                                        $q_comments = mysqli_query($con, "SELECT id FROM post_comment WHERE post = $r[id];");
?>
                                        S&iacute; (<?=mysqli_num_rows($q_comments)?>)
<?php
                                    }
                                    else{
?>
                                        No
<?php
                                    }
?>
                                </td>
                                <td class='blog_column_translations'>
<?php
                                    $total = 2; //Title and text are mandatory
                                    $translated_en = 0;
                                    $translated_eu = 0;

                                    //Calculate field number
                                    if ($r['title_en'] != $r['title_es']){
                                        $translated_en ++;
                                    }
                                    if ($r['title_eu'] != $r['title_es']){
                                        $translated_eu ++;
                                    }
                                    if ($r['text_en'] != $r['text_es']){
                                        $translated_en ++;
                                    }
                                    if ($r['text_eu'] != $r['text_es']){
                                        $translated_eu ++;
                                    }

                                    //Calculate percents
?>
                                    Ingl&eacute;s: <?=intval($translated_en * 100 / $total)?>%
                                    <br/>
                                    Euskera: <?=intval($translated_eu * 100 / $total)?>%
                                </td>
                                <td class='blog_column_action'>
                                    <form method='get' action='<?=server?>/blog/edit/index.php'>
                                        <input type='submit' value='Editar / Traducir'/>
                                        <input type='hidden' name='p' value='<?=$r["id"]?>'/>
                                    </form>
                                    <input type='button' onClick='delete_post(<?=$r["id"]?>, "<?=$r["title_es"]?>");' value='Borrar'/>
                                    <form action='<?=$server?>/blog/moderate/moderate.php?p=<?=$r["id"]?>'>
                                        <input type='submit' value='Moderar comentarios'/>
                                    </form>
                                </td>
                            </tr>";
                        }
?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>
