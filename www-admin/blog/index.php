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
        <?php include('../toolbar.php'); ?>
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
                                echo "<tr>\n<td class='blog_column_title'><a href='https://margolariak.com/blog/$r[permalink]'>" . cutText($r['title_es'], 35, '', '') . "</a></td>";
                                echo "<td class='blog_column_text'>" . str_replace('<br/>', ' ', cutText($r['text_es'], 60, '', '')) . "\n";
                                
                                $q_image = mysqli_query($con, "SELECT image FROM post_image WHERE post = $r[id] ORDER BY idx;");
                                if (mysqli_num_rows($q_image) > 0){
                                    echo "<br/>";
                                    while ($r_image = mysqli_fetch_array($q_image))
                                        echo "<img src='http://$default_host/img/blog/miniature/$r_image[image]'\>\n";
                                }
                                
                                echo "</td>\n";
                                $q_user = mysqli_query($con, "SELECT username FROM user WHERE id = $r[user];");
                                $r_user = mysqli_fetch_array($q_user);
                                echo "<td class='blog_column_details'>$r[ptime]<br/>Por $r_user[username]<br/>Comentarios:";
                                if ($r['comments'] == 1){
                                    $q_comments = mysqli_query($con, "SELECT id FROM post_comment WHERE post = $r[id];");
                                    echo "Si (" . mysqli_num_rows($q_comments) . ")";
                                }
                                else
                                    echo "No";
                                echo "<br/>Visible: ";
                                if ($r['visible'] == 1)
                                    echo "Si";
                                else
                                    echo "No";
                                echo "</td>\n<td class='blog_column_translations'>\n"; //TODO percent
                                
                                
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
                                echo "Ingl&eacute;s: " . intval($translated_en * 100 / $total) . "%<br/>Euskera: " . intval($translated_eu * 100 / $total) . "%\n";
                                
                                
                                echo "\n</td>\n<td class='blog_column_action'>\n<form method='get' action='http://$http_host/blog/edit/index.php'>\n<input type='submit' value='Editar / Traducir'/>\n<input type='hidden' name='p' value='$r[id]'/></form>\n";
                                echo "<input type='button' onClick='delete_post($r[id], \"$r[title_es]\");' value='Borrar'/>";
                                echo "<form action='/blog/moderate/moderate.php?p=$r[id]'>\n<input type='submit' value='Moderar comentarios'/>\n</form>";
                                echo "</td>\n</tr>";
                            }
                            
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
<?php } ?>
