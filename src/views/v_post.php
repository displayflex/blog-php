<?php
	use ftwr\blogphp\core\Config;
?>

<h2><?=nl2br($post['title'])?></h2>
<small><?=nl2br($post['date'])?></small>
<p><?=nl2br($post['content'])?></p>
<a href="<?=Config::ROOT?>"><i class="fa fa-level-up"></i> На главную</a>
