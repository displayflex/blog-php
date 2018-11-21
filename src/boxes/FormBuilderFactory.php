<?php

namespace ftwr\blogphp\boxes;

use ftwr\blogphp\core\Container;
use ftwr\blogphp\core\forms\FormBuilder;

class FormBuilderFactory implements RegisterBoxInterface
{
	public function register(Container $container)
	{
		$container->factory('formBuilderFactory', function ($form) {
			return new FormBuilder($form);
		});
	}
}
