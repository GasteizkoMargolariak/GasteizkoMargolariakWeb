<?php
    include("../functions.php");
    $con = startdb();
    if (!checkSession($con)){
        exit (-1);
    }
    else{
        //Get year
        $year = date("Y");

        //Get data to calculate
        $section = mysqli_real_escape_string($con, $_GET["section"]);

        switch ($section){

            //Calculate header section. Only the progress window is requires
            case "header":
?>
                <h4>Paso 1: Cabecera de la p&aacute;gina de fiestas</h4>";
                Estado:
<?php
                $q = mysqli_query($con, "SELECT * FROM festival WHERE year = $year;");
                if (mysqli_num_rows($q) == 0){
?>
                    <span class='status_red'> No se ha definido una cabecera</span>
<?php
                }
                else{
                    $r = mysqli_fetch_array($q);
?>
                    <ul>
                        <li>
                            Texto castellano: <span class='status_green'>OK</span>
                        </li>
                        <li>
                            Traducci&oacute;n ingl&eacute;s:
<?php
                            if ($r['text_en'] == $r['text_es'] || strlen($r['text_en']) == 0){
?>
                                <span class='status_yellow'>NO</span>
<?php
                            }
                            else{
?>
                                <span class='status_green'>OK</span>
<?php
                            }
?>
                        </li>
                        <li>
                            Traducci&oacute;n euskera:
<?php
                            if ($r['text_eu'] == $r['text_es'] || strlen($r['text_eu']) == 0){
?>
                                <span class='status_yellow'>NO</span>
<?php
                            }
                            else{
?>
                                <span class='status_green'>OK</span>
<?php
                            }
?>
                        </li>
                        <li>
                            Cartel:
<?php
                            if (strlen($r['img']) == 0){
?>
                                <span class='status_yellow'>NO</span>
<?php
                            }
                            else{
?>
                                <span class='status_green'>OK</span>
<?php
                            }
?>
                        </li>
                    </ul>
<?php
                }
                break;

            //Calculate prices progress section.
            case "pricesprogress":
?>
                Estado:
                <ul>
                    <li>
<?php
                        $q = mysqli_query($con, "SELECT * FROM festival_day WHERE year(date) = '$year' AND price > 0;");
                        if (mysqli_num_rows($q) != 6){
?>
                            <span class='status_red'> No se han establecido precios</span>
<?php
                        }
                        else{
?>
                            "Precios establecidos: <span class='status_green'>OK</span>
<?php
                        }
?>
                    </li>
                    <li>
<?php
                        $prices_wrong = 0;
                        while ($r = mysqli_fetch_array($q)){
                            if ($r['price'] <= 0 || $r['price'] > 200){
                                $prices_wrong ++;
                            }
                        }
                        if ($prices_wrong == 0){
?>
                            Precios v&aacute;lidos: 
                            <span class='status_green'><?=(6 - $prices_wrong)?>/6</span>
<?php
                        }
                        else{
?>
                            Precios v&aacute;lidos: 
                            <span class='status_red'><?=(6 - $prices_wrong)?>/6</span>
<?php
                        }
?>
                    </li>
                    <li>
<?php
                        $q = mysqli_query($con, "SELECT id FROM festival_offer WHERE year = $year AND price > 0;");
                        if (mysqli_num_rows($q) > 0){
?>
                            N&uacute;mero de ofertas: <span class='status_green'><?=mysqli_num_rows($q)?></span>
<?php
                        }
                        else{
?>
                            N&uacute;mero de ofertas: <span class='status_yellow'><?=mysqli_num_rows($q)?></span>
<?php
                        }
?>
                    </li>
                </ul>
<?php
            break;

            //Calculate offers window
            case "pricesoffers":
?>
                <h4>Ofertas:</h4>
<?php
                $q_offer = mysqli_query($con, "SELECT * FROM festival_offer WHERE year = $year");
                if (mysqli_num_rows($q_offer) == 0){
?>
                    No se han configurado ofertas<br/>
<?php
                }
                else{
?>
                    <table id='offers'>
                        <tr>
                            <th>Castellano</th>
                            <th>Ingl&eacute;s</th>
                            <th>Euskera</th>
                            <th>D&iacute;as</th>
                            <th>Precio</th>
                            <th>Acci&oacute;n</th>
                        </tr>
<?php
                        while ($r_offer = mysqli_fetch_array($q_offer)){
                            if ($r_offer["name_en"] == $r_offer["name_es"]){
                                $en = "";
                            }
                            else{
                                $en = $r_offer["name_en"];
                            }
                            if ($r_offer["name_eu"] == $r_offer["name_es"]){
                                $eu = "";
                            }
                            else{
                                $eu = $r_offer["name_eu"];
                            }
                            if ($r_offer["description_en"] == $r_offer["description_es"]){
                                $den = "";
                            }
                            else{
                                $den = $r_offer["description_en"];
                            }
                            if ($r_offer["description_eu"] == $r_offer["description_es"]){
                                $deu = "";
                            }
                            else{
                                $deu = $r_offer["description_eu"];
                            }
?>
                            <tr>
                                <td class='large'>
                                    <input type='text' value='<?$r_offer["name_es"]?>' onchange='updateField("festival_offer", "name_es", this.value, <?=$r_offer["id"]?>, "text", false);'/><br/>
                                    <textarea onchange='updateField("festival_offer", "description_es", this.value, <?=$r_offer["id"]?>, "text", false);'><?=$r_offer["description_es"]?></textarea>
                                </td>
                                <td class='large'>
                                    <input type='text' value='<?=$en?>' onchange='updateField("festival_offer", "name_en", this.value, <?=$r_offer["id"]?>);'/><br/>
                                    <textarea onchange='updateField("festival_offer", "description_en", this.value, <?=$r_offer["id"]?>);'><?=$den?></textarea>
                                </td>
                                <td class='large'>
                                    <input type='text' value='<?=$eu?>' onchange='updateField("festival_offer", "name_eu", this.value, <?=$r_offer["id"]?>);'/><br/>
                                    <textarea onchange='updateField("festival_offer", "description_eu", this.value, <?=$r_offer["id"]?>);'><?=$deu?></textarea>
                                </td>
                                <td class='small'>
                                    <input type='number' value='<?=$r_offer["days"]?>' onchange='updateField("festival_offer", "days", this.value, <?=$r_offer["id"]?>, "number", false);'/>
                                </td>
                                <td class='small'>
                                    <input type='number' value='<?=$r_offer["price"]?>' onchange='updateField("festival_offer", "price", this.value, <?=$r_offer["id"]?>, "number", false);'/>&euro;
                                </td>
                                <td class='small'>
                                    <input type='button' value='Eliminar' onClick='if(confirm("Borrar oferta <?=$r_offer["name_es"]?>?"))deleteOffer(<?=$r_offer["id"]?>);'/>
                                </td>
                            </tr> <!-- TODO onclick?> -->
<?php
                        }
?>
                     </table>
<?php
                }
?>
                <input type='button' value='A&ntilde;adir nuevo' onClick='newOffer();'/>
<?php
                break;
            case 'schedule':
                $day = $_GET('day');
                switch($day){
                    case '25':
                        $q = mysqli_query($con, "SELECT * FROM festival_event WHERE gm = 1 AND start > str_to_date('$year-07-25 08:00:00', '%Y-%m-%d %T') AND start < str_to_date('$year-07-26 07:59:59', '%Y-%m-%d %T');");
                        break;
                    case '5':
                        break;
                    case '6':
                        break;
                    case '7':
                        break;
                    case '8':
                        break;
                    case '9':
                        break;
                    default:
                        exit(-1);
                }
                //Load schedule tables
?>
                <table>
                    <tr>
                        <th></th>
                    <tr/>
                </table>
<?php
                break;
        }
    }
?>
