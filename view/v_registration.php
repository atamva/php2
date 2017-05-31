<?php
/**
 * $username имя пользвателя
 * $email адрес пользователя
 * $name имя
 *
 */?>

<form method="post" action="">
    <h2>Введите свои данные</h2>

    <?php if ($error): ?>
        <b style="color: red"> <?php echo $error;?> <br><br> </b>
    <?php endif;?>
    <input name="name" type="text" class="login" placeholder="Имя" required autofocus value="<?php echo $name;?>">
    <br>
    <input name="username" type="text" class="login" placeholder="Логин" required value="<?php echo $username;?>">
    <br>
    <input name="email" type="email" class="login" placeholder="Адрес email" required value="<?php echo $email;?>">
    <br>
    <input name="password" type="password" class="login" placeholder="Пароль" required>
    <br>
    <input name="passcheck" type="password" class="login" placeholder="Подтверждение пароля" required>
    <br>
    <br>
    <button name="in" class="login" type="submit">Зарегестрироваться</button>
</form>

