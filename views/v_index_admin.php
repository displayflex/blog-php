<?php
	use core\Config;
?>

<h2 class="icon fa-file-text-o">Недавние посты</h2>
<?php foreach ($posts as $post): ?>
	<article class="box excerpt">
		<a href="#" class="image left">
			<img src="<?=Config::ROOT?>assets/images/pic0<?=mt_rand(4,6)?>.jpg" alt="" />
		</a>
		<div>
			<header>
				<span class="date">
					<?=$post['date']?>
				</span>
				<h3>
					<a href="<?=Config::ROOT?>post/<?=$post['id']?>">
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
			<a href="<?=Config::ROOT?>post/edit/<?=$post['id']?>">
				<i class="fa fa-pencil"></i> Редактировать
			</a>
			<br>
			<a href="<?=Config::ROOT?>post/delete/<?=$post['id']?>" onclick="return confirm('Удалить статью?')">
				<i class="fa fa-times"></i> Удалить
			</a>
		</div>
	</article>
<?php endforeach; ?>

<a class="button alt icon fa-file-o" href="<?=Config::ROOT?>post/add">Добавить</a>
