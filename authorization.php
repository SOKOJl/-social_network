<?php
	session_start();
	if (!isset($_SESSION['user_id'])){
	
		//Если в сессии нет данных о пользователе, пробуем получить их из куки
		if(isset($_COOKIE['user_id'])){
			$_SESSION['user_id'] = $_COOKIE['user_id'];
			$_SESSION['type_user'] = $_COOKIE['type_user'];
		}
		
		//Если пользователь нажал кнопку Войти для авторизации
		if(isset($_POST['submit'])){
			if(isset($_POST['login'])&&isset($_POST['password'])&&!empty($_POST['login'])&&!empty($_POST['password'])){
				require_once('config.php');
				$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
				$login = mysqli_real_escape_string($db,trim($_POST['login']));
				$password = mysqli_real_escape_string($db,trim($_POST['password']));
				$sql = "SELECT user_id, type_user FROM users WHERE login='$login' AND password=SHA('$password') AND moderation=1";
				$data = mysqli_query($db,$sql);
				@mysqli_close($db);
				if(mysqli_num_rows($data) == 1){
					//Вход в приложение рпошел успешно, присваиваем значения идентификатора пользователя и его типа пользователя (type_user)
					//переменным сессии и куки и переадресуем пользователя на главную страницу
					$row = mysqli_fetch_array($data);
					$_SESSION['user_id'] = $row['user_id'];
					$_SESSION['type_user'] = $row['type_user'];
					setcookie('user_id', $row['user_id'], time()+(60 * 60 * 24 * 30)); //Срок действия куки истекает через 30 дней
					setcookie('type_user', $row['type_user'], time()+(60 * 60 * 24 * 30)); //Срок действия куки истекает через 30 дней
					
					//Когда пользователь прошел авторизацию перенаправляем его на его страничку
					// $page_id = берем из запроса в БД и передаем в GET запросе
					$home_url='http://'.$_SERVER['HTTP_HOST'].'/index.php?page_id='.$row['user_id']; //Если скрипт вложен в папку, то добавить  
																			  //    .dirname($_SERVER['PHP_SELF']).
					header('Location: '.$home_url);
				}
				else
					echo '<p id="attention">Не существует такой комбинации Логина и Пароля!</p>';
			}
			else
				echo '<p id="attention">Заполните поля Логин и Пароль!</p>';
	
		}

		
		//Если пользователь нажал кнопку Зарегистрироваться для регистрации
		if (isset($_POST['registration'])) {
		$msg = array();
		
			//Ищем ошибки в заполнении формы
			if (empty($_POST['login'])||empty($_POST['password1'])||empty($_POST['password2'])||empty($_POST['last_name'])||empty($_POST['first_name'])||
			empty($_POST['birthday'])||empty($_POST['city'])||(empty($_POST['e_mail'])&&empty($_POST['phone']))||!($_POST['soglasie'])) {
				$msg[] = 'Вы не заполнили все необходимые поля или не подтвердили согласие с правилами сайта. Заполните их или подтвердите согласие.';
				if (empty($_POST['login'])) { $msg[] = 'Логин';};
				if (empty($_POST['password1'])) { $msg[] = 'Пароль1';};
				if (empty($_POST['password2'])) { $msg[] = 'Пароль2';};
				if (empty($_POST['last_name'])) { $msg[] = 'Фамилия';};
				if (empty($_POST['first_name'])) { $msg[] = 'Имя';};
				if (empty($_POST['birthday'])) { $msg[] = 'Дата Рождения';};
				if (empty($_POST['city'])) { $msg[] = 'Город';};
				if (empty($_POST['e_mail'])) { $msg[] = 'Почта';};
				if (empty($_POST['phone'])) { $msg[] = 'Телефон';};
				if (!$_POST['soglasie']) { $msg[] = 'Не подтверждено согласие с правилами сайта';};
			}
			else { //Если все поля заполнены начало блока
	
				//Подключаем БД для проверки уникальности полей
				require_once('config.php');
				$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
				//Проверка уникальности логина
				$login = mysqli_real_escape_string($db,trim($_POST['login']));
				$sql = "SELECT login FROM users WHERE login='$login'";
				$data = mysqli_query($db,$sql);
				if(mysqli_num_rows($data) == 1){
					$msg[] = 'Пользователь с таким Логином уже существует';
				}
	
				//Проверка уникальности почты
				$e_mail = mysqli_real_escape_string($db,trim($_POST['e_mail']));
				if (!(empty($e_mail))) {
					$sql = "SELECT e_mail FROM users WHERE e_mail='$e_mail'";
					$data = mysqli_query($db,$sql);
					if(mysqli_num_rows($data) == 1){
						$msg[] = 'Пользователь с такой Почтой уже существует';
					}
				}
	
				//Проверка уникальности телефона
				$phone = mysqli_real_escape_string($db,trim($_POST['phone']));
				if (!(empty($phone))) {
					$sql = "SELECT login FROM users WHERE phone='$phone'";
					$data = mysqli_query($db,$sql);
					if(mysqli_num_rows($data) == 1){
						$msg[] = 'Пользователь с таким Телефоном уже существует';
					}
				}
				@mysqli_close($db);
			};//Если все поля заполнены конец блока
	
			if ($_POST['password1'] != $_POST['password2']) {
				$msg[] = 'Пароли не совпадают';
			};
	
			//Если все в порядке, то добавляем в БД нового пользователя
			if (empty($msg)) {
				require_once('config.php');
				$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
				$login = mysqli_real_escape_string($db,trim($_POST['login']));
				$password = mysqli_real_escape_string($db,trim($_POST['password1']));
				$first_name = mysqli_real_escape_string($db,trim($_POST['first_name']));
				$last_name = mysqli_real_escape_string($db,trim($_POST['last_name']));
				$birthday = mysqli_real_escape_string($db,trim($_POST['birthday']));
				$city = mysqli_real_escape_string($db,trim($_POST['city']));
				$e_mail = mysqli_real_escape_string($db,trim($_POST['e_mail']));
				$phone = mysqli_real_escape_string($db,trim($_POST['phone']));
				if ((!(empty($e_mail)))&&(!(empty($phone)))) {
					$sql = "INSERT INTO users (login, password, first_name, last_name, birthday, city, e_mail, phone) VALUES ('$login', SHA('$password'), '$first_name', '$last_name', '$birthday', '$city', '$e_mail', '$phone');";
				}
				else {
					if (!(empty($e_mail))) {
						$sql = "INSERT INTO users (login, password, first_name, last_name, birthday, city, e_mail) VALUES ('$login', SHA('$password'), '$first_name', '$last_name', '$birthday', '$city', '$e_mail');";
					}
					if (!(empty($phone))) {
						$sql = "INSERT INTO users (login, password, first_name, last_name, birthday, city, phone) VALUES ('$login', SHA('$password'), '$first_name', '$last_name', '$birthday', '$city', '$phone');";
					}
				}
				$data = mysqli_query($db,$sql);
				@mysqli_close($db);
				$home_url='http://'.$_SERVER['HTTP_HOST'].'/index.php'.$row['user_id'];
				header('Refresh: 5; URL='.$home_url);
				echo 'Новый пользователь добавлен! Дождитесь подтверждения модератора и можете пользоваться этим сайтом.';
			}
	
			//Если есть ошибки, выводим их
			else {
				foreach ($msg as $message) {
					echo '<p id="attention">'.$message.'</p>';
				}
			}
		}
	}
?>