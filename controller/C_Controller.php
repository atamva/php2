<?php

/**
 * Created by PhpStorm.
 * User: operations
 * Date: 26.04.2017
 * Time: 16:19
 */
abstract class C_Controller
{
    //обьявление необходимых функций
    abstract function render();
    abstract function before();
    //обработка пользовательского запроса
    public function request($action, $params)
    {
        $this->before();
        $this->$action($params);
        $this->render();
    }
    //стандартные функции которые могут быть совсем везде
    protected function IsGet()
    {
        return $_SERVER['REQUEST_METHOD']=='GET';
    }
    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD']=='POST';
    }
    //Функция шаблонизатора
    protected function template($fileName,$params=[])
    {
        extract($params);
        ob_start();
        include $fileName;
        return ob_get_clean();
    }
    //Магическая функция неправельного адреса
    public function __call($name, $arguments)
    {
        header('Content-type: text/html; charset=utf-8');
        //var_dump($_SERVER);
        $error = "Ошибка 404!  <br>Нет такой страницы :( <br> <a href='http://{$_SERVER['HTTP_HOST']}'>На главную</a> <br>";
        die($error);
    }


}