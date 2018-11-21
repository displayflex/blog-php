<?php

namespace ftwr\blogphp\core\exceptions;

class UnknownIdentifierException extends BaseException
{
	private $error;

	public function __construct($id)
	{
		$this->dest .= '/UnknownIdentifier';
		parent::__construct();
		$this->error = sprintf('Box with identifier "%s" is not defined in container.', $id);
	}

	public function getError()
	{
		return $this->error;
	}
}
