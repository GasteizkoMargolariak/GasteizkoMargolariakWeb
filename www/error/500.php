<?php
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $server = "$proto$http_host";

    //Language
    $lang = selectLanguage();
    include("../lang/lang_$lang.php");

    $cur_section = $lng['section_home'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title><?php echo $lng['error_title'] ?> - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?php echo "$proto$http_host/img/logo/favicon.ico";?>">
        <!-- CSS files -->
        <style>
<?php
            include("../css/ui.css");
            include("../css/error.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php
            include("../css/m/ui.css");
            include("../css/m/error.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
<?php
            include("../script/ui.js");
?>
        </script>
        <meta name="robots" content="noindex follow"/>
    </head>
    <body>
<?php
        include("../header.php")
?>
        <div id="error_code">
            <table id="error_code">
                <tr>
                    <td id="error_number">
                        500
                    </td>
                    <td id="error_tag">
                        <?=$lng["error_error"]?>
                        <br/>
                        <?=$lng["error_code"]?>
                    </td>
                <tr>
            </table>
        </div>
        <br/><br/><br/><br/>
        <div id="error_message" class="section">
            <h3 class='section_title'><?=$lng["error_500_title"]?></h3>
            <div class="entry">
                <?=$lng["error_500_description"]?>
                <br/><br/>
                <?=$lng["error_500_solution"]?>
                <ul>
                    <li>
                        <a href="javascript: history.go(-1);"><?=$lng["error_500_solution_0"]?></a>
                    </li>
                    <li>
                        <a href="<?php echo "$proto$http_host/";?>"><?=$lng["error_500_solution_1"]?></a>
                    </li>
                    <li>
                        <a target='_blank' href="https://www.youtube.com/results?search_query=cats"><?=$lng["error_500_solution_2"]?></a>
                    </li>
                </ul>
            </div>
        </div>
<?php
        include("../footer.php");
        stats(-1, $ad_static, "error", "500");
?>
    </body>
</html>
