<?php

namespace ftwr\blogphp\controllers;

use ftwr\blogphp\core\Config;

class PageController extends BaseController
{
	public function errorAction(\Exception $e)
	{
		if ($e->getCode() === 404) {
			$this->error404Handler();
		} else {
			$this->errorHandler($e->getMessage(), $e->getTraceAsString());
		}
	}

	public function errorHandler($message, $trace)
	{
		$msg = Config::ENVIROMENT_DEV ? $message . '<br><br>Trace:<br>' . $trace : $message;

		$this->title .= Config::ERR_SUBTITLE;
		$this->content = $this->build(
			'v_error_inline',
			[
				'msg' => $msg
			]
		);
	}

	public function error404Handler()
	{
		header("HTTP/1.0 404 Not Found");
		$this->redirect(Config::ROOT . "src/views/v_403.php");
	}
}
