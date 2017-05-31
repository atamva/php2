<?php
/**
 * $error - вывод ошибок
 */?>
<form method="post" action="">
    <h2>Представьтесь</h2>
    <?php if ($error): ?>
        <br>
        <b style="color: red"> <?php echo $error;?> <br><br> </b>
    <?php endif;?>

    <input name="username" type="text" class="login" placeholder="Имя пользователя" required autofocus>
    <br>
    <input name="password" type="password" class="login" placeholder="Пароль" required>
    <br>
    <input type="checkbox" name="save"> запомнить
    <br>
    <button name="in" class="login" type="submit">Войти</button>
    <br>
    <br>
    Еще не зарегестрированы? <a href="/users/registration">Регистрация</a>
</form>