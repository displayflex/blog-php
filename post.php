<?php

	$fname = $_GET['fname'] ?? null;

	if($fname === null){
		echo 'Ошибка 404, не передано название';
	}
	/* 
		todo: проверить корректность параметра 
				- содержит только цифры
				- (*) содержит только цифры, латинские буквы и -
	*/
	elseif(!file_exists('data/' . $fname)){
		echo 'Ошибка 404. Нет такой статьи!';
	}
	else{
		$content = file_get_contents('data/' . $fname);

		echo nl2br($content);
	}