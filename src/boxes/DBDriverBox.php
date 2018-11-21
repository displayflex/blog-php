<?php

namespace ftwr\blogphp\boxes;

use ftwr\blogphp\core\Container;
use ftwr\blogphp\core\DBDriver;
use ftwr\blogphp\core\DBConnector;

class DBDriverBox implements RegisterBoxInterface
{
	public function register(Container $container)
	{
		$container->service('dBDriver', function () {
			return new DBDriver(DBConnector::getConnect());
		});
	}
}
