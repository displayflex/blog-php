<?php

namespace forms;

use core\Forms\Form;

class SignUp extends Form
{
	public function __construct()
	{
		$this->fields = [
			[
				'name' => 'login',
				'type' => 'text',
				'placeholder' => 'Введите логин',
				'class' => 'class-class' // тестовый класс - может быть удален
			],
			[
				'name' => 'password',
				'type' => 'password',
				'placeholder' => 'Введите пароль'
			],
			[
				'name' => 'submitPassword',
				'type' => 'password',
				'placeholder' => 'Повторите пароль'
			],
			[
				'type' => 'submit',
				'value' => 'Зарегистрироваться'
			],
		];

		$this->formName = 'signUp';
		$this->method = 'POST';
	}
}
