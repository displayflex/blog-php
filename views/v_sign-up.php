<h2 class="icon fa-file-text-o">Регистрация</h2>
<form method="post">
	Введите логин<br>
	<input type="text" name="login" value="<?=$login?>"><br>
	Введите пароль<br>
	<input type="password" name="password" value="<?=$password?>"><br>
	Подтвердите пароль<br>
	<input type="password" name="submitPassword" value="<?=$submitPassword?>"><br>
	<input type="submit" value="Зарегистрироваться">
</form>
<p class="warning-msg"><?=$msg;?></p>
