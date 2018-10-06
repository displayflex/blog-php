<?php

	session_start();
	
	include_once 'functions.php';

	if ($_GET['log'] == 'out') {
		logOut();
		header('Location: index.php');
		exit();
	}

	$isAuth = isAuth();

	$sql = sprintf("SELECT * FROM %s ORDER BY `date` DESC", DB_TABLE);
	$query = db_query($sql);
	$posts = $query->fetchAll(PDO::FETCH_ASSOC);

	foreach ($posts as $post) {
		if ($isAuth) { ?>
			<a href="post.php?id=<?=$post['id']?>">
				<?=$post['title']?>
			</a> 
			<a href="edit.php?id=<?=$post['id']?>">
				&#9998
			</a>
			<a href="delete.php?id=<?=$post['id']?>" onclick="return confirm('Удалить статью?')">
				&#10006
			</a>
			<br>
		<?php } else { ?>
			<a href="post.php?id=<?=$post['id']?>">
				<?=$post['title']?>
			</a>
			<br>
		<?php } 
	}
?>

<?php if ($isAuth): ?>
	<br>
	<a href="add.php">Добавить</a>
	<br>
	<a href="index.php?log=out">Выйти</a>
<?php else :?>
	<br>
	<a href="login.php">Войти</a>
<?php endif; ?>