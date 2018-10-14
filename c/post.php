<?php

use core\Templater;
use core\Core;

$id = trim($chpuParams[1] ?? null);

$err404 = false;

if ($id === null || $id == '') {
	$err404 = true;
} elseif (!Core::checkId($id)) {
	$err404 = true;
} else {
	$post = $PostsModel->getOne($id);
}

if (!$post) {
	$err404 = true;
}

if ($err404) {
	$inner = Templater::build('v_404');
} else {
	$inner = Templater::build('v_post', [
		'post' => $post
	]);
}

$title = $err404 ? '404' : 'Просмотр статьи';
