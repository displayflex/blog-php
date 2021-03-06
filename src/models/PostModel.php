<?php

namespace ftwr\blogphp\models;

use ftwr\blogphp\core\DBDriver;
use ftwr\blogphp\core\Validator;

class PostModel extends BaseModel
{
	const TABLE_NAME = 'news';

	protected $schema = [
		'id' => [
			'type' => Validator::TYPE_INTEGER,
			'primary' => true
		],
		'title' => [
			'type' => Validator::TYPE_STRING,
			'length' => [4, 20],
			'notBlank' => true,
			'required' => true
		],
		'content' => [
			'type' => Validator::TYPE_STRING,
			'length' => Validator::LENGTH_ANY,
			'notBlank' => true,
			'required' => true
		],
		'date' => [
			'required' => false
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, self::TABLE_NAME);
		$this->validator->setRules($this->schema);
	}
}
