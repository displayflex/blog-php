<?php

	include_once 'functions.php';

	$fname = $_GET['fname'];

	if ($fname === null){
		echo 'Ошибка 404, не передано название';
	} elseif (!checkTitle($fname)) {
		echo 'Ошибка 404. Введены недопустимые символы';
	} elseif (!file_exists("data/$fname")) {
		echo 'Ошибка 404. Нет такой статьи!';
	} else {
		$title = $fname;
		$content = file_get_contents("data/$title");
	}

	if (count($_POST) > 0) {
		$title = $_POST['title'];
		$content = $_POST['content'];

		if ($title == '' || $content == '') {
			$msg = 'Заполните все поля';
		} elseif (!checkTitle($title)) {
			$msg = 'Название содержит недопустимые символы!';
		} elseif (!file_exists("data/$title")) {
			unlink("data/$fname");
			file_put_contents("data/$title", $content);
			header("Location: index.php");
			exit();
		} else {
			file_put_contents("data/$title", $content);
			header("Location: index.php");
			exit();
		}
	} else {
		$msg = '';
	}
	
?>
<form method="post">
	Название<br>
	<input type="text" name="title" value="<?= $title ?>"><br>
	Контент<br>
	<textarea name="content"><?= $content ?></textarea><br>
	<input type="submit" value="Изменить">
</form>
<?= $msg; ?>