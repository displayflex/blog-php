<?php

namespace m;

class PostModel extends BaseModel
{
	public function __construct(\PDO $db)
	{
		parent::__construct($db, 'news');
	}

	public function addOne($title, $content)
	{
		$sql = sprintf("INSERT INTO %s(`title`, `content`) VALUES (:t, :c)", $this->table);
		$statement = $this->db->prepare($sql);
		$statement->execute([
			't' => $title,
			'c' => $content
		]);
		$this->checkError($statement);

		return $this->db->lastInsertId();
	}

	public function updateOne($id, $title, $content)
	{
		$sql = sprintf("UPDATE %s SET `title`=:t,`content`=:c WHERE `id`=:id", $this->table);
		$statement = $this->db->prepare($sql);
		$statement->execute([
			't' => $title,
			'c' => $content,
			'id' => $id
		]);
		$this->checkError($statement);

		return $this->db->lastInsertId();
	}
}