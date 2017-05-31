<?php
/**главный базовый шаблон
 * $page_title Заголовок страницы
 * $content содержание средней части
 *
 */
?><!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/view/style.css">

</head>
<body>
<h1><?php echo $title ?></h1>
<br>
<a href="/index/index">Главная</a> |
<a href="/article/editor">Консоль редактора</a> |
<a href="/users/admin">Администрирование</a> |
<?php if ($user):?>
    <a href="/users/logout">Выход <?php echo $user['username'] ?></a>
<?php else:?>
    <a href="/users/login">Вход</a>
<?php endif;?>

|
<hr>

<?php echo $content ?>

<hr>
<small><a href="mailto:atamva@list.ru">atamva&copy;</a></small>
</body>
</html>