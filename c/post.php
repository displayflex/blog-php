<?php

$id = trim($_GET['id'] ?? null);

$err404 = false;

if ($id === null || $id == '') {
	$err404 = true;
} elseif (!checkId($id)) {
	$err404 = true;
} else {
	$post = selectOnePost($id);
}

if (!$post) {
	$err404 = true;
}

if ($err404) {
	$inner = template('v_404');
} else {
	$inner = template('v_post', [
		'post' => $post
	]);
}

$title = $err404 ? '404' : 'Просмотр статьи';
