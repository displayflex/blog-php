<?php

include_once 'config.php';

function checkTitle($title)
{
	return preg_match(NOT_ALLOWED_IN_TITLE, $title);
}

function myHash($str)
{
	return hash('sha256', $str . SALT);
}

function isAuth()
{
	$isAuth = false;

	if (isset($_SESSION['is_auth']) && $_SESSION['is_auth']) {
		$isAuth = true;
	} elseif (isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
		if ($_COOKIE['login'] == myHash('admin') && $_COOKIE['password'] == myHash('qwerty')) {
			$_SESSION['is_auth'] = true;
			$isAuth = true;
		}
	}

	return $isAuth;
}

function logOut()
{
		unset($_SESSION['is_auth']);
		setcookie('login', '', 1, '/');
		setcookie('password','', 1, '/');
}

