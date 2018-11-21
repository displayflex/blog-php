<?php

require_once __DIR__ . '/vendor/autoload.php';

use ftwr\blogphp\core\Config;
use ftwr\blogphp\core\DBConnector;
use ftwr\blogphp\models\PostModel;
use ftwr\blogphp\core\Request;
use ftwr\blogphp\core\exceptions\ErrorNotFoundException;
use ftwr\blogphp\core\Container;
use ftwr\blogphp\core\boxes\DBDriverBox;
use ftwr\blogphp\core\boxes\ModelsFactory;
use ftwr\blogphp\core\boxes\UserBox;
use ftwr\blogphp\core\boxes\FormBuilderFactory;

session_start();
error_reporting(E_ALL);

/**
 * После настройки в .htaccess, URI-запрос попадает в $_GET['chpu'], затем разбивается по "/"
 */
// $chpuParams = explode('/', $_GET['chpu']);

// $end = count($chpuParams) - 1;

// if ($chpuParams[$end] == '') {
	// 	unset($chpuParams[$end]);
	// 	$end--;
	// }

/**
 * Разбивает URI-запрос по "/" при помощи $_SERVER['REQUEST_URI'].
 */
$uri = $_SERVER['REQUEST_URI'];
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

$controller = isset($uriParts[0]) && $uriParts !== '' ? trim($uriParts[0]) : 'post';

try {
	switch ($controller) {
		case 'post':
			$controller = 'Post';
			break;

		case 'user':
			$controller = 'User';
			break;

		default:
			throw new ErrorNotFoundException();
			break;
	}

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

	$controller = sprintf('ftwr\blogphp\controllers\%sController', $controller);
	$action = sprintf('%sAction', $action);

	if (!method_exists($controller, $action) || (isset($uriParts[2]) && !ctype_digit($uriParts[2]))) {
		throw new ErrorNotFoundException();
	}

	if ($id !== null) {
		$_GET['id'] = $id;
	}

	$request = new Request($_GET, $_POST, $_SERVER, $_COOKIE, $_SESSION, $_FILES);
	$container = new Container();
	$container->register(new DBDriverBox());
	$container->register(new ModelsFactory());
	$container->register(new UserBox());
	$container->register(new FormBuilderFactory());
	$controller = new $controller($request, $container);
	$controller->$action();
} catch (\Exception $e) {
	$request = new Request($_GET, $_POST, $_SERVER, $_COOKIE, $_SESSION, $_FILES);
	$container = new Container();
	$controller = sprintf('ftwr\blogphp\controllers\%sController', 'Base');
	$controller = new $controller($request, $container);

	if ($e->getCode() === 404) {
		$controller->error404Handler();
	} else {
		$controller->errorHandler($e->getMessage(), $e->getTraceAsString());
	}
}

$controller->render();

// TODO: создать класс Application (последний урок 2ого модуля)
// FIXME: разбить Validator на классы (начало 5ого урока)
// TODO: перенести Boxes из сore в корень проекта
// TODO: пересмотреть классы кук и сессий (как у 1gor)
// TODO: проверить неймспейсы
// TODO: вынести интрефейс сессий и кук в отдельный файл или удалить
