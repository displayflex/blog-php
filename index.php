<?php

	session_start();
	
	include_once 'functions.php';

	if ($_GET['log'] == 'out') {
		logOut();
		header('Location: index.php');
		exit();
	}

	$list = scandir('data');
	$isAuth = isAuth();
	
	foreach ($list as $fname) {
		if (is_file("data/$fname")) {
			if ($isAuth) {
				echo "<a href=\"post.php?fname=$fname\">$fname</a> <a href=\"edit.php?fname=$fname\">&#9998</a><br>";
			} else {
				echo "<a href=\"post.php?fname=$fname\">$fname</a><br>";
			} 
		}
	}
?>

<?php if ($isAuth): ?>
	<br>
	<a href="add.php">Добавить</a>
	<br>
	<a href="index.php?log=out">Выйти</a>
<?php else :?>
	<br>
	<a href="login.php">Войти</a>
<?php endif; ?>