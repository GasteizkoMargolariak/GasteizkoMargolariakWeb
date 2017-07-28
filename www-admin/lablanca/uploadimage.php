<?php
    error_log("starting uploading image");
    $http_host = $_SERVER['HTTP_HOST'];
    $default_host = substr($http_host, 0, strpos($http_host, ':'));
    include("../functions.php");
    $con = startdb('rw');
    if (!checkSession($con)){
        exit (-1);
    }
    else{
        //Get data
        $type = mysqli_real_escape_string($con, $_GET['type']);
        $id = mysqli_real_escape_string($con, $_GET['id']);
        $file = $_FILES['img'];
        error_log("DFILE: " . $_FILES['img']['tmp_name']);
        
        //Check if image file
        $check = getimagesize($_FILES['img']['tmp_name']);
        if($check == false) {
            error_log("Uploading " . $_FILES['img']['tmp_name'] . ": File is not an image - " . $check["mime"] . ".");
            exit(-1);
        }
        
        error_log("uploading image");
        
        //Upload the image to its destination
        switch ($type){
            case 'header':
                $q = mysqli_query($con, "SELECT * FROM festival WHERE id = $id;");
                if (mysqli_num_rows($q) > 0){
                    $r = mysqli_fetch_array($q);
                    move_uploaded_file($_FILES['img']['tmp_name'], "../../www/img/fiestas/tmp.$r[id].png");
                    error_log($_FILES['img']['tmp_name']. " ../../www/img/fiestas/tmp.$r[id].png");
                    
                    //Get image dimensions
                    $i_w = $check[0];
                    $i_h = $check[1];
                    
                    //Calculate preview and miniature dimensions
                    if ($i_w > $i_h){
                        $p_w = $IMG_SIZE_PREVIEW;
                        $p_h = ceil($i_h * $IMG_SIZE_PREVIEW) / $i_w;
                        $m_w = $IMG_SIZE_MINIATURE;
                        $m_h = ceil($i_h * $IMG_SIZE_MINIATURE) / $i_w;
                    }
                    else{
                        $p_h = $IMG_SIZE_PREVIEW;
                        $p_w = ceil($i_h * $IMG_SIZE_PREVIEW) / $i_h;
                        $m_h = $IMG_SIZE_MINIATURE;
                        $m_w = ceil($i_w * $IMG_SIZE_MINIATURE) / $i_h;
                    }
                    
                    //Convert to png
                    $thumb = new Imagick();
                    $thumb->readImage("../../www/img/fiestas/tmp.$r[id].png");    
                    $thumb->writeImage("../../www/img/fiestas/$r[year].png");
                    $thumb->resizeImage(800,800,Imagick::FILTER_POINT, false);
                    $thumb->writeImage("../../www/img/fiestas/view/$r[year].png");
                    $thumb->resizeImage(600,600,Imagick::FILTER_POINT, false);
                    $thumb->writeImage("../../www/img/fiestas/preview/$r[year].png");
                    $thumb->resizeImage(340,340,Imagick::FILTER_POINT, false);
                    $thumb->writeImage("../../www/img/fiestas/miniature/$r[year].png");
                    $thumb->resizeImage(180,180,Imagick::FILTER_POINT, false);
                    $thumb->writeImage("../../www/img/fiestas/thumb/$r[year].png");
                    $thumb->clear();
                    $thumb->destroy(); 
                    
                    //Update database
                    if (strlen($r['img']) == 0){
                        mysqli_query($con, "UPDATE festival SET img = '$r[year].png' WHERE id = $id;");
                        version();
                    }
                }
                break;
        }
    }
?>
