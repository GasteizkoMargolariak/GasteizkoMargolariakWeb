<?php
    include("../functions.php");
    $con = startdb('rw');
    
    //Get fields
    $user = mysqli_real_escape_string($con, $_GET['user']);
    $code = mysqli_real_escape_string($con, $_GET['code']);
    $title_es = urldecode(mysqli_real_escape_string($con, $_GET['titleEs']));
    $title_en = urldecode(mysqli_real_escape_string($con, $_GET['titleEn']));
    $title_eu = urldecode(mysqli_real_escape_string($con, $_GET['titleEu']));
    $text_es = urldecode(mysqli_real_escape_string($con, $_GET['textEs']));
    $text_en = urldecode(mysqli_real_escape_string($con, $_GET['textEn']));
    $text_eu = urldecode(mysqli_real_escape_string($con, $_GET['textEu']));
    $duration = mysqli_real_escape_string($con, $_GET['duration']);
    $action = mysqli_real_escape_string($con, $_GET['action']);
    
    //Validate code
    $q = mysqli_query($con, "SELECT id FROM user WHERE id = $user AND md5(concat(password, md5(salt))) = '$code'");
    //echo "SELECT id FROM user WHERE id = $user AND md5(concat(password, md5(salt))) = '$code'";
    if (mysqli_num_rows($q) == 0){
        error_log("Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
        echo("<status>0</status>\n");
        exit(-1);
    }
    
    //Validate fields
    if (strlen($title_es) == 0 || strlen($text_es) == 0){
        error_log("Sending notification: no text (IP $_SERVER[REMOTE_ADDR])");
        echo("<status>0</status>\n");
        exit(-1);
    }

    if (is_numeric($duration) == false || $duration < 1 && $duration > 48 * 60){
        error_log("Sending notification: invalid duration: $duration (IP $_SERVER[REMOTE_ADDR])");
        echo("<status>0</status>\n");
        exit(-1);
    }
    
    if ($action != 'text' && $action != 'lablanca' && $action != 'gmschedule' && $action != 'cityschedule' && $action != 'location' && $action != 'around' && $action != 'blog' && $action != 'activities' && $action != 'gallery'){
        error_log("Sending notification: invalid action: '$action' (IP $_SERVER[REMOTE_ADDR])");
        echo("<status>0</status>\n");
        exit(-1);
    }
    
    //Handle translations
    if (strlen($titleEn) == 0){
        $titleEn = $titleEs;
    }
    if (strlen($titleEu) == 0){
        $titleEu = $titleEs;
    }
    if (strlen($textEn) == 0){
        $textEn = $textEs;
    }
    if (strlen($textEu) == 0){
        $textEu = $textEs;
    }
    
    //Insert
    mysqli_query($con, "INSERT INTO notification (user, title_es, title_en, title_eu, text_es, text_en, text_eu, action, duration) VALUES ($user, '$title_es', '$title_en', '$title_eu', '$text_es', '$text_en', '$text_eu', '$action', $duration);");
    echo("<status>1</status>\n");
?>
