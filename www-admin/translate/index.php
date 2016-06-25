<?php
	include("../functions.php");
	$con = startdb();
?>
<html>
	<head>
	</head>
	<body>
		<?php
			$res_post = mysqli_query($con, "SELECT id FROM post WHERE text_en = text_es OR text_eu = text_es OR text_en IS NULL OR text_eu IS null;");
			$res_activity = mysqli_query($con, "SELECT id FROM activity WHERE text_en = text_es OR text_eu = text_es OR text_en IS NULL OR text_eu IS null;");
			$res_gallery = mysqli_query($con, "SELECT id FROM photo WHERE description_en = description_es OR description_eu = description_es OR description_en IS NULL OR description_eu IS null;");
		?>
		<ul>
			<li><a href="post/">Traducir posts</a> (<?php echo mysqli_num_rows($res_post); ?> posts sin traduccion)</li>
			<li><a href="activities/">Traducir actividades</a> (<?php echo mysqli_num_rows($res_activity); ?> actividades sin traduccion)</li>
			<li><a href="gallery/">Traducir fotos</a> (<?php echo mysqli_num_rows($res_gallery); ?> fotos sin traduccion)</li>
		</ul>
	</body>
</html>
 
