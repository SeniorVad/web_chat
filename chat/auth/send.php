<? top('Отправить сообщение') ?>
<?php  
if($_POST['text'] and $_POST['enter'] and $_POST['login'] ) {
send_message($_POST['login'], $_POST['text']);
echo'<p style="font-size:20px;"> Сообщение отправлено</p>';
}
?>


<style>
	#inp1{
		width: 200px;
	}
	#stA{
	color:#e0e0eb;
	font-size: 24px;
    text-shadow: 1px 1px 15px #ff00ff;
	}
</style>
<p style="text-indent: 25px; "> <a id="stA" href="dialog">Мои диалоги</a> </p>
<form method="POST" action="/send">
<input id="inp1" type="text" name ="login" placeholder="Логин получателя">
<br><textarea name="text" placeholder="Текст сообщения" cols="27" rows="5" required></textarea>		
<br><input type="submit" name="enter" value="Отправить">
<input type="reset" value="Очистить">
</form>

<? bottom() ?>