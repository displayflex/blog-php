<?php

if (isAuth()) {
	logOut();
}

if (count($_POST) > 0) {
	if ($_POST['login'] == 'admin' && $_POST['password'] == 'qwerty') {
		setSessionParams();
		
		if ($_POST['remember']) {
			setCookieParams();
		}
		
		$msg = '';
		$login = '';
		$password = '';

		header('Location: index.php');
		exit();
	} else {
		$msg = "Неверный логин или пароль!";
		$login = $_POST['login'];
		$password = $_POST['password'];
	}
}

$inner = template('v_login', [
	'msg' => $msg,
	'login' => $login,
	'password' => $password
]);

$title = 'Войти на сайт';
