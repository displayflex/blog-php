<?php

namespace ftwr\blogphp\core;

use ftwr\blogphp\core\exceptions\ErrorNotFoundException;

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
		$options = [
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
		];
		$dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', Config::HOST_ADDRESS, Config::DB_NAME);

		if (!Config::ENVIROMENT_DEV) {
			try {
				$db = new \PDO($dsn, Config::DB_UESRNAME, Config::DB_PASSWORD, $options);
				$db->exec('SET NAMES UTF8');
			} catch (\PDOException $e) {
				throw new ErrorNotFoundException(self::MSG_ERROR, 1);
			}
		}

		$db = new \PDO($dsn, Config::DB_UESRNAME, Config::DB_PASSWORD, $options);
		$db->exec('SET NAMES UTF8');
		
		return $db;
	}
}
