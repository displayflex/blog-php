<?php

namespace core\Exceptions;

class UnauthorizedException extends BaseException
{
	private $error;

	public function __construct($error)
	{
		$this->dest .= '/Unauthorized';
		parent::__construct();
		$this->error = $error;
	}

	public function getError()
	{
		return $this->error;
	}
}
