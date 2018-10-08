<?php foreach ($posts as $post): ?>
	<article class="box excerpt">
		<a href="#" class="image left">
			<img src="assets/images/pic0<?=mt_rand(4,6)?>.jpg" alt="" />
		</a>
		<div>
			<header>
				<span class="date">
					<?=$post['date']?>
				</span>
				<h3>
					<a href="index.php?c=post&id=<?=$post['id']?>">
						<?=$post['title']?>
					</a>
				</h3>
			</header>
			<p>
				<?php
					if (strlen($post['content']) >= 150) {
						echo substr($post['content'], 0, 150) . ' ...';
					} else {
						echo $post['content'];
					}
				?>
			</p>
			<a href="index.php?c=edit&id=<?=$post['id']?>">
				<i class="fa fa-pencil"></i> Редактировать
			</a>
			<br>
			<a href="index.php?c=delete&id=<?=$post['id']?>" onclick="return confirm('Удалить статью?')">
				<i class="fa fa-times"></i> Удалить
			</a>
		</div>
	</article>
<?php endforeach; ?>

<a class="button alt icon fa-file-o" href="index.php?c=add">Добавить</a>
<a class="button alt icon fa-sign-out" href="index.php?log=out">Выйти</a>
