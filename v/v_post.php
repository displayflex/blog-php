<?php if (!$post): ?>
	<p>Ошибка 404. Нет такой статьи!</p>
<?php else: ?>
	<h2><?=nl2br($post['title'])?></h2>
	<small><?=nl2br($post['date'])?></small>
	<p><?=nl2br($post['content'])?></p>
<?php endif; ?>
