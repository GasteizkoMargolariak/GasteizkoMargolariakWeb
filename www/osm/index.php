<?xml version='1.0' encoding='UTF-8'?>
<?php
    session_start();
    include("../functions.php");
    $con = startdb();
    $proto = getProtocol();
    $http_host = $_SERVER["HTTP_HOST"];
    $server = "$proto$http_host";

    // Get path identifier
    $route = mysqli_real_escape_string($con, $_GET["route"]);
    if (intval($route) != 0){
        $q = mysqli_query($con, "SELECT id, name FROM route WHERE id = $route LIMIT 1;");
    }
    else{
        $q = mysqli_query($con, "SELECT id, name FROM route WHERE upper(name) = upper('$route');");
    }
    if (mysqli_num_rows($q) == 0){
        http_response_code(404);
        exit(404);
    }
    $r = mysqli_fetch_array($q);
    $route = $r["id"];

    $q_point = mysqli_query($con, "SELECT id, part, lat_o, lon_o, place_o, lat_d, lon_d, place_d FROM route_point WHERE route = $route ORDER by part;");
    if (mysqli_num_rows($q) == 0){
        http_response_code(404);
        exit(404);
    }
?>
<osm version='0.6' generator='Gasteizko Margolariak V3'>
<?php
    $points = array();
    $t_points = 0;
    while ($r_point = mysqli_fetch_array($q_point)){

        // Origin point
        if ((strlen($r_point["lat_o"]) == 0 || strlen($r_point["lon_o"]) == 0) && strlen($r_point["place_o"]) != 0){
            $q_place = mysqli_query($con, "SELECT lat, lon FROM place WHERE id = $r_point[place_o];");
            if (mysqli_num_rows($q_place) == 0){
                http_response_code(501);
                exit(501);
            }
            else{
                $r_place = mysqli_fetch_array($q_place);
                $lat_o = $r_place["lat"];
                $lon_o = $r_place["lon"];
            }
        }
        else{
            $lat_o = $r_point["lat_o"];
            $lon_o = $r_point["lon_o"];
        }

        // Destination point
        if ((strlen($r_point["lat_d"]) == 0 || strlen($r_point["lon_d"]) == 0) && strlen($r_point["place_d"]) != 0){
            $q_place = mysqli_query($con, "SELECT lat, lon FROM place WHERE id = $r_point[place_d];");
            if (mysqli_num_rows($q_place) == 0){
                http_response_code(501);
                exit(501);
            }
            else{
                $r_place = mysqli_fetch_array($q_place);
                $lat_d = $r_place["lat"];
                $lon_d = $r_place["lon"];
            }
        }
        else{
            $lat_d = $r_point["lat_d"];
            $lon_d = $r_point["lon_d"];
        }

        $points[$t_points] = "$r_point[id]$route$r_point[part]0";
        $points[$t_points + 1] = "$r_point[id]$route$r_point[part]1";
        $t_points = $t_points + 2;
?>
    <node id='<?=$r_point["id"]?><?=$route?><?=$r_point["part"]?>0' lat='<?=$lat_o?>' lon='<?=$lon_o?>' visible='true' version='1' />
    <node id='<?=$r_point["id"]?><?=$route?><?=$r_point["part"]?>1' lat='<?=$lat_d?>' lon='<?=$lon_d?>' visible='true' version='1' />
<?php
    } // while ($r_point = mysqli_fetch_array($q_point))
?>
    <way id='<?=$route?>' visible='true' version='1'>
<?php

    $i = 0;
    while($i < $t_points){
?>
        <nd ref='<?=$points[$i]?>' />
<?php
        $i = $i + 1;
    } // while($i <= $t_points)
?>
    </way>
</osm>
