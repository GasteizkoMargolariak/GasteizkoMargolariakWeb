<?php
	include("../../functions.php");
	$con = startdb();
?>
<html>
	<head>
	</head>
	<body>
		<table>
			<tr>
				<th>Post</th>
				<th>Euskera</th>
				<th>Ingles</th>
				<th> </th>
			</tr>
			<?php
				$res = mysqli_query($con, "SELECT id, title_es, text_es, text_eu, text_en FROM post;");
				while ($row = mysqli_fetch_array($res)){
					echo "<tr><td>$row[title_es]</td>\n";
					if ($row['text_eu'] == $row['text_es'] || strlen($row['text_eu']) == 0)
						echo "<td></td>\n";
					else
						echo "<td>Si</td>\n";
					if ($row['text_en'] == $row['text_es'] || strlen($row['text_en']) == 0)
						echo "<td></td>\n";
					else
						echo "<td>Si</td>\n";
					echo "<td><a href='post.php?id=$row[id]'>Traducir</a>\n";
				}
			?>
		</table>
	</body>
</html>
 
