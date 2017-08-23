<?php
    header('Content-type: application/xml');
    $http_host = $_SERVER['HTTP_HOST'];
    include("functions.php");
    $con = startdb();
    $proto = getProtocol();
    $server = "$proto$http_host";
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url>
        <loc><?=$server?>/</loc>
<?php
        $res_date = mysqli_query($con, "(SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM post) UNION (SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM activity) UNION (SELECT DATE_FORMAT(uploaded, '%Y-%m-%d') AS cdate FROM photo) ORDER BY cdate DESC LIMIT 1;");
        $row_date = mysqli_fetch_array($res_date);
?>
        <lastmod><?=$row_date["cdate"]?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        <image:image>
            <image:loc><?=$server?>/img/logo/GasteizkoMargolariak.png</image:loc>
            <image:caption>Gasteizko Margolariak</image:caption>
            <image:title>Gasteizko Margolariak</image:title>
        </image:image>
        <image:image>
            <image:loc><?=$server?>/img/logo/GasteizkoMargolariak-0.png</image:loc>
            <image:caption>Gasteizko Margolariak Original</image:caption>
            <image:title>Gasteizko Margolariak Original</image:title>
        </image:image>
    </url>
    <url>
        <loc><?=$server?>/ayuda/</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?=$server?>/nosotros/</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc><?=$server?>/blog/</loc>
<?php
        $res_date = mysqli_query($con, "SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM post ORDER BY cdate DESC LIMIT 1;");
        $row_date = mysqli_fetch_array($res_date);
?>
        <lastmod><?=$row_date["cdate"]?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php
    $res_post = mysqli_query($con, "SELECT id, title_es, permalink, DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM post WHERE visible = 1 ORDER BY cdate DESC;");
    $priority = 0.75;
    while ($row_post = mysqli_fetch_array($res_post)){
?>
        <url>
            <loc><?=$server?>/blog/<?=$row_post["permalink"]?></loc>
            <lastmod><?=$row_post["cdate"]?></lastmod>
            <changefreq>weekly</changefreq>
            <priority><?=$priority?></priority>
<?php
            $q_i = mysqli_query($con, "SELECT image FROM post_image WHERE post = $row_post[id] ORDER BY idx;");
            while ($r_i = mysqli_fetch_array($q_i)){
?>
                <image:image>
                    <image:loc><?=$server?>/img/blog/view/<?=$r_i["image"]?></image:loc>
                    <image:title><?=$row_post["title_es"]?></image:title>
                </image:image>
<?php
            }
?>
        </url>
<?php
        $priority = $priority - 0.05;
        if ($priority < 0.45){
            $priority = 0.45;
        }
    }
?>
    <url>
        <loc><?=$server?>/actividades/</loc>
<?php
        $res_date = mysqli_query($con, "SELECT DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM activity ORDER BY cdate DESC LIMIT 1;");
        $row_date = mysqli_fetch_array($res_date);
?>
        <lastmod><?=$row_date["cdate"]?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1</priority>
    </url>
<?php
    $res = mysqli_query($con, "SELECT title_es, id, permalink, DATE_FORMAT(dtime, '%Y-%m-%d') AS cdate FROM activity WHERE visible = 1 ORDER BY cdate DESC;");
    $priority = 0.95;
    while ($row = mysqli_fetch_array($res)){
?>
        <url>
            <loc><?=$server?>/actividades/<?=$row["permalink"]?></loc>
            <lastmod><?=$row["cdate"]?></lastmod>
            <changefreq>weekly</changefreq>
            <priority><?=$priority?></priority>
<?php
            $q_i = mysqli_query($con, "SELECT image FROM activity_image WHERE activity = $row[id] ORDER BY idx;");
            while ($r_i = mysqli_fetch_array($q_i)){
?>
                <image:image>
                    <image:loc><?=$server?>/img/actividades/view/<?=$r_i["image"]?></image:loc>
                    <image:title><?=$row_post["title_es"]?></image:title>
                </image:image>
<?php
            }
?>
        </url>
<?php
        $priority = $priority - 0.05;
        if ($priority < 0.65){
            $priority = 0.65;
        }
    }
?>
    <url>
        <loc><?=$server?>/galeria/</loc>
