<?php

	session_start();

	include_once 'functions.php';

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
			$sql = sprintf("INSERT INTO %s(`title`, `content`) VALUES (:t, :c)", DB_TABLE);
			$query = db_query($sql, [
				't' => $title,
				'c' => $content
			]);

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