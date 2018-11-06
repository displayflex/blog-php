<?php

namespace models;

use core\DBDriver;
use core\Validator;

class SessionModel extends BaseModel
{
	const TABLE_NAME = 'sessions';

	protected $schema = [
		'id' => [
			'type' => Validator::TYPE_INTEGER,
			'primary' => true
		],
		'id_user' => [
			'type' => Validator::TYPE_INTEGER,
			'notBlank' => true,
			'required' => true
		],
		'sid' => [
			'type' => Validator::TYPE_STRING,
			'notBlank' => true,
			'required' => true
		],
		'created_at' => [
			'type' => Validator::TYPE_STRING,
			'notBlank' => true,
			'required' => true
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, self::TABLE_NAME);
		$this->validator->setRules($this->schema);
	}

	public function setDBSessionParams(array $fields)
	{
		$validatedFields = $this->validator->execute($fields);

		if (!$this->validator->success) {
			throw new ModelIncorrectDataException($this->validator->errors);
		}

		if (!$this->getOneByUserId($validatedFields['id_user'])) {
			return $this->addOne(
				[
					'id_user' => $validatedFields['id_user'],
					'sid' => $validatedFields['sid'],
					'created_at' => $validatedFields['created_at']
				],
				false
			);
		}

		return $this->updateOne(
			[
				'sid' => $validatedFields['sid']
			],
			'id_user=:id_user',
			[
				'id_user' => $validatedFields['id_user']
			],
			false
		);
	}

	public function getOneBySid($sid)
	{
		$sql = sprintf("SELECT * FROM %s WHERE `sid`=:sid", $this->table);

		return $this->db->select($sql, ['sid' => $sid], DBDriver::FETCH_ONE);
	}

	public function getOneByUserId($id)
	{
		$sql = sprintf("SELECT * FROM %s WHERE `id_user`=:id_user", $this->table);

		return $this->db->select($sql, ['id_user' => $id], DBDriver::FETCH_ONE);
	}
}
