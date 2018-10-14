<?php

use m\Auth;
use core\Templater;

$isAuth = Auth::check();

if (!$isAuth) {
	header('Location: index.php');
	exit();
}

if (count($_POST) > 0) {
	$title = trim(htmlspecialchars($_POST['title']));
	$content = trim(htmlspecialchars($_POST['content']));
	
	if ($title == '' || $content == '') {
		$msg = 'Заполните все поля.';
	} else {
		$PostsModel->addOne($title, $content);

		header("Location: index.php");
		exit();
	}
} else {
	$title = '';
	$content = '';
	$msg = '';
}

$inner = Templater::build('v_add', [
	'title' => $title,
	'content' => $content,
	'msg' => $msg
]);

$title = 'Добавление статьи';