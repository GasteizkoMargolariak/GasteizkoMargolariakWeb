<?php
    include("../../functions.php");
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
        <title>Taducir posts - Administraci&oacute;n</title>
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="/css/ui.css"/>
        <link rel="stylesheet" type="text/css" href="/css/blog.css"/>
        <!-- CSS for mobile version -->
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/blog.css"/>
        <!-- Script files -->
        <script type="text/javascript" src="/script/ui.js"></script>
    </head>
    <body>
<?php
        include('../../toolbar.php');
?>
        <div id='content'>
            <div class='section'>
                <h3>Lista de posts en el blog</h3>
                <div class='entry'>
<?php
                    $q = mysqli_query($con, "SELECT * FROM post;");
?>
                    <table class='translation_table'>
                        <tr>
                            <th>Nombre</th>
                            <th>Ingles</th>
                            <th>Euskera</th>
                        </tr>
<?php
                        while ($r = mysqli_fetch_array($q)){
?>
                            <tr>
                                <td>
                                    <a href='/blog/translate/translate.php?id=<?=$r["id"]?>'><?=$r["title_es"]?></a>
                                </td>
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
                                <td>
                                    <?=intval($translated_en * 100 / $total)?>%
                                </td>
                                <td>
                                    <?=intval($translated_eu * 100 / $total)?>%
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
