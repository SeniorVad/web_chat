<? top('Мои диалоги') ?>

<?php  
$count = mysqli_fetch_row(mysqli_query($CONNECT, "SELECT COUNT(`id`) FROM `dialog` WHERE `send` = $_SESSION[id] OR `recive` = $_SESSION[id]"));
if (!$count[0]) 
	message('У вас нет диалогов');
?>

<?php 

if (!$param['page']) {
$param['page'] = 1;
$result = mysqli_query($CONNECT, "SELECT * FROM `dialog` WHERE `send` = $_SESSION[id] OR `recive` = $_SESSION[id] ORDER BY `id` DESC LIMIT 0, 5");
} else {
$start = ($param['page'] - 1) * 5;
$result = mysqli_query($CONNECT, str_replace('START', $start, "SELECT * FROM `dialog` WHERE `send` = $_SESSION[id] OR `recive` = $_SESSION[id] ORDER BY `id` DESC LIMIT START, 5"));
}


PageSelector('/dialog/page/', $param['page'], $count);

while ($row = mysqli_fetch_assoc($result)) {
if ($row['status']) $status = '<p style="color:green;">Прочитано</p>';
else $status = '<p style="color:red;text-shadow:none;">Не прочитано</p>';

if ($row['recive'] == $_SESSION['id']) $row['recive'] = $row['send'];
$user = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `login` FROM `users` WHERE `id` = $row[recive]"));
echo '<a href="/message"><div class="ChatBlock"><span>'.$status.'</span>Диалог с '.$user['login'].'</div></a>';
}
?>

<? bottom() ?>