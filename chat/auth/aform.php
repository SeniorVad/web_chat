<?php 

if( $_POST['editpas_f']) {

	if( $_POST['password'] and md5($_POST['password']) != $_SESSION['password'] ){
		password_valid();
		mysqli_query($CONNECT,"UPDATE `users` SET `password` = `$_POST[password]`");
}

message('Сохранено');
}

?>