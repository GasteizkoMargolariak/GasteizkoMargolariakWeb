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
        <title>Actividades - Administracion</title>
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="/css/ui.css"/>
        <link rel="stylesheet" type="text/css" href="/css/actividades.css"/>
        <!-- CSS for mobile version -->
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/activiadades.css"/>
        <!-- Script files -->
        <script type="text/javascript" src="/script/ui.js"></script>
    </head>
    <body>
<?php
        include('../toolbar.php');
?>
        <div id='content'>
            <div class="section">
                <h3 class="section_title">Actividades - <a href="/actividades/add/">Anadir nueva</a></h3>
                <div class="entry">
                    <table id='activity_list'>
                        <tr>
                            <th>Titulo / Fecha</th>
                            <th>Texto</th>
                            <th>Detalles</th>
                            <th>Traducciones</th>
                            <th>Accion</th>
                        </tr>
<?php
                        $q = mysqli_query($con, "SELECT id, DATE_FORMAT(date, '%b %d, %Y') AS dat, title_es, title_eu, title_en, text_es, text_eu, text_en, user, visible, comments, DATE_FORMAT(dtime, '%b %d, %Y %H:%i:%s') AS ptime FROM activity ORDER BY date DESC;");
                        while ($r = mysqli_fetch_array($q)){
?>
                            <tr>
                                <td class='activity_column_title'>
                                    <?=cutText($r['title_es'], 35, '', '')?>
                                    <br/>$r[dat]
                                </td>";
                                <td class='activity_column_text'><?=str_replace('<br/>', ' ', cutText($r['text_es'], 60, '', ''))?>
<?php
                                    //Images
                                    $q_image = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $r[id] ORDER BY idx;");
                                    if (mysqli_num_rows($q_image) > 0){
?>
                                        <br/>
<?php
                                        while ($r_image = mysqli_fetch_array($q_image)){
?>
                                            <img src='http://$default_host/img/actividades/miniature/<?=$r_image["image"]?>'\>
<?php
                                        }
                                    }

                                    //Itinerary
                                    $q_itinerary = mysqli_query($con, "SELECT count(id) AS count FROM activity_itinerary WHERE activity = $r[id]");
                                    $r_itinerary = mysqli_fetch_array($q_itinerary);
                                    if ($r_itinerary['count'] == 0){
?>
                                        <ul>
                                            <li>Sin itinerario.</li>
                                        </ul>
<?php
                                    }
                                    elseif ($r_itinerary['count'] == 1){
?>
                                        <ul>
                                            <li>1 entrada de itinerario.</li>
                                        </ul>
<?php
                                    }
                                    else{
?>
                                        <ul>
                                            <li><?=$r_itinerary["count"]?> entradas de itinerario.</li>
                                        </ul>
<?php
                                    }
?>
                                </td>
<?php
                                $q_user = mysqli_query($con, "SELECT username FROM user WHERE id = $r[user];");
                                $r_user = mysqli_fetch_array($q_user);
?>
                                <td class='activity_column_details'>
                                    <?=$r["ptime"]?>
                                    <br/>
                                    Por <?=$r_user["username"]?>
                                    <br/>
                                    Comentarios:
<?php
                                    if ($r['comments'] == 1){
                                        $q_comments = mysqli_query($con, "SELECT id FROM activity_comment WHERE activity = $r[id];");
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
                                    <br/>
                                    Visible:
<?php
                                    if ($r['visible'] == 1){
?>
                                        S&iacute;
<?php                                }
                                    else{
?>
                                        No
<?php
                                    }
?>s
                                </td>
                                <td class='activity_column_translations'>
                                    Euskera:
<?php
                                    if (strlen($r['text_eu']) == 0 || $r['text_eu'] == $r['text_es']){
?>
                                        No
<?php
                                    }
                                    else{
?>
                                        S&iacute;
<?php                                
                                    }
?>
                                    <br/>
                                    Ingl&eacute;s:
<?php
                                    if (strlen($r['text_en']) == 0 || $r['text_en'] == $r['text_es']){
?>
                                        No
<?php
                                    }
                                    else{
?>
                                        S&iacute;
<?php                                
                                    }
?>
                                </td>
                                <td class='activity_column_action'>
                                    <form action='/activity/edit/edit.php?p=<?=$r[id]?>'>
                                        <input type='submit' value='Editar / Traducir'/>
                                    </form>
                                    <input type='button' onClick='delete_activity(<?=$r["id"]?>, "<?=$r["title_es"]?>");' value='Borrar'/>
                                    <form action='/activity/moderate/moderate.php?p<?=$r["id"]?>'>
                                        <input type='submit' value='Moderar comentarios'/>
                                    </form>
                                </td>
                            </tr>
<?php
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
