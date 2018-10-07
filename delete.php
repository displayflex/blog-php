<?php

	session_start();

	include_once 'functions.php';

	$isAuth = isAuth();

	if (!$isAuth) {
		header('Location: index.php');
		exit();
	}

	$id = $_GET['id'] ?? null;

	if ($id === null || $id == '') {
		echo 'Ошибка 404. Не передано название!';
	} elseif (!checkId($id)) {
		echo 'Ошибка 404. Введены недопустимые символы!';
	} else {
		$sql = sprintf("DELETE FROM %s WHERE `id`=:id", DB_TABLE);
		$query = db_query($sql, [
			'id' => $id
		]);

		header('Location: index.php');
		exit();
	}