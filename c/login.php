<?php

use m\Auth;
use core\Templater;

if (Auth::check()) {
	Auth::logOut();
}

if (count($_POST) > 0) {
	if ($_POST['login'] == 'admin' && $_POST['password'] == 'qwerty') {
		Auth::setSessionParams();
		
		if ($_POST['remember']) {
			Auth::setCookieParams();
		}
		
		$msg = '';
		$login = '';
		$password = '';

		header("Location: " . ROOT);
		exit();
	} else {
		$msg = "Неверный логин или пароль!";
		$login = $_POST['login'];
		$password = $_POST['password'];
	}
}

$inner = Templater::build('v_login', [
	'msg' => $msg,
	'login' => $login,
	'password' => $password
]);

$title = 'Войти на сайт';
