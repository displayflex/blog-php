<?php

	include_once 'functions.php';

	if (count($_POST) > 0) {
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		
		if ($title == '' || $content == '') {
			$msg = 'Заполните все поля';
		} elseif (!checkTitle($title)) {
			$msg = 'Название содержит недопустимые символы!';
		} elseif (file_exists("data/$title")) {
			$msg = 'Такая статья уже существует!';
		} else {
			file_put_contents("data/$title", $content);
			header("Location: index.php");
			exit();
		}
	} else {
		$title = '';
		$content = '';
		$msg = '';
	}
?>

<form method="post">
	Название<br>
	<input type="text" name="title" value="<?=$title?>"><br>
	Контент<br>
	<textarea name="content"><?=$content?></textarea><br>
	<input type="submit" value="Добавить">
</form>
<?=$msg;?>