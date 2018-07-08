<?php
    $http_host = $_SERVER["HTTP_HOST"];
    include("../functions.php");
    $con = startdb("rw");

    //Language
    $lang = selectLanguage();
    include("../lang/lang_$lang.php");

    //Get post data
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $album = mysqli_real_escape_string($con, $_POST["album"]);

    //Get the data form the photos
    $total = 0;
    $files = [];
    $titles = [];
    $descriptions = [];
    while (strlen($_POST["file_$total"]) > 0){
        //$files[$total] = $_POST["file_$total"];
        $img = $_POST["file_$total"];
        error_log("FILE: " . $img);
        //The next line wont work
        if (strpos($img, "data:image/jpeg;base64,") < 10){
            $ext = "jpg";
            $img = str_replace("data:image/jpeg;base64,", "", $img);
        }
        else if (strpos($img, "data:image/png;base64,") < 10){
            $ext = "png";
            $img = str_replace("data:image/png;base64,", "", $img);
        }
        $img = str_replace(" ", "+", $img);
        $files[$total] = base64_decode($img);

        $titles[$total] = mysqli_real_escape_string($con, $_POST["title_$total"]);
        $descriptions[$total] = mysqli_real_escape_string($con, $_POST["description_$total"]);
        //Save image to file
        $file = "image." . $ext;
        $success = file_put_contents($file, $files[$total]);
        $total ++;
    }

    //Get next photo id
    $q_id = mysqli_query($con, "SELECT max(id) AS maxid FROM photo;");
    $r_id = mysqli_fetch_array($q_id);
    $id = $r_id["maxid"] + 1;

    //Store information
    for ($i = 0; $i < $total; $i++){
        //Get filename
        if (strlen($titles[$i]) > 0){
            $fname = "$id-" . permalink($titles[$i]);
        }
        else{
            $fname = "$id";
        }
        //Save image to file
        $file = "../img/galeria/$fname.$ext";
        $success = file_put_contents($file, $files[$i]);
        error_log("SUCCESS" . $success);
        //Convert to jpg and create miniature and preview_image
        //$im = new imagick("../img/galeria/$fname.png");
        //$handle = fopen("../img/galeria/$fname.png", "rb");
        $im = new Imagick("../img/galeria/$fname.$ext");
        //$im -> readImageFile($handle);
        $im -> setImageBackgroundColor("white");
        $im = $im -> flattenImages();
        $im -> setImageFormat("jpg");
        $im -> writeImage("../img/galeria/$fname.jpg");
        $im -> resizeImage(800, 0, Imagick::FILTER_POINT, true);
        $im -> resizeImage(0, 800, Imagick::FILTER_POINT, true);
        $im -> writeImage("../img/galeria/view/$fname.jpg");
        $im -> resizeImage(600, 0, Imagick::FILTER_POINT, true);
        $im -> resizeImage(0, 600, Imagick::FILTER_POINT, true);
        $im -> writeImage("../img/galeria/preview/$fname.jpg");
        $im -> resizeImage(400, 0, Imagick::FILTER_POINT, true);
        $im -> resizeImage(0, 400, Imagick::FILTER_POINT, true);
        $im -> writeImage("../img/galeria/miniature/$fname.jpg");
        $im -> resizeImage(200, 0, Imagick::FILTER_POINT, true);
        $im -> resizeImage(0, 200, Imagick::FILTER_POINT, true);
        $im -> writeImage("../img/galeria/thumb/$fname.jpg");
        $im -> clear();
        $im -> destroy();
        //Delete initial png
        //unlink("../img/galeria/$fname.png");
        //Get width, height ans size
        $s = filesize("../img/galeria/$fname.jpg");
        $w = getimagesize("../img/galeria/$fname.jpg")[0];
        $h = getimagesize("../img/galeria/$fname.jpg")[1];
        //Entry in database
        mysqli_query($con, "INSERT INTO photo (id, file, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, width, height, size, approved, username) VALUES ($id, '$fname.jpg', '$fname', '$titles[$i]', '$titles[$i]', '$titles[$i]', '$descriptions[$i]', '$descriptions[$i]', '$descriptions[$i]', $w, $h, $s, 0, '$username')");
        error_log("INSERT INTO photo (id, file, permalink, title_es, title_en, title_eu, description_es, description_en, description_eu, width, height, size, approved, username) VALUES ($id, '$fname.jpg', '$fname', '$titles[$i]', '$titles[$i]', '$titles[$i]', '$descriptions[$i]', '$descriptions[$i]', '$descriptions[$i]', $w, $h, $s, 0, '$username')");
        mysqli_query($con, "INSERT INTO photo_album VALUES ($id, $album);");
        version();
        $id ++;
    }
?>
