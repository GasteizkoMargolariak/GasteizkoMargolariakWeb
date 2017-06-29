<?php
    $http_host = $_SERVER['HTTP_HOST'];
    include("../../functions.php");
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{ ?>
<html>
    <head>
        <meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title>Moderar comentarios - Administracion</title>
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
        <?php include('../../toolbar.php'); ?>
                <div id='content'>
                        <div class="section">
                                <h3 class="section_title">Moderar comentarios</h3>
                                <h4 span class="TODO">En construcci&oacute;n</h4>
                        </div>
                </div>
    </body>
</html>

<?php } ?>
