<?php
/**
 * Created by PhpStorm.
 * User: operations
 * Date: 26.04.2017
 * Time: 17:07
 */
//require_once 'model'.DIRECTORY_SEPARATOR.'M_Articles.php';
class C_Article extends C_Base
{
    public function action_editor()
    {
        //Извлекаем все статьи
        $articles = M_Articles::getInstance();
        //var_dump($articles->all());
        $all['articles']=$articles->all();
        //Заголовок страницы
        $this->title.= "Консоль редактора";
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_editor.php',$all);
    }
    public function action_edit($id=null)
    {
        //var_dump($id);
        //определяем переменные для шаблона
        $this->title.= "Редактирование статьи";
        $title = '';
        $content = '';
        $id_article = '';
        $error = false;
        $articles = M_Articles::getInstance();
        //выдергиваем содержимое статьи для заполнения формы
        if ($id){
            $article=$articles->get($id[0]);
            $title = htmlentities($article['title']);
            $content = $article['content'];
            $id_article = $article['id_article'];
        }

        // Обработка отправки формы редактирования статьи
        if (!empty($_POST) && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id_article'])){

            if($articles->edit($_POST['id_article'], $_POST['title'],$_POST['content'])){
                die(header('location: /article/editor'));
            }
            $error = "Заполните название";
            $title = $_POST['title'];
            $content = $_POST['content'];
            $id_article = $_POST['id_article'];
        }

        //-----------------------------------------------------
        //обработка кнопки удаления
        if(!empty($_POST) && isset($_POST['delete']) && isset($_POST['id_article'])){
            if ($articles->delete($_POST['id_article'])){
                die(header('location: /article/editor'));
            }
            $error = "Не удалилось, чтото пошло не так :(";
            $title = $_POST['title'];
            $content = $_POST['content'];
            $id_article = $_POST['id_article'];
        }
        //-----------------------------------------------------------------------
        //Внутренний шаблон
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_edit.php',[
            'title' => $title,
            'content' => $content,
            'id_article' => $id_article,
            'error' => $error]);
    }
    public function action_new()
    {
        //определяем переменные для шаблона
        $this->title.= "Новая статья";
        $title = '';
        $content = '';
        $error = false;
        $articles = M_Articles::getInstance();
        // Обработка отправки форы
        if (!empty($_POST) && isset($_POST['title']) && isset($_POST['content'])){
            if($articles->neww($_POST['title'],$_POST['content'])){
                die(header('location: /article/editor'));
            }
            $error = "Название обязательное поле";
            $title = $_POST['title'];
            $content = $_POST['content'];
        }

//Внутренний шаблон
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_new.php',[
            'title' => $title,
            'content' => $content,
            'error' => $error]);
    }
    public function action_delete($id=null)
    {   $articles = M_Articles::getInstance();
        if($id){
            if ($articles->delete($id[0])){
                die(header('location: /article/editor'));//удалилась глрмуль
            }
            die(header('location: /article/editor'));//все данные есть но не удалилась почемуто
        }
        die(header('location: /article/editor'));//нехватает даннх для выполнения команды
    }

}