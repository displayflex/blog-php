<?php

namespace core;

class DBDriver
{
	const FETCH_ALL = 'all';
	const FETCH_ONE = 'one';

	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	// TODO: order by??
	public function select($sql, array $params = [], $fetch = self::FETCH_ALL)
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		$this->checkError($stmt);

		return $fetch === self::FETCH_ALL ? $stmt->fetchAll() : $stmt->fetch();
	}

	public function insert($table, array $params)
	{
		$columns = sprintf('(%s)', implode(', ', array_keys($params)));
		$masks = sprintf('(:%s)', implode(', :', array_keys($params)));

		$sql = sprintf('INSERT INTO %s %s VALUES %s', $table, $columns, $masks);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		$this->checkError($stmt);

		return $this->pdo->lastInsertId();
	}

	public function update($table, $params, $where)
	{
		$setField = [];

		foreach (array_keys($params) as $key => $value) {
			$setField[$key] = $value . '=:' . $value;
		}

		$setField = implode(', ', $setField);

		$whereField = [];

		foreach (array_keys($where) as $key => $value) {
			$whereField[$key] = $value . '=:' . $value;
		}

		$whereField = implode(', ', $whereField);

		$sql = sprintf("UPDATE %s SET %s WHERE %s", $table, $setField, $whereField);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array_merge($params, $where));
		$this->checkError($stmt);

		return $this->pdo->lastInsertId();
	}

	public function delete($table, $where)
	{
		$whereField = [];

		foreach (array_keys($where) as $key => $value) {
			$whereField[$key] = $value . '=:' . $value;
		}

		$whereField = implode(', ', $whereField);

		$sql = sprintf("DELETE FROM %s WHERE %s", $table, $whereField);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($where);
		$this->checkError($stmt);

		return $stmt->rowCount();
	}

	protected function checkError($stmt)
	{
		$info = $stmt->errorInfo();

		if ($info[0] != \PDO::ERR_NONE) {
			exit($info[2]);
		}
	}
}