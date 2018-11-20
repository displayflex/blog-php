<?php

namespace core\Boxes;

use core\Container;
use core\DBDriver;
use core\DBConnector;

class DBDriverBox implements RegisterBoxInterface
{
	public function register(Container $container)
	{
		$container->service('dBDriver', function () {
			return new DBDriver(DBConnector::getConnect());
		});
	}
}
