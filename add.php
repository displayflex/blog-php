<?php

	if(count($_POST) > 0){
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		
		if($title == '' || $content == ''){
			$msg = 'Заполните все поля';
		}
		/*
			проверка корректности title
			проверка уникальности title
		*/
		else{
			// сохранить статью в файл
			header("Location: index.php");
			exit();
		}
	}
	else{
		$msg = '';
	}
	
?>
<form method="post">
	Название<br>
	<input type="text" name="title"><br>
	Контент<br>
	<textarea name="content"></textarea><br>
	<input type="submit" value="Добавить">
</form>
<?php echo $msg; ?>