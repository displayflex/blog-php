<?php

if ($_GET['log'] == 'out') {
	logOut();
	header('Location: index.php');
	exit();
}

$isAuth = isAuth();

$posts = selectAllPosts();

if ($isAuth) {
	$template = 'v_index_admin';
} else {
	$template = 'v_index';
}

$inner = template($template, [
	'posts' => $posts
]);

$title = 'Главная';


