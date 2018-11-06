<?php

namespace models;

use core\DBDriver;
use core\Validator;
use core\Exceptions\ModelIncorrectDataException;

abstract class BaseModel
{
	protected $validator;
	protected $db;
	protected $table;

	public function __construct(DBDriver $db, Validator $validator, $table)
	{
		$this->db = $db;
		$this->validator = $validator;
		$this->table = $table;
	}

	public function getAll($option = '')
	{
		$sql = sprintf("SELECT * FROM %s %s", $this->table, $option);

		return $this->db->select($sql);
	}

	public function getOne($id)
	{
		$sql = sprintf("SELECT * FROM %s WHERE `id`=:id", $this->table);

		return $this->db->select($sql, ['id' => $id], DBDriver::FETCH_ONE);
	}

	public function addOne(array $params, $needValidation = true)
	{
		if ($needValidation) {
			$this->validator->execute($params);
	
			if (!$this->validator->success) {
				throw new ModelIncorrectDataException($this->validator->errors);
			}

			$params = $this->validator->clean;
		}

		return $this->db->insert($this->table, $params);
	}

	public function updateOne(array $params, $where, array $whereParams, $needValidation = true)
	{
		if ($needValidation) {
			$this->validator->execute($params);
	
			if (!$this->validator->success) {
				throw new ModelIncorrectDataException($this->validator->errors);
			}

			$params = $this->validator->clean;
		}

		return $this->db->update($this->table, $params, $where, $whereParams);
	}

	public function deleteOne($where, array $whereParams)
	{
		return $this->db->delete($this->table, $where, $whereParams);
	}
}
