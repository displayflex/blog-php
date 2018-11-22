<?php

namespace ftwr\blogphp\controllers;

use ftwr\blogphp\core\Config;
use ftwr\blogphp\core\Request;
use ftwr\blogphp\core\exceptions\ErrorNotFoundException;
use ftwr\blogphp\core\Container;

class BaseController
{
	protected $title;
	protected $content;
	protected $request;
	protected $container;

	public function __construct(Request $request, Container $container)
	{
		$this->title = Config::SITE_TITLE;
		$this->content = '';
		$this->request = $request;
		$this->container = $container;
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
				'content' => $this->content,
				'userMenu' => $this->userMenu ?? null
			]
		);
	}

	public function getErrorsAsString(array $errors)
	{
		$mergedErrors = array_reduce($errors, 'array_merge', array());
		return implode('<br>', $mergedErrors);
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
