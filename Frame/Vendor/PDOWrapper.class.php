<?php
//声明命名空间
namespace Frame\Vendor;
use \PDO;
use \PDOException;

final class PDOWrapper
{
    //数据库的配置属性
    private $db_type;
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $charset;
    private $pdo = NULL;

    //构造方法
    public function __construct()
    {
        $this->db_type = $GLOBALS['config']['db_type'];
        $this->db_host = $GLOBALS['config']['db_host'];
        $this->db_port = $GLOBALS['config']['db_port'];
        $this->db_user = $GLOBALS['config']['db_user'];
        $this->db_pass = $GLOBALS['config']['db_pass'];
        $this->db_name = $GLOBALS['config']['db_name'];
        $this->charset = $GLOBALS['config']['charset'];
        $this->connectDb(); //创建PDO对象，连通数据库，选择数据库
        $this->setErrMode();//设置PDO的错误模式
    }

    //私有的创建PDO对象
    private function connectDb(){
        try {
            $dsn = "{$this->db_type}:host={$this->db_host};port={$this->db_port};dbname={$this->db_name};charset={$this->charset}";
            //echo $dsn;
            //die();
            $this->pdo = new PDO($dsn,$this->db_user,$this->db_pass);
        }catch (PDOException $e){
            echo "<h2>创建PDO对象失败！</h2>";
            echo "错误状态码：".$e->getCode();
            echo "<br>错误行号：".$e->getLine();
            echo "<br>错误文件：".$e->getFile();
            echo "<br>错误信息：".$e->getMessage();
            die();
        }
    }

    //设置私有的PDO错误模式的方法
    private function setErrMode(){
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    //公共的执行sql语句的方法 主要执行返回结果为布尔值的sql语句
    public function exec($sql){
        try {
            return $this->pdo->exec($sql);
        }catch (PDOException $e){
            $this->showError($e);
        }
    }

    //获取单行数据
    public function fetchOne($sql){
        try {
            //执行sql语句并返回结果集对象
            $PDOStatement = $this->pdo->query($sql);
            //从结果集对象取出一条记录，并返回一维数组
            return $PDOStatement->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            $this->showError($e);
        }
    }

    //获取多行数据
    public function fetchAll($sql){
        try {
            //执行sql语句并返回结果集对象
            $PDOStatement = $this->pdo->query($sql);
            //从结果集对象取出所以记录，并返回二维数组
            return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            $this->showError($e);
        }
    }

    //获取记录数
    public function rowCount($sql){
        try {
            //执行sql语句并返回结果集对象
            $PDOStatement = $this->pdo->query($sql);
            //
            return $PDOStatement->rowCount();
        }catch (PDOException $e){
            $this->showError($e);
        }
    }

    //私有的现实错误信息的方法
    private function showError($e){
        echo "<h2>SQL语句有误！</h2>";
        echo "错误状态码：".$e->getCode();
        echo "<br>错误行号：".$e->getLine();
        echo "<br>错误文件：".$e->getFile();
        echo "<br>错误信息：".$e->getMessage();
    }
}