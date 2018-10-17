<form method="post">
	Логин<br>
	<input type="text" name="login" value="<?=$login?>"><br>
	Пароль<br>
	<input type="password" name="password" value="<?=$password?>"><br>
	<input type="checkbox" name="remember">Запомнить<br>
	<input type="submit" value="Войти">
</form>
<p><?=$msg?></p>