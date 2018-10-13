<?php

session_start();

include_once __DIR__ . '/m/functions.php';

use m\DB;
use m\PostModel;

function __autoload($classname)
{
	include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

$chpuParams = explode('/', $_GET['chpu']);

$end = count($chpuParams) - 1;

if ($chpuParams[$end] == '') {
	unset($chpuParams[$end]);
	$end--;
}

$db = DB::connect();
$postModel = new PostModel($db);

$controller = trim($chpuParams[0] ?? 'home');

if ($controller === null || $controller == '') {
	$msg = 'Ошибка 404. Не передано название!';
	$controller = '404';
} elseif (!checkController($controller)) {
	$msg = 'Ошибка 404. Введены недопустимые символы!';
	$controller = '404';
} elseif (!file_exists(__DIR__ . "/c/$controller.php")) {
	$msg = 'Ошибка 404. Не верный путь!';
	$controller = '404';
}

include_once __DIR__ . "/c/$controller.php";

echo template('v_main', [
	'title' => $title,
	'content' => $inner
]);
