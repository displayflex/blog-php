<?php

session_start();

include_once __DIR__ . '/m/functions.php';

$isAuth = isAuth();

if (!$isAuth) {
	header('Location: index.php');
	exit();
}

$id = $_GET['id'] ?? null;

if (!checkId($id)) {
	$msg = 'Ошибка 404. Введены недопустимые символы!';
} else {
	if ($id === null || $id == '') {
		$msg = 'Ошибка 404. Не передано название!';
	} else {
		$post = selectOnePost($id);

		if (!$post) {
			echo 'Ошибка 404. Нет такой статьи!';
		} else {
			$title = $post['title'];
			$content = $post['content'];
		}
	}

	if (count($_POST) > 0) {
		$title = trim(htmlspecialchars($_POST['title']));
		$content = trim(htmlspecialchars($_POST['content']));

		if ($title == '' || $content == '') {
			$msg = 'Заполните все поля';
		} else {
			updatePost($id, $title, $content);

			header("Location: index.php");
			exit();
		}
	}
}

$inner = template('v_edit', [
	'title' => $title,
	'content' => $content,
	'msg' => $msg
]);

echo template('v_main', [
	'title' => 'Редактирование статьи',
	'content' => $inner
]);
