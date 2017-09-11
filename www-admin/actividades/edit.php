<?php
    include("../functions.php");
    $http_host = $_SERVER["HTTP_HOST"];
    $proto = getProtocol();
    $default_host = substr($http_host, 0, strpos($http_host, ":"));
    $server = "$proto$http_host";
    $default_server = "$proto$default_host";
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
        $id = mysqli_real_escape_string($con, $_GET["id"]);
        $q = mysqli_query($con, "SELECT * FROM activity WHERE id = $id");
        if (mysqli_num_rows($q) != 1){
            header("Location: /actividades/");
            exit (-1);
        }
        else{
            $r = mysqli_fetch_array($q);
?>
<html>
    <head>
        <meta content='text/html; charset=windows-1252' http-equiv='content-type'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title><?=$r["title_es"]?> - Administracion</title>
        <!-- CSS files -->
        <link rel='stylesheet' type='text/css' href='/css/ui.css'/>
        <link rel='stylesheet' type='text/css' href='/css/actividades.css'/>
        <!-- CSS for mobile version -->
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/ui.css'/>
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/actividades.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='/script/ui.js'></script>
        <script type='text/javascript' src='/ckeditor/ckeditor.js'></script>
        <script type='text/javascript' src='script.js'></script>
    </head>
    <body>
<?php
        include("../toolbar.php");
?>
        <div id='content'>
            <div class='section'>
                <h3 class='section_title'>Editar informaci&oacute;n</h3>
                <div class='entry'>
                    <h4>Detalles</h4>
                    Fecha:
                    <input id='date' type='text' placeholder='yyyy/mm/dd' length='10' onChange='updateActivityDate("<?=$server?>", <?=$id?>, this);'/>
                    &nbsp;&nbsp;&nbsp;&nbsp;<br class='mobile'/>
                    Ciudad:
                    <input id='city' type='text' placeholder='Ciudad' lendth='32' value='<?=$r["city"]?>' onChange='dbUpdate("<?=$server?>", "activity", "city", "VARCHAR", <?=$id?>, this);'/>
                    <div id='lang_tabs'>
                        <table>
                            <tr>
                                <td class='pointer lang_tabs_active' id='lang_tab_es' onclick='showLanguage("es");'>
                                    Castellano
                                </td>
                                <td class='pointer' id='lang_tab_eu' onclick='showLanguage("eu");'>
                                    Euskera
                                </td>
                                <td class='pointer' id='lang_tab_en' onclick='showLanguage("en");'>
                                    Ingl&eacute;s
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id='content_lang_es' class='activity_add_language'>
                        <h4>T&iacute;tulo (castellano)</h4>
                        <input name='title' placeholder='T&iacute;tulo' type='text' value='<?=$r["title_es"]?>' onChange='dbUpdate("<?=$server?>", "activity", "title_es", "VARCHAR", <?=$id?>, this);'/>
                        <br/>
                        <h4>Texto (castellano)</h4>
                        <textarea name='text_es' id='text_es' placeholder='Texto' onChange='dbUpdate("<?=$server?>", "activity", "text_es", "VARCHAR", <?=$id?>, this);'><?=$r["text_es"]?></textarea>
                        <script>CKEDITOR.replace('text_es');</script>
                        <br/>
                        <h4>Texto para despu&eacute;s (castellano)<h4>
                        <textarea name='after_es' id='after_es' placeholder='Texto para despu&eacute;s' onChange='dbUpdate("<?=$server?>", "activity", "after_es", "VARCHAR", <?=$id?>, this);'><?=$r["after_es"]?></textarea>
                        <script>CKEDITOR.replace('after_es');</script>
                    </div>
                    <div id='content_lang_eu' class='activity_add_language' style='display:none;'>
                        <h4>T&iacute;tulo (euskera)</h4>
                        <input name='title' placeholder='T&iacute;tulo' type='text' value='<?=$r["title_eu"]?>' onChange='dbUpdate("<?=$server?>", "activity", "title_eu", "VARCHAR", <?=$id?>, this);'/>
                        <br/>
                        <h4>Texto (euskera)</h4>
                        <textarea name='text_eu' id='text_eu' placeholder='Texto' onChange='dbUpdate("<?=$server?>", "activity", "text_eu", "VARCHAR", <?=$id?>, this)'><?=$r["text_eu"]?></textarea>
                        <script>CKEDITOR.replace('text_eu');</script>
                        <br/>
                        <h4>Texto para despu&eacute;s (euskera)</h4>
                        <textarea name='after_eu' id='after_eu' placeholder='Texto para despu&eacute;s' onChange='dbUpdate("<?=$server?>", "activity", "after_eu", "VARCHAR", <?=$id?>, this);'><?=$r["after_eu"]?></textarea>
                        <script>CKEDITOR.replace('after_eu');</script>
                    </div>
                    <div id='content_lang_en' class='activity_add_language' style='display:none;'>
                        <h4>T&iacute;tulo (ingl&eacute;s)</h4>
                        <input name='title' placeholder='T&iacute;tulo' type='text' value='<?=$r["title_en"]?>' onChange='dbUpdate("<?=$server?>", "activity", "title_en", "VARCHAR", <?=$id?>, this);'/>
                        <br/>
                        <h4>Texto (ingl&eacute;s)</h4>
                        <textarea name='text_en' id='text_en' placeholder='Texto' onChange='dbUpdate("<?=$server?>", "activity", "text_en", "VARCHAR", <?=$id?>, this);'><?=$r["text_en"]?></textarea>
                        <script>CKEDITOR.replace('text_en');</script>
                        <br/>
                        <h4>Texto para despu&eacute;s (ingl&eacute;s)</h4>
                        <textarea name='after_en' id='after_en' placeholder='Texto para despu&eacute;s' onChange='dbUpdate("<?=$server?>", "activity", "after_en", "VARCHAR", <?=$id?>, this);'><?=$r["after_en"]?></textarea>
                        <script>CKEDITOR.replace('after_en');</script>
                    </div>
                </div> <!-- .entry -->
                <div class='entry'>
                    <h3>Itinerario</h3>
<?php
                    $q_i = mysqli_query($con, "SELECT * FROM activity_itinerary WHERE activity = $id ORDER BY start;");
                    $q_p = mysqli_query($con, "SELECT id, name_es FROM place ORDER by name_es");
                    $q_r = mysqli_query($con, "SELECT id, name FROM route;");
                    while ($r_i = mysqli_fetch_array($q_i)){
?>
                        <div class='itinerary'>
                            Inicio:
                            <input type='text' class='time' length='5' value='<?=substr($r_i["start"], 11, 5)?>' placeholder='HH:MM' onchange='updateItineraryStart("<?=$server?>", <?=$r_i["id"]?>, this)'/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fin (opcional):
                            <input type='text' class='time' length='5' value='<?=substr($r_i["end"], 11, 5)?>' placeholder='HH:MM' onchange='updateItineraryEnd("<?=$server?>", <?=$r_i["id"]?>, this)'/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br class='mobile'/>Localizaci&oacute;n:
                            <select onChange='togglePlaceRoute(<?=$r_i["id"]?>, this);'>
                                <option value='place' selected>Un lugar</option>
                                <option value='route'>Recorrido (opcional)</option>
                            </select>
                            <select id='select_itinerary_<?=$r_i["id"]?>_place' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "place", "NUMBER", <?=$r_i["id"]?>, this);'>
                                <option value=''></option>
<?php
                                mysqli_data_seek($q_p, 0);
                                while ($r_p = mysqli_fetch_array($q_p)){
                                    $str_sel = "";
                                    if ($r_i["place"] == $r_p["id"]){
                                        $str_sel = "selected";
                                    }
?>
                                    <option value='<?=$r_p["id"]?>' <?=$str_sel?>><?=$r_p["name_es"]?></option>
<?php
                                }
?>
                            </select>
                            <select id='select_itinerary_<?=$r_i["id"]?>_route' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "route", "NUMBER", <?=$r_i["id"]?>, this);' style='display:none;'>
                                <option value=''></option>
