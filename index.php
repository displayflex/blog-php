<?php

session_start();

include_once __DIR__ . '/m/functions.php';

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

include __DIR__ . "/v/$template.php";