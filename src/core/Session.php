<?php

namespace ftwr\blogphp\core;

class Session
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
