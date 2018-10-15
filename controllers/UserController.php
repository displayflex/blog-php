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
		
		if (count($_POST) > 0) {
			if ($_POST['login'] == 'admin' && $_POST['password'] == 'qwerty') {
				Auth::setSessionParams();
				
				if ($_POST['remember']) {
					Auth::setCookieParams();
				}
				
				$msg = '';
				$login = '';
				$password = '';
		
				header("Location: " . ROOT);
				exit();
			} else {
				$msg = "Неверный логин или пароль!";
				$login = $_POST['login'];
				$password = $_POST['password'];
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