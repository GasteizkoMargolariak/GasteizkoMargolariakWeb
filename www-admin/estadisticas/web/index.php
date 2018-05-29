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
        <meta content='text/html; charset=windows-1252' http-equiv='content-type'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title>Estadisticas Web - Administracion</title>
        <!-- CSS files -->
        <link rel='stylesheet' type='text/css' href='/css/ui.css'/>
        <link rel='stylesheet' type='text/css' href='/css/estadisticas.css'/>
        <!-- CSS for mobile version -->
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/ui.css'/>
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/estadisticas.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='/script/ui.js'></script>
    </head>
    <body>
<?php
        include("../../toolbar.php");
?>
        <div id='content'>
            <div id='section_table'>
                <div class='section_row'>
                    <div class='section_cell'>
                        <div class='section'>
                            <h3 class='section_title'>Visitantes &Uacute;nicos</h3>
<?php
                            $q_total = mysqli_query($con, "SELECT COUNT(id) AS c FROM stat_visit;");
                            $q_week = mysqli_query($con, "SELECT COUNT(id) AS c FROM stat_visit WHERE id IN (SELECT visit FROM stat_view WHERE dtime > adddate(curdate(), INTERVAL 1-DAYOFWEEK(curdate()) DAY));");
                            $q_month = mysqli_query($con, "SELECT COUNT(id) AS c FROM stat_visit WHERE id IN (SELECT visit FROM stat_view WHERE dtime > LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY);");
                            $q_year =  mysqli_query($con, "SELECT COUNT(id) AS c FROM stat_visit WHERE id IN (SELECT visit FROM stat_view WHERE dtime > DATE_FORMAT(NOW() ,'%Y-01-01'));");
                            $r_total = mysqli_fetch_array($q_total);
                            $r_week = mysqli_fetch_array($q_week);
                            $r_month = mysqli_fetch_array($q_month);
                            $r_year = mysqli_fetch_array($q_year);
?>
                            <div class='entry'>
                                <ul>
                                    <li>Totales: <?php echo($r_total["c"]); ?> </li>
                                    <li>Semana: <?php echo($r_week["c"]); ?> </li>
                                    <li>Mes: <?php echo($r_month["c"]); ?> </li>
                                    <li>A&ntilde;o: <?php echo($r_year["c"]); ?> </li>
                                </ul>
                            </div> <!-- .entry -->
                        </div> <!-- .section -->
                    </div> <!-- .section_cell -->
                    <div class='section_cell'>
                        <div class='section'>
                            <h3 class='section_title'>Gr&aacute;fico</h3>
                            <div class='entry'>
<?php
                                $q_section = mysqli_query($con, "SELECT COUNT(visit) v, section FROM stat_view WHERE section != 'error' AND entry = '' GROUP BY section ORDER BY COUNT(visit)")
                                $q_blog = mysqli_query($con, "SELECT COUNT(visit) v, section, entry FROM stat_view WHERE section = 'blog' AND entry IS NOT null GROUP section, entry ORDER BY count(visit) DESC LIMIT 4")
?>
                            </div> <!-- .entry -->
                        </div> <!-- .section -->
                    </div> <!-- .section_cell -->
                </div> <!-- .section_row -->
                <div class='section_row'>
                    <div class='section_cell'>
                        <div class='section'>
                            <h3 class='section_title'>Secciones</h3>
                            <div class='entry'>
                                TODO
                            </div> <!-- .entry -->
                        </div> <!-- .section -->
                    </div> <!-- ..section_cell -->
                    <div class='section_cell'>
                        <div class='section'>
                            <h3 class='section_title'>Or&iacute;gen</h3>
                            <div class='entry'>
                                <canvas id="piechart" width="400" height="400"></canvas>
                                <ul id='piechart_legent'>
                                    <li><span id='lori0c'>##</span> <span id='lori0t'></span></li>
                                    <li><span id='lori1c'>##</span> <span id='lori1t'></span></li>
                                    <li><span id='lori2c'>##</span> <span id='lori2t'></span></li>
                                    <li><span id='lori3c'>##</span> <span id='lori3t'></span></li>
                                    <li><span id='lori4c'>##</span> <span id='lori4t'></span></li>
                                    <li><span id='lori5c'>##</span> <span id='lori5t'></span></li>
                                    <li><span id='lori6c'>##</span> <span id='lori6t'></span></li>
                                    <li><span id='lori7c'>##</span> <span id='lori7t'></span></li>
                                </ul>
                                <script type="text/javascript">
                                    var data = [0, 0, 0, 0, 0, 0, 0];
                                    var label = ["", "", "", "", "", "", "", ""];
                                    var colors = ["#CC3333", "#33CC33", "#3333CC", "#CCCC33", "#CC33CC", "#33CCCC", "#CCCCCC", "#333333"];
