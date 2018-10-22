<?php

namespace models;

use core\DBDriver;

abstract class BaseModel
{
	protected $db;
	protected $table;

	public function __construct(DBDriver $db, $table)
	{
		$this->db = $db;
		$this->table = $table;
	}

	public function getAll($order = 'date')
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
		return $this->db->insert($this->table, $params);
	}

	public function updateOne(array $params, array $where)
	{
		return $this->db->update($this->table, $params, $where);
	}

	public function deleteOne(array $where)
	{
		return $this->db->delete($this->table, $where);
	}
}