<?php

namespace models;

use core\DBDriver;

class PostsModel extends BaseModel
{
	const TABLE_POSTS = 'news';

	public function __construct(DBDriver $db)
	{
		parent::__construct($db, self::TABLE_POSTS);
	}
}
