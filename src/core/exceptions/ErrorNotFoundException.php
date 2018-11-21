<?php

namespace ftwr\blogphp\core\exceptions;

class ErrorNotFoundException extends BaseException
{
	public function __construct($message = 'Page Not Found', $code = 404)
	{
		$this->dest .= '/ErrorNotFound';
		parent::__construct($message, $code);
	}
}
