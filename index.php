<?php
//startup();
session_start();

//инклудим нужные классы
function __autoload($className)
{
    //echo $className[0];
    switch ($className[0]){
        case "C":
            if (file_exists('controller'. DIRECTORY_SEPARATOR .$className.'.php')){
                require_once ('controller'. DIRECTORY_SEPARATOR .$className.'.php');
            }
            break;
        case "M":
            if (file_exists('model'. DIRECTORY_SEPARATOR .$className.'.php')){
                require_once ('model'. DIRECTORY_SEPARATOR .$className.'.php');
            }
            break;
        default:
            echo "Это чтото новенькое :)<BR> Надо доработать __автолоад :(";
            break;
    }
}

// это по моему в стартап надо кинуть
//--------------------------------------\
//$mUsers = M_Users::getInstance();
// Очистка старых сессий
//$mUsers->clearSessions();
//----------------------------------.


// Текущий пользователь.
//$user = $mUsers->get();


//Новая обработка URla
$urlinfo = $_SERVER['REQUEST_URI'];
$urlparts = explode('/',$urlinfo);



foreach ($urlparts as $v){
    if ($v !='index.php' && $v != ''){
        $params[]=$v;
    }
}
//var_dump($params);
$controller = isset($params[0])?array_shift($params):'Index';
$action = isset($params[0])?array_shift($params):'index';

$controller = 'C_'.ucfirst($controller);
$action = 'action_'.$action;
//контроллер по умолчанию
if (!class_exists($controller)){
    $controller='C_Index';
}
$controllerObj = new $controller;
$controllerObj->request($action,$params);
//      параметры не забыть посмотреть как передаються $params

//-------------------------------------------------------------------------
/*
//подготовка переменной для выполнения актиона
$action = "action_";
//Берем из гета какой приходит action $_GET['action'] если нет никакого ставим index
$action.=isset($_GET['action']) ? $_GET['action']:"index";

switch ($_GET['cont']){
    case 'article':
        $controller = new C_Article();
        break;
    case 'users':
        $controller = new C_Users();
        break;
    default:
        $controller = new C_Article();
        break;
}
$controller->request($action);

*/