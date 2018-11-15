<?php

namespace forms;

use core\Forms\Form;

class SignIn extends Form
{
	public function __construct()
	{
		$this->fields = [
			[
				'name' => 'login',
				'type' => 'text',
				'placeholder' => 'Логин'
			],
			[
				'name' => 'password',
				'type' => 'password',
				'placeholder' => 'Пароль'
			],
			[
				'name' => 'remember',
				'type' => 'checkbox',
				'label' => 'Запомнить'
			],
			[
				'type' => 'submit',
				'value' => 'Войти'
			],
		];

		$this->formName = 'signIn';
		$this->method = 'POST';
	}
}
