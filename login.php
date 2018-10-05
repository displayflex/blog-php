<?php
	session_start();

	include_once 'functions.php';

	if (isset($_SESSION['is_auth']) || isset($_COOKIE['login'])) {
		logOut();
	}

	if (count($_POST) > 0) {
		if ($_POST['login'] == 'admin' && $_POST['password'] == 'qwerty') {
			$_SESSION['is_auth'] = true;
			
			if ($_POST['remember']) {
				setcookie('login', myHash('admin'), time() + 3600 * 24 * 7, '/');
				setcookie('password', myHash('qwerty'), time() + 3600 * 24 * 7, '/');
			}
			
			header('Location: index.php');
			exit();
		}
	}
?>

<form method="post">
	Логин<br>
	<input type="text" name="login"><br>
	Пароль<br>
	<input type="password" name="password"><br>
	<input type="checkbox" name="remember">Запомнить<br>
	<input type="submit" value="Добавить">
</form>