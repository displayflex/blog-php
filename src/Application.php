<?php

namespace ftwr\blogphp;

use ftwr\blogphp\core\Config;
use ftwr\blogphp\core\Container;
use ftwr\blogphp\core\Request;
use ftwr\blogphp\core\exceptions\ErrorNotFoundException;
use ftwr\blogphp\controllers\PageController;

class Application
{
	const MSG_ERROR = 'Page not found!';

	public $currentController;
	public $currentAction;
	protected $container;
	protected $request;

	public function __construct(Container $container = null)
	{
		$this->enableErrorsHandling();
		$this->container = $container === null ? new Container() : $container;
		$this->initRequest();
		$this->parceUrl();
	}

	public function run()
	{
		$controller = new $this->currentController($this->request, $this->container);
		$action = $this->currentAction;
		$controller->$action();
		$controller->render();
	}

	public function enableErrorsHandling()
	{
		set_exception_handler(function ($e) {
			$controller = new PageController($this->request, $this->container);
			$controller->errorAction($e);
			$controller->render();
		});
	}

	protected function initRequest()
	{
		$this->request = new Request($_GET, $_POST, $_SERVER, $_COOKIE, $_SESSION, $_FILES);
	}

	protected function parceUrl()
	{
		$uri = $this->request->getSERVER('REQUEST_URI');
		$uriParts = $this->getUriAsArr($uri);
		$this->currentController = $this->getController($uriParts);
		$this->currentAction = $this->getAction($uriParts);
	}

	private function getUriAsArr($uri)
	{
		$uriParts = explode('/', $uri);
		unset($uriParts[0]);

		if ($uriParts[1] === str_replace('/', '', Config::ROOT)) {
			unset($uriParts[1]);
		}

		$uriParts = array_values($uriParts);
		$end = count($uriParts) - 1;

		if ($uriParts[$end] == '') {
			unset($uriParts[$end]);
			$end--;
		}

		return $uriParts;
	}

	private function getController(array $uriParts)
	{
		$controller = isset($uriParts[0]) && $uriParts !== '' ? ucfirst($uriParts[0]) : 'Post';
		$controller = sprintf('ftwr\blogphp\controllers\%sController', $controller);

		if (isset($uriParts[0]) && !file_exists('src/controllers/' . ucfirst($uriParts[0]) . 'Controller.php')) {
			throw new ErrorNotFoundException(self::MSG_ERROR, 404);
		}

		return $controller;
	}

	private function getAction(array $uriParts)
	{
		$action = isset($uriParts[1]) && $uriParts[1] !== '' && !ctype_digit($uriParts[1]) ? trim($uriParts[1]) : 'index';

		if (isset($uriParts[1]) && ctype_digit($uriParts[1])) {
			$action = 'post';
			$id = trim($uriParts[1]);
		} else {
			$id = isset($uriParts[2]) && ctype_digit($uriParts[2]) ? trim($uriParts[2]) : null;
		}

		// Убирает все знаки "-" в $action и делает каждое слово кроме первого с заглавной буквы
		if (mb_strpos($action, '-')) {
			$action = explode('-', $action);

			for ($i = 1; $i < count($action); $i++) {
				$action[$i] = ucfirst($action[$i]);
			}

			$action = implode('', $action);
		}

		if ($id !== null) {
			$_GET['id'] = $id;
			$this->initRequest();
		}

		$action = sprintf('%sAction', $action);

		return $action;
	}
}
