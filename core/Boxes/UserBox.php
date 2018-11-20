<?php

namespace core\Boxes;

use core\Container;
use core\User;


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
