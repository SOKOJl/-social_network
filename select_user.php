<?php
//Подключаем БД
require_once('config.php');
$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));

//Ищем в БД пользователя по номеру странички (она соответствует идентификатору пользователя)
//не учитываем при поиске пользователей с неактивированной страничкой
$sql = "SELECT moderation, last_name, first_name, birthday, city, status, avatarka FROM users WHERE user_id='$page_id' AND moderation=1";

//Ищем в БД пользователя по номеру странички (она соответствует идентификатору пользователя)
//Если зашел Admin, то учитываем при поиске пользователей с неактивированной страничкой
if ($_SESSION['type_user'] == 'admin') {
	$sql = "SELECT moderation, last_name, first_name, birthday, city, status, avatarka FROM users WHERE user_id='$page_id'";
};
$data = mysqli_query($db,$sql);
$row = mysqli_fetch_array($data);
@mysqli_close($db);
?>