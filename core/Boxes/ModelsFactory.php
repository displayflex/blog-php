<?php

namespace core\Boxes;

use core\Container;
use core\Validator;
use core\Exceptions\ErrorNotFoundException;

class ModelsFactory implements RegisterBoxInterface
{
	const MSG_ERROR = 'Ошибка 404!';

	public function register(Container $container)
	{
		$container->factory('modelsFactory', function ($name) use ($container) {
			$model = sprintf('\\models\\%sModel', $name);

			if (!class_exists($model)) {
				throw new ErrorNotFoundException(self::MSG_ERROR);
			} // ?

			return new $model(
				$container->getService('dBDriver'),
				new Validator
			);
		});
	}
}
