<?php
/** Шаблон редактирования статьи
 * $title - Заголовок
 * $content  - содержание
 * $error - ошибка юзера
 * $id_article
 */?>
<?php if ($error): ?>
    <b style="color: red"> <?php echo $error;?> </b>
    <br>
<?php endif;?>

<div class="brd">
<form method="post">
    Название: <br>
    <input type="text" name="title" value="<?php echo $title ?>">
    <br>
    <input type="hidden" name="id_article" value="<?php echo $id_article ?>">
    <br>
    Содержание: <br>
    <textarea name="content"><?php echo $content ?></textarea>
    <br>

    <input type="submit" name="Сохранить" value="Сохранить">

</form>
</div>
<div>
<form method="post">

    <input type="submit" name="del" value="Удалить">

    <input type="hidden" name="id_article" value="<?php echo $id_article ?>">

    <input type="hidden" name="delete" value="ok">

</form>
</div>




