<?php

namespace forms;

use core\Forms\Form;

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
