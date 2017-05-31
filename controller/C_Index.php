<?php

/**
 * Created by PhpStorm.
 * User: atamva
 * Date: 16.05.2017
 * Time: 11:45
 */
class C_Index extends C_Base
{
    public function action_article($id=null)
    {
        //Получаем модель статей

        $articles = M_Articles::getInstance();
        $article = $articles->get($id[0]);

        $this->title.=$article['title'];
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_article.php',$article);

    }
    public function action_index()
    {
        //Извлекаем все статьи и сразу добавляем к ним интро
        $articles = M_Articles::getInstance();
        //var_dump($articles->all());
        $all['articles']= $articles->intro($articles->all());



        $this->title.="Просмотр статей";
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_index.php',$all);
        //$this->content = "Добро пожаловать :) <br> Список статей не забыть написать !!!!<br>
        //А так же неоюходимо это отделить в свой собственный контроллер";
    }
}