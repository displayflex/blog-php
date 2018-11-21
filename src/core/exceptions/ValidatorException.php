<?php

namespace ftwr\blogphp\core\exceptions;

class ValidatorException extends BaseException
{
	private $error;

	public function __construct($error)
	{
		$this->dest .= '/Validator';
		parent::__construct();
		$this->error = $error;
	}

	public function getError()
	{
		return $this->error;
	}
}
