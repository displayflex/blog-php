<?php

namespace controllers;

use core\Request;

class BaseController
{
	protected $title;
	protected $content;
	protected $request;

	public function __construct(Request $request)
	{
		$this->title = SITE_TITLE;
		$this->content = '';
		$this->request = $request;
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