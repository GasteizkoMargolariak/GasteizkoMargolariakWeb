<?php
    include("../functions.php");
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
?>
<html>
    <head>
        <meta content='text/html; charset=windows-1252' http-equiv='content-type'/>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>
        <title>Miembros - Administracion</title>
        <!-- CSS files -->
        <link rel='stylesheet' type='text/css' href='/css/ui.css'/>
        <link rel='stylesheet' type='text/css' href='/css/miembros.css'/>
        <!-- CSS for mobile version -->
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/ui.css'/>
        <link rel='stylesheet' type='text/css' media='(max-width : 990px)' href='/css/m/miembros.css'/>
        <!-- Script files -->
        <script type='text/javascript' src='script.js'></script>
        <script type='text/javascript' src='/script/ui.js'></script>
    </head>
    <body>
<?php
        include("../toolbar.php");
?>
        <div id='content'>
            <div class='section' id='section_table'>
                <div class='entry'>
                    Filtro: <input type='text' onkeyup='populate_table(this.value)'/>
                </div><br/>
                <div class='entry' id='entry_table'>
                    <table id='member_table'>
                        <tr>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th style='display:none;'>details</th>
                            <th>Acciones</th>
                        </tr>
<?php
                        $q = mysqli_query($con, "SELECT * FROM member;");
                        $i = 0;
                        while ($r = mysqli_fetch_array($q)){
                            if ($i % 2 == 0){
                                $row_type = "even";
                            }
                            else{
                                $row_type = "odd";
                            }
                            $i ++;
?>                            
                            <tr class='member_row_<?=$row_type>?>'>
                                <td>
                                    <?=$r["lname"]?>
<?php
                                    if ($r['lname2'] != null){
?>
                                        <?=$r["lname2"]?>
<?php
                                    }
?>
                                    , <?=$r["name"]?>
<?php
                                    if ($r['alias'] != null){
?>
                                        <span style='font-style:italic;'> - (<?=$r["alias"]?>)</span>
<?php
                                    }
?>
                                </td>
                                <td style='text-align:center;'>
<?php
                                    if ($r['phone'] != null){
?>
                                        <?=$r["phone"]?><br/>
<?php
                                    }
                                    if ($r['mail'] != null){
?>
                                        <?=$r["mail"]?>
<?php
                                    }
?>
                                </td>
                                <td style='display:none;'><?=$r[name]?> <?=$r[lname]?> <?=$r[lname2]?> <?=$r[dni]?>, <?=$r[address]?></td>
                                <td style='text-align:center;'>
                                    <a href='/miembros/miembro.php?m=$r[id]'>Ficha completa</a>
                                    <br/>
                                    <a href=''>Editar</a> <!-- TODO -->
                                </td>
                            </tr>
<?php
                        }
?>
                    </table>
                </div>
            </div>
            <div class="section" id="section_table_actions">
                <div class="entry">
                    <h3>Acciones</h3>
                    <ul>
                        <li><a href="/miembros/add/">Anadir miembro</a></li>
                        <li><a href="/miembros/add/batch.php">Anadir miembros en lote</a></li>
                    </ul>
                </div><br/>
                <div class="entry">
                    <h3>Recuerda</h3>
                    <p>Estos datos sobre los miembros de Gasteizko Margolariak son confidenciales y estan protegidos bajo la Ley de Proteccion de Datos. Se responsable al utilizarlos.</p>
                    <ul>
                        <li>No los uses para propositos personales o no relacionados con Gasteizko Margolariak</li>
                        <li>No hagas una copia de estos datos</li>
                        <li>No difundas estos a terceras personas</li>
                        <li>El acceso y la modificacion de estos datos es monitorizado por el sistema</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>
