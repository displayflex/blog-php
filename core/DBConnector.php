<?php

namespace core;

use core\Exceptions\ErrorNotFoundException;

class DBConnector
{
	const MSG_ERROR = 'Ошибка 404!';

	private static $instance;

	public static function getConnect()
	{
		if (self::$instance === null) {
			self::$instance = self::getPDO();
		}

		return self::$instance;
	}

	private static function getPDO()
	{
		if (!ENVIROMENT_DEV) {
			try {
				$dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', HOST_ADDRESS, DB_NAME);
				$db = new \PDO($dsn, DB_UESRNAME, DB_PASSWORD);
				$db->exec('SET NAMES UTF8');
			} catch (\PDOException $e) {
				throw new ErrorNotFoundException(self::MSG_ERROR, 1);
			}
		}

		$dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', HOST_ADDRESS, DB_NAME);
		$db = new \PDO($dsn, DB_UESRNAME, DB_PASSWORD);
		$db->exec('SET NAMES UTF8');

		return $db;
	}
}
