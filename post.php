<?php

	include_once 'functions.php';

	$id = $_GET['id'] ?? null;

	if ($id === null || $id == '') {
		echo 'Ошибка 404. Не передано название!';
	} elseif (!checkId($id)) {
		echo 'Ошибка 404. Введены недопустимые символы!';
	} else {
		$sql = sprintf("SELECT * FROM %s WHERE `id`=:id", DB_TABLE);
		$query = db_query($sql, [
			'id' => $id
		]);
		$post = $query->fetch(PDO::FETCH_ASSOC);

		if (!$post) {
			echo 'Ошибка 404. Нет такой статьи!';
		} else { ?>
			<h2><?=nl2br($post['title'])?></h2>
			<small><?=nl2br($post['date'])?></small>
			<p><?=nl2br($post['content'])?></p>
		<?php } 
	}