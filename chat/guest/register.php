<? top('Регистрация') ?>

<h1>Регистрация</h1>

<p>Введите логин</p>
<p><input type="text" placeholder="Login" id="login"></p>
<p>Введите Email</p>
<p><input type="text" placeholder="E-mail" id="email"></p>
<p>Введите пароль</p>
<p><input type="password" placeholder="Пароль" id="password"></p>
<p>Введите имя</p>
<p><input type="text" placeholder="Name" id="name"></p>
<p>Введите фамилию</p>
<p><input type="text" placeholder="Sename" id="sename"></p>
<p>Введите ответ</p>
<p><input type="text" placeholder="<?captcha_show()?>" id="captcha"></p>
<p><button onclick="post_query('gform', 'register', 'login.email.password.name.sename.captcha')">Регистрация</button></p>

<? bottom() ?>