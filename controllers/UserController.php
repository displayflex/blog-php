<?php

namespace controllers;

use models\Auth;

class UserController extends BaseController
{
	public function loginAction()
	{
		if (Auth::check()) {
			Auth::logOut();
		}
		
		if ($this->request->isPOST()) {
			$login = $this->request->getPOST('login');
			$password = $this->request->getPOST('password');

			if ($login === 'admin' && $password === 'qwerty') {
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
				$msg = "Неверный логин или пароль!";
			}
		}

		$this->title .= ' | Войти на сайт';

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