<?php

namespace models;

use core\Config;
use core\DBDriver;
use core\Validator;
use core\Exceptions\ModelIncorrectDataException;

class UserModel extends BaseModel
{
	const TABLE_NAME = 'users';

	public $validator;
	protected $schema = [
		'id' => [
			'type' => Validator::TYPE_INTEGER,
			'primary' => true
		],
		'login' => [
			'type' => Validator::TYPE_STRING,
			'length' => [3, 50],
			'notBlank' => true,
			'required' => true,
			'notContainsSpaces' => true
		],
		'password' => [
			'type' => Validator::TYPE_STRING,
			'length' => [4, 50],
			'notBlank' => true,
			'required' => true,
			'notContainsSpaces' => true
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, self::TABLE_NAME);
		$this->validator->setRules($this->schema);
	}

	public function signUp(array $fields)
	{
		$this->validator->execute($fields);

		if (!$this->validator->success) {
			throw new ModelIncorrectDataException($this->validator->errors);
		}

		return $this->addOne(
			[
				'login' => $this->validator->clean['login'],
				'password' => $this->getHash($this->validator->clean['password']),
			],
			false
		);
	}

	public function getOneByLogin($login)
	{
		$sql = sprintf("SELECT * FROM %s WHERE `login`=:login", $this->table);

		return $this->db->select($sql, ['login' => $login], DBDriver::FETCH_ONE);
	}

	public function getBySid($sid)
	{
		$sql = sprintf(
			"SELECT %s.id AS id, login, password FROM `sessions` JOIN %s ON sessions.id_user = %s.id WHERE `sid`=:sid",
			$this->table,
			$this->table,
			$this->table
		);

		return $this->db->select($sql, ['sid' => $sid], DBDriver::FETCH_ONE);
	}

	public function getHash($password)
	{
		return hash('sha256', $password . Config::SALT);
	}
}
