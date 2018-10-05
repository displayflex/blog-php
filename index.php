<?php

	session_start();
	
	include_once 'functions.php';

	if ($_GET['log'] == 'out') {
		if (isset($_SESSION['is_auth'])) {
			unset($_SESSION['is_auth']);
		}
	
		if (isset($_COOKIE['login'])) {
			setcookie('login', '', 1, '/');
			setcookie('password','', 1, '/');
		}

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

<br>
<?php if ($isAuth): ?>
<a href="add.php">Добавить</a>
<br>
<a href="index.php?log=out">Выйти</a>
<?php else :?>
<a href="login.php">Войти</a>
<?php endif; ?>