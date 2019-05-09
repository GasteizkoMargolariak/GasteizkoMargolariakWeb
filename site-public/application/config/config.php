<?php

    require_once(__DIR__ . "/auth.php");
    require_once(__DIR__ . "/../util/net.php");

    /**
     * Server base url, including protocol, without language indicator.
     * It may be overwritten by the controller to add the language.
     */
    $base_url = get_protocol() . $_SERVER["HTTP_HOST"];
    $static_url = get_protocol() . $_SERVER["HTTP_HOST"];

    /**
     * Page urls, different languages.
     */
    $url_l10n = [
        "es" => [
            "help" => "ayuda",
            "activities" => "actividades",
            "blog" => "blog",
            "us" => "nosotros",
            "gallery" => "galeria",
            "festivals" => "fiestas"
        ],
        "en" => [
            "help" => "help",
            "activities" => "activities",
            "blog" => "blog",
            "us" => "us",
            "gallery" => "gallery",
            "festivals" => "festivals"
        ],
        "eu" => [
            "help" => "laguntza",
            "activities" => "aktibitateak",
            "blog" => "blog",
            "us" => "gu",
            "gallery" => "argaskinak",
            "festivals" => "jaiak"
        ]
    ];

    /**
     * Static url, absolute, language independant.
     */
    $static = [
        "layout" => $static_url . "/img/layout/",
        "content" => $static_url . "/img/content/",
        "fonts" => $static_url . "/fonts/",
        "css" => $static_url . "/css/",
        "script" => $static_url . "/script/",
    ];

    /**
     * Server document root.
     */
    $base_dir = $_SERVER["DOCUMENT_ROOT"];

    /**
     * Paths within the server.
     */
    $path = [
        "application" => $base_dir . "/../application/",
        "controller" => $base_dir . "/../application/Controller.php",
        "entity" => $base_dir . "/../application/entity/",
        "page" => $base_dir . "/../application/page/",
        "util" => $base_dir . "/../application/util/",
        "template" => $base_dir . "/../application/template/",
        "include" => $base_dir . "/../application/template/include/",
        "string" => $base_dir . "/../application/template/string/"
    ];

    require_once(__DIR__ . "/data.php");

?>