<?php
                                    $q = mysqli_query($con, "SELECT os, browser, COUNT(id) AS c, ROUND(COUNT(id) * 100 / $r_total[c]) AS percent, ROUND(COUNT(id) * 360 / $r_total[c]) AS deg FROM stat_visit GROUP BY os,browser ORDER BY c DESC LIMIT 7;");
                                    $i = 0;
                                    $sum = $r_total['c'];
                                    $sumPercent = 100;
                                    $sumDeg = 360;
                                    while ($r = mysqli_fetch_array($q)){
?>
                                        data[<?=$i?>] = <?=$r['deg']?>;
                                        label[<?=$i?>] = "<?=$r['browser']?> en <?=$r['os']?>\n<?=$r['c']?>(<?=$r['percent']?>%)";
<?php
                                        $i ++;
                                        $sum = $sum - $r['c'];
                                        $sumPercent = $sumPercent - $r['percent'];
                                        $sumDeg = $sumDeg - $r['deg'];
                                    }
?>
                                    data[<?=$i?>] = <?=$sumDeg?>;
                                    label[<?=$i?>] = "Otros<?=$sum?> (<?=$sumPercent?>%)";

                                    function drawSegment(canvas, context, i) {
                                        context.save();
                                        var centerX = Math.floor(canvas.width / 2);
                                        var centerY = Math.floor(canvas.height / 2);
                                        radius = Math.floor(canvas.width / 2);

                                        var startingAngle = degreesToRadians(sumTo(data, i));
                                        var arcSize = degreesToRadians(data[i]);
                                        var endingAngle = startingAngle + arcSize;

                                        context.beginPath();
                                        context.moveTo(centerX, centerY);
                                        context.arc(centerX, centerY, radius, 
                                        startingAngle, endingAngle, false);
                                        context.closePath();

                                        context.fillStyle = colors[i];
                                        context.fill();

                                        context.restore();

                                        drawSegmentLabel(i);
                                    }

                                    function drawSegmentLabel(i) {

                                        var dot = document.getElementById("lori" + i + "c");
                                        var tex = document.getElementById("lori" + i + "t");

                                        dot.style.backgroundColor = colors[i];
                                        dot.style.color = colors[i];
                                        tex.innerHTML = label[i];
                                    }

                                    function degreesToRadians(degrees) {
                                        return (degrees * Math.PI)/180;
                                    }

                                    function sumTo(a, i) {
                                        var sum = 0;
                                        for (var j = 0; j < i; j++) {
                                            sum += a[j];
                                        }
                                        return sum;
                                    }

                                    canvas = document.getElementById("piechart");
                                    var context = canvas.getContext("2d");
                                    for (var i = 0; i < data.length; i++) {
                                        drawSegment(canvas, context, i);
                                    }
                                </script>

                            </div> <!-- .entry -->
                        </div> <!-- .section -->
                    </div> <!-- ..section_cell -->
                </div> <!-- .section_row -->
            </div> <!-- #section_table  -->
        </div> <!-- #content -->
    </body>
</html>
<?php
    }
?>
