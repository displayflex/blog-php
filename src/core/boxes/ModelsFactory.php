<?php

namespace ftwr\blogphp\core\boxes;

use ftwr\blogphp\core\Container;
use ftwr\blogphp\core\Validator;
use ftwr\blogphp\core\exceptions\ErrorNotFoundException;

class ModelsFactory implements RegisterBoxInterface
{
	const MSG_ERROR = 'Ошибка 404!';

	public function register(Container $container)
	{
		$container->factory('modelsFactory', function ($name) use ($container) {
			$model = sprintf('ftwr\\blogphp\\models\\%sModel', $name);

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
