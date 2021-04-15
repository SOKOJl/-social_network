<?php
	require_once('authorization.php');
	require_once('header.php');
	if (!isset($_SESSION[user_id])){
		
		//Если пользователь нажал кнопку регистрации
		if (isset($_POST['registration'])||isset($_GET['registration'])) {
			require_once('registration.php');
		}
		else {
			require_once('login.php');
		}
	}
	else {
		if (!isset($_GET['edit'])) {
			require_once('page.php');
		}
		else {
			require_once('edit_page.php');
		}
	}
	require_once('footer.php');
?>