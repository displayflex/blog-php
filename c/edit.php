<?php

use m\Auth;
use core\Templater;
use core\Core;

$isAuth = Auth::check();

if (!$isAuth) {
	header("Location: " . ROOT);
	exit();
}

$id = trim($chpuParams[1] ?? null);

$err404 = false;

if (!Core::checkId($id)) {
	$err404 = true;
} else {
	if ($id === null || $id == '') {
		$err404 = true;
	} else {
		$post = $PostsModel->getOne($id);

		if (!$post) {
			$err404 = true;
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
			$PostsModel->updateOne($id, $title, $content);

			header("Location: " . ROOT);
			exit();
		}
	}
}

if ($err404) {
	$inner = Templater::build('v_404');
} else {
	$inner = Templater::build('v_edit', [
		'title' => $title,
		'content' => $content,
		'msg' => $msg
	]);
}

$title = $err404 ? '404' : 'Редактирование статьи';