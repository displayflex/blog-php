<?php

namespace controllers;

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

			if ($login === ADMIN_LOGIN && $password === ADMIN_PASSWORD) {
				Auth::setSessionParams();

				if ($this->request->getPOST('remember') !== null) {
					Auth::setCookieParams();
				}
				
				$msg = '';
				$login = '';
				$password = '';
		
				header("Location: " . ROOT);
				exit();
			} else {
				$msg = self::MSG_LOGIN_ERR;
			}
		}

		$this->title .= LOGIN_SUBTITLE;

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

			header("Location: " . ROOT);
			exit();
	}
}