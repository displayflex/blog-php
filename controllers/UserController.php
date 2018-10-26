<?php

namespace controllers;

use core\Config;
use models\Auth;

class UserController extends BaseController
{
	const MSG_LOGIN_ERR = 'Неверный логин или пароль!';

	public function loginAction()
	{
		if (Auth::check()) {
			Auth::logOut();
		}

		if ($this->request->isPOST()) {
			$login = $this->request->getPOST('login');
			$password = $this->request->getPOST('password');

			if ($login === Config::ADMIN_LOGIN && $password === Config::ADMIN_PASSWORD) {
				Auth::setSessionParams();

				if ($this->request->getPOST('remember') !== null) {
					Auth::setCookieParams();
				}

				$msg = '';
				$login = '';
				$password = '';

				header("Location: " . Config::ROOT);
				exit();
			} else {
				$msg = self::MSG_LOGIN_ERR;
			}
		}

		$this->title .= Config::LOGIN_SUBTITLE;

		$this->content = $this->build(
			'v_login',
			[
				'msg' => $msg,
				'login' => $login,
				'password' => $password
			]
		);
	}

	public function logoutAction()
	{
		Auth::logOut();

		header("Location: " . Config::ROOT);
		exit();
	}
}
