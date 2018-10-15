<?php

namespace controllers;

class ErrorController extends BaseController
{
	public function badAction()
	{
			header("Location: " . ROOT . "views/v_403.php");
			exit();
	}
}