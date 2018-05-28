<?php
    include("../functions.php");
    $server = get_protocol() . $_SERVER['HTTP_HOST'];
    $default_host = substr($http_host, 0, strpos($http_host, ':'));
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
        <title>Ajustes - Gasteizko Margolariak - Administracion</title>
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="/css/ui.css"/>
        <link rel="stylesheet" type="text/css" href="/css/settings.css"/>
        <!-- CSS for mobile version -->
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/settings.css"/>
        <!-- Script files -->
        <script type="text/javascript" src="/script/ui.js"></script>
        <script type="text/javascript">
            function saveSetting(name, value){
                // TODO: Call guardar.php
            }
        </script>
    </head>
    <body>
<?php
        include('../toolbar.php');
?>
        <div id='content'>
            <div class='section'>
                <h3 class='section_title'>Ajustes</h3>
                <div class='entry'>
                    Permitir que los visitantes comenten anonimamente...
                    <br/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="checkbox" name="comment_blog" value="value">... en el blog</label>
                    <br/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="checkbox" name="comment_activities" value="value">... en las actividades</label>
                    <br/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="checkbox" name="comment_gallery" value="value">... en la galer&iacute;a</label>
                    <br/><br/>
                    <label>
                        <input type="checkbox" name="upload_gallery" value="value">
                        Permitir que los usuarios contribuyan a los albums con sus fotos (puede restringirse por album)
                    </label>
                    <label>
                        <input type="checkbox" name="festivals" value="value">
                        Usar disseño para fiestas de Vitoria.
                    </label>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>
