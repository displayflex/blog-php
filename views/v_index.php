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
		</div>
	</article>
<?php endforeach; ?>

<a class="button medium icon fa-sign-in" href="<?=ROOT?>user/login">Войти</a>