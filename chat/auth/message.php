<? top('Диалог c'.$user['login'] ) 
?>

<?php  
$param['id'] += 1;
// var_dump($param);
$info = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `recive`, `send` FROM `dialog` WHERE `id` = $param[id]"));
// var_dump($info);
if (!in_array($_SESSION['id'], $info)) message('Диалог не найден.');

if ($info['recive'] == $_SESSION['id']) mysqli_query($CONNECT, "UPDATE `dialog` SET `status` = 1 WHERE `id` = $param[id]");

if ($info['send'] == $_SESSION['id']) $info['send'] = $info['recive'];
	
$user = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `login` FROM `users` WHERE `id` = $info[send]"));

$query = mysqli_query($CONNECT, "SELECT * FROM `message` WHERE `did` = $param[id] ORDER BY `id` DESC");

while ( $row = mysqli_fetch_assoc($query) ) {
if ($info['send'] == $row['user']) $row['user'] = $user['login'];
else $row['user'] = $_SESSION['login'];
echo '<div class="ChatBlock"><span>'.$row['date'].' от '.$row['user'].' - </span>'.$row['text'].'</div>';
}
?>
<form method="POST" action="/send">
<input type="hidden" name="login" value="<?php echo $user['login'] ?>">
<br><textarea class="ChatMessage" name="text" placeholder="Текст сообщения....." cols="120" rows="5" required></textarea>
<br><input type="submit" name="enter" value="Отправить"> <input type="reset" value="Очистить">
</form>

<? bottom() ?>