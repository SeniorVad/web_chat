<? top('Рефералы') ?>

<h1>Мои друзья:</h1>

<p style="font-size:18px;">Ваша ссылка для приглашения друзей: <b> http://chat?ref=<?=$_SESSION['id']?> </b> </p>
<?

$query = mysqli_query($CONNECT,"SELECT `email` FROM `users` WHERE `ref` = $_SESSION[id]");
 $query2 = mysqli_query($CONNECT,"SELECT `login` FROM `users` WHERE `ref` = $_SESSION[id]");
if( !mysqli_num_rows($query) ) exit('<p style="color:white;text-shadow:1px 1px 5px #ff00ff;text-indent:5%;">Список приглашенных друзей пуст</p>');

$i = 1;

while( $row = mysqli_fetch_assoc($query) ) {
	echo'<p>#'.($i++).'.Email - '.$row['email'].'</p>';
}
$k = 1;
while ($row = mysqli_fetch_assoc($query2) ) {
	echo'<p>#'.($k++).'.Логин - '.$row['login'].'</p>';
}

 bottom() ?>