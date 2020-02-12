<?

if(is_numeric($_GET['ref']) ){
	setcookie('ref', $_GET['ref'], strtotime('+1 week') );
	header('location: /home');
}


if ( $_SERVER['REQUEST_URI'] == '/' ) 
	$page = 'home';
else {
	$page = substr($_SERVER['REQUEST_URI'], 1);
	if ( !preg_match('/^[A-z0-9]{3,15}$/', $page) ) not_found();
}

$CONNECT = mysqli_connect('localhost', 'root', '', 'chat');

if ( !$CONNECT ) exit('MySQL error');



session_start();




if ( file_exists('all/'.$page.'.php') ) include 'all/'.$page.'.php';

else if ( $_SESSION['id'] and file_exists('auth/'.$page.'.php') ) include 'auth/'.$page.'.php';

else if ( !$_SESSION['id'] and file_exists('guest/'.$page.'.php') ) include 'guest/'.$page.'.php';

else not_found();



function message( $text ) {
	exit('{ "message" : "'.$text.'"}');
}

function go( $url ) {
	exit('{ "go" : "'.$url.'"}');
}

function random_str( $num = 30 ) {
	return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $num);
}


function not_found() {
	exit('<p style="color:red; text-align:center;font-size:20px;">Страница 404</p>');
}

function captcha_show() {

	$questions = array(
		1 => 'Столица Украины?',
		2 => 'Назовите спутник Земли?',
		3 => '37 больше 28?',
		4 => 'В слове «ужин» 5 букв?',
		5 => 'Какое время суток противоположно дню?',
		6 => 'Сколько дней в январе?',
		7 => 'Столица США ?',
		8 => 'Как зовут первого человека на Земле',
		9 => '103 + 32 = ?',
		10 => 'Враг моего врага мой ... ?',
		);

	$num = mt_rand(1, count($questions) );
	$_SESSION['captcha'] = $num;

	echo $questions[$num];

}


function captcha_valid() {

	$answers = array(
		1 => 'киев',
		2 => 'луна',
		3 => 'да',
		4 => 'нет',
		5 => 'ночь',
		6 => '31',
		7 => 'вашингтон',
		8 => 'адам',
		9 => '135',
		10 => 'друг',
		);

if ($answers[$_SESSION['captcha']] != mb_strtolower($_POST['captcha'], 'UTF-8') )
	message('Ответ на вопрос указан неверно');
 
}
function login_valid(){
	if ( !preg_match('/^[A-z0-9]{5,20}$/', $_POST['login']) )
		message('Логин указан неверно и может содержать 5 - 20 символов A-z0-9');
}
function name_valid(){
	if ( !preg_match('/^[A-z]{3,18}$/', $_POST['name']) )
		message('Имя может содержать 3 - 15 символов A-z');
}

function sename_valid(){
	if ( !preg_match('/^[A-z]{3,18}$/', $_POST['sename']) )
		message('Имя может содержать 3 - 15 символов A-z');
}

function email_valid() {
	if ( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL))
		message('E-mail указан неверно');
}


function password_valid() {
	if ( !preg_match('/^[A-z0-9]{10,30}$/', $_POST['password']) )
		message('Пароль указан неверно и может содеражть 10 - 30 символов A-z0-9');
	$_POST['password'] = md5($_POST['password']);
}

function FormChars($p1, $p2 = 0) {
$CONNECT = mysqli_connect('localhost', 'root', '', 'chat');
if ($p2) return mysqli_real_escape_string($CONNECT, $p1);
else return nl2br(htmlspecialchars(trim($p1), ENT_QUOTES), false);
}

function send_message($p1, $p2) {
$CONNECT = mysqli_connect('localhost', 'root', '', 'chat');	
	$p1 = FormChars($p1, 1);
	$p2 = FormChars($p2);


	if ($p1 == $_SESSION['login']) message('Вы не можете отправить сообщение самому себе');
	
	$id = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `login` = '$p1'"));
	
if (!$id) message('Пользователь не найден');
	
	
$row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `id` FROM `dialog` WHERE `recive` = $id[id] AND `send` = $_SESSION[id] OR `recive` = $_SESSION[id] AND `send` = $id[id]"));

if ($row) {

	$did = $row['id'];
	mysqli_query($CONNECT, "UPDATE `dialog` SET `status` = 0, `send` = $_SESSION[id], `recive` = $id[id] WHERE `id` = $row[id]");

	} else {


	mysqli_query($CONNECT, "INSERT INTO `dialog` VALUES ('', 0, $_SESSION[id], $id[id])");
	$did = mysqli_insert_id($CONNECT);
	
	}
	
	mysqli_query($CONNECT, "INSERT INTO `message` VALUES ('', $did, $_SESSION[id], '$p2', NOW())");
		
	}

function PageSelector($p1, $p2, $p3, $p4 = 5) {
/*
$p1 - URL (Например: /news/main/page)
$p2 - Текущая страница (из $Param['page'])
$p3 - Кол-во новостей
$p4 - Кол-во записей на странице
*/
$Page = ceil($p3[0] / $p4); //делим кол-во новостей на кол-во записей на странице.
if ($Page > 1) { //А нужен ли переключатель?
echo '<div class="PageSelector">';
for($i = ($p2 - 3); $i < ($Page + 1); $i++) {
if ($i > 0 and $i <= ($p2 + 3)) {
if ($p2 == $i) $Swch = 'SwchItemCur';
else $Swch = 'SwchItem';
echo '<a class="'.$Swch.'" href="'.$p1.$i.'">'.$i.'</a>';
}
}
echo '</div>';
}
}

function top( $title ) {
echo '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>'.$title.'</title>
<link rel="stylesheet" href="/style.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
<script src="/script.js"></script>
</head>

<body>


<div class="wrapper">

<div class="menu">';
  if($_SESSION['id'])
  echo '
<a href="/profile">Профайл</a>
<a href="/referal">Пригласить друга</a>
<a href="/send">Личный чат</a>
<a href="/logout">Выход</a>
';
 else 
 	echo '
<a href="/login">Авторизация</a>
<a href="/register">Регистрация</a>
';



echo'
</div>
<div class="content">
<div class="block">


';
}



function bottom() {
echo '
</div>
</div>
</div>
<div class="footer">
  <p> Copyright © 2020 Vadim. All Rights Reserved</p>
</div>
</body>
</html>';
}






?>
