
Создаем таблицу пользователей

CREATE TABLE users (
	user_id int (10) AUTO_INCREMENT,
	type_user enum ('admin', 'user') DEFAULT 'user',
	moderation BOOLEAN DEFAULT 0,
	login varchar (20) NOT NULL,
	password varchar (40) NOT NULL,
	first_name varchar (20) NOT NULL,
	last_name varchar (20) NOT NULL,
	birthday date NOT NULL,
	city varchar (20) NOT NULL,
	e_mail varchar (30),
	phone int (10),
	status text,
	avatarka varchar (11),
	PRIMARY KEY (user_id)
	);
	
Создаем таблицу друзей

CREATE TABLE friends (
	id int AUTO_INCREMENT,
	user_id int (10) NOT NULL,
	friend_id int (10) NOT NULL,
	send_invitation BOOLEAN DEFAULT 0,
	confirmed BOOLEAN DEFAULT 0,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES users (user_id),
	FOREIGN KEY (friend_id) REFERENCES users (user_id)
	);	
	
Добавляем администратора в таблицу users

INSERT INTO users (type_user, moderation, login, password, first_name, last_name, birthday, city, e_mail, phone) VALUES ('admin', 1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Administrator', 'Admin', '1990-01-01', 'Москва', 'admin@mail.ru', '9660428605');