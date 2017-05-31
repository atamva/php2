<?php
/**
 * Шаблон редактируемой страницы
 * $articles  - список статей
 * статья
 * id_article - идентификатор
 * title - заголовок
 * content - текст
 */?>
<ul>
    <li><b><a href="/article/new">Новая статья</a></b></li>
    <?php foreach ($articles as $article):?>
        <li><a href="/article/edit/<?php echo $article['id_article'] ?>" ><?php echo $article['title'] ?></a> &nbsp; &nbsp; &nbsp; <a href="/article/delete/<?php echo $article['id_article'] ?>" style = "color: red">Удалить!</a> </li>
    <?php endforeach;?>
</ul>
