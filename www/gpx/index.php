<?xml version='1.0' encoding='UTF-8'?>
<gpx xmlns="http://www.topografix.com/GPX/1/1" xmlns:gpxx="http://www.garmin.com/xmlschemas/GpxExtensions/v3" xmlns:gpxtpx="http://www.garmin.com/xmlschemas/TrackPointExtension/v1" creator="Gasteizko Margolariak" version="2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd http://www.garmin.com/xmlschemas/GpxExtensions/v3 http://www.garmin.com/xmlschemas/GpxExtensionsv3.xsd http://www.garmin.com/xmlschemas/TrackPointExtension/v1 http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd">
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

    $q_point = mysqli_query($con, "SELECT id, part, lat_o, lon_o, lat_d, lon_d FROM route_point WHERE route = $route ORDER by part;");
    if (mysqli_num_rows($q) == 0){
        http_response_code(404);
        exit(404);
    }
?>
    <metadata>
        <link href='<?=$server?>/gpx/<?=$route?>.gpx'>
            <text><?=$r["name"]?> - Gasteizko Margolariak</text>
        </link>
    </metadata>
    <trk>
        <name><?=$r["name"]?> - Gasteizko Margolariak</name>
        <trkseg>
<?php
    $points = array();
    $t_points = 0;
    while ($r_point = mysqli_fetch_array($q_point)){

        // Origin point
        $lat_o = $r_point["lat_o"];
        $lon_o = $r_point["lon_o"];

        // Destination point
        $lat_d = $r_point["lat_d"];
        $lon_d = $r_point["lon_d"];

        $points[$t_points] = "$r_point[id]$route$r_point[part]0";
        $points[$t_points + 1] = "$r_point[id]$route$r_point[part]1";
        $t_points = $t_points + 2;
?>
            <trkpt lat='<?=$lat_o?>' lon='<?=$lon_o?>' />
            <trkpt lat='<?=$lat_d?>' lon='<?=$lon_d?>' />
<?php
    } // while ($r_point = mysqli_fetch_array($q_point))
?>
        </trkseg>
    </trk>
</gpx>
