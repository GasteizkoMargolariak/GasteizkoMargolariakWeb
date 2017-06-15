<?php
    $http_host = $_SERVER['HTTP_HOST'];
    $default_host = substr($http_host, 0, strpos($http_host, ':'));
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
        <title>Galeria - Administracion</title>
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="/css/ui.css"/>
        <link rel="stylesheet" type="text/css" href="/css/galeria.css"/>
        <!-- CSS for mobile version -->
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/galeria.css"/>
        <!-- Script files -->
        <script type="text/javascript" src="/script/ui.js"></script>
    </head>
    <body>
        <?php include('../toolbar.php'); ?>
        <div id='content'>
            <div class="section">
                <h3 class="section_title">Galer&iacute;a - <a href="/galeria/add/">A&ntilde;adir &aacute;lbum</a></h3>
                <div class="entry">
                    <table id='album_list'>
                        <tr>
                            <th>Titulo / Texto</th>
                            <th>Detalles</th>
                            <th>Traducciones</th>
                            <th>Accion</th>
                        </tr>
                        <?php
                            $q = mysqli_query($con, "SELECT id, title_es, title_eu, title_en, description_es, description_eu, description_en, user, DATE_FORMAT(dtime, '%b %d, %Y %H:%i:%s') AS ptime, open FROM album ORDER BY dtime DESC;");
                            while ($r = mysqli_fetch_array($q)){
                                echo("<tr>\n<td class='album_column_title'>" . cutText($r['title_es'], 35, '', '') . "<br/>$r[dat]");
                                echo("<br/>" . str_replace('<br/>', ' ', cutText($r['text_es'], 60, '', '')) . "\n</td>\n");
                                $q_user = mysqli_query($con, "SELECT username FROM user WHERE id = $r[user];");
                                $r_user = mysqli_fetch_array($q_user);
                                echo("<td class='activity_column_details'>$r[ptime]<br/>\n");
                                //Count
                                $q_photos = mysqli_query($con, "SELECT count(id) AS cnt FROM photo, photo_album where album = $r[id] and photo = id;");
                                $r_photos = mysqli_fetch_array($q_photos);
                                echo("$r_photos[cnt] fotos.<br/>");
                                echo("Por $r_user[username]<br/>");
                                echo("<br/>P&uacute;blico: ");
                                                                if ($r['open'] == 1)
                                                                        echo("S&iacute;");
                                                                else
                                                                        echo("No");

                                echo("</td>\n<td class='gallery_column_translations'>\nEuskera: ");
                                if (strlen($r['description_eu']) == 0 || $r['description_eu'] == $r['description_es'])
                                    echo("No");
                                else
                                    echo("S&iacute;");
                                echo("<br/>Ingl&eacute;s: ");
                                    if (strlen($r['description_en']) == 0 || $r['description_en'] == $r['description_es'])
                                    echo("No");
                                else
                                    echo("S&iacute;");
                                echo("\n</td>\n<td class='album_column_action'>\n<form action='/album/edit/edit.php?p=$r[id]'>\n<input type='submit' value='Editar / Traducir'/>\n</form>\n");
                                echo("<input type='button' onClick='delete_album($r[id], \"$r[title_es]\");' value='Borrar'/>");
                                echo("<form action='/album/moderate/moderate.php?p=$r[id]'>\n<input type='submit' value='Moderar comentarios'/>\n</form>");
                                echo("</td>\n</tr>");
                            }
                            
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
<?php } ?>
