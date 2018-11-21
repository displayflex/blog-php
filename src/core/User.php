<?php

namespace ftwr\blogphp\core;

use ftwr\blogphp\models\UserModel;
use ftwr\blogphp\models\SessionModel;
use ftwr\blogphp\core\exceptions\UnauthorizedException;
use ftwr\blogphp\core\exceptions\ModelIncorrectDataException;
use ftwr\blogphp\core\exceptions\ValidatorException;

class User
{
	const MSG_PASSWORDS_DO_NOT_MATCH = 'Пароли не совпадают!';
	const MSG_WRONG_PASSWORD = 'Введен неверный пароль!';
	const MSG_USER_NOT_FOUND = 'Такого пользователья не существует!';


	private $userModel;
	private $sessionModel;
	private $session;
	private $cookie;

	public function __construct(UserModel $userModel, SessionModel $sessionModel)
	{
		$this->userModel = $userModel;
		$this->sessionModel = $sessionModel;
		$this->session = new Session();
		$this->cookie = new Cookie();
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
		$this->session->set('sid', $sid);
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
				$this->session->set('sid', $sid);

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

	protected function setCookieParams(array $params)
	{
		$this->cookie->set('login', $params['login']);
		$this->cookie->set('password', $this->userModel->getHash($params['password']));
	}

	public function logOut()
	{
		$this->session->delete('sid');
		$this->cookie->delete('login');
		$this->cookie->delete('password');
	}
}
