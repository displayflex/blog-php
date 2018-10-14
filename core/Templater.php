<?php

namespace core;

class Templater
{
	public static function build($filename, $vars = [])
	{
		extract($vars);
		ob_start();
		include "v/$filename.php";

		return ob_get_clean();
	}
}
