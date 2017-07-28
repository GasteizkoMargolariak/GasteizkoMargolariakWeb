<?php
    $_SESSION['id'] = $r['_'];
    $_SESSION['salt'] = $r['_'];
    $_SESSION['name'] = $r['_'];
    session_destroy();
    header("Location: /index.php");
?>