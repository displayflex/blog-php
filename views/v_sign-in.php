<h2 class="icon fa-file-text-o">Войти на сайт</h2>

<form <?=$formBuilder->method()?> class="form sign-up">
	<?=$formBuilder->inputSign()?>
	<?php foreach ($formBuilder->fields() as $field) : ?>
		<div class="form-item">
			<?=$field?>
		</div>
	<?php endforeach; ?>
</form>

<p class="warning-msg"><?=$msg;?></p>
