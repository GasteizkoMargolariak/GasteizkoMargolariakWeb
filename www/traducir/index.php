<?php
    session_start();
    $http_host = $_SERVER['HTTP_HOST'];
    include("../functions.php");
    $proto = getProtocol();
    $con = startdb();
    $server = "$proto$http_host";

    //Language
    $lang = 'es';
    include("../lang/lang_es.php");

    $cur_section = 'Traducciones';

    $l = strtolower(mysqli_real_escape_string($con, $_GET['l'])); // language
    if ($l != 'en' && $l != 'eu'){
?>
        <!DOCTYPE html>
            <html>
                <head>
                    <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
                    <meta charset="utf-8"/>
                    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
                    <title>Traducciones - Gasteizko Margolariak</title>
                    <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
                    <!-- CSS files -->
                    <style>
<?php
                            include("../css/ui.css"); 
                            include("../css/traducir.css");
?>
                    </style>
                    <!-- CSS for mobile version -->
                    <style media="(max-width : 990px)">
<?php
                            include("../css/m/ui.css"); 
                            include("../css/m/traducir.css");
?>
                    </style>
                    <!-- Script files -->
                    <script type="text/javascript">
<?php
                        include("../script/ui.js");
?>
                    </script>
                    <!-- Meta tags -->
                    <link rel="canonical" href="<?=$server?>/traducir/"/>
                    <link rel="author" href="<?=server?>"/>
                    <link rel="publisher" href="<?=server?>"/>
                    <meta name="description" content="Traduccion de contenidos de la web de Gasteizko Margolariak"/>
                    <meta property="og:title" content="Traducciones - Gasteizko Margolariak"/>
                    <meta property="og:url" content="<?=$server?>/traducir/"/>
                    <meta property="og:description" content="Traduccion de contenidos de la web de Gasteizko Margolariak"/>
                    <meta property="og:image" content="<?=$server?>/img/logo/logo.png"/>
                    <meta property="og:site_name" content="Gasteizko Margolariak"/>
                    <meta property="og:type" content="website"/>
                    <meta property="og:locale" content="es"/>
                    <meta name="twitter:card" content="summary"/>
                    <meta name="twitter:title" content="Traducciones - Gasteizko Margolariak"/>
                    <meta name="twitter:description" content="Traduccion de contenidos de la web de Gasteizko Margolariak"/>
                    <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
                    <meta name="twitter:url" content="<?$server?>/traducir/"/>
                    <meta name="robots" content="noindex nofollow"/>
                </head>
                <body>
<?php                include("../header.php"); ?>
                    <div id="content">
                        <div class='section' id='w_init'>
                            <h3 class='section_title'>Traducciones</h3>
                            <div class='entry'>
                                <a class='lang' href='<?=$server?>/traducir/eu/'>Traducir al euskera</a>
                                <a class='lang' href='<?=$server?>/traducir/en/'>Traducir al ingl&eacute;s</a>
                            </div>
                        </div>
                    </div>
                </body>
            </html>
<?php
    }
    else{
        $q_string="
        SELECT * FROM (
        SELECT * FROM (
        SELECT 'activity' AS tab, 'title' AS field, id, title_es AS es, title_eu AS eu, title_en AS en, dtime FROM activity WHERE title_es = title_XX
        UNION
        SELECT 'activity' AS tab, 'text' AS field, id, text_es AS es, text_eu AS eu, text_en AS en, dtime FROM activity WHERE text_es = text_XX
        UNION
        SELECT 'activity' AS tab, 'after' AS field, id, after_es AS es, after_eu AS eu, after_en AS en, dtime FROM activity WHERE after_es IS NOT NULL AND after_es = after_XX
        UNION
        SELECT 'activity_itinerary' AS tab, 'name' AS field, activity_itinerary.id AS id, name_es AS es, name_eu AS eu, name_en AS en, dtime FROM activity, activity_itinerary WHERE activity = activity.id AND name_es = name_XX
        UNION
        SELECT 'activity_itinerary' AS tab, 'text' AS field, activity_itinerary.id AS id, text_es AS es, text_eu AS eu, text_en AS en, dtime FROM activity, activity_itinerary WHERE activity = activity.id AND text_es IS NOT null AND text_es = text_XX
        UNION
        SELECT 'album' AS tab, 'title' AS field, id, title_es AS es, title_eu AS eu, title_en AS en, dtime FROM album WHERE title_es = title_XX
        UNION
        SELECT 'album' AS tab, 'description' AS field, id, description_es AS es, description_eu AS eu, description_en AS en, dtime FROM album WHERE description_es IS NOT NULL AND description_es = description_XX
        UNION
        SELECT 'festival' AS tab, 'text' AS field, id, text_es AS es, text_eu AS eu, text_en AS en, str_to_date(year || '-' || month(sysdate()) || '-' || day(sysdate()), '%Y-%m-%d') AS dtime FROM festival WHERE text_es = text_XX
        UNION
        SELECT 'festival' AS tab, 'summary' AS field, id, summary_es AS es, summary_eu AS eu, summary_en AS en, str_to_date(year || '-' || month(sysdate()) || '-' || day(sysdate()), '%Y-%m-%d') AS dtime FROM festival WHERE summary_es IS NOT null AND summary_es = summary_XX
        UNION
        SELECT 'festival_day' AS tab, 'name' AS field, id AS id, name_es AS es, name_eu AS eu, name_en AS en, date AS dtime FROM festival_day WHERE name_es = name_XX
        UNION
        SELECT 'festival_event' AS tab, 'title' AS field, id, title_es AS es, title_eu AS eu, title_en AS en, start AS dtime FROM festival_event WHERE title_es = title_XX
        UNION
        SELECT 'festival_event' AS tab, 'description' AS field, id, description_es AS es, description_eu AS eu, description_en AS en, start AS dtime FROM festival_event WHERE description_es IS NOT null AND description_es = description_XX
        UNION
        SELECT 'festival_offer' AS tab, 'name' AS field, id AS id, name_es AS es, name_eu AS eu, name_en AS en, str_to_date(year || '-' || month(sysdate()) || '-' || day(sysdate()), '%Y-%m-%d') AS dtime FROM festival_offer WHERE name_es = name_XX
        UNION
        SELECT 'festival_offer' AS tab, 'description' AS field, id AS id, description_es AS es, description_eu AS eu, description_en AS en, str_to_date(year || '-' || month(sysdate()) || '-' || day(sysdate()), '%Y-%m-%d') AS dtime FROM festival_offer WHERE description_es IS NOT null AND description_es = description_XX
        UNION
        SELECT 'photo' AS tab, 'title' AS field, id, title_es AS es, title_eu AS eu, title_en AS en, dtime FROM photo WHERE title_es IS NOT null AND title_es = title_XX
        UNION
        SELECT 'photo' AS tab, 'description' AS field, id, description_es AS es, description_eu AS eu, description_en AS en, dtime FROM photo WHERE description_es IS NOT null AND description_es = description_XX
        UNION
        SELECT 'place' AS tab, 'name' AS field, id AS id, name_es AS es, name_eu AS eu, name_en AS en, DATE_SUB(sysdate(), INTERVAL 7 DAY) AS dtime FROM place WHERE name_es = name_XX
        UNION
        SELECT 'place' AS tab, 'address' AS field, id AS id, address_es AS es, address_eu AS eu, address_en AS en, DATE_SUB(sysdate(), INTERVAL 7 DAY) AS dtime FROM place WHERE address_es = address_XX
        UNION
        SELECT 'post' AS tab, 'title' AS field, id, title_es AS es, title_eu AS eu, title_en AS en, dtime FROM post WHERE title_es = title_XX
        UNION
        SELECT 'post' AS tab, 'text' AS field, id, text_es AS es, text_eu AS eu, text_en AS en, dtime FROM post WHERE text_es = text_XX
        UNION
        SELECT 'sponsor' AS tab, 'name' AS field, id AS id, name_es AS es, name_eu AS eu, name_en AS en, DATE_SUB(sysdate(), INTERVAL 8 DAY) AS dtime FROM sponsor WHERE name_es = name_XX
        UNION
        SELECT 'sponsor' AS tab, 'text' AS field, id AS id, text_es AS es, text_eu AS eu, text_en AS en, DATE_SUB(sysdate(), INTERVAL 8 DAY) AS dtime FROM sponsor WHERE text_es = text_XX
        UNION
        SELECT 'sponsor' AS tab, 'address' AS field, id AS id, address_es AS es, address_eu AS eu, address_en AS en, DATE_SUB(sysdate(), INTERVAL 8 DAY) AS dtime FROM sponsor WHERE address_es = address_XX
        ) d ORDER BY dtime DESC LIMIT 100
        ) f ORDER BY rand();
        ";

        // Select language
        $q_string = str_replace('XX', $l, $q_string);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title>Traducciones - Gasteizko Margolariak</title>
        <link rel="shortcut icon" href="<?=$server?>/img/logo/favicon.ico">
        <!-- CSS files -->
        <style>
<?php 
                include("../css/ui.css"); 
                include("../css/traducir.css");
?>
        </style>
        <!-- CSS for mobile version -->
        <style media="(max-width : 990px)">
<?php
                include("../css/m/ui.css"); 
                include("../css/m/traducir.css");
?>
        </style>
        <!-- Script files -->
        <script type="text/javascript">
<?php
             include("../script/ui.js");
?>
            var table = [];
            var field = [];
            var id = [];
            var es = [];
            var en = [];
            var eu = [];
            var idx = 0;

<?php
            $q = mysqli_query($con, $q_string);
            $i = 0;
            while ($r = mysqli_fetch_array($q)){
                // Populate arrays
?>
                table[<?=$i?>] = '<?=$r['tab']?>';
                field[<?=$i?>] = '<?=$r['field']?>';
                id[<?=$i?>] = '<?=$r['id']?>';
                es[<?=$i?>] = '<?=str_replace("'", "´", $r['es'])?>';
                en[<?=$i?>] = '<?=str_replace("'", "´", $r['en'])?>';
                eu[<?=$i?>] = '<?=str_replace("'", "´", $r['eu'])?>';
<?php
                $i = $i + 1;
            }

?>

            function prepare(){
                while (es[idx].length == 0){
                    idx = idx + 1;
                }
                if (es[idx].length <= 180){
                    document.getElementById('i_original').innerHTML = es[idx];
                    document.getElementById('i_original').style.display = 'inline-block';
                    document.getElementById('i_translation').value = '';
                    document.getElementById('i_translation').style.display = 'inline-block';
                    document.getElementById('t_original').innerHTML = '';
                    document.getElementById('t_original').style.display = 'none';
                    document.getElementById('t_translation').innerHTML = '';
                    document.getElementById('t_translation').innerText = '';
                    document.getElementById('t_translation').value = '';
                    document.getElementById('t_translation').style.display = 'none';
                    document.getElementById('type').value = 'i';
                }
                else{
                    document.getElementById('i_original').innerHTML = '';
                    document.getElementById('i_original').style.display = 'none';
                    document.getElementById('i_translation').value = '';
                    document.getElementById('i_translation').style.display = 'none';
                    document.getElementById('t_original').innerHTML = es[idx];
                    document.getElementById('t_original').style.display = 'inline-block';
                    document.getElementById('t_translation').innerHTML = '';
                    document.getElementById('t_translation').innerText = '';
                    document.getElementById('t_translation').value = '';
                    document.getElementById('t_translation').style.display = 'inline-block';
                    document.getElementById('type').value = 't';
                }
            }
            
            function error(title, text){
                document.getElementById('error_title').innerHTML = title;
                document.getElementById('error_msg').innerHTML = text;
                document.getElementById('w_error').style.display = 'block';
                document.getElementById('w_error').style.opacity = 1;
            }
            
            function closeError(){
                document.getElementById('w_error').style.opacity = 0;
                setTimeout(function(){ document.getElementById('w_error').style.display = 'none'; }, 500);
            }
            
            function save(){
                if (document.getElementById('name').value.length == 0){
                    error("Introduce tu nombre.", "Es necesario un nombre para identificar de quien provienen las traducciones.");
                    return;
                }
                if (((document.getElementById('type').value == 'i') && (document.getElementById('i_translation').value.length == 0)) || ((document.getElementById('type').value == 't') && (document.getElementById('t_translation').value.length == 0))){
                    error("Introduce una traducci&oacute;n.", "No has introducido una traducci&oacute;n. Si lo prefieres, utiliza el boton rojo para saltar esta traducci&oacute;n.");
                    return;
                }
                
                // Get data
                var t = encodeURI(table[idx]);
                var f = encodeURI(field[idx]);
                var i = encodeURI(id[idx]);
                var c = "";
                var l = "<?=$l?>";
                var n = document.getElementById('name').value;
                if (document.getElementById('type').value == 'i'){
                    c = encodeURI(document.getElementById('i_translation').value);
                }
                else{
                    c = encodeURI(document.getElementById('t_translation').value);
                }
                var url = "<?=$server?>/traducir/load.php?t=" + t + "&f=" + f + "&i=" + i + "&c=" + c + "&l=" + l + "&n=" + n;
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    // I won't notify  400 status code. I dont' want to be anoying.
                    if (this.readyState == 4 && (this.status == 200 || this.status == 400)) {
                        next();
                    }
                };
                xhttp.open("GET", url, true);
                xhttp.send();
            }

            function next(){
                idx = idx + 1;
                prepare();
            }

        </script>
        <!-- Meta tags -->
        <link rel="canonical" href="<?=$server?>/traducir/"/>
        <link rel="author" href="<?=$server?>"/>
        <link rel="publisher" href="<?=$server?>"/>
        <meta name="description" content="Traduccion de contenidos de la web de Gasteizko Margolariak"/>
        <meta property="og:title" content="Traducciones - Gasteizko Margolariak"/>
        <meta property="og:url" content="<?=$server?>/traducir/"/>
        <meta property="og:description" content="Traduccion de contenidos de la web de Gasteizko Margolariak"/>
        <meta property="og:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta property="og:site_name" content="Gasteizko Margolariak"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="es"/>
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:title" content="Traducciones - Gasteizko Margolariak"/>
        <meta name="twitter:description" content="Traduccion de contenidos de la web de Gasteizko Margolariak"/>
        <meta name="twitter:image" content="<?=$server?>/img/logo/logo.png"/>
        <meta name="twitter:url" content="<?=$server?>/traducir/"/>
        <meta name="robots" content="noindex nofollow"/>
    </head>
    <body onLoad='prepare();'>
