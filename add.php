<?php

session_start();

include_once __DIR__ . '/m/functions.php';

$isAuth = isAuth();

if (!$isAuth) {
	header('Location: index.php');
	exit();
}

if (count($_POST) > 0) {
	$title = trim(htmlspecialchars($_POST['title']));
	$content = trim(htmlspecialchars($_POST['content']));
	
	if ($title == '' || $content == '') {
		$msg = 'Заполните все поля.';
	} else {
		addPost($title, $content);

		header("Location: index.php");
		exit();
	}
} else {
	$title = '';
	$content = '';
	$msg = '';
}

include __DIR__ . "/v/v_add.php";