<?php

namespace core;

class DBConnector
{
	private static $instance;

	public static function getConnect()
	{
		if (self::$instance === null) {
			self::$instance = self::getPDO();
		}

		return self::$instance;
	}

	protected static function getPDO()
	{
		$dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', HOST_ADDRESS, DB_NAME);
		$db = new \PDO($dsn, DB_UESRNAME, DB_PASSWORD);
		$db->exec('SET NAMES UTF8');

		return $db;
	}
}
