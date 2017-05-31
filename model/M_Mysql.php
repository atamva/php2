<?php
class M_Mysql extends mysqli
{
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbName = 'php2';

    ///-----------------------------}*/
    public static $mysqli;

    public static function getInstance()
    {
        if (self::$mysqli ===null)
        {
            self::$mysqli = new self();
        }
        return self::$mysqli;
    }


    function __construct()
    {
        //заглушка для хостинга
        include_once ("patchHosting.php");

        //Подключене к бд
        parent::__construct($this->hostname, $this->username, $this->password);

        //$mysqli = new mysqli($this->hostname, $this->username, $this->password);
        if ($this->connect_error) {
            die('Ошибка подключения (' . $this->connect_errno . ') '
                . $this->connect_error);
        }
        $this->select_db($this->dbName);
        //$db = mysqli_select_db($link, $this->dbName)                  //Cоздание базы на лету, потом сделать
        //if(!$db){create_db; select_db; create_table; create_data}
        $this->query('SET NAMES utf8');
        $this->set_charset('utf8');

    }
    function __destruct()
    {
        $this->close();
    }
    //$query полный текст запроса
    // return array полученных строк из базы
    public function select($query)
    {
        $result = $this->query($query);
        if (!$result){
            die($this->error);
        }
        while ($row = $result->fetch_assoc()){
            $arr[]=$row;
        }
        return $arr;
 //       $mysqli->
    }
    //$table - таблица
    //$object - массив ключи имена столбцов значения данные
    //return id вставленной записи
    public function insert($table,$object)
    {
        $colomns = [];
        $values = [];

        foreach ($object as $key => $value)
        {
            $key = $this->escape_string($key."");      //  mysqli->escape_string($key."");   //mysqli_escape_string($this->link, $key."");
            $colomns[]="`{$key}`";
            if ($value === null){
                $values[]='NULL';
            }
            else{
                $value =  $this->escape_string($value."");
                $values[]="'{$value}'";
            }
        }
        $colomns = implode(', ', $colomns);
        $values = implode(', ', $values);

        $query = sprintf("INSERT INTO `%s` (%s) VALUES (%s)", $table,$colomns,$values);
        $result = $this->query($query);

        if (!$result){
            die($this->error);
        }
        return $this->insert_id;
    }
    //$table - таблица
    //$object - массив ключи имена столбцов значения данные
    //$where - условие часть SQL запроса
    //return количество затронутых строк
    public function update($table, $object, $where)
    {
        $sets = [];
        //var_dump($object);
        foreach ($object as $key => $value) {
            $key = $this->escape_string($key . "");
            if ($value === null) {
                $sets[] = "`$key`=NULL";
            } else {
                $value = $this->escape_string($value . "");
                $sets[] = "`{$key}`='{$value}'";

            }
        }
        //    var_dump($sets);
            $sets = implode(', ', $sets);
            $query = sprintf("UPDATE `%s` SET %s WHERE %s", $table, $sets, $where);
            $result = $this->query($query);
            if (!$result){
                die($this->error);
            }
            $row = $this->affected_rows;
            if ($row ===0)
            {
                $row=true;
            }
            return $row;
            //var_dump($sets);die();



    }

    public function delete($table,$where)
    {
        $query = sprintf("DELETE FROM `%s` WHERE %s", $table, $where);
        $result = $this->query($query);
        if (!$result){
            die($this->error);
        }

        $row = $this->affected_rows;
        return $row;
    }

}

///----------------------------------удалить все что ниже
//$m= M_Mysql::getInstance();
//echo $m->insert('articles', ['title'=>'Новый дабавка', 'content'=>'а тут содержание добаквки']);
//echo $m->insert('articles', ['title'=>'Новый дабавка tot', 'content'=>'а тут содержание добаквки']);
//var_dump($m->select("SELECT * FROM `articles`"));