<?php

namespace controllers;

class ErrorController extends BaseController
{
	public function err403Action()
	{
		// header('HTTP/1.0 403 Forbidden');
		header("Location: " . ROOT . "views/v_403.php");
		exit();
	}
}
