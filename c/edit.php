<?php

$isAuth = isAuth();

if (!$isAuth) {
	header("Location: " . ROOT);
	exit();
}

$id = trim($chpuParams[1] ?? null);

$err404 = false;

if (!checkId($id)) {
	$err404 = true;
} else {
	if ($id === null || $id == '') {
		$err404 = true;
	} else {
		$post = selectOnePost($id);

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
			updatePost($id, $title, $content);

			header("Location: " . ROOT);
			exit();
		}
	}
}

if ($err404) {
	$inner = template('v_404');
} else {
	$inner = template('v_edit', [
		'title' => $title,
		'content' => $content,
		'msg' => $msg
	]);
}

$title = $err404 ? '404' : 'Редактирование статьи';