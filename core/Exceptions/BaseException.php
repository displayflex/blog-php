<?php

namespace core\Exceptions;

use core\Config;
use \Throwable;

class BaseException extends \Exception
{
	protected $dest = Config::LOG_DIR;

	public function __construct($message = '', $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$msg = "\n" . date('H:i:m') . "\n" . $_SERVER['REMOTE_ADDR'] . "\n\n" . $this .
		"\n-------------------------------------------------------------------------------------\n";
		file_put_contents($this->dest . '/' . date('Y-m-d'), $msg, FILE_APPEND);
	}
}
