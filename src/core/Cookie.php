<?php

namespace ftwr\blogphp\core;

class Cookie
{
	public function get($field)
	{
		return $_COOKIE[$field] ?? null;
	}

	public function set($field, $content, $secondsAfterNow = (3600 * 24 * 30))
	{
		setcookie($field, $content, time() + $secondsAfterNow, '/');
	}

	public function delete($field)
	{
		setcookie($field, '', 1, '/');
	}
}
