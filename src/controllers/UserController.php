<?php

namespace ftwr\blogphp\controllers;

use ftwr\blogphp\core\Config;
use ftwr\blogphp\core\exceptions\ModelIncorrectDataException;
use ftwr\blogphp\core\exceptions\ValidatorException;
use ftwr\blogphp\core\exceptions\UnauthorizedException;
use ftwr\blogphp\forms\SignUp;
use ftwr\blogphp\forms\SignIn;

class UserController extends BaseController
{
	const MSG_WRONG_LOGIN_DATA = 'Неверное имя пользователя или пароль!';

	public function signUpAction()
	{
		$form = new SignUp();
		$formBuilder = $this->container->fabricate('formBuilderFactory', $form);

		if ($this->request->isPOST()) {
			$login = htmlspecialchars($this->request->getPost('login'));
			$password = htmlspecialchars($this->request->getPost('password'));
			$submitPassword = htmlspecialchars($this->request->getPost('submitPassword'));
			$user = $this->container->getService('user');

			try {
				$user->signUp($form->handleRequest($this->request));
				$this->redirect(Config::ROOT);
			} catch (ValidatorException $e) {
				$msg = $e->getError();
			} catch (ModelIncorrectDataException $e) {
				$form->addErrors($e->getErrors());
			}
		}

		$this->title .= Config::REGISTRATION_SUBTITLE;
		$this->content = $this->build(
			'v_sign-up',
			[
				'msg' => $msg ?? '',
				'formBuilder' => $formBuilder
			]
		);
	}

	public function signInAction()
	{
		$form = new SignIn();
		$formBuilder = $this->container->fabricate('formBuilderFactory', $form);

		if ($this->request->isPOST()) {
			$login = htmlspecialchars($this->request->getPost('login'));
			$password = htmlspecialchars($this->request->getPost('password'));
			$user = $this->container->getService('user');
			$user->logOut();

			try {
				$user->signIn($form->handleRequest($this->request));
				$this->redirect(Config::ROOT);
			} catch (ModelIncorrectDataException $e) {
				$form->addErrors($e->getErrors());
			} catch (UnauthorizedException $e) {
				$msg = $e->getError();
			}
		}

		$this->title .= Config::LOGIN_SUBTITLE;
		$this->content = $this->build(
			'v_sign-in',
			[
				'msg' => $msg ?? '',
				'formBuilder' => $formBuilder
			]
		);
	}

	public function signOutAction()
	{
		$user = $this->container->getService('user');
		$user->logOut();
		$this->redirect(Config::ROOT);
	}
}
