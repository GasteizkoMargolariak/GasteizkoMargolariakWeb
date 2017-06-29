<?php
    $http_host = $_SERVER['HTTP_HOST'];
    include("../../functions.php");
    $con = startdb('rw');
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
        
        //Get POST data
        $id = mysqli_real_escape_string($con, $_POST['id']);
        $title_es = mysqli_real_escape_string($con, $_POST['title_es']);
        $title_eu = mysqli_real_escape_string($con, $_POST['title_eu']);
        $title_en = mysqli_real_escape_string($con, $_POST['title_en']);
        $text_es = mysqli_real_escape_string($con, $_POST['text_es']);
        $text_eu = mysqli_real_escape_string($con, $_POST['text_eu']);
        $text_en = mysqli_real_escape_string($con, $_POST['text_en']);
        $image = array();
        $image[0] = $_FILES['image_0'];
        $image[1] = $_FILES['image_1'];
        $image[2] = $_FILES['image_2'];
        $image[3] = $_FILES['image_3'];
        $comments = mysqli_real_escape_string($con, $_POST['comments']);
        $visible = mysqli_real_escape_string($con, $_POST['visible']);
        $admin = mysqli_real_escape_string($con, $_POST['admin']);
        
        //Check spanish title
        if ($title_es == null)
            exit();
        
        //Check titles with no text
        if (strlen($title_eu) > 0 && strlen($text_eu) < 1)
            exit();
        if (strlen($title_en) > 0 && strlen($text_en) < 1)
            exit();
        
        //Check text with no titles
        if (strlen($text_eu) > 0 && strlen($title_eu) < 1)
            exit();
        if (strlen($text_en) > 0 && strlen($title_en) < 1)
            exit();
        
        //Check booleans and assign default values if not
        if ($comments == 'off')
            $comments = 0;
        else
            $comments = 1;
        if ($visible == 'off')
            $visible = 0;
        else
            $visible = 1;
        if ($admin == 'on')
            $admin = 1;
        else
            $admin = 0;
            
        // If no translations, same text in all languages
        if (strlen($text_eu) == 0){
            $text_eu = $text_es;
            $title_eu = $title_es; 
        }
        
        if (strlen($text_en) == 0){
            $text_en = $text_es;
            $title_en = $title_es; 
        }
        
        //Get database entry
        $q = mysqli_query($con, "SELECT * FROM post WHERE id = $id;");
        if (mysqli_num_rows($q) == 0)
            exit();
        $r = mysqli_fetch_array($q);
        if ($title_es != $r['title_es']){
            mysqli_query($con, "UPDATE post SET title_es = '$title_es' WHERE id = $id;");
        }
        if ($title_eu != $r['title_eu']){
            mysqli_query($con, "UPDATE post SET title_eu = '$title_eu' WHERE id = $id;");
        }
        if ($title_en != $r['title_en']){
            mysqli_query($con, "UPDATE post SET title_en = '$title_en' WHERE id = $id;");
        }
        if ($text_es != $r['text_es']){
            mysqli_query($con, "UPDATE post SET text_es = '$text_es' WHERE id = $id;");
        }
        if ($text_eu != $r['text_eu']){
            mysqli_query($con, "UPDATE post SET text_eu = '$text_eu' WHERE id = $id;");
        }
        if ($text_en != $r['text_en']){
            mysqli_query($con, "UPDATE post SET text_en = '$text_en' WHERE id = $id;");
        }
        if ($comments != $r['comments']){
            mysqli_query($con, "UPDATE post SET comments = $comments WHERE id = $id;");
        }
        if ($visible != $r['visible']){
            mysqli_query($con, "UPDATE post SET visible = $visible WHERE id = $id;");
        }
        
        while ($file_idx < 4 && $img_idx < 4){
            if ($_POST["delete_image_$file_idx"] == 'yes'){
                mysqli_query($con, "DELETE FROM pot_image WHERE post = $id AND idx = $file_idx;");
                //TODO: Maybe images should be sorted after this
            }
            
            if (file_exists($image[$file_idx]['tmp_name']) > 0){
                
                //Convert to jpg
                $im = new imagick($image[$file_idx]['tmp_name']);
                $im->setImageFormat('jpg');
                $im->writeImage("../../../www/img/blog/$permalink-n$img_idx.jpg");
                $im->resizeImage(800, 800, Imagick::FILTER_POINT, false);
                $im->writeImage("../../../www/img/blog/view/$permalink-n$img_idx.jpg");
                $im->resizeImage(600, 600, Imagick::FILTER_POINT, false);
                $im->writeImage("../../../www/img/blog/preview/$permalink-n$img_idx.jpg");
                $im->resizeImage(350, 350, Imagick::FILTER_POINT, false);
                $im->writeImage("../../../www/img/blog/miniature/$permalink-n$img_idx.jpg");
                $im->resizeImage(200, 200, Imagick::FILTER_POINT, false);
                $im->writeImage("../../../www/img/blog/thumb/$permalink-n$img_idx.jpg");
                $im->clear();
                $im->destroy();
                
                //Database
                mysqli_query($con, "INSERT INTO post_image (post, image, idx) VALUES ($post_id, '$permalink-n$img_idx.jpg', $img_idx);");
                
                $img_idx ++;
            }
            $file_idx ++;
        }
        
        version();
    }
?>
