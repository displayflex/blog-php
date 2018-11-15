<?php

namespace forms;

use core\Forms\Form;

class PostEdit extends Form
{
	public function __construct(array $values)
	{
		$this->fields = [
			[
				'name' => 'title',
				'type' => 'text',
				'placeholder' => 'Название',
				'value' => $values['title']
			],
			[
				'name' => 'content',
				'type' => 'textarea',
				'placeholder' => 'Контент',
				'value' => $values['content']
			],
			[
				'type' => 'submit',
				'value' => 'Изменить'
			],
		];

		$this->formName = 'postEdit';
		$this->method = 'POST';
	}
}
