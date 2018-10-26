<?php

namespace controllers;

use core\Config;
use core\Request;
use core\Exceptions\ErrorNotFoundException;

class BaseController
{
	protected $title;
	protected $content;
	protected $request;

	public function __construct(Request $request)
	{
		$this->title = Config::SITE_TITLE;
		$this->content = '';
		$this->request = $request;
	}

	public function __call($name, $params)
	{
		throw new ErrorNotFoundException();
	}

	public function render()
	{
		echo $this->build(
			'v_main',
			[
				'title' => $this->title,
				'content' => $this->content
			]
		);
	}

	public function errorHandler($message, $trace)
	{
		$msg = Config::ENVIROMENT_DEV ? $message . '<br><br>Trace:<br>' . $trace : $message;
		$this->title .= Config::ERR_SUBTITLE;
		$this->content = $this->build(
			'v_404_inline',
			[
				'msg' => $msg
			]
		);
	}

	public function error404Handler()
	{
		header("HTTP/1.0 404 Not Found");
		$this->redirect(Config::ROOT . "views/v_403.php");
	}

	protected function redirect($uri)
	{
		header(sprintf('Location: %s', $uri));
		exit();
	}

	protected function build($template, array $params = [])
	{
		extract($params);
		ob_start();
		include __DIR__ . "/../views/$template.php";

		return ob_get_clean();
	}
}
