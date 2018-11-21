<?php

namespace ftwr\blogphp\forms;

use ftwr\blogphp\core\forms\Form;

class PostAdd extends Form
{
	public function __construct()
	{
		$this->fields = [
			[
				'name' => 'title',
				'type' => 'text',
				'placeholder' => 'Название',
			],
			[
				'name' => 'content',
				'type' => 'textarea',
				'placeholder' => 'Контент'
			],
			[
				'type' => 'submit',
				'value' => 'Добавить'
			],
		];

		$this->formName = 'postAdd';
		$this->method = 'POST';
	}
}
