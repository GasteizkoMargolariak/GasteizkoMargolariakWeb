<?php
    include("../functions.php");
    $con = startdb();
    
    //Get fields
    $user = mysqli_real_escape_string($con, $_GET['name']);
    $pass = mysqli_real_escape_string($con, $_GET['password']);
    
    //Get user entry
    $q = mysqli_query($con, "SELECT id, md5(salt) AS salt, fname, lname FROM user WHERE active = 1 AND lcase(username) = lcase('$user') AND password = '$pass';");
    if (mysqli_num_rows($q) > 0){
        $r = mysqli_fetch_array($q);
        echo("<status>1</status>\n");
        echo("<id>$r[id]</id>\n");
        echo("<fname>$r[fname]</fname>\n");
        echo("<lname>$r[lname]</lname>\n");
        echo("<salt>" . $r['salt'] . "</salt>\n");
    }
    else{
        echo("<status>0</status>\n");
    }
?>
