<?php
	include_once __DIR__ . '/../core/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=`device-width`, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<!-- Делает редирект на индекс через 5 секунд -->
	<meta http-equiv="refresh" content="5;URL=<?=ROOT?>">
	
	<title>404</title>
	<link rel="stylesheet" href="<?=ROOT?>/assets/css/main.css" />
</head>
<body>
		<header id="header-wrapper" class="header-403">
			<div>
				<h1>Ошбибка 404</h1>
				<p>Сейчас вы будете перенаправлены на главную</p>
			</div>
		</header>
	</div>
</body>
</html>
