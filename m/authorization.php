<?php

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

function myHash($str)
{
	return hash('sha256', $str . SALT);
}

function setCookieParams()
{
	setcookie('login', myHash('admin'), time() + 3600 * 24 * 7, '/');
	setcookie('password', myHash('qwerty'), time() + 3600 * 24 * 7, '/');
}

function setSessionParams()
{
	$_SESSION['is_auth'] = true;
}