<?php     include("../header.php"); ?>
        <div id="content">
            <div class='section'>
                <h3 class='section_title'>Traducciones en 
<?php
                    if ($l == 'en'){
                        echo("ingl&eacute;s");
                    }
                    else{
                        echo("euskera");
                    }
?>
                </h3>
                <div class='entry' id='w_target'>
                    <div class='translation' id='original'>
                        <h3 class='entry_title'>Original (Castellano)</h3>
                        <span id='i_original'></span>
                        <p id='t_original'></p>
                    </div>
                    <div class='translation' id='translation'>
                        <h3 class='entry_title'>Traducci&oacute;n (
<?php
                            if ($l == 'en'){
                                echo("Ingl&eacute;s");
                            }
                            else{
                                echo("Euskera");
                            }
?>
                        )</h3>
                        <input type='text' id='i_translation'/>
                        <textarea id='t_translation'></textarea>
                    </div>
                    <input type='hidden' id='type' value='i'/>
                    <input type='hidden' id='lang' value='<?=$l?>'/>
                </div> <!-- #w_target -->
                <div class='entry'>
                    <table id='buttons'>
                        <tr>
                            <td>
                                <input type='text' id='name' placeholder='Tu nombre...'/>
                            </td>
                            <td>
                                <input type='button' id='next' value='Saltar esta' onClick='next();'/>
                            </td>
                            <td>
                                <input type='button' id='save' value='Guardar' onClick='save();'/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div> <!-- .section -->
            <br/><br/><br/>
            <div class='section' id='w_error'>
                <h3 class='section_title' id='error_title'></h3>
                <div class='entry'>
                    <table>
                        <tr>
                            <td>
                                <span id='error_msg'></span>
                                <br/><br/><br/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type='button' value='Cerrar' onClick='closeError();'>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div> <!-- #content -->
    </body>
</html>

<?php
    }  //if ($l != 'en' && $l != 'en'){  -- ELSE 
?>
