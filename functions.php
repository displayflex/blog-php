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

function db_connect()
{
	static $db;
	
	if ($db === null) {
		$db = new PDO(sprintf('mysql:host=localhost;dbname=%s', DB_NAME), DB_UESRNAME, DB_PASSWORD);
		$db->exec('SET NAMES UTF8');
	}
	
	return $db;
}

function db_query($sql, $params = [])
{
	$db = db_connect();
	
	$query = $db->prepare($sql);
	$query->execute($params);
	
	db_check_error($query);
	
	return $query;
}

function db_check_error($query)
{
	$info = $query->errorInfo();

	if ($info[0] != PDO::ERR_NONE) {
		exit($info[2]);
	}
}