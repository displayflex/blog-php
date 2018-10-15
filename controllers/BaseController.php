<?php

namespace controllers;

class BaseController
{
	protected $title;
	protected $content;

	public function __construct()
	{
		$this->title = 'MyBlog';
		$this->content = '';
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

	protected function build($template, array $params = [])
	{
		extract($params);
		ob_start();
		include __DIR__ . "/../views/$template.php";

		return ob_get_clean();
	}
}