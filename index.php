<?php
	
	$list = scandir('data');
	
	foreach ($list as $fname) {
		if (is_file("data/$fname")) {
			echo "<a href=\"post.php?fname=$fname\">$fname</a> <a href=\"edit.php?fname=$fname\">&#9998</a><br>";
		}
	}
?>

<a href="add.php">Добавить</a>