<?php

$isAuth = isAuth();

if (!$isAuth) {
	header("Location: " . ROOT);
	exit();
}

$id = trim($chpuParams[1] ?? null);

$err404 = false;

if ($id === null || $id == '') {
	$err404 = true;
} elseif (!checkId($id)) {
	$err404 = true;
}

if ($err404) {
	$inner = template('v_404');
	$title = '404';
} else {
	$postModel->deleteOne($id);

	header("Location: " . ROOT);
	exit();
}

