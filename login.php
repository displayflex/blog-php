<?php

session_start();

include_once __DIR__ . '/m/functions.php';

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

include __DIR__ . "/v/v_login.php";
