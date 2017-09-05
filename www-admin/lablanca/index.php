<?php
    include("../functions.php");
    $proto = getProtocol();
    $http_host = $_SERVER['HTTP_HOST'];
    $default_host = substr($http_host, 0, strpos($http_host, ':'));
    $server = $proto . $http_host;
    $server_default = $proto . $default_host;
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
    //Get year
    $year = date("Y");
?>
<html>
    <head>
        <meta content='text/html; charset=windows-1252' http-equiv='content-type'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title>Preparar las fiestas de <?php echo $year; ?> - Administracion</title>
        <!-- CSS files -->
        <link rel='stylesheet' type='text/css' href='/css/ui.css'/>
        <link rel='stylesheet' type='text/css' href='/css/lablanca.css'/>
        <!-- CSS for mobile version -->
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/ui.css'/>
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/lablanca.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='script.js'></script>
        <script type='text/javascript' src='/script/ui.js'></script>
        <script src='../ckeditor/ckeditor.js'></script>
    </head>
    <body onLoad='calculate("all");'>
<?php
        include('../toolbar.php');
?>
        <div id='content'>
            <div class='section' id='section_header'>
                <h3 class='section_title'>Paso 1: Cabecera de la p&aacute;gina de fiestas</h3>
                <div class='entry status' id='status_header'>
                    ...calculando...
                </div>
                <div class='entry' id='entry_header'>
                    <div id='lang_tabs''>
                        <table>
                                <tr>
                                    <td class="pointer lang_tabs_active" id="lang_tab_es" onclick="showLanguage('es');">
                                        Castellano
                                    </td>
                                    <td class="pointer" id="lang_tab_eu" onclick="showLanguage('eu');">
                                        Euskera
                                    </td>
                                    <td class="pointer" id="lang_tab_en" onclick="showLanguage('en');">
                                        Ingl&eacute;s
                                    </td>
                                </tr>
                        </table>
                    </div>
                    <div id="content_lang_es" class="festival_add_language">
<?php
                        $q = mysqli_query($con, "SELECT * FROM festival WHERE year = $year;");
                        $r = mysqli_fetch_array($q);
?>
                        <textarea id='text_es' onChange='updateField("festival", "text_es", this.value, <?=$r["id"]?>, "text", false);calculate("header");'><?=$r["text_es"]?></textarea>
                        <script>
                            CKEDITOR.replace('text_es');
                        </script>
                    </div>
                    <div id="content_lang_en" class="festival_add_language" style="display:none;">
                        <textarea id='text_en' onChange='updateField("festival", "text_en", this.value, <?=$r["id"]?>);calculate("header");'><?=$r["text_en"]?></textarea>
                        <script>
                            CKEDITOR.replace('text_en');
                        </script>
                    </div>
                    <div id="content_lang_eu" class="festival_add_language" style="display:none;">
                        <textarea id='text_eu' onChange='updateField("festival", "text_eu", this.value, <?=$r["id"]?>);calculate("header");'><?=$r["text_eu"]?></textarea>
                        <script>
                            CKEDITOR.replace('text_eu');
                        </script>
                    </div>
                    <div id='header_image'>
                        Cartel:<br/>
<?php
                        if (mysqli_num_rows($q) > 0){
                            if (strlen($r['img']) == 0){
?>
                                <img id='header_img' src='/img/lablanca/header-null.png'/>
                                <br/>
<?php
                            }
                            else{
?>
                                <img id='header_img' src='<?=$server_default?>/img/fiestas/miniature/<?=$r["img"]?>'/>
                                <br/>
<?php
                            }
                        }
                        else{
?>
                            <img id='header_img' src='<?=$server_default?>/img/fiestas/miniature/<?=$r["img"]?>'/>
                            <br/>
<?php
                        }
?>
                        <input type='file' id='header_img_selector' onChange='uploadHeaderImage(event, <?=$r["id"]?>);calculate("header");'/>
                    </div>
                </div>
            </div>
            <br/><br/>
            <div class='section' id='section_prices'>
                <h3 class="section_title">Paso 2: Seleccionar precios:</h4>
                <div class='entry status' id='status_prices'>
                    ...calculando...
                </div>
                <div class='entry' id='entry_prices'>
<?php
                    $q = mysqli_query($con, "SELECT * FROM festival_day WHERE year(date) = '$year' AND price > 0;");
                    if (mysqli_num_rows($q) != 6){
?>
                        <input type='button' value='A&ntilde;adir precios'/> <!-- TODO onclick -->
                        <input type='button' value='Utilzar precios del a&ntilde;o pasado'/> <!-- TODO onclick -->
<?php
                    }
                    else{
?>
                        <h4>D&iacute;as sueltos:</h4>
                        <table id='prices'>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombres</th
                                <th>Precio</th>
                            </tr>
<?php
                            $dates = ["25 Jul", "5 Ago", "6 Ago", "7 Ago", "8 Ago", "9 Ago"];
                            $i = 0;
                            while ($r = mysqli_fetch_array($q)){
                                if ($r['name_en'] == $r['name_es']){
                                    $en = '';
                                }
                                else{
                                    $en = $r['name_en'];
                                }
                                if ($r['name_eu'] == $r['name_es']){
                                    $eu = '';
                                }
                                else{
                                    $eu = $r['name_eu'];
                                }
?>
                                <tr>
                                    <td><?=$dates[$i]?></td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>Castellano:</td>
                                                <td>
                                                    <input type='text' value='<?=$r["name_es"]?>' onchange='updateField("festival_day", "name_es", this.value, <?=$r["id"]?>);'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Euskera:</td>
                                                <td>
                                                    <input type='text' value='<?=$r["name_en"]?>' onchange='updateField("festival_day", "name_en", this.value, <?=$r["id"]?>);'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Ingl&eacute;s:</td>
                                                <td>
                                                    <input type='text' value='<?=$r["name_eu"]?>' onchange='updateField("festival_day", "name_eu", this.value, <?=$r["id"]?>);'/>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <input type='number' value='<?=$r["price"]?>' onchange='updateField("festival_day", "price", this.value, <?=$r["id"]?>, "number", false);'/>&euro;
                                    </td>
                                </tr>
<?php
                                $i = $i + 1;
                            }
?>
                        </table>
                        <div id='prices_offers'>...calculando...</div>
<?php
                    }
