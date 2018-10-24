<?php

namespace models;

use core\DBDriver;
use core\Validator;

abstract class BaseModel
{
	const SORT_COLUMN = 'date';

	protected $db;
	protected $validator;
	protected $table;

	public function __construct(DBDriver $db, Validator $validator, $table)
	{
		$this->db = $db;
		$this->validator = $validator;
		$this->table = $table;
	}

	public function getAll($order = self::SORT_COLUMN)
	{
		$sql = sprintf("SELECT * FROM %s ORDER BY %s DESC", $this->table, $order);

		return $this->db->select($sql);
	}

	public function getOne($id)
	{
		$sql = sprintf("SELECT * FROM %s WHERE `id`=:id", $this->table);

		return $this->db->select($sql, ['id' => $id], DBDriver::FETCH_ONE);
	}

	public function addOne(array $params)
	{
		$this->validator->execute($params);

		if (!$this->validator->success) {
			// process the error
			// $this->validator->errors;
		}

		return $this->db->insert($this->table, $params);
	}

	public function updateOne(array $params, $where, array $whereParams)
	{
		$this->validator->execute($params);

		if (!$this->validator->success) {
			// process the error
			// $this->validator->errors;
		}

		return $this->db->update($this->table, $params, $where, $whereParams);
	}

	public function deleteOne($where, array $whereParams)
	{
		return $this->db->delete($this->table, $where, $whereParams);
	}
}
