<?php

session_start();

include_once __DIR__ . '/m/functions.php';

$chpuParams = explode('/', $_GET['chpu']);

$end = count($chpuParams) - 1;

if ($chpuParams[$end] == '') {
	unset($chpuParams[$end]);
	$end--;
}

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