?>
                </div>
            </div>
            <br/><br/>
            <div class='section' id='section_schedule'>
                <h3 class='section_title'>Paso 3: Establecer programa:</h4>
                <div class='entry status' id='status_schedule'>
                    ...calculando...
                </div>
                <div class='entry entry_schedule' id='entry_schedule_25'>
                    <h3><img src='/img/misc/slid-right.png' onClick='expandSchedule(25);'/>25 de julio</h4>
                    <div id='list_schedule_25' class='list_schedule'>
                    </div>
                    Nuevo:
                    <table class='new_event'>
                        <tr class='title'>
                            <th>Castellano</th>
                            <th>Euskera</th>
                            <th>Ingl&eacute;s</th>
                        </tr>
                        <tr>
                            <td>
                                <input type='text' id='new_event_25_name_es' placeholder='Nombre'/><br/>
                                <textarea id='new_event_25_description_es' placeholder='Descripcion'></textarea>
                            </td>
                            <td>
                                <input type='text' id='new_event_25_name_en' placeholder='Nombre'/><br/>
                                <textarea id='new_event_25_description_en' placeholder='Descripcion'></textarea>
                            </td>
                            <td>
                                <input type='text' id='new_event_25_name_eu' placeholder='Nombre'/><br/>
                                <textarea id='new_event_25_description_eu' placeholder='Descripcion'></textarea>
                            </td>
                        </tr>
                        <tr class='title'>
                            <th>Inicio - Fin</th>
                            <th>Organiza</th>
                            <th>Lugar</th>
                        </tr>
                        <tr>
                            <td>
                                <input type='number' value='00' id='new_event_25_start_h'/>:
                                <input type='number' value='00' id='new_event_25_start_m'/> --
                                <input type='number' id='new_event_25_end_h'/>:
                                <input type='number' id='new_event_25_end_m'/>
                            </td>
                            <td>
                                <select id='new_event_25_host' class='new_event_host'>
                                    <option value='-2' selected='selected'>SELECCIONA...</option>
                                    <option value='-1'>A&Ntilde;ADIR NUEVO...</option>
<?php
                                    $q = mysqli_query($con, "SELECT * FROM people;");
                                    while ($r = mysqli_fetch_array($q)){
?>
                                        <option value='<?=$r["id"]?>'><?=$r["name_es"]?></option>
<?php
                                    }
?>
                                </select>
                            </td>
                            <td>
                                <select id='new_event_25_host' class='new_event_host'>
                                    <option value='-2' selected='selected'>SELECCIONA...</option>
                                    <option value='-1'>A&Ntilde;ADIR NUEVO...</option>
<?php
                                    $q = mysqli_query($con, "SELECT * FROM place;");
                                    while ($r = mysqli_fetch_array($q)){
?>
                                        <option value='<?=$r["id"]?>'><?=$r["name_es"]?> (<?=$r["address_es"]?>)</option>
<?php
                                    }
?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br/><br/>
        </div>
        <div id='screen_cover'></div>
        <div id='form_new_offer' class='section'>
            <h3>A&ntilde;adir oferta</h3>
            <div class='entry'>
                <table>
                    <tr>
                        <th>Castellano</th>
                        <th>Ingl&eacute;s</th>
                        <th>Euskera</th>
                        <th>D&iacute;as</th>
                        <th>Precio</th>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' id='new_offer_title_es'/><br/>
                            <textarea id='new_offer_text_es'></textarea>
                        </td>
                        <td class='large'>
                            <input type='text' id='new_offer_title_en'/><br/>
                            <textarea id='new_offer_text_en'></textarea>
                        </td>
                        <td/>
                            <input type='text' id='new_offer_title_eu'/><br/>
                            <textarea id='new_offer_text_eu'></textarea>
                        </td>
                        <td class='small'>
                            <input type='number' value='1'  id='new_offer_days'/>
                        </td>
                        <td class='small'>
                            <input type='number' value='0'  id='new_offer_price'/>&euro;
                        </td>
                    </tr>
                </table>
                <div id='controls'>
                    <input type='button' value='Guardar' onClick='saveNewOffer(<?p=$year?>);'/>
                    <input type='button' value='Cancelar' onClick='cancelNewOffer();'/>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>
