<?

if ($_POST['login_f']) {
	captcha_valid();
	email_valid();
	password_valid();


	if ( !mysqli_num_rows(mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `email` = '$_POST[email]' AND `password` = '$_POST[password]'")) )
		message('Аккаунт не найден');


	$row = mysqli_fetch_assoc( mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `email` = '$_POST[email]'") );

	
	foreach ($row as $key => $value) 
		$_SESSION[$key] = $value;

	go('profile');

}

else if ($_POST['register_f']) {
	captcha_valid();
	login_valid();
	email_valid();
	password_valid();
	name_valid();
	sename_valid();
	
	if ( mysqli_num_rows(mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `login` = '$_POST[login]'")) )
	 	message('Этот логин уже занят');
	
	if ( mysqli_num_rows(mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `email` = '$_POST[email]'")) )
	 	message('Этот E-mail занят');



	 $code = random_str(5);

	 $_SESSION['confirm'] = array(
	 	'type' => 'register',
	 	'login' => $_POST['login'],
	 	'email' => $_POST['email'],
	 	'password' => $_POST['password'],
	 	'name' => $_POST['name'],
	 	'sename' => $_POST['sename'],
	 	'code' => $code,
	 	);

mail($_POST['email'], 'Регистрация', "Код подтверждения регистрации: <b>$code</b>");

go('confirm');

}

else if ($_POST['recovery_f']) {
		captcha_valid();
		email_valid();

		if ( !mysqli_num_rows(mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `email` = '$_POST[email]'")) )
			message('Аккаунт не найден');

		$code = random_str(5);

	 $_SESSION['confirm'] = array(
	 	'type' => 'recovery',
	 	'email' => $_POST['email'],
	 	'code' => $code,
	 	);

	 mail($_POST['email'], 'Восстановление пароля', "Код подтверждения восстановление пароля: <b>$code</b>");

	 go('confirm');


}

else if ($_POST['confirm_f']) {

	if ( $_SESSION['confirm']['type'] == 'register') {

		if ( $_SESSION['confirm']['code'] != $_POST['code'] )
				message('Код подтверждения регистрации указан неверно');

			if( is_numeric($_COOKIE['ref']) ) 
				$ref = $_COOKIE['ref'];
			else 
				$ref = 0;


mysqli_query($CONNECT, 'INSERT INTO `users` VALUES ("", "'.$_SESSION['confirm']['login'].'","'.$_SESSION['confirm']['email'].'", "'.$_SESSION['confirm']['password'].'","'.$_SESSION['confirm']['name'].'","'.$_SESSION['confirm']['sename'].'",'.$ref.')');
			unset($_SESSION['confirm']);

			go('login');

		}

		else if ( $_SESSION['confirm']['type'] == 'recovery') {

			$newpass = random_str(10);

			mysqli_query($CONNECT, 'UPDATE `users` SET `password` = "'.md5($newpass).'" WHERE `email` = "'.$_SESSION['confirm']['email'].'"');
			unset($_SESSION['confirm']);

			message("Ваш новый пароль: $newpass");

	}

	else not_found();

}

?>