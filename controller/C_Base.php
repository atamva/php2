<?php

/**
 * Created by PhpStorm.
 * User: operations
 * Date: 26.04.2017
 * Time: 16:32
 */
abstract class C_Base extends C_Controller
{
    public function before()
    {
        //заголовок
        $this->title = 'PHP уровень 2 - ';
        $this->content = '';
        //user-------------------------------
        // Менеджеры.
        $users = M_Users::getInstance();
        // Очистка старых сессий.
        $users->ClearSessions();
        // Текущий пользователь.
        $this->user = $users->Get();
        //-----------------------------------
    }
    public function render()
    {
        $params = ['title'=> $this->title,'user'=>$this->user, 'content'=> $this->content];
        $page = $this->template('view'.DIRECTORY_SEPARATOR.'v_main.php',$params);
        header('Content-type: text/html; charset=utf-8');
        echo $page;
    }

}