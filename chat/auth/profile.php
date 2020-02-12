<? top('Профайл') ?>
<?php 
echo '
<p id="prof">Id: '.$_SESSION['id'].'
<p id="prof">Логин: '.$_SESSION['login'].'
<br> <p id="prof">Email: '.$_SESSION['email'].'
<br> <p id="prof">Имя: '.$_SESSION['name'].'
<br> <p id="prof">Фамилия: '.$_SESSION['sename'].'
';
?>
<h1 class="profh1">Редактировать профиль</h1>
<p>Введите пароль</p>
<p><input type="password" placeholder="Новый пароль" id="password"></p>
<p><button onclick="post_query('aform', 'editpas', 'password')">Сохранить</button>
<? bottom() ?>