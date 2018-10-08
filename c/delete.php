<?php

$isAuth = isAuth();

if (!$isAuth) {
	header('Location: index.php');
	exit();
}

$id = trim($_GET['id'] ?? null);

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
	deletePost($id);

	header('Location: index.php');
	exit();
}

