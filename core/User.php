<?php

namespace core;

use models\UserModel;
use models\SessionModel;
use core\Exceptions\UnauthorizedException;
use core\Exceptions\ModelIncorrectDataException;
use core\Exceptions\ValidatorException;

class User
{
	const MSG_PASSWORDS_DO_NOT_MATCH = 'Пароли не совпадают!';
	const MSG_WRONG_PASSWORD = 'Введен неверный пароль!';
	const MSG_USER_NOT_FOUND = 'Такого пользователья не существует!';


	private $userModel;
	private $sessionModel;

	public function __construct(UserModel $userModel, SessionModel $sessionModel)
	{
		$this->userModel = $userModel;
		$this->sessionModel = $sessionModel;
	}

	public function signUp(array $fields)
	{
		if (!$this->comparePasswords($fields)) {
			throw new ValidatorException(self::MSG_PASSWORDS_DO_NOT_MATCH);
		}

		$this->userModel->signUp($fields);
	}

	public function signIn(array $fields)
	{
		$this->userModel->validator->execute($fields);

		if (!$this->userModel->validator->success) {
			throw new ModelIncorrectDataException($this->userModel->validator->errors);
		}

		$user = $this->userModel->getOneByLogin($fields['login']);

		if (!$user) {
			throw new UnauthorizedException(self::MSG_USER_NOT_FOUND);
		}

		if ($this->userModel->getHash($fields['password']) !== $user['password']) {
			throw new UnauthorizedException(self::MSG_WRONG_PASSWORD);
		}

		$sid = $this->generateSid();
		$this->setSessionParams($sid);
		$this->sessionModel->setDBSessionParams([
			'id_user' => $user['id'],
			'sid' => $sid,
			'created_at' => date("Y-m-d H:i:s")
		]);

		if (isset($fields['remember'])) {
			$this->setCookieParams([
				'login' => $fields['login'],
				'password' => $fields['password']
			]);
		}
	}

	public function isAuth(Request $request)
	{
		$sid = $request->getSESSION('sid');
		$login = $request->getCOOKIE('login');
		$password = $request->getCOOKIE('password');

		if (!$sid && !$login) {
			return false;
		}

		if ($this->sessionModel->getOneBySid($sid)) {
			$user = $this->userModel->getBySid($sid);

			if ($user) {
				$this->sessionModel->updateOne(
					[
						'sid' => $sid
					],
					'id_user=:id_user',
					[
						'id_user' => $user['id']
					],
					false
				);

				return true;
			}
		} elseif ($login && $password) {
			$user = $this->userModel->getOneByLogin($login);

			if ($user && $password === $user['password']) {
				$sid = $this->generateSid();
				$this->setSessionParams($sid);

				return true;
			}
		}

		return false;
	}

	protected function comparePasswords($fields)
	{
		return $fields['password'] === $fields['submitPassword'];
	}

	protected function generateSid($number = 10)
	{
		$pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$sid = '';

		for ($i = 0; $i < $number; $i++) {
			$sid .= $pattern[mt_rand(0, strlen($pattern) - 1)];
		}

		return $sid;
	}

	protected function setSessionParams($sid)
	{
		$_SESSION['sid'] = $sid;
	}

	protected function setCookieParams(array $params)
	{
		setcookie('login', $params['login'], time() + 3600 * 24 * 7, '/');
		setcookie('password', $this->userModel->getHash($params['password']), time() + 3600 * 24 * 7, '/');
	}

	public function logOut()
	{
		unset($_SESSION['sid']);
		setcookie('login', '', 1, '/');
		setcookie('password', '', 1, '/');
	}
}
