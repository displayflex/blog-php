<?php

session_start();

error_reporting(E_ALL);

include_once __DIR__ . '/core/config.php';

use core\DBConnector;
use models\PostsModel;
use core\Core;

function __autoload($classname)
{
	include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

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

if ($uriParts[1] === str_replace('/', '', ROOT)) {
	unset($uriParts[1]);
}

$uriParts = array_values($uriParts);

$end = count($uriParts) - 1;

if ($uriParts[$end] == '') {
	unset($uriParts[$end]);
	$end--;
}

$controller = isset($uriParts[0]) && $uriParts !== '' ? trim($uriParts[0]) : 'post';

// проверки контроллера?
// if (!Core::checkController($controller)) {
// 	$controller = 'Error';
// } 

switch ($controller) {
	case 'post':
		$controller = 'Post';
		break;

	case 'user':
		$controller = 'User';
		break;

	default:
		$controller = 'Error';
		break;
}

$action = isset($uriParts[1]) && $uriParts[1] !== '' && !ctype_digit($uriParts[1]) ? trim($uriParts[1]) : 'index';

if (isset($uriParts[1]) && ctype_digit($uriParts[1])) {
	$action = 'post';
	$id = trim($uriParts[1]);
} else {
	$id = isset($uriParts[2]) && ctype_digit($uriParts[2]) ? trim($uriParts[2]) : null;
}

$controller = sprintf('controllers\%sController', $controller);
$action = sprintf('%sAction', $action);

if ($controller === 'controllers\ErrorController') {
	$action = 'err403Action';
	$id = null;
} else {
	if (!method_exists($controller, $action) || (isset($uriParts[2]) && !ctype_digit($uriParts[2]))) {
		$action = 'err404Action';
		$id = null;
	}
}

$controller = new $controller();

if ($id !== null) {
	$controller->$action($id);
} else {
	$controller->$action();
}

$controller->render();
