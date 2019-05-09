<?php


    /**
     * Finds out the protocol the user is connecting to the site with.
     *
     * @return "http://" or "https://".
     */
    function get_protocol(){
        if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on" || $_SERVER["HTTPS"] == 1) || isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") {
            $protocol = "https://";
        }
        else {
            $protocol = "http://";
        }
        return $protocol;
    }


    /**
     * Finds out the IP address of the client.
     * 
     * @return Client IP address.
     */
    function get_ip(){
        $client  = @$_SERVER["HTTP_CLIENT_IP"];
        $forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
        $remote  = $_SERVER["REMOTE_ADDR"];
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }
        else{
            $ip = $remote;
        }
        return $ip;
    }


    /**
     * Registers the page viewed by the user. If it is first page shown in his
     * visit, it also registers the visit. 
     * 
     * @param db Connection to the database.
     * @param section Site section being visited.
     * @param id ID of the entry being visited.
     */
    function stats($db, $section, $id){

        // Get client data
        $ip = get_ip();
        $browser_data = get_browser(null, true);
        $os = $browser_data["platform"];
        $browser = $browser_data["browser"];
        $uagent = $browser_data["browser_name_pattern"];

        //If bot, do nothing
        $bot_kw = Array();
        $bot_kw[0] = "bot";
        $bot_kw[1] = "spider";
        $bot_kw[2] = "crawl";
        $bot_kw[3] = ".com";
        $bot_kw[4] = ".ru";
        $bot_kw[5] = "baidu";
        $bot_kw[6] = "survey";
        $bot_kw[7] = "scan";
        $bot_kw[8] = "feed";
        $bot_kw[9] = "bing";
        $bot_kw[10] = "yahoo";
        $bot_kw[11] = "engine";
        $bot_kw[12] = "preview";
        $bot_kw[13] = "checker";
        $bot_kw[14] = "catalog";
        $bot_kw[15] = "accelerator";
        $bot_kw[16] = "python";
        $bot_kw[14] = "qt";
        $bot_kw[15] = "webdav";
        $bot_kw[16] = "http";
        $bot_kw[17] = "url";
        $bot_kw[18] = "fake";
        $bot_kw[19] = "library";
        $bot_kw[20] = "commerce";
        $bot_kw[21] = "htmlbot";
        $bot_kw[22] = "fetch";
        $bot_kw[23] = "googleb";
        $bot_kw[24] = "facebook";
        $bot_kw[25] = "whatsapp";
        $bot_kw[26] = "pinterest";
        $bot_kw[27] = "scrapy";
        $bot_kw[28] = "libwww";
        $bot_kw[29] = "java standard library";
        $bot_kw[30] = "default browser";
        $bot_kw[31] = "twingly recon";
        $bot_kw[32] = "siteexplorer";
        $bot_kw[33] = "yandex";
        $bot_kw[34] = "wotbox";
        $bot_kw[35] = "apache synapse";
        $bot_kw[36] = "catexplorador";
        $bot_kw[37] = "internet archive";
        if (in_array(strtolower($uagent), $bot_kw)){
            return;
        }

        //Look for a visit with the same IP in the last 30 mins.
        $q = mysqli_query($db, "SELECT stat_visit.id AS visitid FROM stat_view, stat_visit WHERE visit = stat_visit.id AND stat_view.dtime > DATE_SUB(now(), INTERVAL 30 MINUTE) AND ip = '$ip' AND uagent = '$uagent';");
        if (mysqli_num_rows($q) == 0){
            mysqli_query($db, "INSERT INTO stat_visit (ip, uagent, os, browser) VALUES ('$ip', '$uagent', '$os', '$browser');");
            $q = mysqli_query($db, "SELECT stat_visit.id AS visitid FROM stat_visit WHERE ip = '$ip' AND uagent = '$uagent' ORDER BY stat_visit.id DESC LIMIT 1;");
        }
        $r = mysqli_fetch_array($q);
        $visit = $r['visitid'];
        mysqli_query($db, "INSERT INTO stat_view (visit, section, entry) VALUES ($visit, '$section', '$id');");
    }

?>