<?php
        $res_date = mysqli_query($con, "SELECT DATE_FORMAT(uploaded, '%Y-%m-%d') AS cdate FROM photo ORDER BY cdate DESC LIMIT 1;");
        $row_date = mysqli_fetch_array($res_date);
?>
        <lastmod><?=$row_date["cdate"]?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php
    $res = mysqli_query($con, "SELECT DISTINCT album.id AS albumid, album.title_es AS title, album.permalink AS permalink, DATE_FORMAT(uploaded, '%Y-%m-%d') AS cdate FROM photo, album, photo_album WHERE photo = photo.id AND album = album.id ORDER BY cdate DESC;");
    $priority = 0.75;
    while ($row = mysqli_fetch_array($res)){
?>
        <url>
            <loc><?=$server?>/galeria/<?=$row["permalink"]?></loc>
            <lastmod><?=$row["cdate"]?></lastmod>
            <changefreq>weekly</changefreq>
            <priority><?=$priority?></priority>
<?php
            $q_i = mysqli_query($con, "SELECT file, title_es FROM photo, photo_album WHERE id = photo AND album = $row[albumid];");
            while ($r_i = mysqli_fetch_array($q_i)){
?>
                <image:image>
                <image:loc><?=$server?>/img/galeria/view/<?=$r_i["file"]?></image:loc>
<?php
                    if (strlen($r_i['title_es']) > 0){
?>
                        <image:title><?=$r_i["title_es"]?></image:title>
<?php
                    }
                    else{
?>
                        <image:title><?=$row["title"]?></image:title>
<?php
                    }
?>
                </image:image>
<?php
            }
?>
        </url>
<?php
        $priority = $priority - 0.05;
        if ($priority < 0.45){
            $priority = 0.45;
        }
    }
?>
    <url>
        <loc><?=$server?>/lablanca/</loc>
<?php
        $res_lb = mysqli_query($con, 'SELECT value FROM settings WHERE name= "festivals";');
        if (mysqli_num_rows($res_lb) > 0){
            $r_lb = mysqli_fetch_array($res_lb);
            if ($r_lb["value"] == 1){
?>
                <changefreq>daily</changefreq>
                <priority>1</priority>
<?php
            }
            else{
?>
                <changefreq>monthly</changefreq>
                <priority>0.5</priority>
<?php
            }
        }
        else{
?>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
<?php
        }
?>
    </url>
<?php
        $year = date("Y");
        $res_years = mysqli_query($con, "SELECT img, year FROM festival ORDER BY year DESC;");
        $priority = 0.55;
        while ($row_years = mysqli_fetch_array($res_years)){
?>
            <url>
                <loc><?=$server?>/lablanca/<?=$row_years["year"]?></loc>
                <changefreq>yearly</changefreq>
                <priority><?=$priority?></priority>
<?php
                if (strlen($row_years["img"]) > 0){
?>
                    <image:image>
                        <image:loc><?=$server?>/img/fiestas/view/<?$row_years["img"]?></image:loc>
                        <image:title>La Blanca <?=$row_years["year"]?></image:title>
                    </image:image>
<?php
            }
?>
            </url>
            <url>
                <loc><?=$server?>/lablanca/programa/margolariak/<?=$row_years["year"]?></loc>
                <changefreq>yearly</changefreq>
                <priority><?=($priority + 0.3)?></priority>
<?php
                if (strlen($row_years["img"]) > 0){
?>
                    <image:image>
                        <image:loc><?=$server?>/img/fiestas/view/<?=$row_years["img"]?></image:loc>
                        <image:title>La Blanca <?=$row_years["year"]?></image:title>
                    </image:image>
<?php
            }
?>
            </url>
            <url>
                <loc><?=$server?>/lablanca/programa/municipal/<?=$row_years["year"]?></loc>
                <changefreq>yearly</changefreq>
                <priority><?=($priority + 0.1)?></priority>
<?php
                if (strlen($row_years["img"]) > 0){
?>
                    <image:image>
                        <image:loc><?=$server?>/img/fiestas/view/<?=$row_years["img"]?></image:loc>
                        <image:title>La Blanca <?=$row_years["year"]?></image:title>
                    </image:image>
<?php
            }
?>
            </url>
<?php
            $priority = $priority - 0.05;
            if ($priority < 0.40){
                $priority = 0.40;
            }
        } // while ($row_years = mysqli_fetch_array($res_years))
?>
</urlset>
