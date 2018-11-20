<?php

namespace core\Boxes;

use core\Container;
use core\Validator;
use core\Forms\FormBuilder;

class FormBuilderFactory implements RegisterBoxInterface
{
	public function register(Container $container)
	{
		$container->factory('formBuilderFactory', function ($form) {
			return new FormBuilder($form);
		});
	}
}
