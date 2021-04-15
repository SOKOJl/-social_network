<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="bootstrap.min.css">
<title>Печеньки</title>
</head>
<body>
<div class="logo">
<? if (isset($_SESSION['user_id'])){ ?>
	<hr>
	Вы зарегистрированы в приложении как <?php echo $_SESSION['type_user']?>. Для того чтобы выйти пройдите по ссылке <a href="logout.php">ВЫХОД</a>
	<hr>
<?php } ?>
	<h1><a href="index.php">Pechenki.ru</a></h1>
</div>
<div class="main_menu">
	<nav class="cl-effect-1">
	<a href="index.php">Главная</a>
	<?php if (isset($_SESSION['user_id'])){ echo ' | <a href="search.php">Поиск </a>';}; ?>
	</nav>
</div>

