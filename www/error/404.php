<?php
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    
    //Language
    $lang = selectLanguage();
    include("../lang/lang_" . $lang . ".php");
    
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
        <style>
            div#error-code{
                display:        inline-block;
                margin-top:        2em;
                margin-left:     10%;
            }
            td#error-number{
                font-size:        300%;
                border-right:    0.063em solid #000000;
                padding-right:    1.875em;
                padding-left:    1.250em;
            }
            td#error-tag{
                font-size:        110%;
                padding-left:    1.750em;
                padding-right:    1.250em;
            }
            div#error-message{
                width:            80%;
                margin-left:    10%;
            }
            @media (max-width: 990px){
                div#error-code{
                    width:            90%;
                    margin-left:    auto;
                    margin-right:    auto;
                    margin-top:        0;
                    margin-bottom:    0;
                }
                div#error-message{
                    width:            90%;
                    margin-left:    auto;
                    margin-right:    auto;
                    margin-top:        0;
                    margin-bottom:    0;
                }
            }
        </style>
        <meta name="robots" content="noindex follow"/>
    </head>
    <body>
        <?php  include("../header.php");?>
        <div class="section" id="error-code">
            <table id="error-code" class="section">
                <tr>
                    <td id="error-number">
                        404
                    </td>
                    <td id="error-tag">
                        <?php echo "$lng[error_error]<br/>$lng[error_code]\n"; ?>
                    </td>
                <tr>
            </table>
        </div>
        <br/><br/><br/><br/>
        <div id="error-message" class="section">
            <h2><?php echo $lng['error_404_title']; ?></h2>
            <div class="entry">
                <?php echo $lng['error_404_description']; ?>
                <br/><br/>
                <?php echo $lng['error_404_solution']; ?>
                <ul>
                    <li>
                        <a href="javascript: history.go(-1);"><?php echo $lng['error_404_solution_0']; ?></a>
                    </li>
                    <li>
                        <a href="<?php echo "$proto$http_host/";?>"><?php echo $lng['error_404_solution_1']; ?></a>
                    </li>
                    <li>
                        <a target='_blank' href="https://www.youtube.com/results?search_query=cats"><?php echo $lng['error_404_solution_2']; ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
            include("../footer.php");
            stats(-1, $ad_static, "error", "404");
        ?>
    </body>
</html>
