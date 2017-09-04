 <?php
    // Gasteizko Margolariak API v3 //

    // $_POST valid parameters
    define('POST_USER', 'user');
    define('POST_PASS', 'pass');

    // Error messages
    define('ERR_USER', '-USER:');

    include('functions.php');
    $con = startdb();

    // Error control
    $error = "";

    // Get params
    $user = mysqli_real_escape_string($con, $_POST[POST_USER]);
    $pass = mysqli_real_escape_string($con, $_POST[POST_PASS]);

    //Validate user
    $uid = login($con, $user, $pass);
    if ($uid == -1){
        error_log(":SECURITY: Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
        $error = $error . ERR_USER . mysqli_real_escape_string($con, $_POST[GET_USER]);
        error_log($error);
        http_response_code(403); // Forbidden
        exit(-1);
    }
    else{
        http_response_code(204); // No content
    }
?>
