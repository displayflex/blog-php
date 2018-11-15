<?php

namespace controllers;

use core\Config;
use core\DBConnector;
use core\DBDriver;
use core\Validator;
use models\UserModel;
use models\SessionModel;
use core\User;
use core\Exceptions\ModelIncorrectDataException;
use core\Exceptions\ValidatorException;
use core\Exceptions\UnauthorizedException;
use forms\SignUp;
use forms\SignIn;
use core\Forms\FormBuilder;

class UserController extends BaseController
{
	const MSG_WRONG_LOGIN_DATA = 'Неверное имя пользователя или пароль!';

	public function signUpAction()
	{
		$form = new SignUp();
		$formBuilder = new FormBuilder($form);

		if ($this->request->isPOST()) {
			$login = htmlspecialchars($this->request->getPost('login'));
			$password = htmlspecialchars($this->request->getPost('password'));
			$submitPassword = htmlspecialchars($this->request->getPost('submitPassword'));
			$userModel = new UserModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
			$sessionModel = new SessionModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
			$user = new User($userModel, $sessionModel);

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
		$formBuilder = new FormBuilder($form);

		if ($this->request->isPOST()) {
			$login = htmlspecialchars($this->request->getPost('login'));
			$password = htmlspecialchars($this->request->getPost('password'));
			$userModel = new UserModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
			$sessionModel = new SessionModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
			$user = new User($userModel, $sessionModel);
			$user->logOut();

			try {
				$user->signIn($this->request->getPOST());
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
		$userModel = new UserModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$sessionModel = new SessionModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$user = new User($userModel, $sessionModel);
		$user->logOut();
		$this->redirect(Config::ROOT);
	}
}
