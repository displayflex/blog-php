<?php

session_start();

include_once __DIR__ . '/m/functions.php';

$isAuth = isAuth();

if (!$isAuth) {
	header('Location: index.php');
	exit();
}

$id = $_GET['id'] ?? null;

if ($id === null || $id == '') {
	echo 'Ошибка 404. Не передано название!';
} elseif (!checkId($id)) {
	echo 'Ошибка 404. Введены недопустимые символы!';
} else {
	deletePost($id);

	header('Location: index.php');
	exit();
}