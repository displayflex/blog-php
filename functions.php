<?php

function checkTitle($title)
{
	return preg_match("/^[0-9a-z-]+$/i", $title);
}

function isAuth()
{
	$isAuth = false;

	if (isset($_SESSION['is_auth']) && $_SESSION['is_auth']) {
		$isAuth = true;
	} elseif (isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
		if ($_COOKIE['login'] == hash('sha256', 'admin') && $_COOKIE['login'] == hash('sha256', 'admin')) {
			$_SESSION['is_auth'] = true;
			$isAuth = true;
		}
	}

	return $isAuth;
}