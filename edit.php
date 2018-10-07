<?php

	session_start();

	include_once 'functions.php';

	$isAuth = isAuth();

	if (!$isAuth) {
		header('Location: index.php');
		exit();
	}

	$id = $_GET['id'] ?? null;

	if (!checkId($id)) {
		$msg = 'Ошибка 404. Введены недопустимые символы!';
	} else {
		if ($id === null || $id == '') {
			$msg = 'Ошибка 404. Не передано название!';
		} else {
			$sql = sprintf("SELECT * FROM %s WHERE `id`=:id", DB_TABLE);
			$query = db_query($sql, [
				'id' => $id
			]);
			$post = $query->fetch(PDO::FETCH_ASSOC);

			if (!$post) {
				echo 'Ошибка 404. Нет такой статьи!';
			} else {
				$title = $post['title'];
				$content = $post['content'];
			}
		}
	
		if (count($_POST) > 0) {
			$title = trim(htmlspecialchars($_POST['title']));
			$content = trim(htmlspecialchars($_POST['content']));
	
			if ($title == '' || $content == '') {
				$msg = 'Заполните все поля';
			} else {
				$sql = sprintf("UPDATE %s SET `title`=:t,`content`=:c WHERE `id`=:id", DB_TABLE);
				$query = db_query($sql, [
					't' => $title,
					'c' => $content,
					'id' => $id
				]);

				header("Location: index.php");
				exit();
			}
		}
	}
?>
<form method="post">
	Название<br>
	<input type="text" name="title" value="<?=$title?>"><br>
	Контент<br>
	<textarea name="content"><?=$content?></textarea><br>
	<input type="submit" value="Изменить">
</form>
<?=$msg;?>