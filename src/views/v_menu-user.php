<?php
	use ftwr\blogphp\core\Config;
?>

<div class="user-menu">
<?php if (!$isAuth): ?>
	<a class="button medium icon fa-sign-in" href="<?=Config::ROOT?>user/sign-in">Войти</a>
	<br>
	<br>
	<a class="button alt icon fa-user-plus" href="<?=Config::ROOT?>user/sign-up">Зарегистрироваться</a>
<?php else: ?>
	<a class="button alt icon fa-sign-out" href="<?=Config::ROOT?>user/sign-out">Выйти</a>
<?php endif; ?>
</div>
