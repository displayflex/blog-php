<?php

	session_start();

	include_once 'functions.php';

	$isAuth = isAuth();

	if (!$isAuth) {
		header('Location: index.php');
		exit();
	}

	$fname = $_GET['fname'];

	if (!checkTitle($fname)) {
		$msg = 'Ошибка 404. Введены недопустимые символы';
	} else {
		if ($fname === null) {
			$msg = 'Ошибка 404, не передано название';
		} elseif (!file_exists("data/$fname")) {
			$msg = 'Ошибка 404. Нет такой статьи!';
		} else {
			$title = $fname;
			$content = file_get_contents("data/$title");
		}
	
		if (count($_POST) > 0) {
			$title = trim(htmlspecialchars($_POST['title']));
			$content = trim(htmlspecialchars($_POST['content']));
	
			if ($title == '' || $content == '') {
				$msg = 'Заполните все поля';
			} elseif (!checkTitle($title)) {
				$msg = 'Название содержит недопустимые символы!';
			} elseif ($title != $fname) {
				if (file_exists("data/$title")) {
					$msg = 'Такая статья уже существует!';
				} else {
					unlink("data/$fname");
					file_put_contents("data/$title", $content);
					header("Location: index.php");
					exit();
				}
			} else {
				file_put_contents("data/$title", $content);
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