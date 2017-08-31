 <?php
    // Gasteizko Margolariak API v3 //

    // Error messages
    define('ERR_USER', '-USER:');
    define('ERR_TABLE', '-TABLE:');
    define('ERR_WHERE', '-WHERE:');




    include('functions.php');
    $con = startdb();
    // Error control
    $error = "";

    // Validate user/pass
    if (!fastLogin($con)){
        error_log(":SECURITY: Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
        $error = $error . ERR_USER . mysqli_real_escape_string($con, $_POST[GET_USER]);
        error_log($error);
        http_response_code(403); // Forbidden
        exit(-1);
    }
	
	// TODO: Ask server to create a SQLdump
	$date = date_create();
	$fname = date_timestamp_get($date);
	include('../../.htpasswd');
	exec("mysqldump gm -u $username_ro -p$pass_ro > /var/www-dump/$fname.sql");
	// TODO: Ask server to encrypt the dump.
	$pass = mysqli_real_escape_string($con, $_POST[GET_USER]);
	exec("gpg --batch --yes --passphrase $pass -o /var/www-admin/dump/$fname.gpg -c /var/www-dump/$fname.sql.txt")
	// TODO: Make a redirect for a redirect.
	header("Location: /dump/$fname.gpg");
?>
