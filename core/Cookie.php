<?php

namespace core;

interface iCookie
{
	public function get($field);
	public function set($field, $content, $secondsAfterNow);
	public function delete($field);
}

class Cookie implements iCookie
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
