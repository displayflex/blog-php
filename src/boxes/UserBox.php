<?php

namespace ftwr\blogphp\boxes;

use ftwr\blogphp\core\Container;
use ftwr\blogphp\core\User;


class UserBox implements RegisterBoxInterface
{
	public function register(Container $container)
	{
		$container->service('user', function () use ($container) {
			return new User(
				$container->fabricate('modelsFactory', 'User'),
				$container->fabricate('modelsFactory', 'Session')
			);
		});
	}
}
