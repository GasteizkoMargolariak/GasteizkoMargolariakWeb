 <?php
    // Gasteizko Margolariak API v3 //

    // $_GET and $_POST valid parameters
    define('POST_USER', 'user');
    define('POST_PASS', 'pass');
    define('GET_TABLES', 'tables');
    define('GET_WHERE', 'where');

    // Error messages
    define('ERR_USER', '-USER:');
    define('ERR_TABLES', '-TABLES:');
    define('ERR_WHERE', '-WHERE:');

    include('functions.php');
    $con = startdb();

    // Error control
    $error = "";

    // Get params
    $user = mysqli_real_escape_string($con, $_POST[POST_USER]);
    $pass = mysqli_real_escape_string($con, $_POST[POST_PASS]);

    $tables = mysqli_real_escape_string($con, $_GET[GET_TABLES]);
    $where = mysqli_real_escape_string($con, $_GET[GET_WHERE]);

    //Validate user
    $uid = login($con, $user, $pass);
    if ($uid == -1){
        error_log(":SECURITY: Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
        $error = $error . ERR_USER . mysqli_real_escape_string($con, $_POST[GET_USER]);
        error_log($error);
        http_response_code(403); // Forbidden
        exit(-1);
    }

    // Ask server to create a SQLdump
    $date = date_create();
    $fname = date_timestamp_get($date);
    include('../../../www/.htpasswd');
    exec("mysqldump --single-transaction gm -u $username_ro -p'$pass_ro' > /var/www-dump/$fname.sql");

    // Ask server to encrypt the dump.
    $pass = mysqli_real_escape_string($con, $_POST[POST_USER]);
    exec("gpg --batch --yes --passphrase $pass -o /var/www-admin/dump/$fname.gpg -c /var/www-dump/$fname.sql");

    // Make a redirect.
    header("Location: /dump/$fname.gpg");
?>
