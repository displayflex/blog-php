<?php
	session_start();

	if (isset($_SESSION['is_auth'])) {
		unset($_SESSION['is_auth']);
	}

	if (isset($_COOKIE['login'])) {
		setcookie('login', '', 1, '/');
		setcookie('password','', 1, '/');
	}

	if (count($_POST) > 0) {
		if ($_POST['login'] == 'admin' && $_POST['password'] == 'qwerty') {
			$_SESSION['is_auth'] = true;
			
			if ($_POST['remember']) {
				setcookie('login', hash('sha256', 'admin'), time() + 3600 * 24 * 7, '/');
				setcookie('password', hash('sha256', 'qwerty'), time() + 3600 * 24 * 7, '/');
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