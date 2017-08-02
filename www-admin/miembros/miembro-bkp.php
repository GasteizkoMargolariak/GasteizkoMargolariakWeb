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
        $id = mysqli_real_escape_string($con, $_GET['m']);
        $q = mysqli_query($con, "SELECT * FROM member WHERE id = $id;");
        if (mysqli_num_rows($q) == 0){
            header("Location: /miembros/");
            exit (-1);
        }
        else{
            $r = mysqli_fetch_array($q); ?>
        
<html>
    <head>
        <meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title>Miembros - Administracion</title>
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="/css/ui.css"/>
        <link rel="stylesheet" type="text/css" href="/css/miembros.css"/>
        <!-- CSS for mobile version -->
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/miembros.css"/>
        <!-- Script files -->
        <script type="text/javascript" src="script.js"></script>
    </head>
    <body>
        <div class="section">
            <h3><?php echo "$r[name] <span style='font-style:italic;'>$r[alias]</span> $r[lname]";?></h3>
            <div class="entry member_section" id="member_personal">
                <h3>Datos personales</h4>
                <p><span style="font-weight:bold;">Nombre: </span><?php echo $r['name']; ?></p>
                <p><span style="font-weight:bold;">Primer apellido: </span><?php echo $r['lname']; ?></p>
                <p><span style="font-weight:bold;">Segundo apellido: </span><?php echo $r['lname2']; ?></p>
                <p><span style="font-weight:bold;">Alias: </span><?php echo $r['alias']; ?></p>
                <br/>
                <p><span style="font-weight:bold;">Fecha de nacimiento: </span>
                <?php
                    $date = formatDate($r['bdate'], 'es');
                    $date = substr($date, 0, strlen($date) - 12);
                    $dStart = new DateTime($r['bdate']);
                    $dEnd  = new DateTime();
                    $dDiff = $dStart->diff($dEnd);
                    $age =  floor(($dDiff->days) / 365);
                    if ($age < 18)
                        $date = "$date (<span style='color:#aa0000'>$age a&ntilde;os - Menor de edad</span>)";
                    else
                        $date = "$date ($age a&ntilde;os)";
                    echo $date;
                ?>
                </p>
                <p><span style="font-weight:bold;">DNI: </span><?php echo $r['dni'];?></p>
                <p><span style="font-weight:bold;">Direccion: </span><?php echo $r['address'];?></p>
            </div>
            <div class="entry member_section" id="member_gm">
                <h3>En la cuadrilla</h3>
                <p><span style="font-weight:bold;">Fecha de alta: </span>
                <?php
                    $date = formatDate($r['jdate'], 'es');
                    $date = substr($date, 0, strlen($date) - 12);
                    echo $date;
                ?>
                </p>
                <p><span style="font-weight:bold;">Formulario: </span>
                    <?php
                        switch ($r['joined']){
                            case 'origin':
                                echo "Socio inicial";
                                break;
                            case 'phone':
                                echo "V&iacute;a m&oacute;vil";
                                break;
                            case 'oldweb':
                                echo "V&iacute;a web antigua";
                                break;
                            case 'web':
                                echo "V&iacute;a web";
                                break;
                            default:
                                echo "V&iacute;a hoja de inscripci&oacute;n";
                        }
                    ?>
                </p>
                <p><span style="font-weight:bold;">Junta directiva: </span>
                <?php
                    if ($r['board'] == 1)
                        echo "Si";
                    else
                        echo "No";
                ?>
                </p>
            </div>
            <div class="entry member_section" id="member_activities">
                <h3>Actividades</h3>
                <ul id="member_list_days">
                    <?php
                        $paid = 0;
                        $unpaid = 0;
                        $q_year = mysqli_query($con, "SELECT year(date) AS year FROM member_day, festival_day WHERE member_day.day = festival_day.id AND member = $r[id] GROUP BY year(date) ORDER BY year(date)");
                        while ($r_year = mysqli_fetch_array($q_year)){
                            echo "<li>Fiestas $r_year[year]:\n";
                            $q_day = mysqli_query($con, "SELECT day(date) AS dat, price, paid FROM festival_day, member_day WHERE member_day.day = festival_day.id AND year(date) = $r_year[year] AND member = $r[id] ORDER BY date;");
                            echo "<ul>\n";
                            while ($r_day = mysqli_fetch_array($q_day)){
                                if ($r_day['paid'] == 1){
                                    echo "<li>$r_day[dat]</li>\n";
                                    $paid = $paid + intval($r_day['price']);
                                }
                                else{
                                    echo "<li>$r_day[dat] - <span style='color:#aa0000'>No pagado</span></li>\n";
                                    $unpaid = $unpaid + intval($r_day['price']);
                                }
                                    
                            }
                            echo "</ul>\n";
                            echo "</li>\n";
                        }
                    ?>
                </ul>
                <ul id="member_list_activities">
                    <?php
                        $q_activity = mysqli_query($con, "SELECT title_es, price, year(date) AS year, paid FROM member_activity, activity WHERE member = $r[id] AND activity = activity.id");
                        while ($r_activity = mysqli_fetch_array($q_activity)){
                            if ($r_activity['paid'] == 1){
                                    echo "<li>$r_activity[title_es] ($r_activity[year])</li>\n";
                                    $paid = $paid + intval($r_activity['price']);
                                }
                                else{
                                    echo "<li>$r_activity[title_es] ($r_activity[year]) - <span style='color:#aa0000'>No pagado</span></li>\n";
                                    $unpaid = $unpaid + intval($r_activity['price']);
                                }
                        }
                    ?>
                </ul>
                <div id="activity_count">
                    <hr/>
                    Dinero aportado: <?php echo $paid;?> &euro;
                    <?php
                        if ($unpaid > 0)
                            echo "<br/><span style='color:#aa0000'>Debe: $unpaid &euro;</span>"
                    ?>
                </div>
            </div>
            <div class="entry member_section" id="member_social">
                <h3>Redes sociales</h3>
                <ul>
                    <?php
                        if ($r['facebook'] == null)
                            echo "<li style='text-decoration: line-through;'>Facebook</li>\n";
                        else
                            echo "<li><a href='$r[facebook]'>Facebook</a></li>\n";
                        if ($r['twitter'] == null)
                            echo "<li style='text-decoration: line-through;'>Twitter</li>\n";
                        else
                            echo "<li><a href='$r[twitter]'>Twitter</a></li>\n";
                        if ($r['googleplus'] == null)
                            echo "<li style='text-decoration: line-through;'>Google+</li>\n";
                        else
                            echo "<li><a href='$r[googleplus]'>Google+</a></li>\n";
                    ?>
                </ul>
            </div>
            <div class="entry member_section" id="member_intolerances">
                <h3>Intolerancias alimenticias</h3>
                <ul>
                <?php
                    $q_intolerance = mysqli_query($con, "SELECT element FROM intolerance WHERE member = $r[id];");
                    if (mysqli_num_rows($q_intolerance) == 0)
                        echo "<li>Ninguna</li>\n";
                    else
                        while ($r_intolerance = mysqli_fetch_array($q_intolerance))
                            echo "<li>$r_intolerance[element]</li>\n";
                ?>
                </ul>
            </div>
            
        </div>
    </body>
        
        <?php }
    }
?>
