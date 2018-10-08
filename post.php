<?php

include_once __DIR__ . '/m/functions.php';

$id = $_GET['id'] ?? null;

if ($id === null || $id == '') {
	echo 'Ошибка 404. Не передано название!';
} elseif (!checkId($id)) {
	echo 'Ошибка 404. Введены недопустимые символы!';
} else {
	$post = selectOnePost($id);
}

$inner = template('v_post', [
	'post' => $post
]);

echo template('v_main', [
	'title' => 'Просмотр статьи',
	'content' => $inner
]);
