<?php

namespace ftwr\blogphp;

use ftwr\blogphp\core\Config;
use ftwr\blogphp\core\Container;
use ftwr\blogphp\core\Request;
use ftwr\blogphp\boxes\DBDriverBox;
use ftwr\blogphp\boxes\ModelsFactory;
use ftwr\blogphp\boxes\UserBox;
use ftwr\blogphp\boxes\FormBuilderFactory;
use ftwr\blogphp\core\exceptions\ErrorNotFoundException;

class Application
{
	public $currentController;
	public $currentAction;
	protected $container;
	protected $request;

	public function __construct(Container $container = null)
	{
		$this->container = $container === null ? new Container() : $container;
		$this->initRequest();

		$this->container->register(new DBDriverBox());
		$this->container->register(new ModelsFactory());
		$this->container->register(new UserBox());
		$this->container->register(new FormBuilderFactory());

	}

	public function run()
	{
		try {
			$this->parceUrl();
			$controller = new $this->currentController($this->request, $this->container);
			$action = $this->currentAction;
			$controller->$action();
		} catch (\Exception $e) {
			$controller = sprintf('ftwr\blogphp\controllers\%sController', 'Base');
			$controller = new $controller($this->request, $this->container);

			if ($e->getCode() === 404) {
				$controller->error404Handler();
			} else {
				$controller->errorHandler($e->getMessage(), $e->getTraceAsString());
			}
		}

		$controller->render();
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
			throw new ErrorNotFoundException('Page Not Found', 1);
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
