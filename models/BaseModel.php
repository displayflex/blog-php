<?php

namespace models;

abstract class BaseModel
{
	protected $db;
	protected $table;

	public function __construct($db, $table)
	{
		$this->db = $db;
		$this->table = $table;
	}

	public function getAll()
	{
		$sql = sprintf("SELECT * FROM %s ORDER BY `date` DESC", $this->table);
		$statement = $this->db->prepare($sql);
		$statement->execute();
		$this->checkError($statement);

		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getOne($id)
	{
		$sql = sprintf("SELECT * FROM %s WHERE `id`=:id", $this->table);
		$statement = $this->db->prepare($sql);
		$statement->execute([
			'id' => $id
		]);
		$this->checkError($statement);

		return $statement->fetch(\PDO::FETCH_ASSOC);
	}

	public function deleteOne($id)
	{
		$sql = sprintf("DELETE FROM %s WHERE `id`=:id", $this->table);
		$statement = $this->db->prepare($sql);
		$statement->execute([
			'id' => $id
		]);
		$this->checkError($statement);

		return $statement->rowCount();
	}

	protected function checkError($statement)
	{
		$info = $statement->errorInfo();

		if ($info[0] != \PDO::ERR_NONE) {
			exit($info[2]);
		}
	}
}