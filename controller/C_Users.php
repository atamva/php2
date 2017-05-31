<?php

/**
 * Created by PhpStorm.
 * User: atamva
 * Date: 03.05.2017
 * Time: 21:01
 */
class C_Users extends C_Base
{
    public function action_login()
    {
        //подготовка для шаблона
        $error = false;

        $users = M_Users::getInstance();
        if (!empty($_POST))
        {
            if ($users->login($_POST['username'], $_POST['password'], isset($_POST['remember'])))
            {
                header('Location: /');
                die();
            }
            $error = "Неправельный логин или пароль";
        }

        $this->title.="Введите логин и пароль";
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_login.php', ['error'=>$error]);
    }
    public function action_logout(){
        $users = M_Users::getInstance();
        $users->logout();
        header('Location: /');
        die();
    }
    public function action_registration(){
        //-----------------------------------------------------------
        //добавит проверку на неправельные символы в логине и почьте
        //-----------------------------------------------------------
        $error = false;
        $username = "";
        $email = "";
        $users = M_Users::getInstance();
        if (!empty($_POST))
        {
            if (!empty($_POST['name']) and !empty($_POST['username']) and !empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['passcheck']))
            {
                if ($_POST['password']==$_POST['passcheck']){
                    if($users->registration($_POST['name'],$_POST['username'],$_POST['email'],$_POST['password'])){
                        header('Location: /users/registred');
                        die();
                    }
                    $error = "Такое имя уже есть попробуйте другое";
                }
                if (!$error) {
                    $error = "Пароли не совпадают";
                }
            }
            if (!$error){
                $error = "Поля не могут быть пустыми";
            }
            $name = $_POST['name'];
            $username = $_POST['username'];
            $email= $_POST['email'];

        }


        $this->title.="Регистрация";
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_registration.php', ['error'=>$error,'name'=>$name,'username'=>$username, 'email'=>$email]);
    }
    public function action_registred($error = false){

        $this->title.="Спасибо :)";
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_registred.php', ['error'=>$error]);
    }
    public function action_admin(){
        $error = false;


        $this->title.="Администрирование";
        $this->content = $this->template('view'.DIRECTORY_SEPARATOR.'v_admin.php', ['error'=>$error]);
    }
}