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

if ($id === null || $id == '') {
	$err404 = true;
} elseif (!Core::checkId($id)) {
	$err404 = true;
}

if ($err404) {
	$inner = Templater::build('v_404');
	$title = '404';
} else {
	$PostsModel->deleteOne($id);

	header("Location: " . ROOT);
	exit();
}

