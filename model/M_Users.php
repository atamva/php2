<?php
// Менеджер пользователей
//
class M_Users
{	
	private static $instance;	// экземпляр класса
	private $mysqli;				// драйвер БД
	private $sid;				// идентификатор текущей сессии
	private $uid;				// идентификатор текущего пользователя
	
	//
	// Получение экземпляра класса
	// результат	- экземпляр класса MSQL
	//
	public static function getInstance()
	{
		if (self::$instance == null)
			self::$instance = new M_Users();
			
		return self::$instance;
	}

	//
	// Конструктор
	//
	public function __construct()
	{
		$this->mysqli = M_Mysql::getInstance();
		$this->sid = null;
		$this->uid = null;
	}
	
	//
	// Очистка неиспользуемых сессий
	// 
	public function clearSessions()
	{
		$min = date('Y-m-d H:i:s', time() - 60 * 20); 			
		$t = "`time_last` < '%s'";
		$where = sprintf($t, $min);
		$this->mysqli->delete('sessions', $where);
	}

	//
	// Авторизация
	// $login 		- логин
	// $password 	- пароль
	// $remember 	- нужно ли запомнить в куках
	// результат	- true или false
	//
	public function login($username, $password, $remember = true)
	{
		// вытаскиваем пользователя из БД 
		$user = $this->getByLogin($username);
        //echo $user;
        //die();
		if ($user == null)
			return false; //нет такого пользователя в базе
		
		$id_user = $user['id_user'];
				
		// проверяем пароль
		if ($user['password'] != md5($password))
			return false; //не правельный пароль
				
		// запоминаем имя и md5(пароль)
		if ($remember)
		{
			$expire = time() + 3600 * 24 * 100;
			setcookie('username', $username, $expire);
			setcookie('password', md5($password), $expire);
		}		
				
		// открываем сессию и запоминаем SID
		$this->sid = $this->openSession($id_user);
		return true;
	}
	
	//
	// Выход
	//
    public function registration($name,$username,$email,$password){
        //Проверяем есть ли такой пользователь, если есть посылаем в лес
        $t = "SELECT `id_user` FROM `users` WHERE `username`= '%s'";
        $query = sprintf($t, $username);
        if($this->mysqli->select($query)){
            return false;
        }
        $password=md5($password);


        if($this->mysqli->insert("users",['name'=>$name, 'username'=>$username, 'email'=>$email, 'password'=>$password])){
            return true;
        }
        else{
            die("Проблемы на сервере, попробуйте позже");
        }


    }
	public function logout()
	{
		setcookie('username', '', time() - 1);
		setcookie('password', '', time() - 1);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);		
		$this->sid = null;
		$this->uid = null;
	}
						
	//
	// Получение пользователя
	// $id_user		- если не указан, брать текущего
	// результат	- объект пользователя
	//
	public function get($id_user = null)
	{	
		// Если id_user не указан, берем его по текущей сессии.
		if ($id_user == null)
			$id_user = $this->getUid();
			
		if ($id_user == null)
			return null;
			
		// А теперь просто возвращаем пользователя по id_user.
		$t = "SELECT * FROM `users` WHERE `id_user` = '%d'";
		$query = sprintf($t, $id_user);
		$result = $this->mysqli->select($query);
		return $result[0];		
	}
	
	//
	// Получает пользователя по логину
	//
	public function getByLogin($username)
	{	
		$t = "SELECT * FROM `users` WHERE `username` = '%s'";
		$query = sprintf($t, $this->mysqli->real_escape_string($username));
		$result = $this->mysqli->select($query);
		return $result[0];
	}
			
	//
	// Проверка наличия привилегии
	// $priv 		- имя привилегии
	// $id_user		- если не указан, значит, для текущего
	// результат	- true или false
	//
	public function can($priv, $id_user = null)
	{		
		// СДЕЛАТЬ САМОСТОЯТЕЛЬНО
		return false;
	}

	//
	// Проверка активности пользователя
	// $id_user		- идентификатор
	// результат	- true если online
	//
	public function isOnline($id_user)
	{		
		// СДЕЛАТЬ САМОСТОЯТЕЛЬНО
		return false;
	}
	
	//
	// Получение id текущего пользователя
	// результат	- UID
	//
	public function getUid()
	{	
		// Проверка кеша.
		if ($this->uid != null)
			return $this->uid;	

		// Берем по текущей сессии.
		$sid = $this->getSid();
				
		if ($sid == null)
			return null;
			
		$t = "SELECT `id_user` FROM `sessions` WHERE `sid` = '%s'";
		$query = sprintf($t, $this->mysqli->real_escape_string($sid));
		$result = $this->mysqli->select($query);
				
		// Если сессию не нашли - значит пользователь не авторизован.
		if (count($result) == 0)
			return null;
			
		// Если нашли - запоминм ее.
		$this->uid = $result[0]['id_user'];
		return $this->uid;
	}

	//
	// Функция возвращает идентификатор текущей сессии
	// результат	- SID
	//
	private function getSid()
	{
		// Проверка кеша.
		if ($this->sid != null)
			return $this->sid;
	
		// Ищем SID в сессии.
		$sid = $_SESSION['sid'];
								
		// Если нашли, попробуем обновить time_last в базе. 
		// Заодно и проверим, есть ли сессия там.
		if ($sid != null)
		{
			$session = [];
			$session['time_last'] = date('Y-m-d H:i:s'); 			
			$t = "sid = '%s'";
			$where = sprintf($t, $this->mysqli->real_escape_string($sid));
			$affected_rows = $this->mysqli->update('sessions', $session, $where);

			if ($affected_rows == 0)
			{
				$t = "SELECT count(*) FROM `sessions` WHERE `sid` = '%s'";
				$query = sprintf($t, $this->mysqli->real_escape_string($sid));
				$result = $this->mysqli->select($query);
				
				if ($result[0]['count(*)'] == 0)
					$sid = null;			
			}			
		}		
		
		// Нет сессии? Ищем логин и md5(пароль) в куках.
		// Т.е. пробуем переподключиться.
		if ($sid == null && isset($_COOKIE['login']))
		{
			$user = $this->getByLogin($_COOKIE['login']);
			
			if ($user != null && $user['password'] == $_COOKIE['password'])
				$sid = $this->openSession($user['id_user']);
		}
		
		// Запоминаем в кеш.
		if ($sid != null)
			$this->sid = $sid;
		
		// Возвращаем, наконец, SID.
		return $sid;		
	}
	
	//
	// Открытие новой сессии
	// результат	- SID
	//-------------------------------------------------------------------------
    //Возможна дупликация сидов в бажзе подумать и переделать функцию
    //------------------------------------------------------------------------
	private function openSession($id_user)
	{
		// генерируем SID
		$sid = $this->generateStr(10);
				
		// вставляем SID в БД
		$now = date('Y-m-d H:i:s'); 
		$session = array();
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;				
		$this->mysqli->insert('sessions', $session);
				
		// регистрируем сессию в PHP сессии
		$_SESSION['sid'] = $sid;				
				
		// возвращаем SID
		return $sid;	
	}

	//
	// Генерация случайной последовательности
	// $length 		- ее длина
	// результат	- случайная строка
	//
	private function generateStr($length = 10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clean = strlen($chars) - 1;

		while (strlen($code) < $length) 
            $code .= $chars[mt_rand(0, $clean)];

		return $code;
	}
}
