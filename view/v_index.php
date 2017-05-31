<?php
/** Шаблон index
 * $articles =>
 *              $article['title']
 *              $article['intro']
 */?>

<br>
И постраничную навигацию!!!!



    <?php foreach ($articles as $article):?>
        <p><a href="/index/article/<?php echo $article['id_article'] ?>" ><?php echo $article['title'] ?></a>
            <br>
            <?php echo $article['intro'] ?><a href="/index/article/<?php echo $article['id_article'] ?>" >...</a>
        </p>
    <?php endforeach;?>




