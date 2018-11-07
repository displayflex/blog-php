<?php

namespace core;

interface iSession
{
	public function get($field);
	public function set($field, $content);
	public function delete($field);
}

class Session implements iSession
{
	public function get($field)
	{
		return $_SESSION[$field] ?? null;
	}

	public function set($field, $content)
	{
		$_SESSION[$field] = $content;
	}

	public function delete($field)
	{
		unset($_SESSION[$field]);
	}
}
