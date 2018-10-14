<?php

use m\Auth;
use core\Templater;

if (isset($_GET['log']) && $_GET['log'] == 'out') {
	Auth::logOut();
	header("Location: " . ROOT);
	exit();
}

$isAuth = Auth::check();

$posts = $PostsModel->getAll();

if ($isAuth) {
	$template = 'v_index_admin';
} else {
	$template = 'v_index';
}

$inner = Templater::build($template, [
	'posts' => $posts
]);

$title = 'Главная';
