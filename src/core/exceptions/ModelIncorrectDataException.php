<?php

namespace ftwr\blogphp\core\exceptions;

class ModelIncorrectDataException extends BaseException
{
	private $errors;

	public function __construct($errors)
	{
		$this->dest .= '/ModelIncorrectData';
		parent::__construct();
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}
