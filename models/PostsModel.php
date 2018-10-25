<?php

namespace models;

use core\DBDriver;
use core\Validator;

class PostsModel extends BaseModel
{
	const TABLE_POSTS = 'news';

	protected $schema = [
		'id' => [
			'type' => Validator::TYPE_INTEGER,
			'primary' => true
		],
		'title' => [
			'type' => Validator::TYPE_STRING,
			'length' => [5, 20],
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
		parent::__construct($db, $validator, self::TABLE_POSTS);
		$this->validator->setRules($this->schema);
	}
}
