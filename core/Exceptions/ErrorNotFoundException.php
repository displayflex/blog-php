<?php

namespace core\Exceptions;

class ErrorNotFoundException extends BaseException
{
	public function __construct($message = 'Page Not Found', $code = 404)
	{
		$this->dest .= '/ErrorNotFound';
		parent::__construct($message, $code);
	}
}
