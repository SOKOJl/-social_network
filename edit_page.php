<?php
if(($_SESSION['user_id'] == $_GET['page_id'])||($_SESSION['type_user'] == 'admin')){
	
	//Если нажата кнопка сохранить изменения, сохраняем их в БД
	if(isset($_POST['edit_page'])){
		$msg = array();
		require_once('config.php');
		if (!empty($_FILES['avatarka']['name'])) {
			if (!(($_FILES['avatarka']['type'] == 'image/gif')||($_FILES['avatarka']['type'] == 'image/jpeg')||($_FILES['avatarka']['type'] == 'image/jpg')||($_FILES['avatarka']['type'] == 'image/png'))) {
				$msg[] = 'Разрешено загружать только картинки';
			};
			if ($_FILES['avatarka']['size'] <= 0) {
				$msg[] = 'Ошибка загрузки файла';
			};
			if ($_FILES['avatarka']['size'] >= MAX_FILE_SIZE) {
				$msg[] = 'Максимальный размер файла 32 Кб';
			};
			if (empty($msg)) {
				$avatarka = DIR_IMAGES.$_GET['page_id'].'_'.time().'.'.@end(explode('/', $_FILES['avatarka']['type']));
				move_uploaded_file($_FILES['avatarka']['tmp_name'],$avatarka);
				$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
				$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));
				$sql = "UPDATE users SET avatarka='$avatarka' WHERE user_id=$page_id";
				$data = mysqli_query($db,$sql);
				@mysqli_close($db);		
			}
			else {
				foreach ($msg as $message) {
					echo '<p id="attention">'.$message.'</p>';
				}
			}
		};
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$status = mysqli_real_escape_string($db,trim($_POST['status']));
		$first_name = mysqli_real_escape_string($db,trim($_POST['first_name']));
		$last_name = mysqli_real_escape_string($db,trim($_POST['last_name']));
		$birthday = mysqli_real_escape_string($db,trim($_POST['birthday']));
		$city = mysqli_real_escape_string($db,trim($_POST['city']));
		$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));
		if (isset($_POST['moderation'])) {
			$moderation = $_POST['moderation'];
			$sql = "UPDATE users SET moderation=$moderation, status='$status', first_name='$first_name', last_name='$last_name', birthday='$birthday', city='$city' WHERE user_id=$page_id";
		}
		else {
			$sql = "UPDATE users SET status='$status', first_name='$first_name', last_name='$last_name', birthday='$birthday', city='$city'
					WHERE user_id=$page_id";
		};
		$data = mysqli_query($db,$sql);
		@mysqli_close($db);
		echo 'Профиль успешно изменен.';
	}
	
	//Если нажата ссылка удалить фото, то удаляем с БД и файл
	if ((isset($_GET['ft']))&&(!empty($_GET['ft']))) {
		$dir = './'.$_GET['ft'];
		unlink($dir);
		require_once('config.php');
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));
		$sql = "UPDATE users SET avatarka=NULL WHERE user_id=$page_id";
		$data = mysqli_query($db,$sql);
		@mysqli_close($db);
		echo 'Аватарка удалена';
	}
	
	require_once('select_user.php');
	
	if (!$row['moderation']) {
		echo '<p id="attention">Пользователь не одобрен!!!</p>';
	}
?>
<div>
	<form enctype="multipart/form-data" action="index.php?page_id=<?php echo $_GET['page_id']?>&edit=1" method="post">
	<table>
		<?php
		if ($_SESSION['type_user'] == 'admin') {
		echo '<tr>';
			echo '<td>Одобрить пользователя?:</td>';
			if ($row['moderation']) {
				echo '<td><input type="radio" name="moderation" value=1 CHECKED>Да<br>';
			}
			else {
				echo '<td><input type="radio" name="moderation" value=1>Да<br>';
			};
			if (!$row['moderation']) {
				echo '<td><input type="radio" name="status" value=0 CHECKED>Нет';
			}
			else {
				echo '<td><input type="radio" name="status" value=0>Нет';
			};
			echo '</td>';
		echo '</tr>';
		}
		?>
		<tr>
			<td>Аватарка:</td>
			<td><input type="file" name="avatarka" value="<?php echo $row['avatarka']?>"></td>
			<td><a href="index.php?page_id=<?php echo $_GET['page_id']?>&edit=1&ft=<?php echo $row['avatarka']?>">Удалить фото</a></td>
		</tr>
		<tr>
		<tr>
			<td>Статус:</td>
			<td><input type="text" name="status" value="<?php echo $row['status']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Имя:</td>
			<td><input type="text" name="first_name" value="<?php echo $row['first_name']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Фамилия:</td>
			<td><input type="text" name="last_name" value="<?php echo $row['last_name']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Дата рождения:</td>
			<td><input type="text" name="birthday" value="<?php echo $row['birthday']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Город:</td>
			<td><input type="text" name="city" value="<?php echo $row['city']?>"></td>
			<td></td>
		</tr>
	</table>
	<input type="submit" name="edit_page" value="Сохранить изменения">
	</form>
	<a href="index.php?page_id=<?php echo $_GET['page_id']?>">&lt&lt Назад к профилю</a>
</div>
<?php
}
else
	echo 'Простите, но Вы можете редактировать только свою страничку';
?>