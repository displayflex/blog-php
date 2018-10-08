<?php

session_start();

include_once __DIR__ . '/m/functions.php';

if (isAuth()) {
	logOut();
}

if (count($_POST) > 0) {
	if ($_POST['login'] == 'admin' && $_POST['password'] == 'qwerty') {
		setSessionParams();
		
		if ($_POST['remember']) {
			setCookieParams();
		}
		
		header('Location: index.php');
		exit();
	}
}

$inner = template('v_login');

echo template('v_main', [
	'title' => 'Войти на сайт',
	'content' => $inner
]);
