<?php

namespace models;

use core\DBDriver;
use core\Validator;

class PostsModel extends BaseModel
{
	const TABLE_POSTS = 'news';

	protected $schema = [
		'id' => [
			'type' => 'integer',
			'primary' => true
		],
		'title' => [
			'type' => 'string',
			'length' => [5, 20],
			'notBlank' => true,
			'required' => true
		],
		'content' => [
			'type' => 'string',
			'length' => 'big',
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
