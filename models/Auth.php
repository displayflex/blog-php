<?php

namespace models;

class Auth
{
	public static function check()
	{
		$isAuth = false;

		if (isset($_SESSION['is_auth']) && $_SESSION['is_auth']) {
			$isAuth = true;
		} elseif (isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
			if ($_COOKIE['login'] == self::myHash(ADMIN_LOGIN) && $_COOKIE['password'] == self::myHash(ADMIN_PASSWORD)) {
				$_SESSION['is_auth'] = true;
				$isAuth = true;
			}
		}

		return $isAuth;
	}

	public static function logOut()
	{
			unset($_SESSION['is_auth']);
			setcookie('login', '', 1, '/');
			setcookie('password','', 1, '/');
	}

	public static function setCookieParams()
	{
		setcookie('login', self::myHash(ADMIN_LOGIN), time() + 3600 * 24 * 7, '/');
		setcookie('password', self::myHash(ADMIN_PASSWORD), time() + 3600 * 24 * 7, '/');
	}
	
	public static function setSessionParams()
	{
		$_SESSION['is_auth'] = true;
	}
	
	private function myHash($str)
	{
		return hash('sha256', $str . SALT);
	}
}