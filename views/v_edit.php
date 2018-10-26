<h2 class="icon fa-file-text-o">Редактировать пост</h2>
<form method="post">
	Название<br>
	<input type="text" name="title" value="<?=$title?>"><br>
	Контент<br>
	<textarea name="content"><?=$content?></textarea><br>
	<input type="submit" value="Изменить">
</form>
<p class="warning-msg"><?=$msg;?></p>
