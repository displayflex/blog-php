<h2 class="icon fa-file-text-o">Недавние посты</h2>
<?php foreach ($posts as $post): ?>
	<article class="box excerpt">
		<a href="#" class="image left">
			<img src="<?=ROOT?>assets/images/pic0<?=mt_rand(4,6)?>.jpg" alt="" />
		</a>
		<div>
			<header>
				<span class="date">
					<?=$post['date']?>
				</span>
				<h3>
					<a href="<?=ROOT?>post/<?=$post['id']?>">
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
			<a href="<?=ROOT?>post/edit/<?=$post['id']?>">
				<i class="fa fa-pencil"></i> Редактировать
			</a>
			<br>
			<a href="<?=ROOT?>post/delete/<?=$post['id']?>" onclick="return confirm('Удалить статью?')">
				<i class="fa fa-times"></i> Удалить
			</a>
		</div>
	</article>
<?php endforeach; ?>

<a class="button alt icon fa-file-o" href="<?=ROOT?>post/add">Добавить</a>
<a class="button alt icon fa-sign-out" href="<?=ROOT?>user/logout">Выйти</a>
