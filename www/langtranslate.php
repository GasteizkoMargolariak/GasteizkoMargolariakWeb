<?php
	include include("lang/lang_es.php");
	$lng_es = $lng;
	include include("lang/lang_en.php");
	$lng_en = $lng;
	include include("lang/lang_eu.php");
	$lng_eu = $lng;
	$total = count($lng_es);
	echo "<table style='border:0.1em solid #000000; border-collapse:collapse'>\n";
	echo "<tr><th style='border:0.1em solid #000000; width:8em;'>Code</th><th style='border:0.1em solid #000000;'>es</th><th style='border:0.1em solid #000000;display:none;'>en</th><th style='border:0.1em solid #000000;'>eu</th><tr>\n";
	for ($i = 0; $i < count($lng_es); $i++){
		echo "<tr><td style='padding:0em; border:0.1em solid #000000; width:8em; font-size:60%; font-weight:bold; word-break: break-all;'>" . array_search(array_values($lng_es)[$i], $lng_es, true) . "</td><td style='padding:-0.3em 0.1em -0.3em 0.1em; border:0.1em solid #000000;line-height: 110%;font-size:90%;'>" . array_values($lng_es)[$i] . "</td><td style='adding:-0.3em 0.1em -0.3em 0.1em; border:0.1em solid #000000;display:none;'>" . array_values($lng_en)[$i] . "</td><td style='padding:adding:-0.3em 0.1em -0.3em 0.1em;; border:0.1em solid #000000;line-height: 110%;font-size:90%;'>" . array_values($lng_eu)[$i] . "</td></tr>";
	}
	echo "</table>";
?>
