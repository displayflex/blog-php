<?php foreach ($posts as $post): ?>
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
<?php endforeach; ?>

<br>
<a href="add.php">Добавить</a>
<br>
<a href="index.php?log=out">Выйти</a>
