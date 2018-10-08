<?php

session_start();

include_once __DIR__ . '/m/functions.php';

$controller = trim($_GET['c'] ?? 'home');

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