<?php
                                mysqli_data_seek($q_r, 0);
                                while ($r_r = mysqli_fetch_array($q_r)){
                                    $str_sel = "";
                                    if ($r_i["route"] == $r_r["id"]){
                                        $str_sel = "selected";
                                    }
?>
                                    <option value='<?=$r_r["id"]?>' <?=$str_sel?>><?=$r_r["name"]?></option>
<?php
                                }
?>
                            </select>
                            <br/>
                            <table>
                                <tr>
                                    <td class='itinerary_language'>Castellano<br/>(obligatorio)</td>
                                    <td class='itinerary_name'>
                                        <input class='itinerary_name' type='text' value='<?=$r_i["name_es"]?>' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "name_es", "VARCHAR", <?=$r_i["id"]?>, this);'/>
                                    </td>
                                    <td class='itinerary_description'>
                                        <textarea class='itinerary_description' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "description_es", "VARCHAR", <?=$r_i["id"]?>, this);'><?=$r_i["description_es"]?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='itinerary_language'>Euskera<br/>(opcional)</td>
                                    <td class='itinerary_name'>
                                        <input class='itinerary_name' type='text' value='<?=$r_i["name_eu"]?>' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "name_eu", "VARCHAR", <?=$r_i["id"]?>, this);'/>
                                    </td>
                                    <td class='itinerary_description'>
                                        <textarea class='itinerary_description' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "description_eu", "VARCHAR", <?=$r_i["id"]?>, this);'><?=$r_i["description_eu"]?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='itinerary_language'>Ingl&eacute;s<br/>(opcional)</td>
                                    <td class='itinerary_name'>
                                        <input class='itinerary_name' type='text' value='<?=$r_i["name_en"]?>' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "name_en", "VARCHAR", <?=$r_i["id"]?>, this);'/>
                                    </td>
                                    <td class='itinerary_description'>
                                        <textarea class='itinerary_description' onChange='dbUpdate("<?=$server?>", "activity_itinerary", "description_en", "VARCHAR", <?=$r_i["id"]?>, this);'><?=$r_i["description_en"]?></textarea>
                                    </td>
                                </tr>
                            </table>

                        </div> <!-- .itinerary -->
<?php
                    }
?>
                </div>
            </div> <!-- .section -->
        </div> <!-- #content -->
    </body>
</html>
<?php
        }
    }
?>
