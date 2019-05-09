<?php
    include "../application/config/config.php";
    include_once($path["controller"]);
    $request = $_SERVER['REQUEST_URI'];
    $params = explode('/', $request);
    $controller = new Controller($params);
?>
