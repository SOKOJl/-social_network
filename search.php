<?php
	require_once('authorization.php');
	require_once('header.php');
?>
<div>
<form action="search.php" method="get">
	<fieldset>
		<legend>Поиск по:</legend>
		<table>
			<tr>
				<td><label for="search_first_name">Имя:</label></td>
				<td><input type="text" id="search_first_name" name="search_first_name"></td>
			</tr>
			<tr>
				<td><label for="search_last_name">Фамилия:</label></td>
				<td><input type="text" id="search_last_name" name="search_last_name"></td>
			</tr>
			<tr>
				<td><label for="search_city">Город:</label></td>
				<td><input type="text" id="search_city" name="search_city"></td>
			</tr>
			<tr>
				<td><input type="submit" name="search" value="Найти"></td>
				<?php
					if ($_SESSION['type_user'] == 'admin') {
						echo '<td><input type="submit" name="new_people" value="Ожидают модерации"></td>';
					}
					else {
						echo '<td></td>';
					}
				?>
			</tr>
		</table>
	</fieldset>
</form>
</div>
<?php

if ((isset($_GET['new_people']))&&($_SESSION['type_user'] == 'admin')) {
	require_once('config.php');
	$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$sql = "SELECT user_id, moderation, last_name, first_name, city, avatarka
			FROM users 
			WHERE moderation=0";
	$data = mysqli_query($db,$sql);
	while ($row = mysqli_fetch_array($data)) {
	
	//Выводим людей ожидающих модерацию
?>
<div>
	<table>
		<tr>
		<?php
			if (empty($row['avatarka'])) {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
			}
			else {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
			}
			echo '<td><img id="img_search" src="'.$dir.'" alt="'.$row["first_name"].'"></td>';
			echo '<td><a href="index.php?page_id='.$row["user_id"].'">'.$row["first_name"].' '.$row["last_name"].'</a></td><td>'.$row["city"].'</td>';
		?>
		</tr>
	</table>
</div>
<?php
	}
	@mysqli_close($db);
}

if ((isset($_GET['search']))&&(isset($_SESSION['user_id']))) {
	require_once('config.php');
	$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$search_first_name = mysqli_real_escape_string($db,trim($_GET['search_first_name']));
	$search_last_name = mysqli_real_escape_string($db,trim($_GET['search_last_name']));
	$search_city = mysqli_real_escape_string($db,trim($_GET['search_city']));
	if ((empty($search_first_name))&&(empty($search_last_name))&&(empty($search_city))) {
		$msg[] = 'Не заданы критерии для поиска. Укажите хотя бы одно.';
		echo 'Не заданы критерии для поиска. Укажите хотя бы одно.';
	}
	
	
	if (empty($msg)) {
		$str = '(user_id > 0)';
		if (!(empty($search_first_name))) {$str = $str."AND first_name='$search_first_name'";};
		if (!(empty($search_last_name))) {$str = $str."AND last_name='$search_last_name'";};
		if (!(empty($search_city))) {$str = $str."AND city='$search_city'";};
		$sql = "SELECT user_id, moderation, last_name, first_name, city, avatarka
				FROM users 
				WHERE ".$str;
		if ($_SESSION['type_user'] != 'admin') {
			$sql = $sql.'AND moderation=1';
		}
		$data = mysqli_query($db,$sql);
		while ($row = mysqli_fetch_array($data)) {
			if ($_SESSION['user_id'] !== $row['user_id']) {
			//Выводим форму поиска
?>
<div>
	<table>
		<tr>
		<?php
			if (empty($row['avatarka'])) {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
			}
			else {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
			}
			echo '<td><img id="img_search" src="'.$dir.'" alt="'.$row["first_name"].'"></td>';
			echo '<td><a href="index.php?page_id='.$row["user_id"].'">'.$row["first_name"].' '.$row["last_name"].'</a></td><td>'.$row["city"].'</td>';
		?>
		</tr>
	</table>
</div>
<?php
			}
		}
		@mysqli_close($db);
	}
}
require_once('footer.php');
?>