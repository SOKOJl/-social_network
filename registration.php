<?php
if ((!(isset($msg)))||(!(empty($msg)))) { ?>
<div>
<h1>Добро пожаловать на сайт Печеньки!</h1>
<p>Для регистрации на сайте Вам необходимо заполнить форму регистрации (обязательны к заполнению все поля, кроме почты и телефона - обязательно заполнение одного из этих полей) и согласиться с правилами данного сайта</p>
<form action="index.php" method="post">
	<table>
		<tr>
			<td>Логин:</td>
			<td><input type="text" name="login" value="<?php echo $_POST['login']?>"></td>
		</tr>
		<tr>
			<td>Пароль:</td>
			<td><input type="password" name="password1" value="<?php echo $_POST['password1']?>"></td>
		</tr>
		<tr>
			<td>Повторите пароль:</td>
			<td><input type="password" name="password2" value="<?php echo $_POST['password2']?>"></td>
		</tr>
		<tr>
			<td>Имя:</td>
			<td><input type="text" name="first_name" value="<?php echo $_POST['first_name']?>"></td>
		</tr>
		<tr>
			<td>Фамилия:</td>
			<td><input type="text" name="last_name" value="<?php echo $_POST['last_name']?>"></td>
		</tr>
		<tr>
			<td>Дата рождения (формат: "ГГГГ-ММ-ДД"):</td>
			<td><input type="text" name="birthday" value="<?php echo $_POST['birthday']?>"></td>
		</tr>
		<tr>
			<td>Город:</td>
			<td><input type="text" name="city" value="<?php echo $_POST['city']?>"></td>
		</tr>
		<tr>
			<td>Почта:</td>
			<td><input type="text" name="e_mail" value="<?php echo $_POST['e_mail']?>"></td>
		</tr>
		<tr>
			<td>Тефелон (формат: "9660002233"):</td>
			<td><input type="text" name="phone" value="<?php echo $_POST['phone']?>"></td>
		</tr>
		<tr>
			<td>Согласен с правилами: <input type="checkbox" name="soglasie" <?php if ($_POST['soglasie']) { echo 'CHECKED';}  ?>></td>
			<td><textarea>Правила сайта. Вставим позже</textarea></td>
		</tr>
	</table>
	<input type="submit" name="registration" value="Зарегистрироваться">
</form>
</div>
<?php
}
?>