<?php

namespace core;

abstract class Core
{
	// delete this?
	public static function checkTitle($title)
	{
		return preg_match(ALLOWED_IN_TITLE, $title);
	}

	public static function checkController($controller)
	{
		return preg_match(ALLOWED_IN_CONTROLLER, $controller);
	}

	public static function checkId($id)
	{
		return preg_match(ALLOWED_IN_ID, $id);
	}
}
