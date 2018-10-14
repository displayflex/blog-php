<?php

namespace core;

class DB
{
	public static function connect()
	{
		$dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', HOST_ADDRESS, DB_NAME);
		$db = new \PDO($dsn, DB_UESRNAME, DB_PASSWORD);
		$db->exec('SET NAMES UTF8');

		return $db;
	}
}
