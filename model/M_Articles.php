<?php
/**
 * Created by PhpStorm.
 * User: atamva
 * Date: 17.04.2017
 * Time: 18:00
 */
class M_Articles
{
    //ссылка на экземпляр класса
    private static $instance;
    //ссылка на драйвер
    private $mysql;
    private function __construct(){

        $this->mysql = M_Mysql::getInstance();

    }

    //Получение единственного экземпляра класса
    public static function getInstance()
    {
        //гарантия одного экземпляра
        if (self::$instance===null){
            self::$instance=new self();
        }

        return self::$instance;
    }
    //Общие методы для всех моделей
    public function all()
    {
        $query = "SELECT * FROM `articles` ORDER BY `id_article` DESC ";
        //var_dump($this->mysql->select($query));
        return $this->mysql->select($query);
    }
    public function get($id)
    {
        //Запрос в базу
        $sql = sprintf("SELECT * FROM `articles` WHERE `id_article`='%s'",$id);
        $article = $this->mysql->select($sql);
        return $article[0];


    }
    public function neww($title, $content)
    {
        //подготовка данных
        $title = trim($title);
        $content = trim($content);

        //проверка
        if ($title == ''){
            return false;
        }
        // запрос в базу
        $query = $this->mysql->insert('articles',['title'=>$title,'content'=>$content]);
       //     "INSERT INTO `articles` (`title`, `content`) VALUES ('%s', '%s')";
       // $query = sprintf($sql, sql_escape($title), sql_escape($content));

        return $query;
    }
    public function delete($id)
    {
        //запрос в базу
        $sql = $this->mysql->delete('articles',"`id_article`='{$id}'");//DELETE FROM `articles` WHERE `id_article`='%s'";
        //$query = sprintf($sql, sql_escape($id_article));
        return $sql;
    }
    public function intro($all)
    {
        header("content-type: text/html; charset=utf-8");
        $i=0;//var_dump($all);
        foreach ($all as $value){
            //echo "\$i=".$i."<br>";
            //echo $value['content']."<br>";
            $all[$i]['intro']=mb_strcut($value['content'],0,200,"UTF-8");
            //echo "<br>";
            $i++;
        }
        return $all;


    }
    public function edit($id, $title, $content)
    {
        $title = trim($title);
        $content = trim($content);

        //проверка
        if ($title == ''){
            return false;
        }
        // запрос в базу
         $sql = $this->mysql->update('articles',['title'=>$title,'content'=>$content], "`id_article`='{$id}'");
        return $sql;
    }


}
