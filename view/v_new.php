<?php
/** Шаблон саздания новой статьи
 * $title - Заголовок
 * $content  - содержание
 * $error - ошибка юзера
 */?>

<?php if ($error): ?>
    <b style="color: red"> <?php echo $error;?> </b>
<?php endif;?>
<form method="post">
    Название: <br>
    <input type="text" name="title" value="<?php echo $title ?>">
    <br>
    <br>
    Содержание: <br>
    <textarea name="content"><?php echo $content ?></textarea>
    <br>
    <input type="submit" name="Сохранить" value="Сохранить">

</form>
