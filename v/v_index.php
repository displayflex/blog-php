<?php foreach ($posts as $post): ?>
	<a href="post.php?id=<?=$post['id']?>">
		<?=$post['title']?>
	</a>
	<br>
<?php endforeach; ?>

<br>
<a href="login.php">Войти</a>